<?php

namespace App\Http\Services;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Models\Request;
use App\Notifications\RequestStatusChanged;
use Illuminate\Support\Facades\Log;

class BookRequestService
{
    protected BookService $bookService;
    public function __construct()
    {
        $this->bookService = new BookService();
    }
    public function getListOfBookRequests(int $userId): array
    {
        Log::debug('Start getting list of book requests');

        $incomingRequests = Request::whereHas('book', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        $outgoingRequests = Request::where('user_id', $userId)->get();

        return [
            "incomingRequests" => $incomingRequests,
            "outgoingRequests" => $outgoingRequests,
        ];
    }

    /**
     * @throws NotFoundException
     * @throws BadRequestException
     */
    public function createBookRequest(int $bookId, int $userId): void
    {
        $book = $this->bookService->getBookById($bookId);

        if ($book->user_id == $userId) {
            throw BadRequestException::getInstance('You can\'t request your own book');
        }

        Request::create([
            'book_id' => $bookId,
            'user_id' => $userId,
        ]);
    }

    public function approveBookRequest(Request $request): void
    {
        Log::debug("Start approving book request");

        $request->update([
            'status' => 'Approved'
        ]);

        $request->book()->update([
            'is_available' => false
        ]);

        $this->notifyUserAboutChangedStatus($request, 'Approved');
    }

    public function rejectBookRequest(Request $request): void
    {
        Log::debug("Start rejecting book request");

        $request->update([
            'status' => 'Rejected'
        ]);

        $this->notifyUserAboutChangedStatus($request, 'Rejected');
    }

    private function notifyUserAboutChangedStatus(Request $request, string $status): void
    {
        $request->user->notify(new RequestStatusChanged($request->book->title, $status));
    }
}
