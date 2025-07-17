<?php

use App\Http\Controllers\Api\PostApiController;
use Illuminate\Support\Facades\Route;

Route::get('/posts', [PostApiController::class, 'index']);
Route::get('/test-api', function () {
    return ['message' => 'API route file is working!'];
});
