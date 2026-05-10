<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-32">
        <!-- Header -->
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 px-6 pt-16 pb-32 rounded-b-[4rem] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="flex justify-between items-center relative z-10 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Report Hub</h1>
                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest mt-1">Financial Intelligence</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.download', ['month' => $date->month, 'year' => $date->year]) }}" 
                       class="h-12 px-6 bg-emerald-500 rounded-2xl flex items-center justify-center text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                        PDF Export
                    </a>
                </div>
            </div>

            <!-- Month/Year Selector -->
            <form action="{{ route('reports.index') }}" method="GET" class="relative z-10 flex gap-2">
                <div class="flex-1 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-1 flex">
                    <select name="month" onchange="this.form.submit()" class="bg-transparent border-none text-white text-xs font-black uppercase tracking-widest focus:ring-0 flex-1 py-3">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" class="text-slate-900" {{ $date->month == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create(null, $m, 1)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()" class="bg-transparent border-none text-white text-xs font-black uppercase tracking-widest focus:ring-0 w-24 py-3">
                        @foreach(range(date('Y')-5, date('Y')) as $y)
                            <option value="{{ $y }}" class="text-slate-900" {{ $date->year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="px-6 -mt-16 space-y-6 relative z-20">
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 gap-4">
                <!-- Summary Card -->
                <div class="bg-white rounded-[3rem] p-8 shadow-2xl border border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-2 mb-8">
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Income</p>
                            <p class="text-lg font-black text-slate-900">{{ auth()->user()->currency_symbol }}{{ number_format($totalIncome) }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Expenses</p>
                            <p class="text-lg font-black text-rose-500">{{ auth()->user()->currency_symbol }}{{ number_format($totalExpense) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Savings</p>
                            <p class="text-lg font-black {{ $savings >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">{{ auth()->user()->currency_symbol }}{{ number_format($savings) }}</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="h-4 w-full bg-slate-100 rounded-full overflow-hidden flex">
                        @php $expensePercent = $totalIncome > 0 ? min(100, ($totalExpense / $totalIncome) * 100) : 100; @endphp
                        <div class="h-full bg-emerald-400" style="width: {{ 100 - $expensePercent }}%"></div>
                        <div class="h-full bg-rose-400" style="width: {{ $expensePercent }}%"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <p class="text-[8px] font-bold text-slate-400 uppercase">Savings {{ 100 - round($expensePercent) }}%</p>
                        <p class="text-[8px] font-bold text-slate-400 uppercase">Expenses {{ round($expensePercent) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div class="bg-white rounded-[3rem] p-8 shadow-xl border border-slate-100">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Spending by Category</h3>
                <div class="space-y-6">
                    @forelse($categorySummary as $category => $amount)
                        @php $percent = $totalExpense > 0 ? ($amount / $totalExpense) * 100 : 0; @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-black text-slate-700">{{ $category }}</span>
                                <span class="text-sm font-black text-slate-900">{{ auth()->user()->currency_symbol }}{{ number_format($amount) }}</span>
                            </div>
                            <div class="h-2 w-full bg-slate-50 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs font-bold text-slate-300 uppercase py-10">No expenses recorded for this month</p>
                    @endforelse
                </div>
            </div>

            <!-- Debt Overview -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-indigo-50 rounded-[2.5rem] p-6 border border-indigo-100">
                    <p class="text-[8px] font-black text-indigo-400 uppercase tracking-widest mb-1">Total Lent</p>
                    <p class="text-xl font-black text-indigo-900">{{ auth()->user()->currency_symbol }}{{ number_format($totalLent) }}</p>
                </div>
                <div class="bg-rose-50 rounded-[2.5rem] p-6 border border-rose-100">
                    <p class="text-[8px] font-black text-rose-400 uppercase tracking-widest mb-1">Borrowed</p>
                    <p class="text-xl font-black text-rose-900">{{ auth()->user()->currency_symbol }}{{ number_format($totalBorrowed) }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
