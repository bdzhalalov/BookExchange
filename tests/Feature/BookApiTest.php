<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Tests\ApiTestCase;

class BookApiTest extends ApiTestCase
{
    /** @test */
    public function test_can_get_all_books()
    {
        $books = Book::factory(10)->create();
        $response = $this->actingAs(User::factory()->create())
            ->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonCount(10, '');
    }

    /** @test */
    public function test_returns_unauthorized_if_user_not_logged_in_when_getting_books()
    {
        $response = $this->getJson('/api/books');
        $response->assertStatus(401); // Unauthorized
    }

    /** @test */
    public function test_can_get_a_single_book()
    {
        $book = Book::factory()->create();
        $response = $this->actingAs(User::factory()->create())
            ->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $book->id);
    }

    /** @test */
    public function test_returns_unauthorized_when_trying_to_get_a_single_book_without_login()
    {
        $book = Book::factory()->create();
        $response = $this->getJson("/api/books/{$book->id}");
        $response->assertStatus(401);
    }

    /** @test */
    public function test_returns_404_for_nonexistent_book()
    {
        $response = $this->actingAs(User::factory()->create())
            ->getJson('/api/books/345');

        $response->assertStatus(404);
    }
}
