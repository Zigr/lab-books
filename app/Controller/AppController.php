<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Lib\View;

class AppController
{

    public $viev;
    public $request;
    protected $response;

    public function __construct(Request $request = null)
    {
        if ($request)
        {
            $this->request = $request;
        }
        $this->response = new Response();
        $this->viev = new View($this);

//        session_start([
//            'cookie_lifetime' => 86400,
//            'read_and_close' => true,
//        ]);
    }

    /**
     * Renders a template.
     * @param string|   TemplateReferenceInterface $name       A template name or a TemplateReferenceInterface instance
     * @param array     $parameters An array of parameters to pass to the template
     * @return string   The evaluated template as a string
     * @throws \RuntimeException if the template cannot be rendered
     */
    public function render($name, $parameters)
    {
        $content = \Application::get('Templating')->render($name, $parameters);
        $this->response->setContent($content);
        return $content;
    }
    
    protected function renderAjax($data = null, $status = 200, $headers = array(), $json = false)
    {
        $this->response = new JsonResponse($data, $status, $headers, $json);
        return $this->response->getContent();
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function redirect($url, $status = 302, $headers = array())
    {
        $response = new RedirectResponse($url, $status, $headers);
        return $response->send();
    }

    protected function redirectToRoute($route, array $parameters = [], $status = 302)
    {
        return $this->redirect($this->toUrl($route, $parameters), $status);
    }

    protected function redirectToSelf()
    {
        $uri = $this->request->getUri();
        return $this->redirect($uri);
    }

    /**
     * 
     * @param string $route Route name in routes config
     * @param array $parameters array of route parameters
     * @param string $referenceType @see Symfony\Component\Routing\Generator\UrlGeneratorInterface for constants 
     * @return type
     */
    public function toUrl($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_URL)
    {
        return \Application::get('UrlGenerator')->generate($route, $parameters, $referenceType);
    }

    protected function wasPosted()
    {
        return $this->request->isMethod('POST') && $this->isSubmited();
    }

    private function checkToken($token)
    {
        return true;
    }

    protected function isSubmited()
    {
        $token = $this->request->request->get('CSRF_TOKEN');
        if (!$this->checkToken($token))
        {
            return false;
        }
        return true;
    }

    /**
     * If we received DELETE method request?
     * @return boolean
     */
    protected function isDelete()
    {
        return $this->request->isMethod('DELETE');
    }
}
