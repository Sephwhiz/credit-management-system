<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-md">Loan Management</h1>
            <span class="ml-3 px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-[0_0_10px_rgba(239,68,68,0.5)]">
    Admin Mode
</span>
            <p class="text-white/80">Review and manage all loan applications.</p>
        </div>
        
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.loans.index') }}" class="flex gap-2">
            <select name="status" onchange="this.form.submit()" 
                    class="bg-white/90 backdrop-blur-md border border-white/30 rounded-xl px-4 py-2 text-sm text-stark-dark focus:outline-none">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
            
            <input type="text" name="search" placeholder="Search user or amount..." 
                   value="{{ request('search') }}"
                   class="bg-white/90 backdrop-blur-md border border-white/30 rounded-xl px-4 py-2 text-sm text-stark-dark focus:outline-none w-48">
                   
            <button type="submit" class="bg-stark-blue text-white px-4 py-2 rounded-xl hover:bg-stark-dark transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-xl text-green-700 backdrop-blur-sm">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-xl text-red-700 backdrop-blur-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Loans Table Card -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200/50">
                <thead class="bg-stark-blue/10">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-stark-dark/70 uppercase tracking-wider">Applied On</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-stark-dark/70 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/50 bg-white/50">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-white/40 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-stark-dark">#{{ $loan->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-stark-dark">{{ $loan->user->name }}</td>
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
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($loan->status === 'pending')
                                    <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Approve this loan?')" 
                                                class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Reject this loan?')" 
                                                class="text-red-600 hover:text-red-900">Reject</button>
                                    </form>
                                @else
                                    <span class="text-stark-dark/40 text-xs">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-stark-dark/50">
                                No loans found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200/50">
            {{ $loans->links() }}
        </div>
    </div>
</x-app-layout>