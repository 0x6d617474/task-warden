<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class JSONAPI
{
    /**
     * Handle content-negotiation validation on an incoming request for JSONAPI.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        // Client must be willing to accept application/vnd.api+json.
        if (!$request->accepts(['application/vnd.api+json'])) {
            return new Response((string) json_encode([
                'errors' => [
                    [
                        'status' => 406,
                        'title'  => 'Not Acceptable',
                        'detail' => 'The application/vnd.api+json content type was not found in acceptable types.',
                    ],
                ],
            ]), 406);
        }

        // If a non-wildcard media type is sent, it must not contain parameters
        if ($accept = $request->headers->get('Accept')) {
            $valid = true;
            foreach (explode(',', $accept) as $type) {
                $type = trim((string) preg_replace('/;\s*$/', '', $type));
                if (preg_match('#^application/vnd\.api\+json#', $type)) {
                    $valid = false; // Client sent application/vnd.api+json, assume it has parameters
                    if ('application/vnd.api+json' === $type) {
                        $valid = true; // No parameters, good to go

                        break;
                    }
                }
            }

            if (!$valid) {
                return new Response((string) json_encode([
                    'errors' => [
                        [
                            'status' => 406,
                            'title'  => 'Not Acceptable',
                            'detail' => 'The application/vnd.api+json media type contains unsupported parameters.',
                        ],
                    ],
                ]), 406);
            }
        }

        // Content-Type must be set to application/vnd.api+json when sending a payload and/or content-type header.
        // We don't support any extensions or profiles at this time.
        $contentType = $request->headers->get('Content-Type');
        if ('' !== $request->getContent() || !empty($contentType)) {
            if ('application/vnd.api+json' !== $contentType) {
                return new Response((string) json_encode([
                    'errors' => [
                        [
                            'status' => 415,
                            'title'  => 'Unsupported Media',
                            'detail' => 'Content type must match application/vnd.api+json exactly.',
                        ],
                    ],
                ]), 415);
            }
        }

        return $next($request);
    }
}
