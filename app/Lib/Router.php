<?php

namespace App\Lib;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Router
{

    public function __construct($routes = array())
    {
        $this->routes = $routes;
    }

    protected function getRoutes()
    {
        $routes = new RouteCollection();
        foreach ($this->routes as $name => $values)
        {

            $route = new Route((string) $values['uri']);
            isset($values['defaults']) && $route->setDefaults((array) $values['defaults']);
            isset($values['requirements']) && $route->setRequirements((array) $values['requirements']);
            isset($values['options']) && $route->setOptions((array) $values['options']);
            isset($values['host']) && $route->setHost((string) $values['host']);
            isset($values['schemes']) && $route->setSchemes((array) $values['schemes']);
            isset($values['methods']) && $route->setMethods((array) $values['methods']);
            isset($values['condition']) && $route->setCondition((string) $values['condition']);
            $routes->add($name, $route);
            unset($route);
        }
        return $routes;
    }

    public function dispatch(Request $request)
    {
//        try
//        {
            $context = new RequestContext();
            $context->fromRequest($request);

            $routes = $this->getRoutes();
            $matcher = new UrlMatcher($routes, $context);
            $generator = \Application::addClass('UrlGenerator', new \Symfony\Component\Routing\Generator\UrlGenerator($routes, $context));

            $response = new Response();

            $uri = $context->getPathInfo();
            $parameters = $matcher->match($uri);
            if (is_string($parameters['controller']))
            {
                $r = explode('@', $parameters['controller']);
                $controller = $r[0];
                $action = $r[1];
                preg_match_all('#{[\w]+}#', $this->routes[$parameters['_route']]['uri'], $matches);
                $myparams = [];
                foreach (array_pop($matches) as $name)
                {
                    $n = str_replace(['{', '}'], '', $name);
                    $myparams[$n] = $parameters[$n];
                }
                $request->attributes = $parameters;
                $instance = new $controller($request);
                $content = call_user_func_array(array($instance, $action), $myparams);
            } elseif (is_callable($parameters['controller']))
            {
                $content = call_user_func($parameters['controller'], $request);
            }
            if (empty($content))
            {
                $response = $instance->getResponse();
                if (!$response instanceof Response)
                {
                    throw new LogicException('Invalid controller response');
                }
                return $response;
            }
//        } catch (App\Exception\RoutingException $ex)
//        {
//            var_dump($ex->getMessage());
//            $response = new Response('Not Found', 404);
//        } catch (Routing\Exception\ResourceNotFoundException $ex)
//        {
//            var_dump($ex->getMessage());
//            $response = new Response('Not Found', 404);
//        } catch (\Exception $ex)
//        {
//            var_dump($ex->getMessage());
//            $response = new Response('An error occurred ' . $ex->getMessage(), 500);
//        } finally
//        {
            $response->setContent($content);
            //$response->prepare($request);
            return $response;
//        }
    }

}
