<?php

namespace App\Model\Database;

use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Ddl\Column;
use Zend\Db\Sql\Ddl\Constraint;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use App\Model\Book;
use App\Model\Author;
use App\Model\Publisher;
use App\Model\EntityFile;
use App\Model\Category;
use App\Model\BookAuthor;
use App\Model\BookCategory;

class Migration
{

    protected $prefix = '';
    protected $sql;
    protected $adapter;
    protected $results = array();
    protected $errors = array();

    public function __construct()
    {
        $this->adapter = new Adapter(
                config('application.database.adapter')
        );
        $this->sql = new Sql($this->adapter);
        $this->prefix = config('application.database.table_prefix', '');
    }

    public function up()
    {
        try
        {
            $table = (new Ddl\CreateTable("{$this->prefix}publishers"))
                    ->addColumn(new Column\Integer('id', null, null, ['AUTO_INCREMENT' => true]))
                    ->addConstraint(new Constraint\PrimaryKey('id'))
                    ->addColumn(new Column\Varchar('name', 255))
                    ->addConstraint(new Constraint\UniqueKey('name', 'idx_unq_publisher_name'))
                    ->addColumn(new Column\Varchar('address', 255))
                    ->addColumn(new Column\Varchar('phone', 128))
                    ->addColumn(new Column\Timestamp('created_at'))
                    ->addColumn(new Column\Datetime('updated_at', true));
            $this->executeDdl($table);

            $table = (new Ddl\CreateTable("{$this->prefix}books"))
                    ->addColumn(new Column\Integer('id', null, null, ['AUTO_INCREMENT' => true]))
                    ->addConstraint(new Constraint\PrimaryKey('id'))
                    ->addColumn(new Column\Varchar('isbn', 128, false, null))
                    ->addColumn(new Column\Varchar('name', 255, false, null))
                    ->addConstraint(new Constraint\UniqueKey('name', 'idx_unq_book_name'))
                    ->addColumn(new Column\Integer('publisher_id', false, 0))
                    ->addConstraint(new Constraint\ForeignKey('fk_book_publisher', 'publisher_id', "{$this->prefix}publishers", 'id'))
                    ->addColumn(new Column\Datetime('issued_at', true, null, ['comment' => 'дата издательства']))
                    ->addColumn(new Column\Timestamp('created_at'))
                    ->addColumn(new Column\Datetime('updated_at', true))
                    ->addColumn(new Column\Datetime('deleted_at', true));
            $this->executeDdl($table);

            $table = (new Ddl\CreateTable("{$this->prefix}authors"))
                    ->addColumn(new Column\Integer('id', null, null, ['AUTO_INCREMENT' => true]))
                    ->addConstraint(new Constraint\PrimaryKey('id'))
                    ->addColumn(new Column\Varchar('name', 255))
                    ->addConstraint(new Constraint\UniqueKey('name', 'idx_unq_author_name'))
                    ->addColumn(new Column\Timestamp('created_at'))
                    ->addColumn(new Column\Datetime('updated_at', true));
            $this->executeDdl($table);

            $table = (new Ddl\CreateTable("{$this->prefix}entity_files"))
                    ->addColumn(new Column\Integer('id', null, null, ['AUTO_INCREMENT' => true]))
                    ->addConstraint(new Constraint\PrimaryKey('id'))
                    ->addColumn(new Column\Varchar('name', 32, true)) // md5 name
                    ->addConstraint(new Constraint\UniqueKey('name', 'idx_unq_file_name'))
                    ->addColumn(new Column\Integer('book_id', true, null, ['INDEX' => 'idx_file_book_id (book_id ASC)']))
                    ->addColumn(new Column\Varchar('imageinfo', 1024, true, null, ['comment' => 'Serialized array info about image, concerning storage,representation: thumbs,dimmensions etc.']))
                    ->addColumn(new Column\Varchar('source_name', 255, true, null, ['comment' => 'Original base name of the file']))
                    ->addColumn(new Column\Varchar('source_path', 512, true, null, ['comment' => 'Original URL of the file. For remote files']))
                    ->addColumn(new Column\Timestamp('created_at'))
                    ->addColumn(new Column\Datetime('updated_at', true));
            $this->executeDdl($table);

            //written_by
            $table = (new Ddl\CreateTable("{$this->prefix}book_authors"))
                    ->addColumn(new Column\Integer('id', null, null, ['AUTO_INCREMENT' => true]))
                    ->addConstraint(new Constraint\PrimaryKey('id'))
                    ->addColumn(new Column\Integer('author_id', null, null, ['INDEX' => 'idx_author_id (author_id ASC)']))
                    ->addColumn(new Column\Integer('book_id', null, null, ['INDEX' => 'idx_author_book_id (book_id ASC)']));
            $this->executeDdl($table);


            $table = (new Ddl\CreateTable("{$this->prefix}categories"))
                    ->addColumn(new Column\Integer('id', null, null, ['AUTO_INCREMENT' => true]))
                    ->addConstraint(new Constraint\PrimaryKey('id'))
                    ->addColumn(new Column\Integer('parent_id'))
                    ->addConstraint(new Constraint\ForeignKey('fk_category_parent', 'parent_id', "{$this->prefix}categories", 'id'))
                    ->addColumn(new Column\Varchar('title', 255))
                    ->addConstraint(new Constraint\UniqueKey('title', 'idx_unq_category_name'))
                    ->addColumn(new Column\Timestamp('created_at'))
                    ->addColumn(new Column\Datetime('updated_at', true));
            $this->executeDdl($table);

            $table = (new Ddl\CreateTable("{$this->prefix}book_categories"))
                    ->addColumn(new Column\Integer('id', null, null, ['AUTO_INCREMENT' => true]))
                    ->addConstraint(new Constraint\PrimaryKey('id'))
                    ->addColumn(new Column\Integer('category_id', null, null, ['INDEX' => 'idx_category_id (category_id ASC)']))
                    ->addColumn(new Column\Integer('book_id', null, null, ['INDEX' => 'idx_category_book_id (book_id ASC']));
            $this->executeDdl($table);

            $this->foreignKeyCheck(false);
            $sql="INSERT INTO `{$this->prefix}categories` (`id`, `parent_id`, `title`, `created_at`, `updated_at`) VALUES (NULL, 0, 'Категории', NULL, NOW())" ;
            $res = $this->adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
//            $category = new Category([],true);
//            $data = ['parent_id' =>null, 'title' => 'Root', 'updated_at' => date('Y-m-d H:i:s')];
//            $res = $category->setData($data)->save();
            $this->foreignKeyCheck(true);
        } catch (\Exception $ex)
        {
            $this->results[] = 'ERROR: ' . $ex->getMessage() . PHP_EOL . $table->getSqlString();
        } finally
        {
            return ['results' => $this->results, 'errors' => $this->errors];
        }
    }

