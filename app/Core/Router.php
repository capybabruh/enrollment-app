<?php
// app/Core/Router.php

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if (isset($this->routes[$method][$path])) {
            [$controller, $action] = $this->routes[$method][$path];
            (new $controller())->$action();
            return;
        }

        // Route tồn tại nhưng sai method -> 405
        foreach ($this->routes as $methodRoutes) {
            if (isset($methodRoutes[$path])) {
                http_response_code(405);
                view('errors/405');
                return;
            }
        }

        // Không tìm thấy route nào khớp -> 404
        http_response_code(404);
        view('errors/404');
    }
}
