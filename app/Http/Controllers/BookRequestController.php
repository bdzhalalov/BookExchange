<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Http\Services\BookRequestService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Request as BookRequest;

class BookRequestController extends Controller
{
    protected BookRequestService $bookRequestService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->bookRequestService = new BookRequestService();
    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        try {
            $userId = Auth::id();

            $requests =  $this->bookRequestService->getListOfBookRequests($userId);
            return response()->json($requests);
        } catch (Exception $exception) {
            Log::error(
                'Error while getting list of requests',
                [
                    'exception' => $exception,
                ]
            );

            return response()->json(['Internal server error'], 500);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'bookId' => ['required', 'int']
            ]);

            $bookId = $data['bookId'];
            $userId = Auth::id();

            $this->bookRequestService->createBookRequest($bookId, $userId);
        } catch (BadRequestException|NotFoundException $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        } catch (Exception $exception) {
            Log::error(
                "Error while creating new book request",
                [
                    'exception' => $exception
                ]
            );

            return response()->json(['Internal server error'], 500);
        }
    }

    /**
     * @param BookRequest $request
     * @return void
     * @throws AuthorizationException
     */
    public function approve(BookRequest $request): void
    {
        $this->authorize('update', $request);

        $this->bookRequestService->approveBookRequest($request);
    }

    /**
     * @param BookRequest $request
     * @return void
     * @throws AuthorizationException
     */
    public function reject(BookRequest $request): void
    {
        $this->authorize('update', $request);

        $this->bookRequestService->rejectBookRequest($request);
    }
}
