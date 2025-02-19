<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;

use App\Http\Controllers\BookmarkController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/tags', TagController::class . '@apiIndex');

Route::get('/bookmarks', BookmarkController::class . '@apiIndex');
