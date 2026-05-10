<x-app-layout>
    <div class="min-h-screen bg-slate-50" x-data="{ 
        tab: 'needs',
        categories: {{ $categories->count() > 0 ? $categories->toJson() : '{needs:[], wants:[], savings:[]}' }},
        newCatName: '',
        isAdding: false,
        selectedCatId: null,
        
        // Calculator State
        showCalc: false,
        calcDisplay: '',
        amount: '{{ old('amount', '') }}',
        
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
                $dispatch('notify', { msg: 'Invalid calculation expression', type: 'error' });
            }
        },
        applyCalc() {
            if (this.calcDisplay) this.calculate();
            this.showCalc = false;
        },

        async addCategory() {
            if (!this.newCatName) return;
            this.isAdding = true;
            try {
                const response = await fetch('{{ url('categories/quick-add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: this.newCatName, budget_type: this.tab })
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Error');
                if (!this.categories[this.tab]) this.categories[this.tab] = [];
                this.categories[this.tab].push(data);
                this.newCatName = '';
                $dispatch('notify', { msg: 'Category added to taxonomy' });
            } catch (e) { 
                $dispatch('notify', { msg: 'Error: ' + e.message, type: 'error' });
            }
            this.isAdding = false;
        },

        validate(e) {
            if (!this.amount || parseFloat(this.amount) <= 0) {
                $dispatch('notify', { msg: 'Please enter a valid amount greater than 0', type: 'error' });
                e.preventDefault();
                return false;
            }
            if (!this.selectedCatId) {
                $dispatch('notify', { msg: 'Please select a category to continue', type: 'error' });
                e.preventDefault();
                return false;
            }
            return true;
        }
    }">
        <!-- Header -->
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-6 pt-16 pb-20 rounded-b-[4rem] shadow-2xl">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-extrabold text-white">Add Expense</h1>
                <a href="{{ route('dashboard') }}" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            </div>
        </div>

        <div class="px-6 -mt-12 pb-24">
            <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6" @submit="validate($event)">
                @csrf
                
                <!-- Amount Card -->
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 text-center relative overflow-hidden active:scale-95 transition-all @error('amount') border-red-500 ring-4 ring-red-50 @enderror" @click="showCalc = true; if(!calcDisplay) calcDisplay = amount">
                    <input type="hidden" name="amount" x-model="amount">
                    <div class="absolute top-0 right-0 p-4">
                        <div class="h-8 w-8 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tap to calculate</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-3xl font-black text-slate-300">৳</span>
                        <div class="text-5xl font-black text-slate-900" x-text="amount || '0.00'"></div>
                    </div>
                    @error('amount')
                        <p class="mt-4 text-[10px] font-black text-red-500 uppercase tracking-widest">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Budget Tabs -->
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
                                <input type="radio" name="category_id" :value="cat.id" x-model="selectedCatId" class="peer sr-only">
                                <div class="p-5 rounded-3xl border-2 border-slate-100 bg-white peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white transition-all text-center shadow-sm">
                                    <p class="text-sm font-black" x-text="cat.name"></p>
                                </div>
                            </label>
                        </template>
                        
                        <!-- Quick Add -->
                        <div class="col-span-2 mt-4">
                            <div class="bg-white border-2 border-slate-100 rounded-[3rem] p-3 flex items-center gap-4 focus-within:border-indigo-500 focus-within:ring-8 focus-within:ring-indigo-50 transition-all shadow-sm">
                                <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-[1.5rem] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </div>
                                <input type="text" x-model="newCatName" @keyup.enter="addCategory()" placeholder="New category name..." 
                                    class="flex-1 bg-transparent border-none outline-none ring-0 py-3 text-base font-black text-slate-800 placeholder:text-slate-300 focus:ring-0 focus:outline-none">
                                <button type="button" @click="addCategory()" class="bg-indigo-600 text-white px-8 py-3.5 rounded-[1.5rem] font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-200 active:scale-90 transition-all" :disabled="isAdding">
                                    <span x-show="!isAdding">Add</span>
                                    <span x-show="isAdding" class="flex items-center"><svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date & Description -->
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Transaction Date</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50 @error('expense_date') border-red-500 @enderror">
                        @error('expense_date')
                            <p class="mt-2 ml-4 text-[9px] font-black text-red-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}" placeholder="Optional details..." class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] font-black text-xl shadow-2xl shadow-slate-300 transition-all active:scale-95 flex items-center justify-center gap-3">
                        <span>Save Transaction</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Calculator Overlay -->
        <div x-show="showCalc" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-end">
            <div x-show="showCalc" x-transition.move.bottom.duration.500ms class="bg-white w-full rounded-t-[4rem] p-8 shadow-2xl max-h-[90vh] overflow-y-auto" @click.away="showCalc = false">
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-8"></div>
                
                <!-- Display -->
                <div class="bg-slate-50 p-6 rounded-[2.5rem] mb-6 text-right min-h-[80px] flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Expression</p>
                    <div class="text-3xl font-black text-slate-900 break-all" x-text="calcDisplay || '0'"></div>
                </div>

                <!-- Keys Grid -->
                <div class="grid grid-cols-4 gap-3">
                    <button type="button" @click="clearCalc()" class="h-16 bg-red-50 text-red-600 rounded-2xl font-black text-lg">AC</button>
                    <button type="button" @click="backspaceCalc()" class="h-16 bg-orange-50 text-orange-600 rounded-2xl font-black text-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"></path></svg>
                    </button>
                    <button type="button" @click="appendCalc('/')" class="h-16 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-xl">÷</button>
                    <button type="button" @click="appendCalc('*')" class="h-16 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-xl">×</button>

                    <button type="button" @click="appendCalc('7')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">7</button>
                    <button type="button" @click="appendCalc('8')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">8</button>
                    <button type="button" @click="appendCalc('9')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">9</button>
                    <button type="button" @click="appendCalc('-')" class="h-16 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-xl">-</button>

                    <button type="button" @click="appendCalc('4')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">4</button>
                    <button type="button" @click="appendCalc('5')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">5</button>
                    <button type="button" @click="appendCalc('6')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">6</button>
                    <button type="button" @click="appendCalc('+')" class="h-16 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-xl">+</button>

                    <div class="col-span-3 grid grid-cols-3 gap-3">
                        <button type="button" @click="appendCalc('1')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">1</button>
                        <button type="button" @click="appendCalc('2')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">2</button>
                        <button type="button" @click="appendCalc('3')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">3</button>
                        <button type="button" @click="appendCalc('0')" class="col-span-2 h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">0</button>
                        <button type="button" @click="appendCalc('.')" class="h-16 bg-slate-100 text-slate-800 rounded-2xl font-black text-xl">.</button>
                    </div>

                    <div class="col-span-1 grid grid-rows-2 gap-3">
                        <button type="button" @click="calculate()" class="h-16 bg-purple-600 text-white rounded-2xl font-black text-2xl">=</button>
                        <button type="button" @click="applyCalc()" class="h-16 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">DONE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
