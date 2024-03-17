<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class RootController extends Controller
{
    public function index(Request $request): Response
    {
        return new Response([
            'meta'  => new \stdClass(),
            'data'  => null,
            'links' => [
                '_self' => route('api.root'),
            ],
        ]);
    }
}
