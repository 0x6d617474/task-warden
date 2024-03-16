<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class BasicTest extends TestCase
{
    /**
     * Make sure a dead simple route returns what it is supposed to.
     * Also ensures that the core application is working!
     *
     * @test
     */
    public function basic()
    {
        $response = $this->get(route('test'));

        $response->assertStatus(200);

        $this->assertSame('Hello world!', $response->getContent());
    }
}
