<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 * @author ZIgr <zaporozhets igor at gmai coml>
 * 
 *  Run build-in php server
 *    cd project_folder
 *    php -S localhost:8888 -t ./public/
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Helper\SlotsHelper;
use App\Lib\Router;
use App\Lib\Templating\Helper\UrlHelper;

class Application
{

    protected static $classes = [
    ];

    /**
     * Registers commonly used classes
     * @param string $alias
     * @param object $class
     */
    public static function addClass($alias, $class)
    {
        if (!array_key_exists($alias, static::$classes))
        {
            static::$classes[$alias] = $class;
        }
        return static::$classes[$alias];
    }

    public function __construct()
    {
        $env = config('application.APP_ENV', 'dev');
        if ($env === 'dev')
        {
            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 'on');
            ini_set('html_errors', 'on');
        }
        $this->registerAutoload();

        $filesystemLoader = new FilesystemLoader(APPPATH . DS . 'Resources/views/%name%.html.php');
        $templating = Application::addClass('Templating', new PhpEngine(new TemplateNameParser(), $filesystemLoader));
        $templating->setHelpers([new SlotsHelper(), new UrlHelper()]);
 
        $translator = Application::addClass('Translator', new Symfony\Component\Translation\Translator('uk_UA')); ;
        $translator->addLoader('array', new Symfony\Component\Translation\Loader\ArrayLoader());
    }

    public function run()
    {
        $router = Application::addClass('Router', new Router(config('application.routes')));
        $request = Application::addClass('Request', Request::createFromGlobals());
        $response = $router->dispatch($request);
        $response->send();
    }

    public function registerAutoload()
    {
        $loader = require dirname(APPPATH) . DS . 'vendor/autoload.php';
        $loader->addPsr4('App\\', APPPATH);
        $loader->addClassMap(['Application' => APPPATH . DS . 'Application.php']);
        //spl_autoload_register('app_psr4_autoloader');
    }

    public static function get($alias)
    {
        if (array_key_exists($alias, static::$classes))
        {
            return static::$classes[$alias];
        }
        return null;
    }

}
