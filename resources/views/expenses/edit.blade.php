<x-app-layout>
    <div class="min-h-screen bg-slate-50" x-data="{ 
        tab: '{{ old('budget_type', $expense->category->budget_type) }}',
        categories: {{ $categories->toJson() }},
        selectedCatId: {{ old('category_id', $expense->category_id) }},
        
        // Calculator State
        showCalc: false,
        calcDisplay: '',
        amount: '{{ old('amount', $expense->amount) }}',
        
        appendCalc(val) {
            this.calcDisplay += val;
        },
        clearCalc() {
            this.calcDisplay = '';
        },
        backspaceCalc() {
            this.calcDisplay = this.calcDisplay.slice(0, -1);
        },
        calculate() {
            try {
                const expr = this.calcDisplay.replace(/[^-()\d/*+.]/g, '');
                if (!expr) return;
                const result = eval(expr);
                this.amount = parseFloat(result).toFixed(2);
                this.calcDisplay = this.amount;
            } catch (e) {
                alert('Invalid expression');
            }
        },
        applyCalc() {
            if (this.calcDisplay) this.calculate();
            this.showCalc = false;
        },

        validate(e) {
            if (!this.amount || parseFloat(this.amount) <= 0) {
                alert('Please enter a valid amount greater than 0');
                e.preventDefault();
                return false;
            }
            if (!this.selectedCatId) {
                alert('Please select a category');
                e.preventDefault();
                return false;
            }
            return true;
        }
    }">
        <!-- Header -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 px-6 pt-16 pb-20 rounded-b-[4rem] shadow-2xl">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-extrabold text-white">Edit Entry</h1>
                <a href="{{ route('dashboard') }}" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            </div>
        </div>

        <div class="px-6 -mt-12 pb-24">
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl mb-6 shadow-sm">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.update', $expense) }}" method="POST" class="space-y-6" id="update-form" @submit="validate($event)">
                @csrf
                @method('PUT')
                
                <!-- Amount Card -->
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 text-center relative overflow-hidden active:scale-95 transition-all" @click="showCalc = true; if(!calcDisplay) calcDisplay = amount">
                    <input type="hidden" name="amount" x-model="amount">
                    <div class="absolute top-0 right-0 p-4">
                        <div class="h-8 w-8 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tap to recalculate</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-3xl font-black text-slate-300">৳</span>
                        <div class="text-5xl font-black text-slate-900" x-text="amount || '0.00'"></div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="bg-white p-2 rounded-[2.5rem] shadow-lg border border-slate-100 flex gap-1">
                    <template x-for="t in ['needs', 'wants', 'savings']">
                        <button type="button" @click="tab = t" :class="tab === t ? 'bg-slate-900 text-white shadow-xl' : 'text-slate-400'" 
                            class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest rounded-[2rem] transition-all" x-text="t"></button>
                    </template>
                </div>

                <!-- Category Grid -->
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="cat in (categories[tab] || [])" :key="cat.id">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="category_id" :value="cat.id" x-model="selectedCatId" class="peer sr-only" required>
                                <div class="p-5 rounded-3xl border-2 border-slate-100 bg-white peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white transition-all text-center shadow-sm">
                                    <p class="text-sm font-black" x-text="cat.name"></p>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Date & Description -->
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Transaction Date</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Description</label>
                        <input type="text" name="description" value="{{ old('description', $expense->description) }}" placeholder="Optional details..." class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50">
                    </div>
                </div>

                <!-- Update Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] font-black text-xl shadow-2xl shadow-slate-300 transition-all active:scale-95 flex items-center justify-center gap-3">
                        <span>Update Transaction</span>
                    </button>
                </div>
            </form>

            <!-- Delete Form (Moved Outside for Safety) -->
            <div class="mt-4">
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-red-500 py-4 font-bold text-sm tracking-widest uppercase">Delete Entry</button>
                </form>
            </div>
        </div>

        <!-- Calculator Overlay (Same as Create) -->
        <div x-show="showCalc" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-end">
            <div x-show="showCalc" x-transition.move.bottom.duration.500ms class="bg-white w-full rounded-t-[4rem] p-8 shadow-2xl max-h-[90vh] overflow-y-auto" @click.away="showCalc = false">
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-8"></div>
                <div class="bg-slate-50 p-6 rounded-[2.5rem] mb-6 text-right min-h-[80px] flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Expression</p>
                    <div class="text-3xl font-black text-slate-900 break-all" x-text="calcDisplay || '0'"></div>
                </div>
                <div class="grid grid-cols-4 gap-3">
                    <button type="button" @click="clearCalc()" class="h-16 bg-red-50 text-red-600 rounded-2xl font-black text-lg">AC</button>
                    <button type="button" @click="backspaceCalc()" class="h-16 bg-orange-50 text-orange-600 rounded-2xl font-black text-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"></path></svg>
                    </button>
                    <button type="button" @click="appendCalc('/')" class="h-16 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-xl">÷</button>
                    <button type="button" @click="appendCalc('*')" class="h-16 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-xl">×</button>
                    <template x-for="n in ['7','8','9','-','4','5','6','+','1','2','3']">
                        <button type="button" @click="appendCalc(n)" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl" x-text="n"></button>
                    </template>
                    <button type="button" @click="calculate()" class="h-16 bg-purple-600 text-white rounded-2xl font-black text-2xl">=</button>
                    <button type="button" @click="appendCalc('0')" class="col-span-2 h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">0</button>
                    <button type="button" @click="appendCalc('.')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">.</button>
                    <button type="button" @click="applyCalc()" class="h-16 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">DONE</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
