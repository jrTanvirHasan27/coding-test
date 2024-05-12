<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [UserController::class, 'transactions'])->name('dashboard');
    Route::get('/deposit', [TransactionController::class, 'showDepositedTransactions'])->name('deposit.transactions');
    Route::post('/deposit', [TransactionController::class, 'makeDeposit'])->name('make.deposit');
    Route::get('/withdrawal', [TransactionController::class, 'showWithdrawalTransactions'])->name('withdrawal.transactions');
    Route::post('/withdrawal', [TransactionController::class, 'makeWithdrawal'])->name('make.withdrawal');
});
