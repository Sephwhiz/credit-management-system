<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h1 class="text-3xl font-bold text-white drop-shadow-md">Admin Analytics</h1>
        <span class="ml-3 px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-[0_0_10px_rgba(239,68,68,0.5)]">
    Admin Mode
</span>
        
        <!-- Custom Dropdown (Same as User Analytics) -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="flex items-center bg-white/90 backdrop-blur-md px-4 py-2 rounded-xl shadow-lg border border-white/30 hover:bg-white focus:outline-none">
                <span class="text-sm font-medium text-stark-dark mr-2">Time Period:</span>
                <span class="text-sm font-semibold text-stark-blue">{{ ucfirst(str_replace('_', ' ', $filter)) }}</span>
                <svg class="w-4 h-4 text-stark-dark ml-2 transition-transform duration-200" 
                     :class="{ 'rotate-180': open }" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <div x-show="open" @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                <form method="GET" action="{{ route('admin.analytics') }}">
                    <button type="submit" name="filter" value="today" 
                            class="w-full text-left px-4 py-2 text-sm hover:bg-stark-blue hover:text-white transition-colors {{ $filter === 'today' ? 'bg-stark-blue text-white' : 'text-stark-dark' }}">Today</button>
                    <button type="submit" name="filter" value="week" 
                            class="w-full text-left px-4 py-2 text-sm hover:bg-stark-blue hover:text-white transition-colors {{ $filter === 'week' ? 'bg-stark-blue text-white' : 'text-stark-dark' }}">This Week</button>
                    <button type="submit" name="filter" value="month" 
                            class="w-full text-left px-4 py-2 text-sm hover:bg-stark-blue hover:text-white transition-colors {{ $filter === 'month' ? 'bg-stark-blue text-white' : 'text-stark-dark' }}">This Month</button>
                </form>
            </div>
        </div>
    </div>

    <!-- SECTION A: User-Style Charts -->
    <h2 class="text-xl font-bold text-white mb-4 drop-shadow-md">Portfolio Overview</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 p-8 min-h-[400px] flex flex-col">
            <h3 class="text-lg font-bold text-stark-dark mb-4 flex items-center">
                <span class="w-2 h-2 bg-stark-blue rounded-full mr-2"></span> Application Status
            </h3>
            <div class="flex-1 relative w-full" style="min-height: 300px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 p-8 min-h-[400px] flex flex-col">
            <h3 class="text-lg font-bold text-stark-dark mb-4 flex items-center">
                <span class="w-2 h-2 bg-pink-500 rounded-full mr-2"></span> Loans by Category
            </h3>
            <div class="flex-1 relative w-full" style="min-height: 300px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- SECTION B: Business-Focused Charts -->
    <h2 class="text-xl font-bold text-white mb-4 drop-shadow-md">Business Performance</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 p-8 min-h-[400px] flex flex-col">
            <h3 class="text-lg font-bold text-stark-dark mb-4 flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Monthly Revenue Trend
            </h3>
            <div class="flex-1 relative w-full" style="min-height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 p-8 min-h-[400px] flex flex-col">
            <h3 class="text-lg font-bold text-stark-dark mb-4 flex items-center">
                <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span> Approval Rate (%)
            </h3>
            <div class="flex-1 relative w-full" style="min-height: 300px;">
                <canvas id="approvalChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Alpine.js + Chart.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Status Pie
            new Chart(document.getElementById('statusChart'), {
                type: 'pie',
                data: {
                    labels: @json($chart1Data['labels']),
                    datasets: [{ data: @json($chart1Data['data']), backgroundColor: @json($chart1Data['colors']), borderWidth: 2, borderColor: '#ffffff' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
            });

            // Chart 2: Category Pie
            new Chart(document.getElementById('categoryChart'), {
                type: 'pie',
                data: {
                    labels: @json($chart2Data['labels']),
                    datasets: [{ data: @json($chart2Data['data']), backgroundColor: @json($chart2Data['colors']), borderWidth: 2, borderColor: '#ffffff' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
            });

            // Chart 3: Revenue Line
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: @json($chart3Data['labels']),
                    datasets: [{ label: 'Revenue ($)', data: @json($chart3Data['data']), borderColor: @json($chart3Data['color']), tension: 0.4, fill: true, backgroundColor: 'rgba(14, 165, 233, 0.1)' }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });

            // Chart 4: Approval Rate Bar
            new Chart(document.getElementById('approvalChart'), {
                type: 'bar',
                data: {
                    labels: @json($chart4Data['labels']),
                    datasets: [{ label: 'Approval %', data: @json($chart4Data['data']), backgroundColor: @json($chart4Data['color']), borderRadius: 6 }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
            });
        });
    </script>
</x-app-layout>