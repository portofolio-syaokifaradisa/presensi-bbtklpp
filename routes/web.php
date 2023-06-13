<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PangkatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatusPegawaiController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('verify', [LoginController::class, 'verify'])->name('verify');
});


Route::middleware('auth')->group(function(){
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::prefix('dashboard')->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('present', [DashboardController::class, 'present'])->name('present');
        Route::get('datatable', [DashboardController::class, 'datatable']);
        Route::get('print', [DashboardController::class, 'print'])->name('print');
        Route::get('print-late', [DashboardController::class, 'printLate']);
        Route::prefix('{type}')->name('keterangan.')->group(function(){
            Route::get('/', [DashboardController::class, 'keterangan'])->name('index');
            Route::post('store', [DashboardController::class, 'store'])->name('store');
        });
    });
    
    Route::prefix('profile')->name('profile.')->group(function(){
        Route::get('edit', [ProfileController::class, 'index'])->name('index');
        Route::put('update', [ProfileController::class, 'update'])->name('update');
        Route::get('password', [ProfileController::class, 'password'])->name('password');
        Route::put('update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });

    Route::prefix('absensi')->name('absensi.')->group(function(){
        Route::get('/', [AbsensiController::class, 'index'])->name('index');
        Route::get('datatable', [AbsensiController::class, 'datatable']);
        Route::get('print', [AbsensiController::class, 'print']);
        Route::get('summary', [AbsensiController::class, 'summary']);
        Route::get('print-late', [AbsensiController::class, 'printLate']);
    });

    Route::prefix('account')->name('account.')->group(function(){
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('datatable', [AccountController::class, 'datatable']);
        Route::get('create', [AccountController::class, 'create'])->name('create');
        Route::post('store', [AccountController::class, 'store'])->name('store');
        Route::get('print', [AccountController::class, 'print']);
        Route::prefix('{id}')->group(function(){
            Route::get('edit', [AccountController::class, 'edit'])->name('edit');
            Route::put('update', [AccountController::class, 'update'])->name('update');
            Route::get('delete', [AccountController::class, 'delete'])->name('delete');
        });
    });

    Route::prefix('jabatan')->name('jabatan.')->group(function(){
        Route::get('/', [JabatanController::class, 'index'])->name('index');
        Route::get('datatable', [JabatanController::class, 'datatable']);
        Route::get('create', [JabatanController::class, 'create'])->name('create');
        Route::post('store', [JabatanController::class, 'store'])->name('store');
        Route::prefix('{id}')->group(function(){
            Route::get('edit', [JabatanController::class, 'edit'])->name('edit');
            Route::put('update', [JabatanController::class, 'update'])->name('update');
            Route::get('delete', [JabatanController::class, 'delete'])->name('delete');
        });
    });

    Route::prefix('pangkat')->name('pangkat.')->group(function(){
        Route::get('/', [PangkatController::class, 'index'])->name('index');
        Route::get('datatable', [PangkatController::class, 'datatable']);
        Route::get('create', [PangkatController::class, 'create'])->name('create');
        Route::post('store', [PangkatController::class, 'store'])->name('store');
        Route::prefix('{id}')->group(function(){
            Route::get('edit', [PangkatController::class, 'edit'])->name('edit');
            Route::put('update', [PangkatController::class, 'update'])->name('update');
            Route::get('delete', [PangkatController::class, 'delete'])->name('delete');
        });
    });

    Route::prefix('status')->name('status.')->group(function(){
        Route::get('/', [StatusPegawaiController::class, 'index'])->name('index');
        Route::get('datatable', [StatusPegawaiController::class, 'datatable']);
        Route::get('create', [StatusPegawaiController::class, 'create'])->name('create');
        Route::post('store', [StatusPegawaiController::class, 'store'])->name('store');
        Route::prefix('{id}')->group(function(){
            Route::get('edit', [StatusPegawaiController::class, 'edit'])->name('edit');
            Route::put('update', [StatusPegawaiController::class, 'update'])->name('update');
            Route::get('delete', [StatusPegawaiController::class, 'delete'])->name('delete');
        });
    });
});