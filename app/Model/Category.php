<?php

namespace App\Model;

use App\Model\Entity;
use App\Model\BookCategory;

/**
 * @package lab_books
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 * @license http://URL name
 */
class Category extends Entity
{

    public $id;
    public $parent_id;
    public $title;
    public $created_at;
    public $updated_at;
    protected $_tableGatewayClass = 'App\\Model\\DbTable\\CategoryTable';
    protected $_modelClass = self::class;

    /** validation rules */
    protected static  $rules = [
        'name' => ['pattern' => '[\w\s\-\_]{5,}', 'message' => 'Допускаются только буквенно цифровые символы, дефисы и подчеркивания. Не менее 5 символов', 'view_key' => 'category'],
    ];

    public function info()
    {
        $books = [];

        if ($this->isExists())
        {
            foreach (BookCategory::all(['category_id' => $this->id]) as $book)
            {
                $books[] = Book::one(['id' => $book['id']]);
            }
        }
        return [
            'category' => $this->getFields(),
            'books' => $books,
        ];
    }

    public function delete($id = null)
    {
        $books = BookCategory::all(['category_id' => $id]);
        foreach ($books as $book)
        {
            $res = (new BookCategory($book))
                    ->delete();
        }
        $res = parent::delete($id);
    }

    public function setData($data)
    {
        if(empty($data['parent_id'])){
            $data['parent_id'] = 1;
        }
        return parent::setData($data);
    }
}
