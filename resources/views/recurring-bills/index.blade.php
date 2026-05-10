<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-32">
        <!-- Header -->
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 px-6 pt-16 pb-24 rounded-b-[4rem] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="flex justify-between items-center relative z-10 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Recurring Hub</h1>
                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest mt-1">Smart Bill Automation</p>
                </div>
                <a href="{{ route('recurring-bills.create') }}" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 text-white active:scale-90 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                </a>
            </div>

            <!-- Global Status -->
            <div class="bg-white/10 backdrop-blur-md rounded-[2.5rem] p-6 border border-white/10 shadow-xl relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Active Automation</p>
                    <p class="text-2xl font-black text-white">{{ $bills->where('status', 'active')->count() }} Subscriptions</p>
                </div>
                <div class="h-12 w-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
        </div>

        <div class="px-6 -mt-10 space-y-6">
            <!-- Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.opacity.duration.500ms 
                    class="bg-emerald-500 text-white p-6 rounded-[2.5rem] shadow-lg flex items-center gap-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <p class="font-black text-sm uppercase tracking-widest">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Bill List -->
            <div class="space-y-4">
                @forelse($bills as $bill)
                    <div class="bg-white rounded-[3rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center justify-between relative overflow-hidden group {{ $bill->status === 'paused' ? 'opacity-60 grayscale' : '' }}">
                        <div class="flex items-center gap-5">
                            <div class="h-14 w-14 rounded-[1.5rem] flex items-center justify-center {{ $bill->status === 'active' ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-400' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-800">{{ $bill->description }}</h3>
                                    <span class="px-2 py-0.5 bg-slate-100 rounded-full text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $bill->frequency }}</span>
                                </div>
                                <p class="text-xl font-black text-slate-900 mt-0.5">৳{{ number_format($bill->amount) }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Next: {{ $bill->next_deduction_date->format('d M, Y') }}</span>
                                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[8px] font-black rounded-md uppercase">{{ $bill->category->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('recurring-bills.edit', $bill) }}" class="h-10 w-10 bg-slate-50 text-slate-300 rounded-xl flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('recurring-bills.toggle', $bill) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="h-10 w-10 rounded-xl flex items-center justify-center transition-all {{ $bill->status === 'active' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-100' : 'bg-slate-200 text-slate-500' }}">
                                        @if($bill->status === 'active')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                            <form action="{{ route('recurring-bills.destroy', $bill) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" @click="$dispatch('confirm', { 
                                    title: 'Stop Automation?', 
                                    message: 'This will permanently stop the recurring payment for {{ $bill->description }}.', 
                                    confirmText: 'Stop & Delete',
                                    form: $el.closest('form') 
                                })" class="text-[8px] font-black text-slate-300 uppercase tracking-widest hover:text-red-500 transition-colors">Stop & Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <div class="h-24 w-24 bg-slate-100 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-400">No automation set</h3>
                        <p class="text-xs text-slate-300 font-bold mt-1">Add your Netflix, Rent, or Internet bills</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
