<?php
namespace App\Exception;

use App\Exception\AppException;

use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\RequestContextAwareInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class RoutingException extends AppException{
    
}

