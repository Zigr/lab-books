<?php

namespace App\Exception;

class AppException extends \Exception
{

    public static function getExeptionChain(\Exception $e, $method, $line)
    {
        $description = [];
        do
        {
            $description[] = sprintf("File: %s Line: %d Message: %s Code: (%d) Class: [%s]\n", $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), get_class($e));
        } while ($e = $e->getPrevious());

        $message = sprintf('[%s]: %s', $method, implode(PHP_EOL, $description));

        return $message;
    }

}
