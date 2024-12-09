<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Resources\BookResource;
use App\Http\Services\BookService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class BookController extends Controller
{
    private BookService $bookService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->bookService = new BookService();
    }

    /**
     * @return Collection|JsonResponse
     */
    public function list(): Collection|JsonResponse
    {
        try {
            return $this->bookService->getBooksList(Auth::id());
        } catch (\Exception $exception) {
            Log::error(
                "Error while getting list of books",
                ['exception' => $exception],
            );

            return response()->json(['Internal server error'], 500);
        }
    }

    /**
     * @param Request $request
     * @return Collection|JsonResponse
     */
    public function create(Request $request): Collection|JsonResponse
    {
        try {
            //TODO: create validator for reusing
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'author' => ['required', 'string', 'max:255'],
                'genre' => ['required', 'string', 'max:100'],
                'condition' => ['required', 'string'],
                'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            ]);
            $data = $request->only(['title', 'author', 'genre', 'condition']);

            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $originalName = $file->getClientOriginalName();
                $coverImagePath = $file->storeAs('public' . '/covers', $originalName);
            }
            $data['cover_image'] = $coverImagePath;
            $data['user_id'] = Auth::id();

            return $this->bookService->createBook($data);
        } catch (\Exception $exception) {
            Log::error(
                "Error while updating book",
                ['exception' => $exception],
            );

            return response()->json(['Internal server error'], 500);
        }
    }

    /**
     * @param int $bookId
     * @return Collection|JsonResponse
     */
    public function getById(int $bookId): Collection|JsonResponse
    {
        try {
            return $this->bookService->getBookById($bookId);
        } catch (NotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (\Exception $exception) {
            Log::error(
                "Error while getting book by id",
                ['exception' => $exception],
            );

            return response()->json(['Internal server error'], 500);
        }
    }

    /**
     * @param int $bookId
     * @param Request $request
     * @return BookResource|JsonResponse
     */
    public function update(int $bookId, Request $request): BookResource|JsonResponse
    {
        try {
            //TODO: create validator for reusing
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'author' => ['required', 'string', 'max:255'],
                'genre' => ['required', 'string', 'max:100'],
                'condition' => ['required', 'string'],
                'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            ]);
            $data = $request->only(['title', 'author', 'genre', 'condition']);

            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $originalName = $file->getClientOriginalName();
                $coverImagePath = $file->storeAs('public' . '/covers', $originalName);
            }
            $data['cover_image'] = $coverImagePath;
            $data['user_id'] = Auth::id();

            return $this->bookService->updateBookById($bookId, $data);
        } catch (NotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (\Exception $exception) {
            Log::error(
                "Error while updating book",
                ['exception' => $exception],
            );

            return response()->json(['Internal server error'], 500);
        }
    }

    /**
     * @param int $bookId
     * @return Application|Factory|View|JsonResponse
     */
    public function edit(int $bookId): Factory|View|Application|JsonResponse
    {
        try {
            $book = $this->bookService->getBookById($bookId)->jsonSerialize();

            return view('books.edit', compact('book'));
        } catch (NotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (\Exception $exception) {
            Log::error(
                "Error while getting form for editing book",
                [
                    'bookId' => $bookId,
                    'exception' => $exception
                ],
            );

            return response()->json(['Internal server error'], 500);
        }
    }

    /**
     * @param int $bookId
     * @return JsonResponse
     */
    public function delete(int $bookId): JsonResponse
    {
        try {
            $this->bookService->deleteBookById($bookId);

            return response()->json(["message" => "Book with id $bookId deleted"]);
        } catch (Exception $exception) {
            Log::error(
                "Error while deleting book",
                [
                    'bookId' => $bookId,
                    'exception' => $exception
                ]
            );

            return response()->json(['Internal server error'], 500);
        }
    }
}
