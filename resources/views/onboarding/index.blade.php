<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <!-- Header Section -->
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-6 pt-16 pb-20 rounded-b-[4rem] shadow-2xl">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Setup Plan</h1>
                    <p class="text-indigo-200 mt-2 text-sm font-medium">Personalize your 60-25-15 budget strategy.</p>
                </div>
                <div class="h-14 w-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Progress Stepper -->
            <div class="mt-10 flex items-center gap-4">
                <div class="flex flex-col gap-2 flex-1">
                    <div class="h-1.5 w-full bg-white rounded-full"></div>
                    <span class="text-[10px] font-bold text-white uppercase tracking-widest">Income</span>
                </div>
                <div class="flex flex-col gap-2 flex-1">
                    <div class="h-1.5 w-full bg-white rounded-full"></div>
                    <span class="text-[10px] font-bold text-white uppercase tracking-widest">Ratio</span>
                </div>
                <div class="flex flex-col gap-2 flex-1">
                    <div class="h-1.5 w-full bg-white/20 rounded-full"></div>
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Finish</span>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="px-6 -mt-12 pb-12">
            <form action="{{ route('onboarding.store') }}" method="POST" id="onboarding-form">
                @csrf
                
                <div class="space-y-6">
                    <!-- Card 1: Income Details -->
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Income Details</h3>
                                <p class="text-xs text-slate-400 font-medium">Define your monthly earnings</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="relative group">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-indigo-600 transition-colors">Monthly Income (BDT)</label>
                                <div class="relative">
                                    <div class="absolute left-5 top-1/2 -translate-y-1/2 flex items-center pointer-events-none">
                                        <span class="text-2xl font-black text-slate-300">৳</span>
                                    </div>
                                    <input type="number" name="monthly_income" id="monthly_income" value="32000" 
                                        class="block w-full rounded-3xl border-slate-100 py-5 pl-12 text-2xl font-black text-slate-900 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 transition-all bg-slate-50/50">
                                </div>
                            </div>

                            <div class="relative">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Cycle Start Date</label>
                                <select name="cycle_start_date" class="block w-full rounded-3xl border-slate-100 py-5 text-lg font-bold text-slate-800 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 transition-all bg-slate-50/50 px-6 appearance-none">
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}" {{ $i == 7 ? 'selected' : '' }}>
                                            Day {{ $i }} of the month
                                        </option>
                                    @endfor
                                </select>
                                <div class="absolute right-6 top-[55px] pointer-events-none text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: 60-25-15 Distribution -->
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="h-12 w-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Budget Strategy</h3>
                                <p class="text-xs text-slate-400 font-medium">Distribute your wealth</p>
                            </div>
                        </div>

                        <div class="space-y-10" id="ratio-container">
                            <!-- Needs Slider -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center px-1">
                                    <div class="flex items-center gap-2">
                                        <div class="h-3 w-3 rounded-full bg-indigo-500"></div>
                                        <span class="text-sm font-black text-slate-700 uppercase tracking-tight">Fixed Needs</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-black text-indigo-600" id="needs-val">60</span><span class="text-indigo-300 font-bold">%</span>
                                    </div>
                                </div>
                                <div class="relative flex items-center">
                                    <input type="range" name="needs_ratio" id="needs_ratio" min="0" max="100" value="60" 
                                        class="w-full h-3 bg-slate-100 rounded-full appearance-none cursor-pointer accent-indigo-600 ratio-slider">
                                </div>
                                <div class="flex justify-between items-center px-1">
                                    <span class="text-[10px] font-bold text-slate-400">Allocated Amount</span>
                                    <span class="text-sm font-black text-slate-800">৳<span id="needs-amount">19,200</span></span>
                                </div>
                            </div>

                            <!-- Wants Slider -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center px-1">
                                    <div class="flex items-center gap-2">
                                        <div class="h-3 w-3 rounded-full bg-purple-500"></div>
                                        <span class="text-sm font-black text-slate-700 uppercase tracking-tight">Personal Wants</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-black text-purple-600" id="wants-val">25</span><span class="text-purple-300 font-bold">%</span>
                                    </div>
                                </div>
                                <div class="relative flex items-center">
                                    <input type="range" name="wants_ratio" id="wants_ratio" min="0" max="100" value="25" 
                                        class="w-full h-3 bg-slate-100 rounded-full appearance-none cursor-pointer accent-purple-600 ratio-slider">
                                </div>
                                <div class="flex justify-between items-center px-1">
                                    <span class="text-[10px] font-bold text-slate-400">Allocated Amount</span>
                                    <span class="text-sm font-black text-slate-800">৳<span id="wants-amount">8,000</span></span>
                                </div>
                            </div>

                            <!-- Savings Slider -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center px-1">
                                    <div class="flex items-center gap-2">
                                        <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                                        <span class="text-sm font-black text-slate-700 uppercase tracking-tight">Future Savings</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-black text-emerald-600" id="savings-val">15</span><span class="text-emerald-300 font-bold">%</span>
                                    </div>
                                </div>
                                <div class="relative flex items-center">
                                    <input type="range" name="savings_ratio" id="savings_ratio" min="0" max="100" value="15" 
                                        class="w-full h-3 bg-slate-100 rounded-full appearance-none cursor-pointer accent-emerald-600 ratio-slider">
                                </div>
                                <div class="flex justify-between items-center px-1">
                                    <span class="text-[10px] font-bold text-slate-400">Allocated Amount</span>
                                    <span class="text-sm font-black text-slate-800">৳<span id="savings-amount">4,800</span></span>
                                </div>
                            </div>

                            <!-- Total Indicator -->
                            <div class="pt-8 border-t-2 border-dashed border-slate-100 flex justify-between items-center">
                                <div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Usage Capacity</span>
                                    <p class="text-xs font-medium text-slate-500 mt-1" id="ratio-status">Perfectly balanced</p>
                                </div>
                                <div class="h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center border-2 border-white shadow-inner">
                                    <span class="text-lg font-black text-slate-900" id="total-ratio-display">100%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-4">
                        <button type="submit" id="submit-btn" class="w-full bg-indigo-600 text-white py-6 rounded-[2.5rem] font-black text-xl shadow-2xl shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-3">
                            <span>Finalize My Plan</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                        <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-6">You can change these settings later in profile</p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 28px;
            width: 28px;
            border-radius: 50%;
            background: white;
            cursor: pointer;
            border: 6px solid currentColor;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-top: -2px;
        }
        
        .ratio-slider {
            color: inherit;
        }

        #needs_ratio { color: #6366f1; }
        #wants_ratio { color: #a855f7; }
        #savings_ratio { color: #10b981; }
    </style>

    <script>
        document.addEventListener('turbo:load', () => {
            const incomeInput = document.getElementById('monthly_income');
            const sliders = document.querySelectorAll('.ratio-slider');
            const totalDisplay = document.getElementById('total-ratio-display');
            const statusDisplay = document.getElementById('ratio-status');
            const submitBtn = document.getElementById('submit-btn');

            function updateValues() {
                const income = parseFloat(incomeInput.value) || 0;
                let total = 0;

                sliders.forEach(slider => {
                    const type = slider.id.split('_')[0];
                    const val = parseInt(slider.value);
                    total += val;
                    
                    document.getElementById(`${type}-val`).textContent = val;
                    const amount = (income * val) / 100;
                    document.getElementById(`${type}-amount`).textContent = amount.toLocaleString();
                });

                totalDisplay.textContent = total + '%';
                
                if (total !== 100) {
                    const diff = 100 - total;
                    statusDisplay.textContent = diff > 0 ? `Needs ${diff}% more` : `Exceeds limit by ${Math.abs(diff)}%`;
                    statusDisplay.classList.add('text-red-500');
                    statusDisplay.classList.remove('text-emerald-500');
                    totalDisplay.parentElement.classList.add('bg-red-50');
                    totalDisplay.parentElement.classList.remove('bg-emerald-50');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'bg-slate-400');
                    submitBtn.classList.remove('bg-indigo-600');
                } else {
                    statusDisplay.textContent = 'Perfectly balanced';
                    statusDisplay.classList.add('text-emerald-500');
                    statusDisplay.classList.remove('text-red-500');
                    totalDisplay.parentElement.classList.add('bg-emerald-50');
                    totalDisplay.parentElement.classList.remove('bg-red-50');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'bg-slate-400');
                    submitBtn.classList.add('bg-indigo-600');
                }
            }

            if (incomeInput) incomeInput.addEventListener('input', updateValues);
            sliders.forEach(slider => {
                if (slider) slider.addEventListener('input', updateValues);
            });

            // Prevent empty submit
            const form = document.getElementById('onboarding-form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    if (!incomeInput.value || parseFloat(incomeInput.value) <= 0) {
                        e.preventDefault();
                        window.dispatchEvent(new CustomEvent('notify', { 
                            detail: { msg: 'Monthly income is required to build your plan', type: 'error' } 
                        }));
                    }
                });
            }
            
            if (incomeInput && sliders.length > 0) updateValues();
        });
    </script>
</x-app-layout>
