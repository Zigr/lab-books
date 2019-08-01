<?php

namespace App\Lib;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Sql;

class ModelDb extends AbstractTableGateway
{

    protected $adapter;
    protected $profiler;

    /** @inherit */
    protected $table;
    protected $query;
    protected $prefix;

    public function __construct()
    {
        /**  @todo  */
        //$this->featureSet = new Feature\FeatureSet();
        //$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());

        $this->adapter = new Adapter(
                config('application.database.adapter')
        );

        

        $this->query = new Sql($this->adapter);
        $this->prefix = config('application.database.table_prefix', '');
        $this->table = $this->prefix . $this->table;

        /** @link https://framework.zend.com/manual/2.4/en/modules/zend.db.table-gateway.html description */
        // $this->table = 'my_table';
        //$this->featureSet = new Feature\FeatureSet();
        //\Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($this->adapterr);
        //$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        //$this->featureSet->addFeature(new Feature\RowGatewayFeature($this->getPrimaryKey()));
        
        $this->initialize();
    }

    /**
     * 
     * @return string prefixed table name
     */
//    public function getTable()
//    {
//
//        return $this->prefix . $this->table;
//    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function truncate()
    {
        $this->foreignKeyCheck(false);
        $this->getAdapter()->query('TRUNCATE TABLE ' . $this->adapter->getPlatform()->quoteIdentifier($this->table));
        $this->foreignKeyCheck(true);
        return $this;
    }

    public function getPrimaryKey(){
        return !empty(($this->primaryKey)) ? $this->primaryKey : 'id';
    }


    protected function foreignKeyCheck($mode)
    {
        if ('mysql' === strtolower($this->adapter->getPlatform()->getName()))
        {
            $check = intval(boolval($mode));
            $sql = "SET FOREIGN_KEY_CHECKS = $check";
            $res = $this->adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        }
    }

}
