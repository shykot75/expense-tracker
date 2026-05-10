<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-32">
        <!-- Header -->
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 px-6 pt-24 pb-32 rounded-b-[4rem] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="flex justify-between items-center relative z-10 mb-8">
                <h1 class="text-3xl font-black text-white tracking-tight">Loan Tracker</h1>
                <a href="{{ route('loans.create') }}" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 text-white active:scale-90 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                </a>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 gap-4 relative z-10">
                <div class="bg-white/10 backdrop-blur-md rounded-[2.5rem] p-6 border border-white/10 shadow-xl">
                    <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Total Lent</p>
                    <p class="text-2xl font-black text-white">{{ auth()->user()->currency_symbol }}{{ number_format($totalLent) }}</p>
                    <div class="mt-4 h-1 w-full bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-400" style="width: 100%"></div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-[2.5rem] p-6 border border-white/10 shadow-xl">
                    <p class="text-[10px] font-black text-rose-200 uppercase tracking-widest mb-1">Total Borrowed</p>
                    <p class="text-2xl font-black text-white">{{ auth()->user()->currency_symbol }}{{ number_format($totalBorrowed) }}</p>
                    <div class="mt-4 h-1 w-full bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-400" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 -mt-16 space-y-6 relative z-20" x-data="{ tab: 'active' }">
            <!-- Segmented Control -->
            <div class="bg-white p-2 rounded-[2.5rem] shadow-xl border border-slate-100 flex gap-1">
                <button @click="tab = 'active'" :class="tab === 'active' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-4 text-[9px] font-black uppercase tracking-widest rounded-[2rem] transition-all">Active Loans</button>
                <button @click="tab = 'settled'" :class="tab === 'settled' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-4 text-[9px] font-black uppercase tracking-widest rounded-[2rem] transition-all">Settled History</button>
            </div>

            <!-- Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.opacity.duration.500ms 
                    class="bg-emerald-500 text-white p-6 rounded-[2.5rem] shadow-lg flex items-center gap-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <p class="font-black text-sm uppercase tracking-widest">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Active Loans List -->
            <div x-show="tab === 'active'" class="space-y-4" x-transition>
                @forelse($loans->where('status', 'active') as $loan)
                    <div class="bg-white rounded-[3rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center justify-between group">
                        <div class="flex items-center gap-5">
                            <div class="h-14 w-14 rounded-[1.5rem] flex items-center justify-center {{ $loan->loan_type === 'lent' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                @if($loan->loan_type === 'lent')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5-5v12"></path></svg>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-800">{{ $loan->person_name }}</h3>
                                    <span class="px-2 py-0.5 bg-slate-100 rounded-full text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $loan->loan_type }}</span>
                                </div>
                                <p class="text-xl font-black text-slate-900 mt-0.5">{{ auth()->user()->currency_symbol }}{{ number_format($loan->amount) }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $loan->loan_date->format('d M, Y') }}</span>
                                    @if($loan->deadline_date)
                                        <span class="text-[9px] font-bold {{ $loan->deadline_date->isPast() ? 'text-rose-500' : 'text-slate-300' }} uppercase tracking-widest">
                                            • Due {{ $loan->deadline_date->format('d M') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <form action="{{ route('loans.toggle', $loan) }}" method="POST">
                                @csrf
                                <button type="submit" class="h-10 w-10 bg-slate-50 text-slate-300 rounded-xl flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-500 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                            <a href="{{ route('loans.edit', $loan) }}" class="h-10 w-10 bg-slate-50 text-slate-300 rounded-xl flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <div class="h-24 w-24 bg-slate-100 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-400">No active loans</h3>
                        <p class="text-xs text-slate-300 font-bold mt-1">Start tracking your borrowing and lending</p>
                    </div>
                @endforelse
            </div>

            <!-- Settled Loans List -->
            <div x-show="tab === 'settled'" class="space-y-6" x-transition x-cloak>
                <!-- Settled Summary -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-emerald-50/50 p-4 rounded-[2rem] border border-emerald-100/50">
                        <p class="text-[8px] font-black text-emerald-600 uppercase tracking-widest mb-1">Recovered</p>
                        <p class="text-lg font-black text-emerald-900">{{ auth()->user()->currency_symbol }}{{ number_format($totalSettledLent) }}</p>
                    </div>
                    <div class="bg-rose-50/50 p-4 rounded-[2rem] border border-rose-100/50">
                        <p class="text-[8px] font-black text-rose-600 uppercase tracking-widest mb-1">Cleared</p>
                        <p class="text-lg font-black text-rose-900">{{ auth()->user()->currency_symbol }}{{ number_format($totalSettledBorrowed) }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($loans->where('status', 'paid') as $loan)
                    <div class="bg-white/60 rounded-[3rem] p-6 shadow-sm border border-slate-100 flex items-center justify-between opacity-75 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
                        <div class="flex items-center gap-5">
                            <div class="h-14 w-14 rounded-[1.5rem] flex items-center justify-center bg-slate-100 text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-500 line-through">{{ $loan->person_name }}</h3>
                                    <span class="px-2 py-0.5 bg-slate-50 rounded-full text-[8px] font-black uppercase tracking-widest text-slate-300">Settled</span>
                                </div>
                                <p class="text-xl font-black text-slate-400">{{ auth()->user()->currency_symbol }}{{ number_format($loan->amount) }}</p>
                                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mt-1">Paid on {{ $loan->updated_at->format('d M, Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <form action="{{ route('loans.toggle', $loan) }}" method="POST">
                                @csrf
                                <button type="submit" class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center hover:bg-indigo-100 transition-all" title="Reactivate">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">No settled history yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
