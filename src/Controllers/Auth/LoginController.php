<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\App;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Core\Logger;
use App\Models\User;

class LoginController
{
    /**
     * Show the login form.
     */
    public function showForm(Request $request): Response
    {
        if (Session::isAuthenticated()) {
            return Response::redirect('/admin');
        }

        return Response::view('pages/auth/login', [], 200, 'auth');
    }

    /**
     * Process login attempt.
     */
    public function login(Request $request): Response
    {
        $data = $request->all();

        $validator = Validator::make($data)
            ->required('email', __('email'))
            ->email('email', __('email'))
            ->required('password', __('password'))
            ->minLength('password', 6, __('password'));

        if ($validator->fails()) {
            Session::flash('errors', $validator->errors());
            flash_old_input($data);
            return Response::redirect('/login');
        }

        $user = User::findByEmail($data['email']);

        if (!$user || !User::verifyPassword($data['password'], $user['password_hash'])) {
            Session::flash('error', __('login_failed'));
            flash_old_input($data);
            return Response::redirect('/login');
        }

        if (empty($user['is_active'])) {
            Session::flash('error', __('account_disabled'));
            flash_old_input($data);
            return Response::redirect('/login');
        }

        Session::setAuth($user);
        User::updateLastLogin((int) $user['id']);
        Logger::audit('login');

        return Response::redirect('/admin');
    }

    /**
     * Switch application locale.
     */
    public function switchLocale(Request $request, string $locale): Response
    {
        App::setLocale($locale);

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        return Response::redirect($referer);
    }
}
