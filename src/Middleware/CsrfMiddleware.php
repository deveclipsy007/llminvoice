<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;

class CsrfMiddleware
{
    public function handle(Request $request, array $params = []): ?Response
    {
        // Only validate on state-changing methods
        if (!in_array($request->method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return null;
        }

        // Check body param first, then header (for AJAX)
        $token = $request->input('_csrf')
            ?? ($request->headers['x-csrf-token'] ?? null);

        if (!Csrf::validate($token)) {
            if ($request->isAjax()) {
                return Response::json(['error' => 'CSRF token mismatch', 'code' => 419], 419);
            }

            flash('error', __('messages.csrf_error'));
            $referer = $_SERVER['HTTP_REFERER'] ?? '/login';
            return Response::redirect($referer);
        }

        return null;
    }
}
