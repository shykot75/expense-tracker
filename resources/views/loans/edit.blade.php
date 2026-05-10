<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-32" x-data="{ 
        type: '{{ $loan->loan_type }}', 
        status: '{{ $loan->status }}',
        person: '{{ old('person_name', $loan->person_name) }}',
        amount: '{{ old('amount', $loan->amount) }}',
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
                <h1 class="text-3xl font-black text-white tracking-tight">Edit Loan</h1>
            </div>
        </div>

        <div class="px-6 -mt-16 relative z-20">
            <div class="bg-white rounded-[3.5rem] p-8 shadow-2xl border border-slate-100">
                <form action="{{ route('loans.update', $loan) }}" method="POST" class="space-y-8" @submit="validate($event)">
                    @csrf
                    @method('PUT')
                    
                    <!-- Loan Status Toggle -->
                    <div class="bg-slate-50 p-2 rounded-[2.5rem] flex gap-1">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="active" x-model="status" class="hidden">
                            <div :class="status === 'active' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400'" class="py-4 text-[10px] font-black uppercase tracking-[0.2em] text-center rounded-[2rem] transition-all">Active Debt</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="paid" x-model="status" class="hidden">
                            <div :class="status === 'paid' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400'" class="py-4 text-[10px] font-black uppercase tracking-[0.2em] text-center rounded-[2rem] transition-all">Fully Paid</div>
                        </label>
                    </div>

                    <!-- Loan Type Toggle (Read-only aesthetic or swappable) -->
                    <div class="bg-slate-50 p-2 rounded-[2.5rem] flex gap-1 border-2 border-slate-100/50">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="loan_type" value="lent" x-model="type" class="hidden">
                            <div :class="type === 'lent' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-300'" class="py-3 text-[9px] font-black uppercase tracking-[0.2em] text-center rounded-[1.5rem] transition-all">Lent</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="loan_type" value="borrowed" x-model="type" class="hidden">
                            <div :class="type === 'borrowed' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-300'" class="py-3 text-[9px] font-black uppercase tracking-[0.2em] text-center rounded-[1.5rem] transition-all">Borrowed</div>
                        </label>
                    </div>

                    <div class="space-y-6">
                        <!-- Person Name -->
                        <div class="bg-slate-50 p-6 rounded-[2.5rem] focus-within:ring-8 focus-within:ring-indigo-50 transition-all border border-transparent focus-within:border-indigo-100">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2">Person</label>
                            <input type="text" name="person_name" x-model="person" 
                                class="w-full bg-transparent border-none text-xl font-black text-slate-800 focus:ring-0">
                        </div>

                        <!-- Amount -->
                        <div class="bg-slate-50 p-6 rounded-[2.5rem] focus-within:ring-8 focus-within:ring-indigo-50 transition-all border border-transparent focus-within:border-indigo-100">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2">Amount</label>
                            <div class="flex items-center gap-4">
                                <span class="text-2xl font-black text-slate-300">৳</span>
                                <input type="number" name="amount" step="0.01" x-model="amount" 
                                    class="w-full bg-transparent border-none text-3xl font-black text-slate-900 focus:ring-0">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Loan Date -->
                            <div class="bg-slate-50 p-6 rounded-[2.5rem]">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2">Date</label>
                                <input type="date" name="loan_date" value="{{ $loan->loan_date->format('Y-m-d') }}" 
                                    class="w-full bg-transparent border-none text-sm font-black text-slate-800 focus:ring-0">
                            </div>
                            <!-- Due Date -->
                            <div class="bg-slate-50 p-6 rounded-[2.5rem]">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2">Deadline</label>
                                <input type="date" name="deadline_date" value="{{ $loan->deadline_date ? $loan->deadline_date->format('Y-m-d') : '' }}" 
                                    class="w-full bg-transparent border-none text-sm font-black text-slate-800 focus:ring-0">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 space-y-4">
                        <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] font-black text-xl shadow-2xl shadow-indigo-200 active:scale-95 transition-all">
                            Update Record
                        </button>
                    </form>

                    <form action="{{ route('loans.destroy', $loan) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="$dispatch('confirm', { 
                            title: 'Delete Loan?', 
                            message: 'Permanently remove this loan record for {{ $loan->borrower_name }}?', 
                            confirmText: 'Delete Permanently',
                            form: $el.closest('form') 
                        })" class="w-full text-red-400 font-black text-[10px] uppercase tracking-[0.2em] py-2">
                            Delete Record Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
