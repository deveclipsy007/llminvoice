<?php

declare(strict_types=1);

/**
 * LLMInvoice - Front Controller
 * All HTTP requests are routed through this single entry point.
 */

// Force UTF-8 encoding for all PHP output and string operations
ini_set('default_charset', 'UTF-8');
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}
if (function_exists('mb_http_output')) {
    mb_http_output('UTF-8');
}

// Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load helper functions
require_once __DIR__ . '/../src/Helpers/functions.php';

use App\Core\App;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Load configuration
$GLOBALS['config'] = [
    'app'         => require dirname(__DIR__) . '/config/app.php',
    'database'    => require dirname(__DIR__) . '/config/database.php',
    'ai'          => require dirname(__DIR__) . '/config/ai.php',
    'email'       => require dirname(__DIR__) . '/config/email.php',
    'permissions' => require dirname(__DIR__) . '/config/permissions.php',
];

// Bootstrap application
App::boot();

// Set up error handling
set_exception_handler(function (\Throwable $e) {
    $debug = config('app.debug', false);

    if (Request::capture()->isAjax()) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error'   => $debug ? $e->getMessage() : __('messages.server_error'),
            'code'    => 500,
            'trace'   => $debug ? $e->getTraceAsString() : null,
        ]);
        exit;
    }

    http_response_code(500);
    if ($debug) {
        echo '<h1>Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        echo '<h1>500 - Server Error</h1>';
        echo '<p>Something went wrong. Please try again later.</p>';
    }

    \App\Core\Logger::error($e->getMessage(), [
        'file'  => $e->getFile(),
        'line'  => $e->getLine(),
        'trace' => $e->getTraceAsString(),
    ]);
    exit;
});

set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline) {
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Create request
$request = Request::capture();

// Create router and load routes
$router = new Router();
$routes = require dirname(__DIR__) . '/config/routes.php';
$router->loadRoutes($routes);

// Dispatch request through middleware pipeline and get response
$response = $router->dispatch($request);

// Send response to client
$response->send();
