<?php

declare(strict_types=1);

namespace App\Core;

class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }

    public static function validate(?string $token): bool
    {
        if ($token === null || empty($_SESSION['_csrf'])) {
            return false;
        }

        return hash_equals($_SESSION['_csrf'], $token);
    }

    public static function metaTag(): string
    {
        return '<meta name="csrf-token" content="' . self::token() . '">';
    }
}
