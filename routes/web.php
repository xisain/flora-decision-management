<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\adminMiddleware;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::prefix('admin')->middleware([adminMiddleware::class, 'auth'])->group(function () {
    Route::post('/sidebar/toggle', function (Request $request) {
        $request->session()->put('sidebar_open', $request->input('open'));
        return response()->json(['success' => true]);
    })->name('sidebar.toggle');

    Route::get('/', [DashboardController::class, 'index'])->name('admin.home');

    Route::prefix('penerimaan')->group(function () {

    });
    Route::prefix('penyemaian')->group(function () {

    });
    Route::prefix('inspeksi')->group(function () {

    });
    Route::prefix('management')->group(function () {
        // Route::prefix('pengguna')->group(function () {
            Route::resource('user',UserController::class);
        // });
        Route::prefix('collector')->group(function () {

        });
        Route::prefix('role')->group(function () {

        });
        Route::prefix('tim')->group(function () {

        });
    });

});

require __DIR__ . '/auth.php';
