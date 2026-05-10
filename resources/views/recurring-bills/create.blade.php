<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-32" x-data="{ 
        frequency: '{{ old('frequency', 'monthly') }}',
        amount: '{{ old('amount') }}',
        description: '{{ old('description') }}',
        categoryId: '{{ old('category_id') }}',
        validate(e) {
            if (!this.categoryId) {
                $dispatch('notify', { msg: 'Please select a category', type: 'error' });
                e.preventDefault(); return false;
            }
            if (!this.description) {
                $dispatch('notify', { msg: 'What is this service for?', type: 'error' });
                e.preventDefault(); return false;
            }
            if (!this.amount || parseFloat(this.amount) <= 0) {
                $dispatch('notify', { msg: 'Enter a valid payment amount', type: 'error' });
                e.preventDefault(); return false;
            }
            return true;
        }
    }">
        <!-- Header -->
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 px-6 pt-16 pb-32 rounded-b-[4rem] shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 relative z-10">
                <a href="{{ route('recurring-bills.index') }}" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-3xl font-black text-white tracking-tight">New Automation</h1>
            </div>
        </div>

        <div class="px-6 -mt-16 relative z-20">
            <div class="bg-white rounded-[3.5rem] p-8 shadow-2xl border border-slate-100">
                <form action="{{ route('recurring-bills.store') }}" method="POST" class="space-y-8" @submit="validate($event)">
                    @csrf
                    
                    <!-- Frequency Toggle -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">How often?</label>
                        <div class="bg-slate-50 p-2 rounded-[2.5rem] flex gap-1 overflow-x-auto no-scrollbar border @error('frequency') border-red-500 @else border-transparent @enderror">
                            @foreach(['daily', 'weekly', 'monthly', 'yearly'] as $freq)
                                <label class="flex-1 min-w-[80px] cursor-pointer">
                                    <input type="radio" name="frequency" value="{{ $freq }}" x-model="frequency" {{ $freq === 'monthly' ? 'checked' : '' }} class="hidden">
                                    <div :class="frequency === '{{ $freq }}' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400'" class="py-3 text-[8px] font-black uppercase tracking-widest text-center rounded-[1.5rem] transition-all">
                                        {{ ucfirst($freq) }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('frequency') <p class="text-[9px] font-black text-red-500 ml-6 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-6">
                        <!-- Category -->
                        <div class="bg-slate-50 p-6 rounded-[2.5rem] border @error('category_id') border-red-500 @else border-transparent @enderror">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2">Category</label>
                            <select name="category_id" x-model="categoryId" class="w-full bg-transparent border-none text-lg font-black text-slate-800 focus:ring-0">
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-[9px] font-black text-red-500 mt-2 ml-4 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div class="bg-slate-50 p-6 rounded-[2.5rem] focus-within:ring-8 focus-within:ring-indigo-50 transition-all border @error('description') border-red-500 @else border-transparent focus-within:border-indigo-100 @enderror">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2">Service Name</label>
                            <input type="text" name="description" x-model="description" placeholder="e.g. Netflix, Rent, Internet..." 
                                class="w-full bg-transparent border-none text-xl font-black text-slate-800 placeholder:text-slate-300 focus:ring-0">
                            @error('description') <p class="text-[9px] font-black text-red-500 mt-2 ml-4 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <!-- Amount -->
                        <div class="bg-slate-50 p-6 rounded-[2.5rem] focus-within:ring-8 focus-within:ring-indigo-50 transition-all border @error('amount') border-red-500 @else border-transparent focus-within:border-indigo-100 @enderror">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2">Amount</label>
                            <div class="flex items-center gap-4">
                                <span class="text-2xl font-black text-slate-300">৳</span>
                                <input type="number" name="amount" x-model="amount" step="0.01" placeholder="0.00" 
                                    class="w-full bg-transparent border-none text-3xl font-black text-slate-900 placeholder:text-slate-200 focus:ring-0">
                            </div>
                            @error('amount') <p class="text-[9px] font-black text-red-500 mt-2 ml-4 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <!-- Start Date -->
                        <div class="bg-slate-50 p-6 rounded-[2.5rem] border @error('next_deduction_date') border-red-500 @else border-transparent @enderror">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2">First Payment Date</label>
                            <input type="date" name="next_deduction_date" value="{{ old('next_deduction_date', date('Y-m-d')) }}" onclick="this.showPicker()"
                                class="w-full bg-transparent border-none text-sm font-black text-slate-800 focus:ring-0">
                            @error('next_deduction_date') <p class="text-[9px] font-black text-red-500 mt-2 ml-4 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] font-black text-xl shadow-2xl shadow-indigo-200 active:scale-95 transition-all">
                        Activate Automation
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
