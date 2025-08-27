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

    protected $anotherUser;

    protected $books;

    protected $booksForAnotherUser;

    protected $createdBook;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');

        $this->user = User::factory()->unverified()->create();
        $this->anotherUser = User::factory()->unverified()->create();
        $this->books = Book::factory(5)->for($this->user)->create();
        $this->booksByAnotherUser = Book::factory(5)->for($this->anotherUser)->create();
        $this->createdBook = [
            "title" => "Test book",
            "author" => "Testson Tester",
            "genre" => "Testing",
            "condition" => "New",
            "user_id" => $this->user->id,
        ];
    }
}
