<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-32" x-data="{ 
        type: 'lent',
        person: '{{ old('person_name') }}',
        amount: '{{ old('amount') }}',
        loanDate: '{{ old('loan_date', date('Y-m-d')) }}',
        validate(e) {
            if (!this.person) {
                $dispatch('notify', { msg: 'Who is this loan with?', type: 'error' });
                e.preventDefault(); return false;
            }
            if (!this.amount || parseFloat(this.amount) <= 0) {
                $dispatch('notify', { msg: 'Enter a valid loan amount', type: 'error' });
                e.preventDefault(); return false;
            }
            return true;
        }
    }">
        <!-- Header -->
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 px-6 pt-16 pb-32 rounded-b-[4rem] shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 relative z-10">
                <a href="{{ route('loans.index') }}" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-3xl font-black text-white tracking-tight">New Loan</h1>
            </div>
        </div>

        <div class="px-6 -mt-16 relative z-20">
            <div class="bg-white rounded-[3.5rem] p-8 shadow-2xl border border-slate-100">
                <form action="{{ route('loans.store') }}" method="POST" class="space-y-8" @submit="validate($event)">
                    @csrf
                    
                    <!-- Loan Type Toggle -->
                    <div class="bg-slate-50 p-2 rounded-[2.5rem] flex gap-1">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="loan_type" value="lent" x-model="type" class="hidden">
                            <div :class="type === 'lent' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400'" class="py-4 text-[10px] font-black uppercase tracking-[0.2em] text-center rounded-[2rem] transition-all">I Lent Money</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="loan_type" value="borrowed" x-model="type" class="hidden">
                            <div :class="type === 'borrowed' ? 'bg-rose-500 text-white shadow-lg' : 'text-slate-400'" class="py-4 text-[10px] font-black uppercase tracking-[0.2em] text-center rounded-[2rem] transition-all">I Borrowed</div>
                        </label>
                    </div>

                    <div class="space-y-6">
                        <!-- Person Name -->
                        <div class="bg-slate-50 p-6 rounded-[2.5rem] focus-within:ring-8 focus-within:ring-indigo-50 transition-all border @error('person_name') border-red-500 @else border-transparent @enderror focus-within:border-indigo-100">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2">Who is it with?</label>
                            <input type="text" name="person_name" x-model="person" placeholder="Person name..." 
                                class="w-full bg-transparent border-none text-xl font-black text-slate-800 placeholder:text-slate-300 focus:ring-0">
                            @error('person_name')
                                <p class="mt-2 ml-4 text-[9px] font-black text-red-500 uppercase tracking-widest">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="bg-slate-50 p-6 rounded-[2.5rem] focus-within:ring-8 focus-within:ring-indigo-50 transition-all border @error('amount') border-red-500 @else border-transparent @enderror focus-within:border-indigo-100">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2">Amount</label>
                            <div class="flex items-center gap-4">
                                <span class="text-2xl font-black text-slate-300">৳</span>
                                <input type="number" name="amount" x-model="amount" step="0.01" placeholder="0.00" 
                                    class="w-full bg-transparent border-none text-3xl font-black text-slate-900 placeholder:text-slate-200 focus:ring-0">
                            </div>
                            @error('amount')
                                <p class="mt-2 ml-4 text-[9px] font-black text-red-500 uppercase tracking-widest">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Loan Date -->
                            <div class="bg-slate-50 p-6 rounded-[2.5rem] border @error('loan_date') border-red-500 @else border-transparent @enderror">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2">Date</label>
                                <input type="date" name="loan_date" x-model="loanDate" onclick="this.showPicker()"
                                    class="w-full bg-transparent border-none text-sm font-black text-slate-800 focus:ring-0">
                                @error('loan_date')
                                    <p class="mt-2 text-[8px] font-black text-red-500 uppercase tracking-widest">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Due Date -->
                            <div class="bg-slate-50 p-6 rounded-[2.5rem]">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2">Deadline</label>
                                <input type="date" name="deadline_date" onclick="this.showPicker()"
                                    class="w-full bg-transparent border-none text-sm font-black text-slate-800 focus:ring-0">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-slate-50 p-6 rounded-[2.5rem]">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2">Note (Optional)</label>
                            <textarea name="description" rows="2" placeholder="What's this for?..." 
                                class="w-full bg-transparent border-none text-sm font-bold text-slate-600 placeholder:text-slate-300 focus:ring-0 resize-none"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] font-black text-xl shadow-2xl shadow-indigo-200 active:scale-95 transition-all">
                        Create Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
