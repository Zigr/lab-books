<?php

namespace App\Lib;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Manages templates
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 * @deprecated since version 0.2
 */
class View
{

    /** @var $viewPath where tempates are located */
    protected $viewPath;

    /** @var $layout common page template */
    protected $layout;

    /** @var $contents to be rendered inside layout */
    protected $contents;

    /** $var $controller object */
    protected $controller;
    protected $request;

    public function __construct($controller, $options = array())
    {
        $this->layout = isset($options['layout']) ? $options['layout'] : 'layout';
        $this->viewPath = isset($options['viewPath']) ? $options['viewPath'] : APPPATH . DS . 'views';
        $this->controller = $controller;
        $this->request = $controller->request;
    }

    public function toUri($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_URL)
    {
        return $this->controller->toUrl($route, $parameters, $referenceType);
    }
    public function hostname(){
        return $this->controller->request->getHost();
    }

    /**
     *  Renders a given file with the supplied variables.
     *
     *  @param string $file    The file to be rendered.
     *  @param mixed $vars     The variables to be substituted in the view.
     *  @access public
     *  @return string
     */
    public function render($file, $vars = null)
    {
        $layout = $this->viewPath . DS . "{$this->layout}.php";
        $file = $this->viewPath . DS . "{$file}.php";
        if (!file_exists($file))
        {
            throw new \RuntimeException(
                    'Could not read  file. Please ensure that the path is correct and '
                    . "permissions of {$file} are valid."
            );
        }
        if (is_array($vars))
        {
            extract($vars);
        }
        //ini_set('display_errors', 0);

        ob_start();
        require_once $file;
        $contents = ob_get_clean();

        ob_start();
        require_once $layout;

        $content = ob_get_clean();

        //ini_set('display_errors', 1);

        return $content;
    }

}
