<?php

namespace Tests;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

class ApiTestCase extends TestCase
{
    use DatabaseMigrations, WithFaker;

    protected $user;

    protected $books;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');

        $this->user = User::factory()->unverified()->create();
        $this->books = Book::factory(5)->for($this->user)->create();
    }
}
