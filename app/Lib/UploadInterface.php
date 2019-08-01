<?php

namespace App\Lib;

/**
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 * @date Jun 14, 2019
 * @encoding UTF-8
 */
interface UploadInterface
{
    /**
     * @return string Client original name
     */
    function getOriginalName();

    /**
     * @return boolean Whether this is a remote file
     */
    function isRemote();
    /**
     * @return array stream context options for stream_context_create() @link https://www.php.net/manual/en/function.stream-context-create.php 
     */
    function getContextOptions();
    /**
     * @return array stream context parameters for stream_context_create() @link https://www.php.net/manual/en/function.stream-context-create.php 
     */
    function getContextParams();
    /**
     * @return file path see @link https://www.php.net/manual/en/splfileinfo.tostring.php 
     */
    function __toString();
    /**
     * @return string The path, where to copy
     */
    function getDestination();
}
