<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    public readonly string $method;
    public readonly string $uri;
    public readonly string $path;
    public readonly array $queryParams;
    public readonly array $bodyParams;
    public readonly array $files;
    public readonly array $headers;

    private array $routeParams = [];

    private function __construct(
        string $method,
        string $uri,
        array $queryParams,
        array $bodyParams,
        array $files,
        array $headers,
    ) {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $this->queryParams = $queryParams;
        $this->bodyParams = $bodyParams;
        $this->files = $files;
        $this->headers = $headers;
    }

    public static function capture(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = str_replace('_', '-', strtolower(substr($key, 5)));
                $headers[$name] = $value;
            }
        }

        // Support JSON body
        $bodyParams = $_POST;
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $bodyParams = $decoded;
            }
        }

        return new self($method, $uri, $_GET, $bodyParams, $_FILES, $headers);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->bodyParams[$key]
            ?? $this->queryParams[$key]
            ?? $this->routeParams[$key]
            ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->queryParams, $this->bodyParams, $this->routeParams);
    }

    public function only(array $keys): array
    {
        $all = $this->all();
        return array_intersect_key($all, array_flip($keys));
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->bodyParams[$key] ?? $default;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->queryParams[$key] ?? $default;
    }

    public function param(string $key, mixed $default = null): mixed
    {
        return $this->routeParams[$key] ?? $default;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function isAjax(): bool
    {
        return ($this->headers['x-requested-with'] ?? '') === 'XMLHttpRequest'
            || str_contains($this->headers['accept'] ?? '', 'application/json');
    }

    public function isMethod(string $method): bool
    {
        return $this->method === strtoupper($method);
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function routeParams(): array
    {
        return $this->routeParams;
    }

    public function ip(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    public function url(): string
    {
        return config('app.url', 'http://localhost:8000') . $this->path;
    }
}
