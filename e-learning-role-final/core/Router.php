<?php
class Router {
    protected $routes = [];
    public function get($url, $action) {
        $this->routes['GET'][$url] = $action;
    }
    public function post($url, $action) {
        $this->routes['POST'][$url] = $action;
    }
    public function dispatch($url) {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = rtrim($url, '/');
        foreach ($this->routes[$method] as $route => $action) {
            if (preg_match('#^' . $route . '$#', $url, $matches)) {
                [$controller, $methodName] = explode('@', $action);
                require_once "../app/controllers/$controller.php";
                $controllerInstance = new $controller();
                return $controllerInstance->$methodName(...array_slice($matches, 1));
            }
        }
        echo "404 - Page not found";
    }
}
