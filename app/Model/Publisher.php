<?php

namespace App\Model;

use App\Model\Entity;
use App\Model\DbTable\BookTableGateway;

/**
 * @package lab_books
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 */
class Publisher extends Entity
{

    public $id;
    public $name;
    public $address;
    public $phone;
    public $updated_at;
    public $created_at;
    protected $_tableGatewayClass = 'App\\Model\\DbTable\\PublisherTable';
    protected $_modelClass = self::class;
    protected static  $rules = [
        'name' => ['pattern' => '[\w\s\-\_]{5,}', 'message' => 'Только буквенно цифровые символы, дефисы и подчеркивания. Не менее 5 символов','view_key'=>'publisher'],
    ];

    public function info()
    {
        $books = [];

        if ($this->isExists())
        {
            foreach (Book::all(['publisher_id' => $this->id]) as $book)
            {
                $books[] =Book::one(['id' => $book['id']]);
            }
        }

        return [
            'publisher' => $this->getFields(),
            'books' => $books,
        ];
    }

    public function getList($where = null, $fields = null, $order = null, $direction = "asc", $limit = null, $page = null)
    {
        $list = static::all($where, $fields, $order, $direction, $limit, $page);
        
        $result = [];
        foreach ($list as $publisher)
        {
            $this->id = $publisher['id'];
            $this->load();
            $result[] = $this->getFields();
        }
        return $result;
    }

}
