<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\App;
use App\Core\Request;
use App\Core\Response;

class LocaleMiddleware
{
    public function handle(Request $request, array $params = []): ?Response
    {
        // Check query param: ?lang=en
        $locale = $request->query('lang');

        if ($locale) {
            App::setLocale($locale);
        }

        return null;
    }
}
