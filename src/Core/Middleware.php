<?php

declare(strict_types=1);

namespace App\Core;

class Middleware
{
    private static array $aliases = [
        'auth'   => \App\Middleware\AuthMiddleware::class,
        'role'   => \App\Middleware\RoleMiddleware::class,
        'csrf'   => \App\Middleware\CsrfMiddleware::class,
        'locale' => \App\Middleware\LocaleMiddleware::class,
        'audit'  => \App\Middleware\AuditMiddleware::class,
    ];

    /**
     * Run middleware pipeline.
     * Returns Response if pipeline should halt, null if all passed.
     */
    public static function run(array $middlewareNames, Request $request): ?Response
    {
        foreach ($middlewareNames as $name) {
            $result = self::executeMiddleware($name, $request);
            if ($result instanceof Response) {
                return $result;
            }
        }

        return null;
    }

    private static function executeMiddleware(string $definition, Request $request): ?Response
    {
        // Parse "role:admin|user" -> name="role", params=["admin", "user"]
        $parts = explode(':', $definition, 2);
        $name = $parts[0];
        $params = isset($parts[1]) ? explode('|', $parts[1]) : [];

        $class = self::$aliases[$name] ?? null;

        if ($class === null) {
            throw new \RuntimeException("Middleware not found: {$name}");
        }

        if (!class_exists($class)) {
            throw new \RuntimeException("Middleware class not found: {$class}");
        }

        $middleware = new $class();

        return $middleware->handle($request, $params);
    }

    public static function registerAlias(string $name, string $class): void
    {
        self::$aliases[$name] = $class;
    }
}
