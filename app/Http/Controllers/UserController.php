<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class UserController extends Controller
{
    public function transactions()
    {
        // Retrieve authenticated user
        $user = auth()->user();

        // Retrieve transactions associated with the user
        $transactions = Transaction::where('user_id', $user->id)->latest()->get();

        // Retrieve user's balance
        $balance = $user->balance;
        $accountType = $user->account_type;

        // Return dashboard view with transactions
        return view('dashboard', compact('transactions', 'balance', 'accountType'));
    }
}

