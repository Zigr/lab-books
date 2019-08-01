<?php
namespace App\Model;

use App\Model\Entity;
use App\Model\DbTable\BookTableGateway;
/**
 * @package lab_books
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 */

class BookAuthor extends Entity
{
    public $id;
    public $book_id;
    public $author_id;
    
    protected $_tableGatewayClass = 'App\\Model\\DbTable\\BookAuthorTable';
    protected $_modelClass = self::class;
}

