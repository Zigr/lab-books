<?php

define('APPPATH', __DIR__);
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

function app_psr4_autoloader($class)
{

    // project-specific namespace prefix
    $prefix = 'App\\';
    // base directory for the namespace prefix
    $base_dir = APPPATH . DS;

    // does the class use the namespace prefix?
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0)
    {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . DS . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file))
    {
        require_once $file;
    }
}

if (!function_exists('config'))
{
    function config($module, $default = array())
    {
        static $data;
        $parts = explode('.', $module);

        if (empty($parts))
        {
            return $default;
        }
        if (!isset($data[$module]))
        {
            $dist = file_exists(APPPATH . DS . 'config/' . $parts[0] . '.php') ? require APPPATH . DS . 'config/' . $parts[0] . '.php' : [];
            $local = file_exists(APPPATH . DS . 'config/' . $parts[0] . '.local.php') ? APPPATH . DS . 'config/' . $parts[0] . '.local.php' : [];
            $data[$parts[0]] = array_merge_recursive($dist, $local);
        }
        $arr = $data[$parts[0]];
        array_shift($parts);
        foreach ($parts as $key)
        {
            if (!isset($arr[$key]))
            {
                return $default;
            }
            $arr = $arr[$key];
        }
        return $arr;
    }
}

/**
 * @todo see twelve-factor applications @link http://www.12factor.net/ for improvements
 * @param string $varname variable name 
 * @return $_ENV value
 */
if (!function_exists('env'))
{
    function env($varname,$default ='')
    {
        $var = getenv($varname);
        return $var !== false ? $var : $default;
    }
}

if (!function_exists('is_cli'))
{

    function is_cli()
    {
        if (php_sapi_name() === 'cli')
        {
            return true;
        }
        return false;
    }

}

