<?php

namespace Tests\Feature;

use Tests\ApiTestCase;

class BookApiTest extends ApiTestCase
{
    /** @test */
    public function test_can_get_all_books()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    /** @test */
    public function test_returns_unauthorized_if_user_not_logged_in_when_getting_books()
    {
        $response = $this->getJson('/api/books');
        $response->assertStatus(401);
    }

    /** @test */
    public function test_can_get_a_single_book()
    {
        $bookId = $this->books[0]->id;

        $response = $this->actingAs($this->user)
            ->getJson("/api/books/{$bookId}");

        $response->assertStatus(200)
            ->assertJsonIsArray();
    }

    /** @test */
    public function test_returns_unauthorized_when_trying_to_get_a_single_book_without_login()
    {
        $response = $this->getJson("/api/books/{$this->books[0]->id}");
        $response->assertStatus(401);
    }

    /** @test */
    public function test_returns_404_for_nonexistent_book()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/books/345');

        $response->assertStatus(404);
    }
}
