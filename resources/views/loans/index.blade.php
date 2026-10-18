<x-app-layout>
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white drop-shadow-md">My Loans</h1>
        <a href="{{ route('loans.create') }}" class="px-6 py-2 bg-white/20 backdrop-blur-sm border border-white/30 text-white rounded-xl hover:bg-white/30 transition-all shadow-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Apply New Loan
        </a>
    </div>

    <!-- Glass Table Container -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 overflow-hidden w-full">
        @if($loans->isEmpty())
            <div class="p-12 text-center text-stark-dark/60">
                <p>No loans found. Start by applying for one!</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200/50">
                    <thead class="bg-stark-blue/5">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">Applied On</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/50 bg-white/50">
                        @foreach($loans as $loan)
                            <tr class="hover:bg-white/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-stark-dark">#{{ $loan->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-stark-dark">{{ $loan->loanType->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-stark-dark">${{ number_format($loan->amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $loan->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($loan->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                           ($loan->status === 'paid' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-stark-dark/70">{{ $loan->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>