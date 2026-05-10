<x-app-layout>
    <!-- Chart.js and Logic at the top for instant availability -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Move data to global constants to avoid HTML attribute quote conflicts
        const CHART_DATA = @json($catBreakdown);
        const CHART_TOTAL = {{ $totalSpend ?: 1 }};

        function renderExpenseChart() {
            // Wait for DOM to be fully stable
            setTimeout(() => {
                const canvas = document.getElementById('categoryChart');
                if (!canvas) return;
                
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(CHART_DATA),
                        datasets: [{
                            data: Object.values(CHART_DATA),
                            backgroundColor: [
                                '#6366f1', '#a855f7', '#ec4899', '#f43f5e', '#ef4444', 
                                '#f59e0b', '#10b981', '#06b6d4', '#3b82f6', '#8b5cf6'
                            ],
                            borderWidth: 0,
                            hoverOffset: 25,
                            borderRadius: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        animation: { duration: 1500, easing: 'easeOutQuart' },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 30,
                                    font: { size: 11, weight: '700', family: 'Outfit' },
                                    generateLabels: (chart) => {
                                        const chartData = chart.data;
                                        return chartData.labels.map((label, i) => {
                                            const val = chartData.datasets[0].data[i];
                                            const perc = ((val / CHART_TOTAL) * 100).toFixed(1);
                                            return {
                                                text: `${label} (${perc}%)`,
                                                fillStyle: chartData.datasets[0].backgroundColor[i],
                                                strokeStyle: 'transparent',
                                                pointStyle: 'circle',
                                                index: i
                                            };
                                        });
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                padding: 15,
                                callbacks: {
                                    label: (ctx) => ` ৳${ctx.raw.toLocaleString()} (${((ctx.raw/CHART_TOTAL)*100).toFixed(1)}%)`
                                }
                            }
                        }
                    }
                });
            }, 50); 
        }
    </script>

    <div class="min-h-screen bg-slate-50" x-data="{
        month: '{{ $month }}',
        year: '{{ $year }}',
        
        changeMonth(delta) {
            let m = parseInt(this.month) + delta;
            let y = parseInt(this.year);
            if (m > 12) { m = 1; y++; }
            if (m < 1) { m = 12; y--; }
            window.location.href = `{{ route('expenses.index') }}?month=${String(m).padStart(2, '0')}&year=${y}`;
        }
    }" x-init="renderExpenseChart()">
        <!-- Header & Month Switcher -->
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-6 pt-16 pb-20 rounded-b-[4rem] shadow-2xl">
            <div class="flex justify-between items-center mb-8">
                <button @click="changeMonth(-1)" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center text-white border border-white/20 active:scale-90 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div class="text-center">
                    <h1 class="text-2xl font-black text-white tracking-tight">{{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</h1>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full mt-2">
                        <div class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse"></div>
                        <p class="text-indigo-100 text-[9px] font-black uppercase tracking-widest">Monthly Intelligence</p>
                    </div>
                </div>
                <button @click="changeMonth(1)" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center text-white border border-white/20 active:scale-90 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>

            <!-- Total Card -->
            <div class="bg-white/10 backdrop-blur-md rounded-[3rem] p-8 border border-white/10 text-center">
                <p class="text-indigo-200 text-[10px] font-black uppercase tracking-widest mb-2">Total Monthly Investment</p>
                <h2 class="text-5xl font-black text-white">৳{{ number_format($totalSpend, 0) }}</h2>
            </div>
        </div>

        <div class="px-6 -mt-10 pb-24 space-y-6">
            <!-- Analytics Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Top Category</p>
                    <p class="text-xs font-black text-slate-800 truncate">{{ $highestCat }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Most Frugal</p>
                    <p class="text-xs font-black text-slate-800 truncate">{{ $lowestCat }}</p>
                </div>
            </div>

            <!-- Breakdown Chart -->
            <div class="bg-white p-8 rounded-[3.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 min-h-[450px]">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-8">Spending Profile</h3>
                <div class="relative h-80">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <!-- Type Selector -->
            <div class="bg-white p-2 rounded-[2.5rem] shadow-lg border border-slate-100 flex gap-1 overflow-x-auto no-scrollbar">
                @foreach(['all' => 'Slate', 'needs' => 'Indigo', 'wants' => 'Purple', 'savings' => 'Emerald'] as $key => $color)
                <a href="{{ route('expenses.index', ['month' => $month, 'year' => $year, 'type' => $key]) }}" 
                    class="px-6 py-4 rounded-[2rem] text-[9px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ request('type', 'all') == $key ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400' }}">
                    {{ ucfirst($key) }}
                </a>
                @endforeach
            </div>

            <!-- Transactions List -->
            <div class="space-y-4">
                @forelse($expenses as $expense)
                    <a href="{{ route('expenses.edit', $expense) }}" class="block bg-white p-6 rounded-[3rem] shadow-sm border border-slate-50 flex items-center gap-5 active:scale-95 transition-all">
                        <div class="h-14 w-14 rounded-[1.5rem] bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-black text-slate-800 truncate">{{ $expense->description ?: $expense->category->name }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">{{ $expense->category->name }} • {{ $expense->expense_date->format('d M, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-black text-slate-900 tracking-tighter">৳{{ number_format($expense->amount, 0) }}</p>
                        </div>
                    </a>
                @empty
                    <div class="bg-white p-16 rounded-[4rem] border-2 border-dashed border-slate-200 text-center">
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Empty Selection</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
