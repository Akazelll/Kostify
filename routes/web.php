<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Storage;



Route::get('storage-file/{path}', function ($path) {
    // base64_decode untuk keamanan dan menangani karakter aneh di nama file
    $realPath = base64_decode($path);

    if (!Storage::disk('public')->exists($realPath)) {
        abort(404);
    }

    // Menggunakan response() adalah cara paling aman dan efisien
    return Storage::disk('public')->response($realPath);
})->where('path', '.*')->name('storage.file');

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    Route::get('/penghunis', [PenghuniController::class, 'index'])->name('penghunis.index');
    Route::post('/penghunis', [PenghuniController::class, 'store'])->name('penghunis.store');
    Route::put('/penghunis/{penghuni}', [PenghuniController::class, 'update'])->name('penghunis.update');
    Route::delete('/penghunis/{penghuni}', [PenghuniController::class, 'destroy'])->name('penghunis.destroy');

    Route::get('/billings', [BillingController::class, 'index'])->name('billings.index');
    Route::post('/billings', [BillingController::class, 'store'])->name('billings.store');
    Route::post('/billings/{billing}/pay', [BillingController::class, 'submitPayment'])->name('billings.pay');
    Route::delete('/billings/{billing}', [BillingController::class, 'destroy'])->name('billings.destroy');
    Route::get('/billings/{billing}/invoice', [BillingController::class, 'generateInvoice'])->name('billings.invoice');
    Route::post('/billings/{billing}/payments', [BillingController::class, 'storePayment'])->name('billings.store_payment');
});
