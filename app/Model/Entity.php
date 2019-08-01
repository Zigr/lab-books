<?php

namespace App\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use App\Lib\ValidationTrait;

/**
 * Common model operations
 *
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 */
class Entity
{
    use ValidationTrait;

    /** @var boolean enable overall query profiling  Overrides individual table profiling */
    protected static $_enableProfiling;

    /** @var boolean enable instance query profiling     */
    protected $_profilingEnabled;
    protected $errors;
    protected $_tableGateway;
    protected $_profiler;
    protected $_modelClass;

    /** @var array Pattern or callback based validation rules 
     * $rules = [
     * 
     * 'pattern|function'=>'','message'=>'' ,'view_key'=>'rule set name in a view'];
     * ]
     */
    protected static $rules;

    public function setTableGateway($table)
    {
        // if string given, make new table class
        if (is_string($table))
        {
            $table = new $table();
        } else
        {
            throw new \RuntimeException('Name of gateway class should be a string');
        }
        if (!is_a($table, 'Zend\Db\TableGateway\AbstractTableGateway'))
        {
            throw new \RuntimeException('Invalid table type is specified[' . get_class($table) . '] Must be Zend\Db\TableGateway\AbstractTableGateway');
        }

        $this->_tableGateway = $table;
        return $this;
    }

    public function getTableGateway()
    {
        return $this->_tableGateway;
    }

    protected function setProfiling($debug)
    {
        if (!(static::$_enableProfiling || $debug))
        {
            return false;
        }
        $this->_profilingEnabled = true;
        $this->_profiler = new \Zend\Db\Adapter\Profiler\Profiler();
        $this->_tableGateway->getAdapter()->setProfiler($this->_profiler);
    }

    /**
     * 
     * @param array $data table fields
     * @param boolean $debug display profiling info for <b>given</b> table
     */
    public function __construct($data = array(), $debug = false)
    {
        $this->setTableGateway($this->_tableGatewayClass);
        $this->setProfiling($debug);
        if (!empty($data))
        {
            $this->populate($data);
        }
        // load row
        $this->load();
    }

    /**
     * enables overall query profiling  Overrides individual table profiling 
     * @param boolean $enable
     * @return void 
     */
    public static function enableProfiling($enable)
    {
        static::$_enableProfiling = $enable;
    }

    public function __call($method_name, $arguments)
    {
        if (method_exists($this, $method_name))
        {
            return call_user_func_array([$this, $method_name], $arguments);
        }
        throw new \BadMethodCallException("Calling inexistent method [$method_name]");
    }

    public static function one($where = null, $fields = null)
    {
        $entity = new static ;
        $table = $entity->getTableGateway();
        $tableName = $table->getTable();
        $query = new Select($tableName);


        if (is_array($where))
        {
            foreach ($where as $key => $value)
            {
                $query->where(array($key => $value));
            }
        }
        if ($fields)
        {
            $query->columns($fields);
        }
        $query->limit(1);
        $row = $table->selectWith($query);
        $entity->dumpProfile(__METHOD__ . ':' . __LINE__);
        if ($row)
        {
            $entity->populate($row->current());
            return $entity->getFields($fields);
        }

        return null;
    }

    public static function count($where = null)
    {
        $entity = new static;
        $table = $entity->getTableGateway();
        $tableName = $table->getTable();
        $query = (new Select($tableName))
                ->columns(array("num" => new \Zend\Db\Sql\Expression("COUNT(1)")), false)
                ->limit(1);

        if (is_array($where))
        {
            foreach ($where as $key => $value)
            {
                $query->where(array($key => $value));
            }
        }
        $row = $table->selectWith($query);
        $entity->dumpProfile(__METHOD__ . ':' . __LINE__);
        return $row->current()->num;
    }

    public static function all($where = null, $fields = null, $order = null, $direction = "asc", $limit = null, $page = null)
    {
        $entity = new static;
        $table = $entity->getTableGateway();
        $tableName = $table->getTable();
        $query = new Select($tableName);

        if ($fields)
        {
            $query->columns($fields);
        }
        if (is_array($where))
        {
            foreach ($where as $key => $value)
            {
                $query->where("$key=$value");
            }
        }

        if ($order)
        {
            $query->order("{$order} {$direction}");
        }

        if ($limit && $page)
        {
            $offset = ($page - 1) * $limit;
            $query->limit($limit, $offset);
        }

        $entities = array();

        $rows = $table->selectWith($query);
        $entity->dumpProfile(__METHOD__ . ':' . __LINE__);
        foreach ($rows as $row)
        {
            $entity->populate($row);
            $entities[$entity->id] = $entity->getFields($fields);
        }
        return $entities;
    }

    protected function dumpProfile($source)
    {
        if (!$this->_profilingEnabled)
        {
            return false;
        }

        $profile = $this->_profiler->getLastProfile();
        $parameters = '';
        foreach ($profile['parameters']->getNamedArray() as $name => $value)
        {
            $parameters .= sprintf('%s %s ', $name, $value);
        }
        $message = sprintf('Source: %s<br>Model: %s.<br>Table: %s <br>SQL: %s<br>Parameters: %s<br>Elapsed time: %s', $source, get_class($this), $this->getTableGateway()->getTable(), $profile['sql'], $parameters, $profile['elapse']);
        echo "<p class='alert alert-info'>$message</p>";
    }

