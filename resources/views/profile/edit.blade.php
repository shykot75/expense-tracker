<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <!-- Header -->
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-6 pt-16 pb-24 rounded-b-[4rem] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="flex justify-between items-center relative z-10">
                <h1 class="text-3xl font-extrabold text-white tracking-tight">My Profile</h1>
                <a href="{{ route('dashboard') }}" class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            </div>
        </div>

        <div class="px-6 -mt-12 pb-32 space-y-8">
            <!-- Notifications -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-3xl font-bold text-sm shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="bg-orange-50 border border-orange-200 text-orange-600 px-6 py-4 rounded-3xl font-bold text-sm shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    {{ session('warning') }}
                </div>
            @endif

            <!-- Avatar Section -->
            <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 flex flex-col items-center" x-data="{ uploading: false }">
                <div class="relative group">
                    <div class="h-32 w-32 rounded-[2.5rem] overflow-hidden border-4 border-white shadow-2xl relative">
                        <!-- Loading Overlay -->
                        <div x-show="uploading" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm z-20 flex flex-col items-center justify-center text-white">
                            <svg class="animate-spin h-8 w-8 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-[8px] font-black uppercase tracking-widest">Uploading...</span>
                        </div>

                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="h-full w-full object-cover" id="avatar-preview">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=512" class="h-full w-full object-cover" id="avatar-preview">
                        @endif
                    </div>
                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="absolute bottom-0 right-0" id="avatar-form" @submit="uploading = true">
                        @csrf
                        <label class="h-10 w-10 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg cursor-pointer hover:bg-indigo-500 transition-all border-4 border-white active:scale-90">
                            <input type="file" name="avatar" class="hidden" onchange="document.getElementById('avatar-form').requestSubmit()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </label>
                    </form>
                </div>
                <h2 class="mt-6 text-xl font-black text-slate-800">{{ $user->name }}</h2>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">{{ $user->email }}</p>
            </div>

            <!-- Profile Info Form -->
            <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100">
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                            class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                        @error('name') <p class="text-xs text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                            class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                        @error('email') <p class="text-xs text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                            class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                        @error('phone') <p class="text-xs text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl active:scale-95 transition-all">Update Information</button>
                    </div>
                </form>
            </div>

            <!-- Password Change -->
            <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6">Security</h3>
                <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Password</label>
                        <input type="password" name="current_password" required 
                            class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                        @error('current_password') <p class="text-xs text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
                        <input type="password" name="password" required 
                            class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                        @error('password') <p class="text-xs text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required 
                            class="block w-full rounded-2xl border-slate-100 py-4 px-6 font-bold text-slate-800 bg-slate-50/50 focus:ring-4 focus:ring-indigo-50">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-slate-100 text-slate-600 py-5 rounded-2xl font-black text-sm uppercase tracking-widest active:scale-95 transition-all">Update Password</button>
                    </div>
                </form>
            </div>

            <!-- Logout Section (Highlighted) -->
            <div class="bg-red-50 p-8 rounded-[3rem] border-2 border-red-100">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-12 w-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-red-900 uppercase tracking-widest">Sign Out</h3>
                        <p class="text-xs text-red-400 font-bold">Safely end your session</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white py-5 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-red-200 active:scale-90 transition-all">Logout Now</button>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="text-center pt-4">
                <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('WARNING: This will permanently delete your account and all data. Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <div class="mb-4">
                        <input type="password" name="password" placeholder="Confirm password to delete account" required 
                            class="block w-full rounded-2xl border-slate-100 py-3 px-6 text-center text-xs font-bold text-slate-800 bg-white shadow-sm mb-4">
                    </div>
                    <button type="submit" class="text-slate-400 hover:text-red-500 font-bold text-[10px] uppercase tracking-widest transition-colors">Delete Account Permanently</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
