<!-- resources/views/components/app-sidebar.blade.php -->
<div class="min-h-screen flex font-sans antialiased text-stark-dark" 
     style="background: linear-gradient(180deg, #0ea5e9 0%, #ffffff 100%);">
    
    <!-- Sidebar (Transparent to Blend Perfectly) -->
    <aside class="w-64 flex-shrink-0 flex flex-col fixed h-full z-30 transition-all duration-300 lg:relative bg-transparent border-r border-white/10">
        
        <!-- Logo Area -->
        <div class="h-20 flex items-center justify-center border-b border-white/10">
            <span class="text-2xl font-bold text-white tracking-widest drop-shadow-lg">CMS</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto">
            
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-stark-dark hover:bg-white/20 hover:text-white transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-white/90' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <a href="{{ route('loans.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-stark-dark hover:bg-white/20 hover:text-white transition-all duration-200 group {{ request()->routeIs('loans.*') && !request()->routeIs('loans.create') ? 'bg-white/20 text-white shadow-lg' : 'text-white/90' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                My Loans
            </a>

            <a href="{{ route('loans.create') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-stark-dark hover:bg-white/20 hover:text-white transition-all duration-200 group {{ request()->routeIs('loans.create') ? 'bg-white/20 text-white shadow-lg' : 'text-white/90' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Apply for Loan
            </a>

            <a href="{{ route('charts.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-stark-dark hover:bg-white/20 hover:text-white transition-all duration-200 group {{ request()->routeIs('charts.*') ? 'bg-white/20 text-white shadow-lg' : 'text-white/90' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                Analytics
            </a>

            <!-- ADMIN ONLY SECTION -->
            @if(Auth::check() && Auth::user()->isAdmin())
                <div class="my-6 border-t border-white/10"></div>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-xl text-white bg-red-500/20 hover:bg-red-500 hover:text-white transition-all duration-200 group border border-red-500/30 {{ request()->routeIs('admin.*') ? 'bg-red-500 shadow-[0_0_15px_rgba(239,68,68,0.5)]' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Admin Panel
                </a>
            @endif

        </nav>

        <!-- User Profile Bottom -->
        <div class="p-4 border-t border-white/10 bg-black/5 backdrop-blur-md">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold border border-white/30">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-white/70 hover:text-white transition-colors mt-1">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area (NO BACKGROUND - Inherits Parent Gradient) -->
    <main class="flex-1 min-w-0 overflow-y-auto p-6 lg:p-10 relative">
        <!-- Subtle Glow Overlay for Depth -->
        <div class="absolute inset-0 pointer-events-none opacity-30" 
             style="background: radial-gradient(circle at center, rgba(255, 255, 255, 0.2) 0%, transparent 70%);"></div>
        
        <!-- Actual Content -->
        <div class="relative z-10">
            {{ $slot }}
        </div>
    </main>
</div>