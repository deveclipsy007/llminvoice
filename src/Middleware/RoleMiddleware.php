<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class RoleMiddleware
{
    public function handle(Request $request, array $params = []): ?Response
    {
        $userRole = Session::userRole();
        $allowedRoles = $params;

        if ($userRole === null || !in_array($userRole, $allowedRoles, true)) {
            if ($request->isAjax()) {
                return Response::json(['error' => 'Forbidden', 'code' => 403], 403);
            }

            flash('error', __('messages.access_denied'));
            return Response::redirect('/admin');
        }

        return null;
    }
}
