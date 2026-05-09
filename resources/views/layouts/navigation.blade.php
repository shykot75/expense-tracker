<nav class="fixed bottom-0 inset-x-0 bg-white/80 backdrop-blur-lg border-t border-slate-200 pb-safe z-50">
    <div class="flex justify-around items-center h-16 max-w-lg mx-auto">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-slate-400' }} flex flex-col items-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[10px] mt-1 font-bold">Home</span>
        </a>
        <a href="{{ route('recurring-bills.index') }}" class="{{ request()->routeIs('recurring-bills.*') ? 'text-indigo-600' : 'text-slate-400' }} flex flex-col items-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-[10px] mt-1 font-bold">Bills</span>
        </a>
        <div class="relative -top-5">
            <a href="{{ route('expenses.create') }}" class="bg-indigo-600 text-white p-4 rounded-2xl shadow-lg shadow-indigo-200 ring-4 ring-slate-50 flex items-center justify-center active:scale-90 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>
        <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.index') ? 'text-indigo-600' : 'text-slate-400' }} flex flex-col items-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="text-[10px] mt-1 font-bold">History</span>
        </a>
        <a href="/profile" class="flex flex-col items-center text-slate-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-[10px] mt-1 font-bold">Profile</span>
        </a>
    </div>
</nav>
