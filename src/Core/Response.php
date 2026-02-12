<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    private int $statusCode;
    private array $headers;
    private string $body;

    public function __construct(string $body = '', int $statusCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public static function view(string $template, array $data = [], int $status = 200, ?string $layout = null): self
    {
        $basePath = App::basePath();
        $templatePath = $basePath . '/templates/' . $template . '.php';

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template not found: {$template}");
        }

        // Render the page content
        extract($data);
        ob_start();
        require $templatePath;
        $content = ob_get_clean();

        // If layout specified, wrap content in layout
        if ($layout !== null) {
            $layoutPath = $basePath . '/templates/layouts/' . $layout . '.php';
            if (!file_exists($layoutPath)) {
                throw new \RuntimeException("Layout not found: {$layout}");
            }
            ob_start();
            require $layoutPath;
            $content = ob_get_clean();
        }

        return new self($content, $status, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    public static function json(array|object $data, int $status = 200): self
    {
        return new self(
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $status,
            ['Content-Type' => 'application/json; charset=UTF-8']
        );
    }

    public static function redirect(string $url, int $status = 302): self
    {
        return new self('', $status, ['Location' => $url]);
    }

    public static function download(string $content, string $filename, string $contentType = 'application/octet-stream'): self
    {
        return new self($content, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($content),
        ]);
    }

    public static function error(int $statusCode, string $message = ''): self
    {
        if (Request::capture()->isAjax()) {
            return self::json(['error' => $message ?: 'Error', 'code' => $statusCode], $statusCode);
        }

        return new self(
            '<h1>' . $statusCode . '</h1><p>' . htmlspecialchars($message) . '</p>',
            $statusCode,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
