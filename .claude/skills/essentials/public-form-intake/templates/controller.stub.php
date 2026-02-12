<?php

namespace App\Controllers\Client;

use App\Core\Request;
use App\Core\Response;

class PublicFormController
{
    public function submit(Request $request): Response
    {
        $name = trim((string) $request->post('visitor_name', ''));
        $responses = $request->post('responses', []);

        if ($name === '' || !is_array($responses) || $responses === []) {
            return Response::json([
                'success' => false,
                'errors' => ['Dados obrigatorios ausentes.'],
            ], 422);
        }

        // persist + analysis + notify
        return Response::json(['success' => true], 200);
    }
}