    public function down()
    {
        if ('mysql' === strtolower($this->adapter->getPlatform()->getName()))
        {
            return $this->safeDown();
        }

        $drop = new Ddl\DropTable("{$this->prefix}books");
        $this->executeDdl($drop);

        $drop = new Ddl\DropTable("{$this->prefix}authors");
        $this->executeDdl($drop);

        $drop = new Ddl\DropTable("{$this->prefix}publishers");
        $this->executeDdl($drop);

        $drop = new Ddl\DropTable("{$this->prefix}entity_files");
        $this->executeDdl($drop);

        $drop = new Ddl\DropTable("{$this->prefix}book_authors");
        $this->executeDdl($drop);

        $drop = new Ddl\DropTable("{$this->prefix}categories");
        $this->executeDdl($drop);

        $drop = new Ddl\DropTable("{$this->prefix}book_categories");
        $this->executeDdl($drop);

        return ['results' => $this->results, 'errors' => $this->errors];
    }

    protected function safeDown()
    {
        $schema = $this->adapter->getCurrentSchema();
        $prefix = $this->prefix;
        $created = [
            'publishers', 'books', 'authors', 'entity_files', 'book_authors', 'categories', 'book_categories'
        ];
        foreach ($created as &$v)
        {
            $v = $prefix . $v;
        }
        $quotedList = $this->adapter->getPlatform()
                ->quoteValueList($created);

        $sql = "SELECT 
            TABLE_NAME, TABLE_SCHEMA, ENGINE, TABLE_TYPE
        FROM information_schema.tables
        WHERE
        table_schema = '$schema'
        AND TABLE_NAME IN ($quotedList)
        AND TABLE_TYPE = 'BASE TABLE'";
        $resultSet = $this->adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        foreach ($resultSet as $row)
        {
            $drop = new Ddl\DropTable($row->TABLE_NAME);
            $this->executeDdl($drop);
        }

        return ['results' => $this->results, 'errors' => $this->errors];
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

    protected function saveBundle($bundle)
    {
        $entity = $bundle;
        $book = new Book();
        $data = $entity['categories'];
        foreach ($data as $category)
        {
            $cat = !empty($category['id']) ? new Category(['id' => $category['id']]) : (new Category())->loadBy(['title' => $category['title']]);
            $catIds[] = $cat->isExists() ? $cat->id : $cat->setData($category)->save();
            $book->setCategory($cat);
        }
        
        $data = $entity['authors'];
        foreach ($data as $author)
        {
            $a = !empty($author['id']) ? new Author(['id' => $author['id']]) : (new Author())->loadBy(['name' => $author['name']]);
            $authorIds[] = $a->isExists() ? $a->id : $a->setData($author)->save();
            $book->setAuthor($a);
        }
        
        $data = $entity['publisher'];
        if (empty($data['address']))
        {
            $data['address'] = '<none>';
        }
        if (empty($data['phone']))
        {
            $data['phone'] = '<none>';
        }
        
        $publisher = !empty($publisher['id']) ? new Publisher(['id' => $data['id']]) : (new Publisher([]))->loadBy(['name' => $data['name']]);
        $publisher_id = $publisher->isExists() ? $publisher->id : $publisher->setData($data)->save();
        $book->setPublisher($publisher);

        if (empty($entity['book']['isbn']))
        {
            $entity['book']['isbn'] = '<none>';
        }
        $book->setData($entity['book']);
        $book_id = $book->save();

        if (!empty($entity['files']))
        {
            foreach ($entity['files'] as $file)
            {
                $fileModel = new EntityFile();
                $uploadPath = config('application.image.upload_path', APPPATH . DS, 'public/images');
                $fileModel->setBook($book);

                $opts = array('http' =>
                    array(
                        'method' => 'GET',
                        'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0',
                        'ignore_errors' => '1'
                    )
                );
                $remoteFile = new Lib\UploadFileRemote($file['source_path'], $opts);

                $destination = $uploadPath . DS . $book_id . DS . $remoteFile->getOriginalName();
                $remoteFile->setDestination($destination);

                $fileModel->setUploadFile($remoteFile);
                $res = $fileModel->save();
                $fileModel->hasErrors() && $this->errors[] = $fileModel->errors();
            }
        }
    }

    public function seed()
    {
        $bundles = require_once APPPATH . DS . 'storage' . DS . 'sample_data.php';
        foreach ($bundles as $bundle)
        {
            $this->saveBundle($bundle);
        }
        return['results' => $this->results, 'errors' => $this->errors];
    }

    /**
     * 
     * @param array $tables Tables to truncate
     * @return array Array with results
     */
    public function truncate($tables = [])
    {
        /** @todo make method */
        $prefix = $this->prefix;
        $created = [
            'publishers', 'books', 'authors', 'entity_files', 'book_authors', 'categories', 'book_categories'
        ];
        foreach ($created as &$v)
        {
            $v = $prefix . $v;
        }
        /** End of @todo make method */
        if ('*' == $tables[0])
        {
            $this->foreignKeyCheck(false);
            $this->adapter->setProfiler(new \Zend\Db\Adapter\Profiler\Profiler());
            foreach ($created as $tableName)
            {
                $resultSet = $this->adapter->query('TRUNCATE TABLE ' . $this->adapter->getPlatform()->quoteIdentifier($tableName), 'execute');
                $results = $resultSet->getResource();
                $profilerInfo = $this->adapter->getProfiler()->getLastProfile();
                $err = !empty($results->error) ? $results->error . \implode(' ', $results->error_list) : 'none';
                $message = sprintf('SQL: %s Errors: %s', $profilerInfo['sql'], $err);
                $this->results[] = $message;
                if ($err != 'none')
                {
                    $this->errors[] = $err;
                }
            }

            $this->foreignKeyCheck(true);
        }

        return['results' => $this->results, 'errors' => $this->errors];
    }

    protected function executeDdl(Ddl\SqlInterface $table)
    {
        try
        {
            $result = $this->adapter->query(
                            $this->sql->getSqlStringForSqlObject($table),
                            Adapter::QUERY_MODE_EXECUTE
                    )->getAffectedRows();
            $this->results[] = sprintf('Affected Rows: %s SQL: %s', $result, $table->getSqlString());
        } catch (\Exception $ex)
        {
            $this->errors[] = sprintf('Error: %s SQL: %s', $ex->getMessage(), $table->getSqlString());
        }
    }

}
