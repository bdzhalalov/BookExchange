<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

class ApiTestCase extends TestCase
{
    use DatabaseMigrations, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }
}
