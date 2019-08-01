<?php

namespace App\Model;

use App\Model\Entity;
use App\Model\DbTable\BookTableGateway;
use App\Model\BookAuthor;

/**
 * @package {$prefix}books
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 */
class Book extends Entity
{

    public $id;
    public $isbn;
    public $name;
    public $publisher_id;
    public $issued_at;
    public $deleted_at;
    public $created_at;
    public $updated_at;

    /**  @var array  */
    protected $authorIds = [];

    /**  @var array  */
    protected $categoryIds = [];
    protected $_tableGatewayClass = 'App\\Model\\DbTable\\BookTable';
    protected $_modelClass = self::class;
    protected static $rules = [
        'name' => ['pattern' => '[\w\s\-\_]{5,}', 'message' => 'Только буквенно цифровые символы, дефисы и подчеркивания. Не менее 5 символов', 'view_key' => 'book'],
        //'isbn' => ['pattern' => '\w\s\-\_]{11,20}', 'message' => 'Поле %s должно быть заполнено', 'view_key' => 'book'],
        'isbn' => ['with' => '\\Symfony\\Component\\Validator\\Validation', 'view_key' => 'book', 'contraints' => [
                'NotBlank' => ['message' => 'book.isbn.not_blank'],
                'Length' => ['min' => 10, 'max' => 20, 'message' => 'book.isbn.length']
            ]],
        'categories' => ['pattern' => '[\d]+', 'message' => 'Поле %s должно быть заполнено и быть длиной до 20 символов', 'view_key' => 'book'],
        '$publisher_id' => ['function' => 'notEmpty', 'message' => 'Поле %s должно быть заполнено', 'view_key' => 'book'],
        'issued_at' => ['function' => 'notEmpty', 'message' => 'Поле %s должно быть заполнено', 'view_key' => 'book'],
    ];

    protected function notEmpty($value)
    {
        return !empty($value);
    }

    public function getSearch($where = null, $fields = null, $order = null, $direction = "asc", $limit = null, $page = null)
    {
        $book = new self();
        $table = $book->getTableGateway();
        $adapter = $table->getAdapter();
        $prefix = $table->getPrefix();
        $sql = "";
        /**
          SELECT {$prefix}books.*,{$prefix}categories.* ,bc.* ,{$prefix}authors.*, {$prefix}publishers.*,{$prefix}entity_files.* FROM {$prefix}books
          LEFT JOIN {$prefix}book_categories bc ON bc.book_id = {$prefix}books.id
          LEFT JOIN {$prefix}categories  ON {$prefix}categories.id = bc.category_id
          LEFT JOIN {$prefix}book_authors ba on {$prefix}books.id = ba.book_id
          LEFT JOIN {$prefix}authors on {$prefix}authors.id = ba.author_id
          LEFT JOIN {$prefix}publishers on {$prefix}publishers.id = {$prefix}books.publisher_id
          LEFT JOIN {$prefix}entity_files on {$prefix}entity_files.book_id = {$prefix}books.id
         */
        $statement = $adapter->createStatement($sql);
        $result = $statement->execute();
        $rows = array();
        foreach ($result as $row)
        {
            $rows[] = $row;
        }

        return $rows;
    }

    public function info($extended = false)
    {
        $authors = [];
        $categories = [];
        $files = [];

        foreach (BookAuthor::all(['book_id' => $this->id]) as $author)
        {
            $authors[$author['author_id']] = $author;
        }

        foreach (BookCategory::all(['book_id' => $this->id]) as $category)
        {
            $categories[$category['category_id']] = $category;
        }

        foreach (EntityFile::all(['book_id' => $this->id]) as $photo)
        {
            $files[$photo['id']] = $photo;
        }
        $publisher = Publisher::one(['id' => $this->publisher_id]);
        
        $book = $this->getFields();
        $book['authors'] = array_keys($authors);
        $book['categories'] = array_keys($categories);
        $book['files'] = !$extended ? array_keys($files) : $files;
        $book['publisher'] = $publisher;
        return [
            'book' => $book,
        ];
    }

    public function getList($where = null, $fields = null, $order = null, $direction = "asc", $limit = null, $page = null)
    {
        $list = static::all($where, $fields, $order, $direction, $limit, $page);
        $result = [];
        foreach ($list as $book)
        {
            $this->id = $book['id'];
            $this->load();
            $result[] = $this->info();
        }
        return $result;
    }

    public function setAuthorIds($ids)
    {
        $this->authorIds = (array) $ids;
        return $this;
    }

    public function setAuthor(Author $author)
    {
        if ($author->isExists())
        {
            $this->authorIds = array_merge($this->authorIds, [$author->id]);
        }
        return $this;
    }

    public function setCategory(Category $category)
    {
        if ($category->isExists())
        {
            $this->categoryIds = array_merge($this->categoryIds, [$category->id]);
        }
        return $this;
    }

    public function setPublisher(Publisher $publisher)
    {
        if ($publisher->isExists())
        {
            $this->publisher_id = $publisher->id;
        }
        return $this;
    }

    public function setData($data)
    {
        if (isset($data['authors']))
        {
            $this->setAuthorIds($data['authors']);
        }

        if (isset($data['categories']))
        {
            $this->setCategoryIds($data['categories']);
        }

        return parent::setData($data);
    }

    public function setCategoryIds($ids)
    {
        $this->categoryIds = (array) $ids;
        return $this;
    }

    public function validate()
    {

        parent::validate();
    }

    public function save($validate = null)
    {
        $bookId = parent::save($validate);

        if ($this->isExists())
        {
            $origCat = BookCategory::all(['book_id' => $this->id], ['id']);
            foreach ($origCat as $cat)
            {
                (new BookCategory(['id' => $cat['id']]))->delete();
            }
            $origAuthor = BookAuthor::all(['book_id' => $this->id], ['id']);
            foreach ($origAuthor as $author)
            {
                (new BookAuthor(['id' => $author['id']]))->delete();
            }
        }
        foreach ($this->categoryIds as $id)
        {
            if (empty($id))
            {
                continue;
            }
            $category = new BookCategory([]);
            $category->setData(['book_id' => $this->id, 'category_id' => $id]);
            $res = $category->save($validate);
        }
        foreach ($this->authorIds as $id)
        {
            if (empty($id))
            {
                continue;
            }
            $author = new BookAuthor();
            $author->setData(['book_id' => $this->id, 'author_id' => $id]);
            $res = $author->save($validate);
        }
        return $bookId;
    }

    public function delete($id = null)
    {
        $bookAuthors = BookAuthor::all(['book_id' => $id]);
        foreach ($bookAuthors as $author)
        {
            $res = (new BookAuthor(['id' => $author['id']]))->delete();
        }
        $bookCat = BookCategory::all(['book_id' => $id]);
        foreach ($bookCat as $cat)
        {
            $res = (new BookCategory(['id' => $cat['id']]))->delete();
        }
        $bookPhotos = EntityFile::all(['book_id' => $id]);
        foreach ($bookPhotos as $photo)
        {
            $res = (new EntityFile(['id' => $photo['id']]))->delete();
        }
        return parent::delete($id);
    }

}
