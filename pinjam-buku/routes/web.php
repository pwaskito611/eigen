<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/member/getAll', [MemberController::class, 'index']);
Route::post('/member/create', [MemberController::class, 'create']);

Route::post('/book/create', [BookController::class, 'create']);
Route::get('/book/available', [BookController::class, 'available']);

Route::post('/borrow/borrow',[BorrowController::class, 'borrow']);
Route::post('/borrow/return',[BorrowController::class, 'returnBook']);