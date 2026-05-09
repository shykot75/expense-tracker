<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <!-- Header Section -->
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-6 pt-16 pb-20 rounded-b-[4rem] shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Recurring Bills</h1>
                    <p class="text-indigo-200 mt-2 text-sm font-medium">Manage your fixed monthly costs.</p>
                </div>
                <div class="h-12 w-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="px-6 -mt-10 pb-24 space-y-4">
            <!-- Add Button Card -->
            <a href="{{ route('recurring-bills.create') }}" class="block bg-white p-6 rounded-[2.5rem] shadow-lg border-2 border-dashed border-slate-200 flex items-center justify-center gap-3 active:scale-95 transition-all">
                <div class="h-10 w-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-2xl font-black">+</span>
                </div>
                <span class="text-sm font-black text-slate-600 uppercase tracking-widest">Add New Fixed Bill</span>
            </a>

            <!-- Bill List -->
            <div class="space-y-4">
                @forelse($bills as $bill)
                    <div class="bg-white p-6 rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center gap-4 relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-2 h-full bg-indigo-600"></div>
                        
                        <div class="h-14 w-14 rounded-[1.5rem] bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-black text-slate-800">{{ $bill->description }}</h3>
                                <span class="px-2 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-lg uppercase">{{ $bill->category->name }}</span>
                            </div>
                            <p class="text-xs font-bold text-slate-400 mt-0.5">Due on: {{ $bill->next_deduction_date->format('M d, Y') }}</p>
                        </div>

                        <div class="text-right">
                            <p class="text-xl font-black text-slate-900">৳{{ number_format($bill->amount, 0) }}</p>
                            <form action="{{ route('recurring-bills.destroy', $bill) }}" method="POST" onsubmit="return confirm('Remove this bill?')" class="mt-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] font-black text-red-400 uppercase hover:text-red-600 tracking-widest">Remove</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-12 rounded-[3rem] border-2 border-dashed border-slate-200 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-slate-400 font-bold">No recurring bills set yet.</h3>
                        <p class="text-slate-300 text-xs mt-2">Add things like Rent, WiFi, or Netflix to track them automatically.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
