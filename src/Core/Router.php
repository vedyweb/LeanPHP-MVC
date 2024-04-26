<?php
namespace LeanPHP\Core;

use LeanPHP\Core\Logger;

class Router
{
    private $routes = [];
    private $middlewares = [];
    private $prefix = '';

    private $baseFolder;

    private $groupFolder;
    private $controllerNamespace;
    private $authNamespace;

    public function __construct($baseFolder = '', $appName = '')
    {
        $this->groupFolder = $_ENV['APP_FOLDER'];
        $this->controllerNamespace = $appName ?: $_ENV['APP_NAME'] . '\Controller\\';
        $this->authNamespace = $appName ?: $_ENV['APP_NAME'] . '\Core\\JwtAuth';
    }

    public function get($path, $controller, $method)
    {
        $controller = $this->controllerNamespace . $controller;
        $fullPath = $this->groupFolder . $this->prefix . $this->baseFolder . $path;
        $this->routes['GET'][$fullPath] = ['controller' => $controller, 'method' => $method];
    }

    public function post($path, $controller, $method)
    {
        $controller = $this->controllerNamespace . $controller;
        $fullPath = $this->groupFolder . $this->prefix . $this->baseFolder . $path;
        $this->routes['POST'][$fullPath] = ['controller' => $controller, 'method' => $method];
    }

    public function put($path, $controller, $method)
    {
        $controller = $this->controllerNamespace . $controller;
        $fullPath = $this->groupFolder . $this->prefix . $this->baseFolder . $path;
        $this->routes['PUT'][$fullPath] = ['controller' => $controller, 'method' => $method];
    }

    public function delete($path, $controller, $method)
    {
        $controller = $this->controllerNamespace . $controller;
        $fullPath = $this->groupFolder . $this->prefix . $this->baseFolder . $path;
        $this->routes['DELETE'][$fullPath] = ['controller' => $controller, 'method' => $method];
    }

    public function addMiddleware($path, $method)
    {
        $auth = $this->authNamespace;
        $fullPath = $this->groupFolder . $path . '.*';
        $this->middlewares[$fullPath] = ['controller' => $auth, 'method' => $method];
    }

    public function group($prefix, $callback, $middleware = null)
    {
        $previousPrefix = $this->prefix;
        $this->prefix = $this->prefix . $this->baseFolder . $prefix;

        if ($middleware) {
            $this->addMiddleware($this->prefix, $middleware['method']);
        }

        call_user_func($callback, $this);
        $this->prefix = $previousPrefix;
    }


    public function dispatch($uri, $request, $response)
    {
        try {
            Logger::logError("Trying to dispatch URI: " . $uri); // Gelen URI'yi loglayın

            if (!$this->handleRouting($uri, $request, $response)) {
                http_response_code(404);
                echo json_encode(['error' => 'Not Found', 'message' => 'No route matches the provided URI.']);
                Logger::logError("404 Not Found: No route matches the provided URI - " . $uri);

            }
        } catch (\Exception $exception) {
            Logger::logError($exception);
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error', 'message' => $exception->getMessage()]);
        }
    }

    private function handleRouting($uri, $request, $response)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $matched = false;

        foreach ($this->middlewares as $path => $middlewareDetails) {
            $pattern = "@^" . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $path) . "$@D";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $controller = new $middlewareDetails['controller'];
                $middlewareResult = call_user_func_array([$controller, $middlewareDetails['method']], array_merge([$request, $response], $matches));
                if ($middlewareResult === false) {
                    echo "Authorization Failed!";
                    return false; // Exiting if middleware fails
                }
                break;
            }
        }

        if ($matched)
            return true;

        foreach ($this->routes[$requestMethod] as $path => $details) {
            $pattern = "@^" . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $path) . "$@D";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $controllerName = $details['controller'];
                $methodName = $details['method'];
                $controller = new $controllerName;
                call_user_func_array([$controller, $methodName], array_merge([$request, $response], $matches));
                $matched = true;
                return true;
            }
        }

        // If no specific pattern matched, check if there's a direct match
        if (!$matched && isset($this->routes[$requestMethod][$uri])) {
            $controllerName = $this->routes[$requestMethod][$uri]['controller'];
            $methodName = $this->routes[$requestMethod][$uri]['method'];
            $controller = new $controllerName;
            $controller->$methodName($request, $response);
            return true;
        }

        // No routes matched at all
        return false;
    }
}