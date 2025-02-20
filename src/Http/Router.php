<?php

namespace App\Http;

class Router
{
    private $routes = [];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function route(Request $request)
    {
        $method = $request->method;
        $uri = strtok($request->uri, '?');

        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            if (is_callable($action)) {
                ob_start();
                call_user_func($action);
                $content = ob_get_clean();
                return new Response($content);
            } elseif (is_array($action)) {
                [$class, $method] = $action;
                if (class_exists($class) && method_exists($class, $method)) {
                    $controller = new $class();
                    ob_start();
                    call_user_func([$controller, $method]);
                    $content = ob_get_clean();
                    return new Response($content);
                }
            }
        }

        return new Response("404 Not Found", 404);
    }
}
?>