<?php

namespace App\Http\Services;

use App\Exceptions\NotFoundException;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as Collection;
use Illuminate\Support\Facades\Log;

class BookService
{
    public function __construct()
    {
    }

    public function getBooksList($userId): Collection
    {
        Log::debug(
            'Start getting list of user\'s books',
            ['userId' => $userId]
        );

        $books = Book::where('user_id', $userId)->orderBy('id')->get();

        return BookResource::collection($books);
    }

    public function createBook($data): Collection
    {
        Log::debug('Start creating new book');

        $createdBook = collect([Book::create($data)]);

        return BookResource::collection($createdBook);
    }

    /**
     * @throws NotFoundException
     */
    public function getBookById(int $bookId): Collection
    {
        Log::debug(
            'Start getting book by id',
            ['bookId' => $bookId]
        );

        $book = Book::where('id', $bookId)->get();
        if ($book->isEmpty()) {
            throw NotFoundException::getInstance("Book with id $bookId not found");
        }

        return BookResource::collection(collect($book));
    }

    /**
     * @throws NotFoundException
     */
    public function updateBookById(int $bookId, array $data): BookResource
    {
        Log::debug(
            'Start updating book by id',
            ['bookId' => $bookId]
        );

        $book = Book::find($bookId);
        if (!$book) {
            throw NotFoundException::getInstance("Book with id $bookId not found");
        }

        $book->update($data);

        return new BookResource($book);
    }

    public function deleteBookById(int $bookId): void
    {
        Log::debug(
            'Start deleting book by id',
            ['bookId' => $bookId]
        );

        Book::where('id', $bookId)->delete();
    }
}
