<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2 drop-shadow-md">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="text-white/80">Here's what's happening with your credit portfolio today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 w-full">
        
        <!-- Total Loans Card -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-stark-dark/60 uppercase tracking-wider">Total Loans</h3>
                <div class="w-10 h-10 rounded-full bg-stark-blue/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-stark-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-stark-dark">0</p>
            <p class="text-xs text-stark-dark/50 mt-1">Active applications</p>
        </div>

        <!-- Approved Amount Card -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-stark-dark/60 uppercase tracking-wider">Approved</h3>
                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-stark-dark">$0.00</p>
            <p class="text-xs text-stark-dark/50 mt-1">Total funded amount</p>
        </div>

        <!-- Pending Applications Card -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-stark-dark/60 uppercase tracking-wider">Pending</h3>
                <div class="w-10 h-10 rounded-full bg-yellow-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-stark-dark">0</p>
            <p class="text-xs text-stark-dark/50 mt-1">Awaiting review</p>
        </div>

        <!-- Next Due Date Card -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-stark-dark/60 uppercase tracking-wider">Next Payment</h3>
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-stark-dark">--</p>
            <p class="text-xs text-stark-dark/50 mt-1">No upcoming dues</p>
        </div>

    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-white/20 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-stark-dark">Recent Activity</h3>
            <a href="{{ route('loans.index') }}" class="text-sm text-stark-blue hover:text-stark-dark transition-colors">View All</a>
        </div>
        
        <div class="p-8 min-h-[300px] flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 rounded-full bg-stark-light/50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-stark-dark/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <p class="text-stark-dark/60 font-medium mb-1">No recent loan activity found.</p>
            <p class="text-sm text-stark-dark/40 mb-6">Start building your credit history today.</p>
            <a href="{{ route('loans.create') }}" class="inline-flex items-center px-5 py-2.5 bg-stark-blue text-white text-sm font-medium rounded-xl hover:bg-stark-dark transition-all shadow-stark hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Apply for Your First Loan
            </a>
        </div>
    </div>
</x-app-layout>