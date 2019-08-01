<?php

namespace App\Model;

use App\Model\Entity;
use App\Model\Book;
use App\Model\DbTable\BookTableGateway;
use App\Exception\AppException;
use App\Lib\UploadInterface;

/**
 * @package lab_books
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 */
class EntityFile extends Entity
{

    public $id;
    public $name;
    public $book_id;
    public $imageinfo;
    public $source_path;
    public $source_name;
    public $updated_at;
    public $created_at;
    protected $_tableGatewayClass = 'App\\Model\\DbTable\\EntityFileTable';
    protected $_modelClass = self::class;
    protected $uploadFile;
    protected static $rules = [
        'name' => ['pattern' => '[\w\s\-\_]{5,}', 'message' => 'Только буквенно цифровые символы, дефисы и подчеркивания. Не менее 5 символов', 'view_key' => 'book'],
        'isbn' => ['pattern' => '\w\s\-\_]{11,20}', 'message' => 'Поле %s должно быть заполнено', 'view_key' => 'book'],
        'categories' => ['pattern' => '[\d]+', 'message' => 'Поле %s должно быть заполнено и быть длиной до 20 символов', 'view_key' => 'book'],
        '$publisher_id' => ['function' => 'notEmpty', 'message' => 'Поле %s должно быть заполнено', 'view_key' => 'book'],
        'issued_at' => ['function' => 'notEmpty', 'message' => 'Поле %s должно быть заполнено', 'view_key' => 'book'],
    ];

    /**
     * Ensure the root directory exists.
     * @param string $rootDir root directory path
     * @return void
     */
    protected function ensureDirectory($rootDir)
    {
        if (!is_dir($rootDir))
        {
            $umask = umask(0);
            if (!@mkdir($rootDir, 0777, true))
            {
                $mkdirError = error_get_last();
            }
            umask($umask);
            clearstatcache(false, $rootDir);
            if (!is_dir($rootDir))
            {
                $errorMessage = isset($mkdirError['message']) ? $mkdirError['message'] : '';
                $this->errors[] = sprintf('Impossible to create the root directory "%s". %s', $rootDir, $errorMessage);
            }
        }
    }

    /**
     * 
     * @param UploadInterface $file 
     */
    public function setUploadFile(UploadInterface $file)
    {
        $this->uploadFile = $file;
        return $this;
    }

    /**
     * @param string $name hashed name
     * @return string relative file path
     */
    public function filePath()
    {
        return $this->isExists() ? $this->book_id . DS . $this->name : null;
    }

    public function getImageinfo()
    {
        return ($this->isExists()) ? json_decode($this->imageinfo) : null;
    }

    public function setBook(Book $book)
    {
        $this->book = $book;
        return $this;
    }

    public function save($validate = null)
    {
        $file = $this->uploadFile;

        if (empty($file))
        {
            return false;
        }

        $destDir = APPPATH . str_replace(APPPATH, '', dirname($file->getDestination()));
        $this->ensureDirectory($destDir);

        $origFileName = $file->getOriginalName();
        $origFileExt = pathinfo($origFileName, PATHINFO_EXTENSION);

        $destNameEncoded = md5($destDir . DS . $file->getOriginalName());
        $destPathName = sprintf('%s/%s',
                $destDir,
                $destNameEncoded  //,$origFileExt
        );

        $srcPathName = $file->getRealPath();
        $srcContext = stream_context_create($file->getContextOptions(), $file->getContextParams());
        $srcStream = fopen($srcPathName, 'r', false, $srcContext);

        $dstContext = stream_context_create();
        $dstStream = fopen($destPathName, 'w+b', false, $dstContext);

        if (!($srcStream && $dstStream) || stream_copy_to_stream($srcStream, $dstStream) === false || !(fclose($srcStream) && fclose($dstStream)))
        {
            $this->errors[] = "Unable copy $srcPathName to $destPathName";
            return false;
        }
        $this->book_id = $this->book->id;
        $this->source_name = $file->getOriginalName();
        $this->name = $destNameEncoded;

        $info = getimagesize($destPathName);
        $info = array_merge($info, ['size' => filesize($destPathName)]);

        $this->imageinfo = json_encode($info);
        $this->created_at = $this->updated_at = (new \DateTime())->format('Y-m-d H:i:s');
        if ($file->isRemote())
        {
            $this->source_path = $file->__toString();
        }
        $this->setData($this->getFields());
        $res = parent::save($validate);
        if ($res)
        {
            $this->errors = null;
        }
        return $res;
    }

}
