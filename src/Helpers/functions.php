<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Csrf;
use App\Core\Session;

/**
 * Get environment variable.
 */
function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    if ($value === false || $value === null) {
        return $default;
    }

    return match (strtolower((string) $value)) {
        'true', '(true)'   => true,
        'false', '(false)' => false,
        'null', '(null)'   => null,
        'empty', '(empty)' => '',
        default            => $value,
    };
}

/**
 * Get configuration value using dot notation.
 */
function config(string $key, mixed $default = null): mixed
{
    return App::config($key, $default);
}

/**
 * Generate full URL.
 */
function url(string $path = ''): string
{
    $base = rtrim(config('app.url', 'http://localhost:8000'), '/');
    return $base . '/' . ltrim($path, '/');
}

/**
 * Generate asset URL.
 */
function asset(string $path): string
{
    return url($path);
}

/**
 * Check if user is authenticated.
 */
function auth_check(): bool
{
    return Session::isAuthenticated();
}

/**
 * Get authenticated user data.
 */
function auth_user(): ?array
{
    if (!auth_check()) {
        return null;
    }
    return [
        'id' => Session::userId(),
        'name' => Session::userName(),
        'email' => Session::userEmail(),
        'role' => Session::userRole(),
    ];
}

/**
 * Get authenticated user ID.
 */
function auth_id(): ?int
{
    return Session::userId();
}

/**
 * Get authenticated user role.
 */
function auth_role(): ?string
{
    return Session::userRole();
}

/**
 * Check if user has any of the given roles.
 */
function auth_role_is(string|array $roles): bool
{
    $userRole = auth_role();
    if ($userRole === null) {
        return false;
    }
    $roles = (array) $roles;
    return in_array($userRole, $roles, true);
}

/**
 * Check if user has a specific permission.
 */
function auth_can(string $permission): bool
{
    $role = auth_role();
    if ($role === null) {
        return false;
    }

    $permissions = config('permissions.' . $role, []);

    foreach ($permissions as $perm) {
        if ($perm === $permission) {
            return true;
        }
        // Wildcard matching: "clients.*" matches "clients.create"
        if (str_ends_with($perm, '.*')) {
            $prefix = substr($perm, 0, -2);
            if (str_starts_with($permission, $prefix . '.')) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Flash a message to the session.
 */
function flash(string $key, mixed $value): void
{
    Session::flash($key, $value);
}

/**
 * Get old input value (from flash).
 */
function old(string $key, mixed $default = null): mixed
{
    return Session::getFlash('_old_input_' . $key, $default);
}

/**
 * Store old input for form repopulation.
 */
function flash_old_input(array $data): void
{
    foreach ($data as $key => $value) {
        if ($key !== '_csrf') {
            Session::flash('_old_input_' . $key, $value);
        }
    }
}

/**
 * Escape HTML entities.
 */
function e(mixed $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8', true);
}

/**
 * Output CSRF hidden field.
 */
function csrf_field(): string
{
    return Csrf::field();
}

/**
 * Get CSRF token.
 */
function csrf_token(): string
{
    return Csrf::token();
}

/**
 * Redirect helper.
 */
function redirect(string $url, int $status = 302): \App\Core\Response
{
    return \App\Core\Response::redirect($url, $status);
}

/**
 * Abort with error.
 */
function abort(int $code, string $message = ''): never
{
    http_response_code($code);
    echo $message ?: "HTTP Error {$code}";
    exit;
}

/**
 * Debug dump and die.
 */
function dd(mixed ...$vars): never
{
    echo '<pre style="background:#050505;color:#C8FF00;padding:20px;font-family:monospace;overflow:auto">';
    foreach ($vars as $var) {
        var_dump($var);
        echo "\n---\n";
    }
    echo '</pre>';
    exit;
}

/**
 * Generate a random UUID v4.
 */
function generate_uuid(): string
{
    $bytes = random_bytes(16);
    $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40);
    $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
}

/**
 * Generate a random token.
 */
function generate_token(int $length = 32): string
{
    return bin2hex(random_bytes($length));
}

/**
 * Get the base path of the project.
 */
function base_path(string $path = ''): string
{
    return App::basePath($path);
}

/**
 * Get flash messages for display.
 */
function get_flash_messages(): array
{
    $types = ['success', 'error', 'warning', 'info'];
    $messages = [];

    foreach ($types as $type) {
        $msg = Session::getFlash($type);
        if ($msg !== null) {
            $messages[] = ['type' => $type, 'message' => $msg];
        }
    }

    return $messages;
}

/**
 * Translate a string.
 */
function __(string $key, array $replace = []): string
{
    static $translations = [];
    $locale = App::locale();

    // Load translations file if not cached
    if (!isset($translations[$locale])) {
        $path = App::basePath("lang/{$locale}/messages.php");
        $translations[$locale] = file_exists($path) ? require $path : [];
    }

    // Dot notation lookup
    $parts = explode('.', $key);
    $value = $translations[$locale];
    foreach ($parts as $part) {
        if (!is_array($value) || !isset($value[$part])) {
            return $key; // Return key itself if translation not found
        }
        $value = $value[$part];
    }

    if (!is_string($value)) {
        return $key;
    }

    // Replace placeholders
    foreach ($replace as $placeholder => $replacement) {
        $value = str_replace('{' . $placeholder . '}', $replacement, $value);
    }

    return $value;
}
