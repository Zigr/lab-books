<?php

namespace App\Lib;

use \Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Lib\UploadInterface;

/**
 * wrapper around Symfony\Component\HttpFoundation\File\UploadedFile;

 * @date Jun 14, 2019
 * @encoding UTF-8
 *  
 */
class UploadFileLocal implements UploadInterface
{

    protected $file;
    protected $destination;

    public function __construct($file, $destination = null)
    {
        $this->file = $file;
        $this->destination = $destination;
    }

    public function __toString()
    {
        return parent::__toString();
    }

    public function getContextOptions()
    {
        return null;
    }

    public function getContextParams()
    {
        return null;
    }

    public function getOriginalName()
    {
        return $this->file->getClientOriginalName();
    }

    public function isRemote()
    {
        return false;
    }

    public function getDestination()
    {
        return $this->destination;
        ;
    }

    public function getRealPath()
    {
        return $this->file->getRealPath();
    }

}
