<?php

use App\Http\Controllers\Api\peneliti\NomorAksesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/nomor-akses', [NomorAksesController::class, 'generate'])->name('nac.service');
