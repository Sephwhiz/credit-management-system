<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <h1 class="text-3xl font-bold text-white drop-shadow-md">Admin Dashboard</h1>
            <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-[0_0_10px_rgba(239,68,68,0.5)]">
                Admin Mode
            </span>
        </div>
        <p class="text-white/80">System-wide credit management overview.</p>
    </div>

    <!-- KPI Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 w-full">
        
        <!-- Total Disbursed -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-stark-dark/60 uppercase tracking-wider">Total Disbursed</h3>
                <div class="w-10 h-10 rounded-full bg-stark-blue/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-stark-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-stark-dark">${{ number_format($totalDisbursed, 2) }}</p>
            <p class="text-xs text-stark-dark/50 mt-1">Lifetime funded amount</p>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-stark-dark/60 uppercase tracking-wider">Pending Approvals</h3>
                <div class="w-10 h-10 rounded-full bg-yellow-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-stark-dark">{{ $pendingApprovals }}</p>
            <p class="text-xs text-stark-dark/50 mt-1">Awaiting admin review</p>
        </div>

        <!-- Overdue Loans -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-stark-dark/60 uppercase tracking-wider">Overdue Loans</h3>
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-stark-dark">{{ $overdueLoans }}</p>
            <p class="text-xs text-stark-dark/50 mt-1">Past due date</p>
        </div>

        <!-- New Users This Week -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-stark-dark/60 uppercase tracking-wider">New Users</h3>
                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-stark-dark">{{ $newUsersThisWeek }}</p>
            <p class="text-xs text-stark-dark/50 mt-1">Registered this week</p>
        </div>

    </div>

    <!-- YTD Interest Summary Card -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-stark-dark mb-1">Total Interest Earned (YTD)</h3>
                <p class="text-sm text-stark-dark/60">Calendar year {{ now()->year }} approved loans</p>
            </div>
            <div class="text-right">
                <p class="text-4xl font-bold text-stark-blue">${{ number_format($ytdInterest, 2) }}</p>
                <p class="text-xs text-stark-dark/50 mt-1">Cumulative interest revenue</p>
            </div>
        </div>
    </div>

    <!-- Link to Analytics Page (Option B) -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 p-8 text-center">
        <p class="text-stark-dark/60 mb-4">View detailed portfolio analytics and business performance metrics.</p>
        <a href="{{ route('admin.analytics') }}" class="inline-flex items-center px-6 py-3 bg-stark-blue text-white rounded-xl hover:bg-stark-dark transition-all shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            View Full Analytics
        </a>
    </div>
</x-app-layout>