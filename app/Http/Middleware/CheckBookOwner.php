<?php

namespace App\Http\Middleware;

use App\Models\Book;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBookOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bookId = $request->route()->parameter('id');

        $book = Book::where('id', $bookId)->first();

        if ($book && $book->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}
