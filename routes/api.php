<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookRequestController;
use Illuminate\Support\Facades\Route;

// CRUD operations with books
Route::prefix('books')->group( function () {
    Route::get('/', [BookController::class, 'list'])->name('books.list');
    Route::post('/', [BookController::class, 'create'])->name('books.create');
    Route::prefix('{id}')->middleware(['checkBookOwner'])->group( function () {
        Route::get('/', [BookController::class, 'getById'])->name('books.byId');
        Route::patch('/', [BookController::class, 'update'])->name('books.update');
        Route::delete('/', [BookController::class, 'delete'])->name('books.delete');
    });
});

// CRUD operations with book requests
Route::prefix('requests')->group( function () {
    Route::get('/', [BookRequestController::class, 'list']);
    Route::post('/', [BookRequestController::class, 'create']);
    Route::patch('/{request}/approve', [BookRequestController::class, 'approve']);
    Route::patch('/{request}/reject', [BookRequestController::class, 'reject']);
});
