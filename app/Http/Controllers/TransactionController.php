<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function showDepositedTransactions()
    {
        $depositedTransactions = Transaction::where('transaction_type', 'deposit')->get();

        return view('deposited_transactions', ['transactions' => $depositedTransactions]);
    }
    public function makeDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $newTransaction = Transaction::create([
            'user_id' => auth()->id(),
            'transaction_type' => 'deposit',
            'amount' => $request->amount,
            'fee' => 0,
            'date' => now(),
        ]);

        // Update user balance
        $user = User::find(auth()->id());
        $user->balance += $newTransaction->amount;
        $user->save();


        return redirect()->route('deposit.transactions')->with('success', 'Deposit successful.');
    }

    public function showWithdrawalTransactions()
    {
        $withdrawalTransactions = Transaction::where('transaction_type', 'withdrawal')->get();

        return view('withdrawal_transactions', ['transactions' => $withdrawalTransactions]);
    }

    public function makeWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $user = User::find(auth()->id());
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Calculate withdrawal fee based on account type and amount
        $withdrawalFee = $this->calculateWithdrawalFee($user, $request->amount);

        // Apply free withdrawal conditions for Individual accounts
        if ($user->account_type == 'individual') {
            if (Carbon::now()->isFriday() || $request->amount <= 1000 || $this->isFirstWithdrawalOfMonth($user, $request->amount)) {
                $withdrawalFee = 0; 
            }
        }

        $newTransaction = Transaction::create([
            'user_id' => $user->id,
            'transaction_type' => 'withdrawal',
            'amount' => $request->amount,
            'fee' => $withdrawalFee,
            'date' => now(),
        ]);

         $user = User::find(auth()->id());
         $user->balance -= $newTransaction->amount;
         $user->save();

        return redirect()->route('withdrawal.transactions')->with('success', 'Withdrawal successful.');
    }

    // Method to calculate withdrawal fee based on account type and amount
    private function calculateWithdrawalFee($user, $amount)
    {
        $withdrawalRate = ($user->account_type == 'individual') ? 0.015 : 0.025;

        // Decrease withdrawal fee for Business accounts after total withdrawal of 50K
        if ($user->account_type == 'business' && $user->total_withdrawal >= 50000) {
            $withdrawalRate = 0.015;
        }

        $withdrawalFee = $withdrawalRate * $amount;

        return $withdrawalFee;
    }

    // Method to check if it's the first withdrawal of the month
    private function isFirstWithdrawalOfMonth($user, $amount)
    {
        $month = now()->month;
        $totalWithdrawalThisMonth = $user->transactions()
            ->where('transaction_type', 'withdrawal')
            ->whereMonth('created_at', $month)
            ->sum('amount');

        return $amount <= 5000 && $totalWithdrawalThisMonth == 0;
    }
}
