<?php

declare(strict_types=1);

namespace App\Core;

class App
{
    private static string $basePath = '';

    public static function boot(): void
    {
        self::$basePath = dirname(__DIR__, 2);

        // Timezone
        date_default_timezone_set(config('app.timezone', 'America/Sao_Paulo'));

        // Session
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);
        }

        // CSRF token
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        // Idle timeout
        $timeout = (int) env('SESSION_IDLE_TIMEOUT', 1800);
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
            session_unset();
            session_destroy();
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);
        }
        $_SESSION['last_activity'] = time();

        // Locale
        $locale = $_SESSION['locale'] ?? $_COOKIE['locale'] ?? config('app.locale', 'pt');
        $_SESSION['locale'] = $locale;

        // Ensure storage directories exist
        $logDir = self::$basePath . '/storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }
    }

    public static function config(string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);
        $value = $GLOBALS['config'] ?? [];

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $default;
            }
            $value = $value[$part];
        }

        return $value;
    }

    public static function basePath(string $path = ''): string
    {
        if (self::$basePath === '') {
            self::$basePath = dirname(__DIR__, 2);
        }

        return self::$basePath . ($path ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : '');
    }

    public static function locale(): string
    {
        return $_SESSION['locale'] ?? config('app.locale', 'pt');
    }

    public static function setLocale(string $locale): void
    {
        $allowed = ['pt', 'en', 'es'];
        if (in_array($locale, $allowed, true)) {
            $_SESSION['locale'] = $locale;
            setcookie('locale', $locale, time() + 86400 * 365, '/');
        }
    }
}
