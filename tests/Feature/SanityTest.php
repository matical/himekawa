<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SanityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_works()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
