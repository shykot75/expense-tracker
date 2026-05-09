<x-app-layout>
    <div class="min-h-screen bg-slate-50" x-data="{ 
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
                alert('Invalid expression');
            }
        },
        applyCalc() {
            if (this.calcDisplay) this.calculate();
            this.showCalc = false;
        }
    }">
        <!-- Header -->
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 px-6 pt-16 pb-20 rounded-b-[4rem] shadow-2xl">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-extrabold text-white">Add Fixed Bill</h1>
                    <p class="text-indigo-200 mt-2 text-sm font-medium">Set a monthly commitment.</p>
                </div>
                <a href="{{ route('recurring-bills.index') }}" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            </div>
        </div>

        <div class="px-6 -mt-12 pb-24">
            <form action="{{ route('recurring-bills.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Amount Card -->
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 text-center relative overflow-hidden active:scale-95 transition-all" @click="showCalc = true; if(!calcDisplay) calcDisplay = amount">
                    <input type="hidden" name="amount" x-model="amount">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Monthly Amount</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-3xl font-black text-slate-300">৳</span>
                        <div class="text-5xl font-black text-slate-900" x-text="amount || '0.00'"></div>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Bill Name (e.g. Rent, WiFi)</label>
                        <input type="text" name="description" value="{{ old('description') }}" required placeholder="Netflix, Gym, etc." 
                            class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Category</label>
                        <select name="category_id" required class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Next Payment Date</label>
                        <input type="date" name="next_deduction_date" value="{{ old('next_deduction_date', date('Y-m-01', strtotime('+1 month'))) }}" required 
                            class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] font-black text-xl shadow-2xl active:scale-95 flex items-center justify-center gap-3">
                        <span>Save Fixed Bill</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Calculator Overlay -->
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
