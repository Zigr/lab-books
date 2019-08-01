<?php

namespace App\Lib;

use App\Lib\UploadInterface;

/**
 * @date Jun 16, 2019
 * @encoding UTF-8
 *  
 */
class UploadFileRemote implements UploadInterface
{

    protected $url;
    protected $options;
    protected $params;
    protected $destination;

    /**
     * @param string $url
     * @param array $options
     * @param array $params
     * @param array $destination Where to copy
     */
    public function __construct($url, $options = null, $params = null, $destination = null)
    {
        $this->url = $url;
        $this->options = $options;
        $this->params = $params;
        $this->destination = $destination;
    }

    public function __toString()
    {
        return $this->url;
    }

    public function getContextOptions()
    {
        return $this->options;
    }

    /**
     * 
     * @param array $options stream context options accepted by @link https://www.php.net/manual/en/function.stream-context-create.phpn
     * @return $this
     */
    public function setContextOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getContextParams()
    {
        return $this->params;
    }

    /**
     * @param array $params stream context parameters accepted by @link https://www.php.net/manual/en/function.stream-context-create.phpn
     * @return $this
     */
    public function setContextParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function getOriginalName()
    {
        $pathParts = explode('/', parse_url($this->url, PHP_URL_PATH));
        return end($pathParts);
    }

    public function isRemote()
    {
        return true;
    }

    public function setDestination($dest)
    {
        $this->destination = $dest;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getRealPath()
    {
        return $this->url;
    }

}
