<?php
namespace care\core;

namespace care\core;

class Router
{
    private $routes = [];
    private $notFoundRoute;

    public function add($route, $callback)
    {
        $this->routes[$route] = $callback;
    }

    public function setNotFound($callback)
    {
        $this->notFoundRoute = $callback;
    }

    public function dispatch($uri)
    {
        $uri = trim($uri, '/');

        if (array_key_exists($uri, $this->routes)) {
            call_user_func($this->routes[$uri]);
        } elseif ($this->notFoundRoute) {
            call_user_func($this->notFoundRoute);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 Not Found</h1>";
            echo "<p>The page you are looking for does not exist.</p>";
        }
    }
}

//class Router {
//    private $routes = [];
//    private $defaultRoute;
//
//    public function add($route, $callback) {
//        $this->routes[$route] = $callback;
//    }
//
//    public function setDefault($callback) {
//        $this->defaultRoute = $callback;
//    }
//    public function setNotFound($callback) {
//        $this->notFoundCallback = $callback;
//    }
//    public function dispatch($uri) {
//        if (array_key_exists($uri, $this->routes)) {
//            call_user_func($this->routes[$uri]);
//        } elseif ($this->defaultRoute) {
//            call_user_func($this->defaultRoute);
//        } else {
//            if ($this->notFoundCallback) {
//                call_user_func($this->notFoundCallback);
//            } else {
//                $this->show404();
//            }
//        }
//    }
//    private function show404() {
//        // You can either include a specific 404 page or output custom 404 HTML
//        header("HTTP/1.0 404 Not Found");
//        echo "<h1>404 Not Found</h1>";
//        echo "<p>The page you are looking for does not exist.</p>";
//        // Include or render a custom 404 view file if you have one
//        // include __DIR__ . '/../views/404.php'; // Example path to 404 page
//    }
//}

//class Router {
//    private $routes = [];
//    private $defaultRoute;
//
//    public function add($route, $callback) {
//        $this->routes[] = ['route' => trim($route, '/'), 'callback' => $callback];
//    }
//
//    public function setDefault($callback) {
//        $this->defaultRoute = $callback;
//    }
//
//    public function dispatch($uri) {
//        $uri = trim($uri, '/');
//
//        foreach ($this->routes as $route) {
//            if ($this->match($route['route'], $uri)) {
//                call_user_func($route['callback']);
//                return;
//            }
//        }
//
//        if ($this->defaultRoute) {
//            call_user_func($this->defaultRoute);
//        } else {
//            echo "404 Not Found";
//        }
//    }
//
//    private function match($route, $uri) {
//        return $route === $uri;
//    }
//}
