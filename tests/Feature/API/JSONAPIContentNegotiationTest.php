<?php

declare(strict_types=1);

namespace API;

use Tests\TestCase;

/**
 * @internal
 *
 * @covers \App\Http\Middleware\JSONAPI
 */
final class JSONAPIContentNegotiationTest extends TestCase
{
    /**
     * Client must be willing to accept application/vnd.api+json.
     *
     * @test
     */
    public function explicit_accept_header_passes(): void
    {
        $response = $this->get(route('api.root'), [
            'accept' => 'application/vnd.api+json',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Accept header can be a wildcard value, so long as it validates.
     *
     * @test
     */
    public function wildcard_accept_header_passes(): void
    {
        $response = $this->get(route('api.root'), [
            'accept' => '*/*',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Client which doesn't accept application/vnd.api+json is given a 406.
     *
     * @test
     */
    public function unacceptable_gives_406(): void
    {
        $response = $this->get(route('api.root'), [
            'accept' => 'text/html',
        ]);

        $response->assertStatus(406);
    }

    /**
     * Client must not include parameters on the application/vnd.api+json media type.
     *
     * @test
     */
    public function media_type_must_not_include_parameters(): void
    {
        $response = $this->get(route('api.root'), [
            'accept' => 'application/vnd.api+json; param="foo"',
        ]);

        $response->assertStatus(406);
    }

    /**
     * Client may send data if formatted correctly.
     *
     * @test
     */
    public function successful_payload(): void
    {
        $response = $this->post(route('api.root'), [
            'data' => 'test',
        ], [
            'content-type' => 'application/vnd.api+json',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Client must include a content type if they send a payload.
     *
     * @test
     */
    public function client_with_payload_must_include_content_type(): void
    {
        $response = $this->post(route('api.root'), [
            'data' => 'test',
        ], []);

        $response->assertStatus(415);
    }

    /**
     * Content-type must exactly match application/vnd.api+json.
     *
     * @test
     */
    public function content_type_must_match_jsonapi(): void
    {
        $response = $this->post(route('api.root'), [
            'data' => 'test',
        ], [
            'content-type' => 'application/vnd.api+json; foo="bar"',
        ]);

        $response->assertStatus(415);
    }
}
