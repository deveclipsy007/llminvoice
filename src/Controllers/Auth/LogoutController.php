<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Logger;

class LogoutController
{
    /**
     * Log the user out and redirect to login.
     */
    public function logout(Request $request): Response
    {
        Logger::audit('logout');
        Session::destroy();

        // Start a fresh session so we can flash a message
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);
        }

        Session::flash('success', __('logged_out'));

        return Response::redirect('/login');
    }
}
