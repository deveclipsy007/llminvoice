<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, string $controller, string $action, array $middleware = []): void
    {
        $this->routes[] = [
            'method'     => strtoupper($method),
            'path'       => $path,
            'controller' => $controller,
            'action'     => $action,
            'middleware'  => $middleware,
        ];
    }

    public function loadRoutes(array $routeDefinitions): void
    {
        foreach ($routeDefinitions as $key => $definition) {
            // Format: "GET /path" => [ControllerClass, method, [middleware]]
            [$methodStr, $path] = explode(' ', $key, 2);

            // Support multiple methods: "GET|POST /path"
            $methods = explode('|', $methodStr);
            $controller = $definition[0];
            $action = $definition[1];
            $middleware = $definition[2] ?? [];

            foreach ($methods as $method) {
                $this->addRoute(trim($method), $path, $controller, $action, $middleware);
            }
        }
    }

    public function dispatch(Request $request): Response
    {
        $path = rtrim($request->path, '/') ?: '/';
        $method = $request->method;

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->matchPath($route['path'], $path);
            if ($params === null) {
                continue;
            }

            // Set route parameters on request
            $request->setRouteParams($params);

            // Run middleware pipeline
            if (!empty($route['middleware'])) {
                $middlewareResponse = Middleware::run($route['middleware'], $request);
                if ($middlewareResponse !== null) {
                    return $middlewareResponse;
                }
            }

            // Resolve controller
            $controllerClass = $this->resolveControllerClass($route['controller']);
            if (!class_exists($controllerClass)) {
                throw new \RuntimeException("Controller not found: {$controllerClass}");
            }

            $controller = new $controllerClass();
            $action = $route['action'];

            if (!method_exists($controller, $action)) {
                throw new \RuntimeException("Action not found: {$controllerClass}::{$action}");
            }

            // Call controller action with request and route params
            $result = $controller->$action($request, ...$this->buildActionParams($params));

            if ($result instanceof Response) {
                return $result;
            }

            // If controller returns array, convert to JSON
            if (is_array($result)) {
                return Response::json($result);
            }

            // If controller returns string, wrap in response
            if (is_string($result)) {
                return new Response($result);
            }

            throw new \RuntimeException("Invalid response from {$controllerClass}::{$action}");
        }

        // No route matched
        return Response::error(404, __('messages.page_not_found'));
    }

    private function matchPath(string $routePath, string $requestPath): ?array
    {
        // Convert route params to regex
        $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) {
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $routePath);

        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestPath, $matches)) {
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return $params;
        }

        return null;
    }

    private function resolveControllerClass(string $controller): string
    {
        // If already fully qualified
        if (str_starts_with($controller, 'App\\')) {
            return $controller;
        }

        // Try namespaced resolution: "Admin\KanbanController" -> "App\Controllers\Admin\KanbanController"
        return 'App\\Controllers\\' . $controller;
    }

    private function buildActionParams(array $params): array
    {
        // Convert route params to ordered values for method arguments
        return array_values($params);
    }
}
