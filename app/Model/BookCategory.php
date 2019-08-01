<?php
namespace App\Model;

use App\Model\Entity;
use App\Model\DbTable\BookTableGateway;
/**
 * @package lab_books
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 */

class BookCategory extends Entity
{
    public $id;
    public $book_id;
    public $category_id;
    
    protected $_tableGatewayClass = 'App\\Model\\DbTable\\BookCategoryTable';
    protected $_modelClass = self::class;
}

