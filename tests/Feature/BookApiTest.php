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
    public function test_returns_forbidden_when_getting_book_from_another_user()
    {
        $bookId = $this->booksByAnotherUser[0]->id;

        $response = $this->actingAs($this->user)
            ->getJson("/api/books/{$bookId}");

        $response->assertStatus(403);
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

    /** @test */
    public function test_can_create_a_book()
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/books", $this->createdBook);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', $this->createdBook);
    }

    /** @test */
    public function test_returns_unauthorized_when_trying_to_create_a_book_without_login()
    {
        $response = $this->postJson("/api/books", $this->createdBook);

        $response->assertStatus(401);
        $this->assertDatabaseMissing('books', $this->createdBook);
    }

    /** @test */
    public function test_get_validation_errors_when_trying_to_create_an_invalid_book()
    {
        unset($this->createdBook['author']);
        $response = $this->actingAs($this->user)
            ->postJson("/api/books", $this->createdBook, ['accept' => 'application/json']);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('books', $this->createdBook);
    }

    /** @test */
    public function test_can_update_a_book()
    {
        $bookId = $this->books[0]->id;
        $this->books[0]->title = 'Updated title';

        $response = $this->actingAs($this->user)
            ->patchJson("/api/books/{$bookId}", $this->books[0]->toArray());

        $response->assertStatus(200);
    }

    /** @test */
    public function test_returns_forbidden_when_trying_to_update_a_book_by_another_user()
    {
        $bookId = $this->booksByAnotherUser[0]->id;
        $this->booksByAnotherUser[0]->title = 'Updated title';

        $response = $this->actingAs($this->user)
            ->patchJson("/api/books/{$bookId}", $this->booksByAnotherUser[0]->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function test_returns_unauthorized_when_trying_to_update_a_book_without_login()
    {
        $bookId = $this->books[0]->id;
        $this->books[0]->title = 'Updated title';

        $response = $this->patchJson("/api/books/{$bookId}", $this->books[0]->toArray());

        $response->assertStatus(401);
    }

    /** @test */
    public function test_get_validation_errors_when_trying_to_update_an_invalid_book()
    {
        $bookId = $this->books[0]->id;
        unset($this->books[0]->author);

        $response = $this->actingAs($this->user)
            ->patchJson("/api/books/{$bookId}", $this->books[0]->toArray());

        $response->assertStatus(422);
    }

    /** @test */
    public function test_can_delete_a_book()
    {
        $bookId = $this->books[0]->id;

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/books/{$bookId}");

        $response->assertStatus(200);
    }

    /** @test */
    public function test_returns_forbidden_when_trying_to_delete_a_book_by_another_user()
    {
        $bookId = $this->booksByAnotherUser[0]->id;

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/books/{$bookId}");

        $response->assertStatus(403);
    }

    /** @test */
    public function test_returns_unauthorized_when_trying_to_delete_a_book_without_login()
    {
        $bookId = $this->books[0]->id;

        $response = $this->deleteJson("/api/books/{$bookId}");
        $response->assertStatus(401);
    }
}
