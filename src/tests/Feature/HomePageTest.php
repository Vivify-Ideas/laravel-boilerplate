<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase {
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldReturnSuccess()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldContainLaravelKeyword()
    {
        $response = $this->get('/');

        $response->assertSee('Laravel');
    }
}
