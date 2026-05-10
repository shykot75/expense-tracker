<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-32 pt-12 px-6" x-data="{ tab: 'overview' }">
        <!-- Header -->
        <div class="flex items-center justify-between mb-10 px-2">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Financial Time Machine</p>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Wealth Forecast</h2>
            </div>
            <div class="h-14 w-14 bg-white rounded-3xl shadow-xl flex items-center justify-center border border-slate-100">
                <span class="text-2xl">⏳</span>
            </div>
        </div>

        @if(!$overview)
            <div class="bg-white p-12 rounded-[3.5rem] shadow-xl text-center border border-slate-100">
                <div class="h-20 w-20 bg-indigo-50 text-indigo-600 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-3xl">
                    📊
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">No Budget Plan Detected</h3>
                <p class="text-xs text-slate-500 font-bold mb-8 leading-relaxed">We need your 60-25-15 budget settings to calculate your financial future.</p>
                <a href="{{ route('settings.index', ['tab' => 'budget']) }}" class="inline-block bg-slate-900 text-white px-10 py-5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl active:scale-95 transition-all">Setup Budget Now</a>
            </div>
        @else
            <!-- Key Projections -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-8 rounded-[3rem] shadow-xl text-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-2">6 Month Outlook</p>
                    <h3 class="text-2xl font-black">৳{{ number_format($overview['six_month_forecast']) }}</h3>
                </div>
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full blur-2xl"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">12 Month Growth</p>
                    <h3 class="text-2xl font-black text-slate-900">৳{{ number_format($overview['one_year_forecast']) }}</h3>
                </div>
            </div>

            <!-- Main Growth Chart Card -->
            <div class="bg-white p-8 rounded-[3.5rem] shadow-xl border border-slate-100 mb-8 relative overflow-hidden">
                <div class="flex items-center justify-between mb-10 px-2">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Projected Wealth Accumulation</h3>
                    <div class="flex gap-2">
                        <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                        <span class="w-3 h-3 bg-slate-100 rounded-full"></span>
                    </div>
                </div>

                <!-- Visual Chart (Elite SVG Representation) -->
                <div class="h-48 w-full relative mt-4 mb-8">
                    <svg class="w-full h-full" viewBox="0 0 100 40" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="chartGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:rgb(99, 102, 241);stop-opacity:0.2" />
                                <stop offset="100%" style="stop-color:rgb(99, 102, 241);stop-opacity:0" />
                            </linearGradient>
                        </defs>
                        <!-- Area -->
                        <path d="M 0 40 L 0 35 Q 25 32 50 25 T 100 5 L 100 40 Z" fill="url(#chartGrad)" />
                        <!-- Line -->
                        <path d="M 0 35 Q 25 32 50 25 T 100 5" fill="none" stroke="#6366f1" stroke-width="1.5" stroke-linecap="round" />
                        <!-- Points -->
                        <circle cx="0" cy="35" r="1" fill="#6366f1" />
                        <circle cx="50" cy="25" r="1" fill="#6366f1" />
                        <circle cx="100" cy="5" r="1" fill="#6366f1" />
                    </svg>
                    
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity bg-white/10 backdrop-blur-[2px] rounded-2xl pointer-events-none">
                        <p class="text-[10px] font-black text-indigo-600 bg-white px-4 py-2 rounded-full shadow-lg border border-indigo-50 uppercase tracking-widest">Steady Compound Growth</p>
                    </div>
                </div>

                <div class="grid grid-cols-4 text-center px-2">
                    <div class="space-y-1">
                        <p class="text-[8px] font-black text-slate-300 uppercase">Now</p>
                        <p class="text-[10px] font-black text-slate-900">৳0</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[8px] font-black text-slate-300 uppercase">4m</p>
                        <p class="text-[10px] font-black text-slate-900">৳{{ number_format($overview['monthly_contribution'] * 4) }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[8px] font-black text-slate-300 uppercase">8m</p>
                        <p class="text-[10px] font-black text-slate-900">৳{{ number_format($overview['monthly_contribution'] * 8) }}</p>
                    </div>
                    <div class="space-y-1 border-l border-slate-100">
                        <p class="text-[8px] font-black text-indigo-400 uppercase">12m</p>
                        <p class="text-[10px] font-black text-indigo-600 font-black">৳{{ number_format($overview['one_year_forecast']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline of Success -->
            <div class="bg-slate-900 p-10 rounded-[4rem] shadow-2xl text-white relative overflow-hidden">
                <div class="absolute bottom-0 right-0 -mr-20 -mb-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <h3 class="text-sm font-black uppercase tracking-[0.3em] mb-10 text-center opacity-60">Timeline of Success</h3>
                
                <div class="space-y-10 relative z-10">
                    @forelse($goals as $goal)
                        <div class="flex items-start gap-6 group">
                            <div class="flex flex-col items-center">
                                <div class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center text-xl shadow-lg border border-white/10 group-hover:bg-white group-hover:text-slate-900 transition-all duration-500">
                                    {{ $goal->estimated_date ? '🎯' : '⌛' }}
                                </div>
                                @if(!$loop->last)
                                    <div class="w-0.5 h-12 bg-white/10 mt-4 rounded-full"></div>
                                @endif
                            </div>
                            <div class="flex-1 pt-1">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="text-sm font-black tracking-tight">{{ $goal->name }}</h4>
                                    <span class="text-[8px] font-black text-indigo-400 uppercase tracking-widest">
                                        {{ $goal->estimated_date ? $goal->estimated_date->diffForHumans(['parts' => 1]) : 'Unknown' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-1.5 bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 rounded-full" style="width: {{ ($goal->current_amount / $goal->target_amount) * 100 }}%"></div>
                                    </div>
                                    <p class="text-[10px] font-black text-white/40 tracking-wider uppercase">
                                        ETA: {{ $goal->estimated_date ? $goal->estimated_date->format('M Y') : 'Set Budget' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center opacity-40">
                            <p class="text-[10px] font-black uppercase tracking-widest">No Active Savings Goals Found</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-12 pt-8 border-t border-white/5 text-center">
                    <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] leading-relaxed">
                        Projections are based on your current 60-25-15 allocation of <br> ৳{{ number_format($overview['monthly_contribution']) }} per month.
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
