<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Weather;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testHomePage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testHistoryPage()
    {
        $response = $this->get('/history/');

        $response->assertStatus(200);
    }

    public function testUnknownPage()
    {
        $response = $this->get('/unknown/');

        $response->assertStatus(404);
    }
}