    public static function find($id)
    {
        return new static(['id' => intval($id)]);
    }

    public function load()
    {
        if ($this->id)
        {
            $row = static::one(array("id" => $this->id));
            if (!$row)
            {
                return null;
            }
            foreach (array_keys($this->getFields()) as $key)
            {
                $this->$key = $row[$key];
            }
            return $this;
        }
        return null;
    }

    public function loadBy($fields = [])
    {
        $row = static::one($fields);
        if (!empty($row['id']))
        {
            foreach (array_keys($this->getFields()) as $key)
            {
                $this->$key = $row[$key];
            }
            return $this;
        }
        return $this;
    }

    public function isExists()
    {
        return !empty($this->id);
    }

    /**
     * 
     * @param callable $validate validation callback
     * @return $this inst
     */
    public function save($validate = null)
    {
        if ($validate)
        {
            $this->errors = call_user_func_array($validate, $this->getFields());
        }
        if ($this->isExists())
        {
            $res = $this->update($this, $this->getFields());
        } else
        {
            $res = $this->id = $this->insert($this, $this->getFields());
        }
        return $res;
    }

    public function delete($id = null)
    {
        if (!$this->isExists() && !$id)
        {
            return false;
        }
        $table = $this->getTableGateway();
        $tableName = $table->getTable();
        $query = new \Zend\Db\Sql\Delete($tableName);
        $where = ($this->id) ? ['id' => $this->id] : ['id' => $id];
        $query->where($where);
        $res = $table->deleteWith($query);
        $this->dumpProfile(__METHOD__ . ':' . __LINE__);
        return $res;
    }

    public function setData($data)
    {
        if (array_key_exists('id', $data))
        {
            unset($data['id']);
        }
        $this->populate($data);
        return $this;
    }

    public function update($entity, $data)
    {
        // $entity = new $this->_modelClass($data);
        $table = $entity->getTableGateway();
        $tableName = $table->getTable();
        $entity->setData($data);

        $query = new \Zend\Db\Sql\Update();

        $query->table($tableName)
                ->set($data)
                ->where(array("id" => $this->id));
        $result = $table->updateWith($query);
        $this->dumpProfile(__METHOD__ . ':' . __LINE__);
        return $result;
    }

    public function insert($entity, $data)
    {
        $table = $entity->getTableGateway();
        $tableName = $table->getTable();
        if (isset($data['id']))
        {
            unset($data['id']);
        }
        $query = new \Zend\Db\Sql\Insert();

        $query
                ->into($tableName)
                ->values($data);
//        try
//        {

        $result = $table->insertWith($query);
//        } catch (\Zend\Db\Adapter\Exception\RuntimeException $ex)
//        {
//            $m = sprintf('Catched error %s %s', $ex->getMessage(),$this->_profiler->getLastProfile()['sql']);
//            print_r($m);
//        }
        $this->dumpProfile(__METHOD__ . ':' . __LINE__);
        return $result ? $table->getLastInsertValue() : null;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    protected function applyRules($field, $subject, $errorKey = null)
    {
        $rules = static::$rules;
        if (isset($rule[$field]['pattern']))
        {
            if (!$this->validatePattern($rule[$field]['pattern'], $subject))
            {
                $this->errors[$errorKey][$field] = sprintf($rules[$field]['message'], $field, $subject);
            }
        } elseif (isset($rules[$field]['function']))
        {
            if (!$this->validateCallback($rules[$field]['function'], [$subject]))
            {
                $this->errors[$errorKey][$field] = sprintf($rules[$field]['message'], $field, $subject);
            }
        }elseif (isset($rules[$field]['with'])){
            $result = $this->validateWith($rules[$field]['with'],$field,'uk');
            if(count($result) > 0){
                $this->errors[$errorKey][$field] = implode('<br />', $result);
            }
        }
    }

    /**
     * @return boolean validation result
     */
    public function validate()
    {
        $this->errors = null;
        $values = $this->getFields();
        foreach ($values as $field => $value)
        {
            if (array_key_exists($field, static::$rules))
            {
                $nameInView = isset(static::$rules[$field]['view_key']) ? static::$rules[$field]['view_key'] : null;
                $this->applyRules($field, $value, $nameInView);
            }
        }

        return empty($this->errors);
    }

    protected function validatePattern($pattern, $test)
    {
        return preg_match("#^$pattern$#", $test);
    }

    protected function validateCallback($method, $args = [])
    {
        if (method_exists($this, $method))
        {
            return call_user_func_array([$this, $method], $args);
        }
    }

    /**
     * 
     * @param array $filter if set only desired fields returned
     * @return array mapping props to array of the entity
     */
    protected function getFields($filter = [])
    {
        $reflection = new \ReflectionClass($this);
        $props = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        $ret = array();
        foreach ($props as $prop)
        {
            $ret[$prop->name] = $this->{$prop->name};
        }
        if (!empty($filter))
        {
            foreach ($ret as $key=>$val)
            {
                if (!in_array($key, $filter))
                {
                    unset($ret[$key]);
                }
            }
        }
        return $ret;
    }

    protected function populate($data)
    {
        // set all existing properties
        foreach ((array) $data as $key => $value)
        {
            // should be public, use Reflection instead
            if (property_exists($this, $key))
            {
                $this->$key = $value;
            }
        }
    }

}
