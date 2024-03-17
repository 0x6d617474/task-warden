<?php

declare(strict_types=1);

namespace API;

use JsonSchema\Validator;
use Tests\TestCase;

/**
 * @internal
 *
 * @covers \App\Http\Controllers\API\RootController
 */
final class BasicAPITest extends TestCase
{
    /**
     * Ensure the root endpoint responds and adheres to the JSON-API standard.
     *
     * @test
     */
    public function root_endpoint_functional_and_compliant(): void
    {
        $response = $this->get(route('api.root'));

        $response->assertStatus(200);

        $content = $response->getContent();

        $this->assertIsString($content);

        $data = json_decode($content);

        $validator = new Validator();
        $validator->validate($data, (object) ['$ref' => 'file://' . realpath(base_path('tests/resources/jsonapi-schema.json'))]);
        $this->assertTrue($validator->isValid());

        $this->assertSame(route('api.root'), $data->links->_self);
    }
}
