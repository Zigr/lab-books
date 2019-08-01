<?php

namespace App\Model;

use App\Model\Entity;
use App\Model\DbTable\BookTableGateway;

/**
 * @package lab_books
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 */
class Author extends Entity
{

    public $id;
    public $name;
    public $created_at;
    public $updated_at;
    protected $_tableGatewayClass = 'App\\Model\\DbTable\\AuthorTable';
    protected $_modelClass = self::class;
    protected static  $rules = [
        'name' => ['pattern' => '[\w\s\-\_]{5,}', 'message' => 'Только буквенно цифровые символы, дефисы и подчеркивания. Не менее 5 символов','view_key'=>'author'],
    ];

    public function info($id = null)
    {
        $books = [];

        if ($this->isExists())
        {
            foreach (BookAuthor::all(['author_id' => $this->id]) as $book)
            {
                $books[] = Book::one(['id' => $book['author_id']]);
            }
        }

        return [
            'author' => $this->getFields(),
            'books' => $books,
        ];
    }

    public function getList($where = null, $fields = null, $order = null, $direction = "asc", $limit = null, $page = null)
    {
        $list = static::all($where, $fields, $order, $direction, $limit, $page);
        return $list;
    }
    
    public function save($validate = null)
    {
        $this->updated_at = (new \DateTime)->format('Y-m-d H:i:s');
        return parent::save($validate);
    }

}
