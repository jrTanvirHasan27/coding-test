<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all users
        $users = User::all();

        // Initialize variables to store sums
        $withdrawalSum = 0;
        $depositSum = 0;

        // Generate transactions for each user and calculate sums
        foreach ($users as $user) {
            for ($i = 0; $i < 10; $i++) { // Generating 10 transactions for each user
                $transactionType = $faker->randomElement(['withdrawal', 'deposit']);
                $amount = $faker->randomFloat(2, 10, 1000);

                // Update sums based on transaction type
                if ($transactionType === 'withdrawal') {
                    $withdrawalSum += $amount;
                } else {
                    $depositSum += $amount;
                }

                // Create transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'transaction_type' => $transactionType,
                    'amount' => $amount,
                    'fee' => $faker->randomFloat(2, 0, 10),
                    'date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
                ]);
            }
        }

        // Calculate net change in balance
        $netChange = $depositSum - $withdrawalSum;

        // Update main balance of users
        DB::table('users')->update(['balance' => DB::raw('balance + ' . $netChange)]);
    }
}
