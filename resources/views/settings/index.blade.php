<x-app-layout>
    <div class="min-h-screen bg-slate-50" x-data="{ 
        tab: new URLSearchParams(window.location.search).get('tab') || 'profile',
        catTab: 'needs',
        showBudgetModal: false,
        showResetModal: false,
        editingCategory: null,
        uploading: false,
        showGoalModal: false,
        isAddingCat: false,
        
        // Category Add State
        newCatName: '',
        contributingGoal: null,
        goalAmount: '',
        newGoalName: '',
        newGoalTarget: '',

        validateGoal(e) {
            if (!this.newGoalName) {
                $dispatch('notify', { msg: 'What are you saving for?', type: 'error' });
                e.preventDefault(); return false;
            }
            if (!this.newGoalTarget || parseFloat(this.newGoalTarget) <= 0) {
                $dispatch('notify', { msg: 'Set a valid target amount', type: 'error' });
                e.preventDefault(); return false;
            }
            return true;
        },

        validateContribution(e) {
            if (!this.goalAmount || parseFloat(this.goalAmount) <= 0) {
                $dispatch('notify', { msg: 'Enter a valid amount to contribute', type: 'error' });
                e.preventDefault(); return false;
            }
            return true;
        },

        // Budget Data
        income: '{{ $plan->monthly_income ?? 0 }}',
        cycle: '{{ $plan->cycle_start_date ?? 1 }}',
        needs: '{{ $plan->needs_percentage ?? 60 }}',
        wants: '{{ $plan->wants_percentage ?? 25 }}',
        savings: '{{ $plan->savings_percentage ?? 15 }}',

        totalPerc() {
            return parseFloat(this.needs) + parseFloat(this.wants) + parseFloat(this.savings);
        },

        async addCategory() {
            if (!this.newCatName) return;
            this.isAddingCat = true;
            try {
                const response = await fetch('{{ url('categories/quick-add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: this.newCatName, budget_type: this.catTab })
                });
                if (!response.ok) throw new Error('Error adding category');
                window.location.reload(); 
            } catch (e) { 
                $dispatch('notify', { msg: e.message, type: 'error' });
            }
            this.isAddingCat = false;
        }
    }">
        <!-- Header & Profile Quick View -->
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-6 pt-16 pb-20 rounded-b-[4rem] shadow-2xl">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-black text-white tracking-tight">Settings Hub</h1>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="h-10 px-4 bg-white/10 rounded-xl flex items-center gap-2 text-white border border-white/20 text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>

            <!-- Avatar Card -->
            <div class="bg-white/10 backdrop-blur-md rounded-[3rem] p-6 border border-white/10 flex items-center gap-5">
                <div class="relative group">
                    <div class="h-20 w-20 rounded-[1.5rem] overflow-hidden border-2 border-white/20 shadow-xl relative">
                        <div x-show="uploading" class="absolute inset-0 bg-slate-900/60 flex items-center justify-center"><svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="h-full w-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatar-form" class="absolute -bottom-2 -right-2" @submit="uploading = true">
                        @csrf
                        <label class="h-8 w-8 bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-lg cursor-pointer border-2 border-slate-900 active:scale-90 transition-all">
                            <input type="file" name="avatar" class="hidden" onchange="document.getElementById('avatar-form').requestSubmit()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                        </label>
                    </form>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white leading-tight">{{ $user->name }}</h2>
                    <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="px-6 -mt-10 pb-32 space-y-6">
            <!-- Segmented Control -->
            <div class="bg-white p-2 rounded-[2.5rem] shadow-xl border border-slate-100 flex gap-1">
                <button @click="tab = 'profile'; history.replaceState(null, '', '?tab=profile')" :class="tab === 'profile' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-4 text-[9px] font-black uppercase tracking-widest rounded-[2rem] transition-all">My Profile</button>
                <button @click="tab = 'budget'; history.replaceState(null, '', '?tab=budget')" :class="tab === 'budget' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-4 text-[9px] font-black uppercase tracking-widest rounded-[2rem] transition-all">Budget Hub</button>
                <button @click="tab = 'goals'; history.replaceState(null, '', '?tab=goals')" :class="tab === 'goals' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-4 text-[9px] font-black uppercase tracking-widest rounded-[2rem] transition-all">Goals</button>
                <button @click="tab = 'categories'; history.replaceState(null, '', '?tab=categories')" :class="tab === 'categories' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-4 text-[9px] font-black uppercase tracking-widest rounded-[2rem] transition-all">Taxonomy</button>
                <button @click="tab = 'achievements'; history.replaceState(null, '', '?tab=achievements')" :class="tab === 'achievements' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-4 text-[9px] font-black uppercase tracking-widest rounded-[2rem] transition-all">Achievements</button>
            </div>

            <!-- Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.opacity.duration.500ms 
                    class="bg-emerald-500 text-white p-6 rounded-[2.5rem] shadow-lg flex items-center gap-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <p class="font-black text-sm uppercase tracking-widest">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Profile Content -->
            <div x-show="tab === 'profile'" class="space-y-6" x-transition>
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 px-1">Personal Info</h3>
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                        @csrf @method('PATCH')
                        <input type="hidden" name="active_tab" value="profile">
                        <div class="space-y-2">
                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-4 focus:ring-indigo-50" placeholder="Name">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Email Address</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-4 focus:ring-indigo-50" placeholder="Email">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Phone Number</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-4 focus:ring-indigo-50" placeholder="+880...">
                        </div>
                        <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-widest shadow-lg active:scale-95 transition-all mt-4">Update Information</button>
                    </form>
                </div>

                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 px-1">Security</h3>
                    <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <input type="hidden" name="active_tab" value="profile">
                        <input type="password" name="current_password" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-4 focus:ring-indigo-50" placeholder="Current Password">
                        <input type="password" name="password" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-4 focus:ring-indigo-50" placeholder="New Password">
                        <input type="password" name="password_confirmation" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-4 focus:ring-indigo-50" placeholder="Confirm Password">
                        <button type="submit" class="w-full bg-slate-100 text-slate-600 py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-widest active:scale-95 transition-all mt-2">Change Password</button>
                    </form>
                </div>

                <!-- Danger Links -->
                <div class="flex gap-4">
                    <button @click="showResetModal = true" class="flex-1 bg-red-50 text-red-500 py-4 rounded-2xl text-[9px] font-black uppercase tracking-widest active:scale-95 transition-all">Nuclear Reset</button>
                    <button @click="document.getElementById('delete-modal').classList.remove('hidden')" class="flex-1 bg-slate-100 text-slate-400 py-4 rounded-2xl text-[9px] font-black uppercase tracking-widest active:scale-95 transition-all">Delete Account</button>
                </div>
            </div>

            <!-- Budget Content -->
            <div x-show="tab === 'budget'" class="space-y-6" x-transition>
                <div class="bg-white p-8 rounded-[3.5rem] shadow-xl border border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-8 px-1">Financial Intelligence</h3>
                    <form action="{{ route('settings.updateBudget') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="active_tab" value="budget">
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Monthly Income</label>
                            <input type="number" name="monthly_income" x-model="income" class="w-full bg-slate-50 border-none rounded-3xl py-6 px-8 text-2xl font-black text-slate-900 focus:ring-8 focus:ring-indigo-50">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Cycle Start Day</label>
                            <input type="number" name="cycle_start_date" x-model="cycle" min="1" max="31" class="w-full bg-slate-50 border-none rounded-2xl py-5 px-6 font-black text-slate-900 focus:ring-4 focus:ring-indigo-50">
                        </div>
                        <div class="bg-indigo-50/50 p-6 rounded-[2.5rem] space-y-4">
                            <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest text-center">Allocation Ratios (%)</p>
                            <div class="grid grid-cols-3 gap-3">
                                <input type="number" name="needs_percentage" x-model="needs" class="w-full bg-white border-none rounded-xl py-4 text-center font-black text-slate-900 focus:ring-0">
                                <input type="number" name="wants_percentage" x-model="wants" class="w-full bg-white border-none rounded-xl py-4 text-center font-black text-slate-900 focus:ring-0">
                                <input type="number" name="savings_percentage" x-model="savings" class="w-full bg-white border-none rounded-xl py-4 text-center font-black text-slate-900 focus:ring-0">
                            </div>
                            <p class="text-center text-[9px] font-black tracking-widest" :class="totalPerc() === 100 ? 'text-emerald-500' : 'text-red-500'">TOTAL: <span x-text="totalPerc()"></span>%</p>
                        </div>
                        <button type="submit" :disabled="totalPerc() !== 100" class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] font-black text-xl shadow-2xl shadow-indigo-200 transition-all disabled:opacity-30">Apply Updates</button>
                    </form>
                </div>
            </div>

            <!-- Goals Content -->
            <div x-show="tab === 'goals'" class="space-y-6" x-transition>
                <div class="bg-indigo-900 p-8 rounded-[3.5rem] shadow-xl text-white flex justify-between items-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black">Financial Goals</h3>
                        <p class="text-indigo-300 text-[10px] font-black uppercase tracking-widest mt-1">Turning dreams into reality</p>
                    </div>
                    <button type="button" @click="showGoalModal = true" class="h-12 w-12 bg-white text-indigo-900 rounded-2xl flex items-center justify-center shadow-lg active:scale-90 transition-all cursor-pointer relative z-20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>

                @forelse($goals as $goal)
                    <div class="bg-white p-8 rounded-[3.5rem] shadow-xl border border-slate-100 space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-slate-100" style="background-color: {{ $goal->color }}20; color: {{ $goal->color }}">
                                    🎯
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-slate-800">{{ $goal->name }}</h4>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Target: ৳{{ number_format($goal->target_amount) }}
                                        @if($goal->deadline) • {{ $goal->deadline->format('M Y') }} @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-black text-slate-900">{{ $goal->progress_percentage }}%</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="h-4 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000" 
                                 style="width: {{ $goal->progress_percentage }}%; background-color: {{ $goal->color }}"></div>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em]">Remaining</span>
                                <span class="text-xs font-black text-slate-700">৳{{ number_format($goal->remaining_amount) }}</span>
                            </div>
                            <div class="flex gap-2">
                                <form action="{{ route('savings-goals.destroy', $goal) }}" method="POST" id="delete-goal-{{ $goal->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" @click="$dispatch('confirm', { 
                                        title: 'Remove Goal?', 
                                        message: 'This will delete your target for {{ $goal->name }}.', 
                                        confirmText: 'Remove Target',
                                        form: $el.closest('form') 
                                    })" class="h-10 w-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center active:scale-90 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                <button @click="contributingGoal = {{ $goal->id }}; goalAmount = ''" class="bg-slate-900 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg active:scale-95 transition-all">Add Funds</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <div class="h-24 w-24 bg-slate-100 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-2">No Goals Set</h4>
                        <p class="text-xs text-slate-400 font-medium">Define your dreams and start tracking.</p>
                        <button @click="showGoalModal = true" class="mt-6 text-indigo-600 font-black text-[10px] uppercase tracking-[0.2em] underline underline-offset-4">Create My First Goal</button>
                    </div>
                @endforelse
            </div>

            <!-- Categories Content (Tabbed Grid) -->
            <div x-show="tab === 'categories'" class="space-y-6" x-transition>
                <div class="bg-white p-8 rounded-[3.5rem] shadow-xl border border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 px-1">Universal Taxonomy</h3>
                    
                    <div class="flex gap-2 mb-8 overflow-x-auto no-scrollbar">
                        @foreach(['needs', 'wants', 'savings'] as $type)
                            <button @click="catTab = '{{ $type }}'" :class="catTab === '{{ $type }}' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-slate-50 text-slate-400'" class="px-6 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest transition-all">{{ ucfirst($type) }}</button>
                        @endforeach
                    </div>

                    <!-- Quick Add Box -->
                    <div class="mb-8 p-4 bg-slate-50 rounded-[2.5rem] border border-slate-100 flex items-center gap-3 focus-within:border-indigo-400 transition-all">
                        <input type="text" x-model="newCatName" @keyup.enter="addCategory()" placeholder="Add category to this tab..." 
                            class="flex-1 bg-transparent border-none py-2 text-sm font-bold text-slate-800 placeholder:text-slate-300 focus:ring-0">
                        <button @click="addCategory()" :disabled="isAddingCat" class="bg-indigo-600 text-white px-6 py-2.5 rounded-2xl font-black text-[10px] uppercase tracking-widest active:scale-95 transition-all">
                            <span x-show="!isAddingCat">Add</span>
                            <span x-show="isAddingCat"><svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-3 min-h-[300px]">
                        @foreach(['needs', 'wants', 'savings'] as $type)
                            <template x-if="catTab === '{{ $type }}'">
                                <div class="col-span-2 grid grid-cols-2 gap-3">
                                    @forelse($categories[$type] ?? [] as $cat)
                                        <div class="bg-slate-50 p-5 rounded-3xl border border-slate-100 relative group flex items-center justify-between">
                                            <p class="text-xs font-black text-slate-800 truncate pr-6">{{ $cat->name }}</p>
                                            <button @click='editingCategory = @json($cat)' class="h-8 w-8 bg-white text-slate-400 rounded-xl flex items-center justify-center hover:text-indigo-600 shadow-sm"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                        </div>
                                    @empty
                                        <div class="col-span-2 py-20 text-center">
                                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">No Categories Found</p>
                                        </div>
                                    @endforelse
                                </div>
                            </template>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Achievements Content -->
            <div x-show="tab === 'achievements'" class="space-y-6" x-transition>
                <div class="bg-gradient-to-br from-orange-500 to-rose-500 p-8 rounded-[3.5rem] shadow-xl text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Current Tracking Streak</p>
                            <h3 class="text-4xl font-black mt-1">{{ Auth::user()->current_streak }} Days</h3>
                        </div>
                        <span class="text-5xl">🔥</span>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[3.5rem] shadow-xl border border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-8 px-1 text-center">Badge Collection</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $allBadgeKeys = ['first_step', 'week_warrior', 'month_master', 'debt_slayer', 'automator'];
                            $userBadges = Auth::user()->badges ?? [];
                        @endphp

                        @foreach($allBadgeKeys as $key)
                            @php $details = \App\Services\GamificationService::getBadgeDetails($key); @endphp
                            @if(in_array($key, $userBadges))
                                <!-- Earned Badge -->
                                <div class="bg-slate-50 p-6 rounded-[2.5rem] border-2 border-indigo-100 flex flex-col items-center text-center group active:scale-95 transition-all">
                                    <div class="h-16 w-16 bg-white rounded-2xl flex items-center justify-center text-3xl mb-4 shadow-sm">
                                        {{ $details['icon'] }}
                                    </div>
                                    <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest mb-1">{{ $details['name'] }}</h4>
                                    <p class="text-[8px] font-bold text-slate-400 leading-tight">{{ $details['description'] }}</p>
                                </div>
                            @else
                                <!-- Locked Badge -->
                                <div class="bg-slate-50 p-6 rounded-[2.5rem] border-2 border-dashed border-slate-200 flex flex-col items-center text-center opacity-40 grayscale">
                                    <div class="h-16 w-16 bg-slate-100 rounded-2xl flex items-center justify-center text-3xl mb-4">
                                        🔒
                                    </div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $details['name'] }}</h4>
                                    <p class="text-[8px] font-bold text-slate-300 leading-tight">{{ $details['description'] }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Edit Modal -->
        <div x-show="editingCategory" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-sm rounded-[3rem] p-10 shadow-2xl" @click.away="editingCategory = null">
                <h3 class="text-xl font-black text-slate-900 mb-6 px-1">Edit Taxonomy</h3>
                <form :action="editingCategory ? `{{ url('settings/categories') }}/${editingCategory.id}` : '#'" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <input type="hidden" name="active_tab" value="categories">
                    <input type="text" name="name" :value="editingCategory?.name" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-8 focus:ring-indigo-50">
                    <div class="flex gap-3">
                        <button type="button" @click="editingCategory = null" class="flex-1 bg-slate-100 text-slate-400 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest">Cancel</button>
                        <button type="submit" class="flex-1 bg-slate-900 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg">Save</button>
                    </div>
                </form>
                <form :action="editingCategory ? `{{ url('settings/categories') }}/${editingCategory.id}` : '#'" method="POST" class="mt-4">
                    @csrf @method('DELETE')
                    <button type="button" @click="$dispatch('confirm', { 
                        title: 'Delete Taxonomy?', 
                        message: 'Permanently remove this category?', 
                        confirmText: 'Delete',
                        form: $el.closest('form') 
                    })" class="w-full text-red-500 font-black text-[10px] uppercase tracking-widest">Delete Category</button>
                </form>
            </div>
        </div>

        <!-- Nuclear Reset Modal -->
        <div x-show="showResetModal" x-transition.opacity class="fixed inset-0 bg-red-900/60 backdrop-blur-md z-[60] flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-sm rounded-[3.5rem] p-10 shadow-2xl text-center" @click.away="showResetModal = false">
                <h3 class="text-2xl font-black text-slate-900 mb-2">Nuclear Reset?</h3>
                <p class="text-xs text-slate-500 font-bold mb-8">This will wipe all data. This is permanent.</p>
                <form action="{{ route('settings.reset') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="confirmation" placeholder="Type RESET" class="w-full bg-slate-50 border-2 border-red-100 rounded-2xl py-4 px-6 text-center font-black text-red-500 focus:ring-0">
                    <button type="submit" class="w-full bg-red-500 text-white py-5 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-red-200">Confirm Wipe</button>
                </form>
            </div>
        </div>

        <!-- DELETE ACCOUNT MODAL (NEW SECURE VERSION) -->
        <div id="delete-modal" class="hidden fixed inset-0 bg-slate-900/90 backdrop-blur-xl z-[70] flex items-center justify-center p-6" x-data="{ confirmWord: '' }">
            <div class="bg-white w-full max-w-md rounded-[4rem] p-10 shadow-2xl text-center">
                <div class="h-20 w-20 bg-red-50 text-red-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4">Final Security Check</h3>
                <p class="text-xs text-slate-500 font-bold mb-8 leading-relaxed">To permanently delete your account and all financial data, please enter your password and type the phrase below exactly.</p>
                
                <form action="{{ route('profile.destroy') }}" method="POST" class="space-y-4">
                    @csrf @method('DELETE')
                    <input type="password" name="password" placeholder="Confirm your password" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-center font-bold text-slate-800">
                    
                    <div class="py-4 bg-red-50 rounded-2xl border-2 border-red-100">
                        <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-2">Type exactly:</p>
                        <p class="text-sm font-black text-red-600 mb-3">DELETE MY ACCOUNT</p>
                        <input type="text" name="confirmation" x-model="confirmWord" class="w-full bg-transparent border-none text-center font-black text-slate-900 focus:ring-0">
                    </div>

                    <button type="submit" :disabled="confirmWord !== 'DELETE MY ACCOUNT'" class="w-full bg-red-600 text-white py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-widest shadow-xl shadow-red-200 disabled:opacity-30">
                        Destroy Account Permanently
                    </button>
                    <button type="button" @click="document.getElementById('delete-modal').classList.add('hidden')" class="text-[10px] font-black text-slate-300 uppercase tracking-widest pt-4 block w-full">I made a mistake, go back</button>
                </form>
            </div>
        </div>
        <!-- New Goal Modal -->
        <div x-show="showGoalModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-sm rounded-[3.5rem] p-10 shadow-2xl" @click.away="showGoalModal = false">
                <h3 class="text-xl font-black text-slate-900 mb-6">Set New Target</h3>
                <form action="{{ route('savings-goals.store') }}" method="POST" class="space-y-6" @submit="validateGoal($event)">
                    @csrf
                    <input type="hidden" name="active_tab" value="goals">
                    <div class="space-y-2">
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Goal Name</label>
                        <input type="text" name="name" x-model="newGoalName" placeholder="e.g. Dream Laptop" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-8 focus:ring-indigo-50">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Target Amount (৳)</label>
                        <input type="number" name="target_amount" x-model="newGoalTarget" placeholder="50,000" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-8 focus:ring-indigo-50">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Deadline (Optional)</label>
                        <input type="date" name="deadline" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-slate-800 focus:ring-8 focus:ring-indigo-50">
                    </div>
                    <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-widest shadow-xl">Lock In Target</button>
                </form>
            </div>
        </div>

        <!-- Contribute Modal -->
        <div x-show="contributingGoal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-sm rounded-[3.5rem] p-10 shadow-2xl" @click.away="contributingGoal = null">
                <h3 class="text-xl font-black text-slate-900 mb-6">Add Funds</h3>
                <form :action="contributingGoal ? `{{ url('savings-goals') }}/${contributingGoal}/contribute` : '#'" method="POST" class="space-y-6" @submit="validateContribution($event)">
                    @csrf
                    <input type="hidden" name="active_tab" value="goals">
                    <div class="space-y-2">
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Contribution Amount (৳)</label>
                        <input type="number" name="amount" x-model="goalAmount" placeholder="1000" class="w-full bg-slate-50 border-none rounded-2xl py-6 px-8 text-2xl font-black text-slate-900 focus:ring-8 focus:ring-indigo-50">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-widest shadow-xl">Add to Progress</button>
                    <button type="button" @click="contributingGoal = null" class="w-full text-slate-400 font-bold text-[8px] uppercase tracking-widest">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
