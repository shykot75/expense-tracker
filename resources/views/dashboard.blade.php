<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-24">
        <!-- Top Stats Section (Dark Mode Style) -->
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-6 pt-16 pb-24 rounded-b-[4rem] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>

            <div class="flex justify-between items-center mb-10 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center overflow-hidden">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-full w-full object-cover" alt="Avatar">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=6366f1&color=fff" class="h-full w-full object-cover" alt="Avatar">
                        @endif
                    </div>
                    <div>
                        <p class="text-indigo-200 text-[10px] font-black uppercase tracking-widest">Welcome back,</p>
                        <h1 class="text-xl font-black text-white">{{ explode(' ', Auth::user()->name)[0] }}</h1>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-12 px-4 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 text-white gap-2">
                        <span class="text-xl">🔥</span>
                        <span class="text-sm font-black">{{ Auth::user()->current_streak }}</span>
                    </div>
                    <div class="h-12 w-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Central Balance Card -->
            <div class="text-center relative z-10">
                <p class="text-indigo-200 text-[10px] font-black uppercase tracking-widest mb-2">Available Balance</p>
                <h2 class="text-6xl font-black text-white tracking-tight">৳{{ number_format($totalRemaining, 0) }}</h2>
                <div class="mt-6 inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/10">
                    <span class="text-xs font-black text-white">Spent ৳{{ number_format($totalSpent, 0) }}</span>
                    <span class="w-1 h-1 bg-white/40 rounded-full"></span>
                    <span class="text-xs font-black text-indigo-300">{{ number_format($spentPercentage, 1) }}% of Income</span>
                </div>
            </div>
        </div>

        <div class="px-6 -mt-12 space-y-8 relative z-20">
            <!-- Progress Breakdown Grid -->
            <div class="grid grid-cols-1 gap-6">
                @foreach(['needs' => 'Indigo', 'wants' => 'Purple', 'savings' => 'Emerald'] as $type => $color)
                <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-{{ strtolower($color) }}-50 flex items-center justify-center text-{{ strtolower($color) }}-600">
                                @if($type == 'needs')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                @elseif($type == 'wants')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                    <span>{{ ucfirst($type) }}</span>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[8px] rounded-md font-bold">{{ $plan->{$type . '_percentage'} }}% Goal</span>
                                </h3>
                                <p class="text-[10px] font-bold text-slate-400">৳{{ number_format($breakdown[$type]['remaining'], 0) }} Remaining</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-slate-900">৳{{ number_format($breakdown[$type]['spent'], 0) }}</p>
                            <p class="text-[10px] font-bold text-slate-400">of ৳{{ number_format($breakdown[$type]['budget'], 0) }}</p>
                        </div>
                    </div>
                    
                    <!-- Premium Progress Bar -->
                    <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ strtolower($color) }}-500 rounded-full transition-all duration-1000 shadow-lg shadow-{{ strtolower($color) }}-200" 
                             style="width: {{ $breakdown[$type]['percentage'] }}%"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span class="text-[9px] font-black text-{{ strtolower($color) }}-600 uppercase tracking-tighter">{{ number_format($breakdown[$type]['percentage'], 0) }}% Spent</span>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-tighter">Budget Goal</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Financial Intelligence Hub (New) -->
            <div class="space-y-4">
                <div class="flex justify-between items-center px-2">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Financial Intelligence</h3>
                </div>
                <a href="{{ route('reports.index') }}" class="block bg-gradient-to-br from-slate-900 to-indigo-900 p-8 rounded-[3rem] shadow-2xl shadow-indigo-100 relative overflow-hidden active:scale-95 transition-all">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <h3 class="text-xl font-black text-white">Monthly Reports</h3>
                            <p class="text-indigo-200 text-[10px] font-black uppercase tracking-widest mt-1">Analyze spending & export PDF</p>
                        </div>
                        <div class="h-14 w-14 bg-white/10 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center border border-white/20 text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2a4 4 0 014-4h4m0 0l-4-4m4 4l-4 4m5 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2h2"></path></svg>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Loan Tracker Summary (New) -->
            <div class="space-y-4">
                <div class="flex justify-between items-center px-2">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Debt Overview</h3>
                    <a href="{{ route('loans.index') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Manage Loans</a>
                </div>
                <a href="{{ route('loans.index') }}" class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group active:scale-95 transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="h-8 w-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Lent</span>
                        </div>
                        <p class="text-xl font-black text-slate-900">৳{{ number_format($totalLent, 0) }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group active:scale-95 transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="h-8 w-8 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5-5v12"></path></svg>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Borrowed</span>
                        </div>
                        <p class="text-xl font-black text-slate-900">৳{{ number_format($totalBorrowed, 0) }}</p>
                    </div>
                </a>
            </div>

            <!-- Recent Activity Section -->
            <div class="space-y-4">
                <div class="flex justify-between items-center px-2">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recent Activity</h3>
                    <a href="{{ route('expenses.index') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">View All</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentExpenses as $expense)
                        <a href="{{ route('expenses.edit', $expense) }}" class="block bg-white p-5 rounded-[2.5rem] shadow-sm border border-slate-50 flex items-center gap-4 active:scale-95 transition-all">
                            <div class="h-12 w-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-slate-800 truncate">{{ $expense->description ?: $expense->category->name }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $expense->category->name }} • {{ $expense->expense_date->format('M d') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-black text-slate-900">৳{{ number_format($expense->amount, 0) }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="bg-white p-8 rounded-[3rem] border-2 border-dashed border-slate-200 text-center">
                            <p class="text-sm font-bold text-slate-400">No recent activity.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
