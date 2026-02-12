<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request, array $params = []): ?Response
    {
        if (!Session::isAuthenticated()) {
            if ($request->isAjax()) {
                return Response::json(['error' => 'Unauthorized', 'code' => 401], 401);
            }

            flash('error', __('messages.login_required'));
            return Response::redirect('/login');
        }

        return null;
    }
}
