<?php

use App\Http\Controllers\Admin\CollectorController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExplorationTeamController;
use App\Http\Controllers\Admin\TanamanLoggingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\peneliti\inspeksiTanamanController;
use App\Http\Controllers\peneliti\PenelitiDashboardController;
use App\Http\Controllers\peneliti\penerimaanTanamanController;
use App\Http\Controllers\peneliti\penyemaianTanamanController;
use App\Http\Controllers\peneliti\ranking\KebunRayaKoleksiController;
use App\Http\Controllers\peneliti\ranking\PelaporanPrometheeController;
use App\Http\Controllers\peneliti\ranking\RankingTanamanController;
use App\Http\Middleware\adminMiddleware;
use App\Http\Middleware\penelitiMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');
Route::middleware('auth')->group(function () {
    Route::post('/sidebar/toggle', function (Request $request) {
        $request->session()->put('sidebar_open', $request->input('open'));

        return response()->json(['success' => true]);
    })->name('sidebar.toggle');
    Route::prefix('admin')->middleware([adminMiddleware::class, 'auth'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.home');
        Route::prefix('management')->group(function () {
            Route::resource('user', UserController::class);
            Route::resource('collector', CollectorController::class);
            Route::resource('tim-explorasi', ExplorationTeamController::class);
            Route::resource('criteria', CriteriaController::class);
            Route::resource('logtanaman', TanamanLoggingController::class);
        });

    });
    Route::prefix('peneliti')->middleware([penelitiMiddleware::class])->group(function () {
        Route::get('/', [PenelitiDashboardController::class, 'index'])->name('peneliti.home');
        Route::resource('penerimaan', penerimaanTanamanController::class)->names('peneliti.penerimaan');
        Route::resource('penyemaian', penyemaianTanamanController::class)->names('peneliti.penyemaian');

        Route::get('inspeksi/editEvaluasi/{id}', [inspeksiTanamanController::class, 'editEvaluasi'])->name('peneliti.inspeksi.editEvaluasi');
        Route::put('inspeksi/editEvaluasi/{id}', [inspeksiTanamanController::class, 'updateEvaluasi'])->name('peneliti.inspeksi.updateEvaluasi');

        Route::resource('inspeksi', inspeksiTanamanController::class)->names('peneliti.inspeksi');

        // Ranking & Koleksi Kebun Raya
        Route::get('ranking', [RankingTanamanController::class, 'index'])->name('peneliti.ranking.index');
        Route::post('koleksi', [KebunRayaKoleksiController::class, 'store'])->name('peneliti.koleksi.store');
        Route::get('koleksi', [KebunRayaKoleksiController::class, 'index'])->name('peneliti.koleksi.index');
        Route::delete('koleksi/{id}', [KebunRayaKoleksiController::class, 'destroy'])->name('peneliti.koleksi.destroy');
        Route::get('koleksi/export', [KebunRayaKoleksiController::class, 'export'])->name('peneliti.koleksi.export');

        // Pelaporan PROMETHEE II
        Route::get('pelaporan', [PelaporanPrometheeController::class, 'index'])->name('peneliti.pelaporan.index');
        Route::get('pelaporan/export', [PelaporanPrometheeController::class, 'export'])->name('peneliti.pelaporan.export');
    });
    Route::prefix('test')->group(function () {
        Route::get('/', [RankingTanamanController::class, 'index'])->name('detail.rank');
        Route::get('/{inspeksi_tanaman_id}', [RankingTanamanController::class, 'findbyInspeksiTanamanId']);
    });
});

require __DIR__.'/auth.php';
