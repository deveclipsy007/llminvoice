<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Logger;
use App\Core\Request;
use App\Core\Response;

class AuditMiddleware
{
    public function handle(Request $request, array $params = []): ?Response
    {
        Logger::audit(
            'request',
            'http',
            null,
            null,
            ['method' => $request->method, 'path' => $request->path]
        );

        return null;
    }
}
