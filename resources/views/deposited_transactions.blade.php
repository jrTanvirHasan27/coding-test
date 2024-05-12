<!-- deposited_transactions.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Deposited Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h3 class="font-semibold text-lg mb-4">Deposited Transactions</h3>
                    <div class="overflow-x-auto mb-4">
                        <!-- Display the deposited transactions -->
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                            <!-- Table Header -->
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fee</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                </tr>
                            </thead>
                            <!-- Table Body -->
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->transaction_type }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->amount }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->fee }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Form for new deposit -->
                    <form action="{{ route('make.deposit') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" id="amount" name="amount"
                                class="mt-1 p-2 block w-full border-gray-300 rounded-md">
                        </div>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded border border-gray-500">Deposit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
