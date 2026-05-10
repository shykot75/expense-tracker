<x-app-layout>
    <div class="min-h-screen flex flex-col justify-center px-6 py-12 lg:px-8 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500" x-data="{ loading: false, showPassword: false }">
        <div class="sm:mx-auto sm:w-full sm:max-lg">
            <!-- Logo -->
            <div class="mx-auto h-20 w-20 flex items-center justify-center overflow-hidden mb-8">
                <img src="{{ asset('images/logo.png') }}" class="h-full w-full object-contain" alt="WislySpend Logo">
            </div>
            <h2 class="text-center text-3xl font-bold tracking-tight text-white">Start Your Journey</h2>
            <p class="mt-2 text-center text-sm text-white/80">Join thousands managing their wealth better</p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[400px]">
            <div class="glass-card p-8 rounded-[2rem] shadow-2xl relative overflow-hidden">
                <!-- Loading Overlay -->
                <div x-show="loading" x-transition.opacity class="absolute inset-0 bg-white/60 backdrop-blur-sm z-50 flex items-center justify-center">
                    <div class="flex flex-col items-center">
                        <svg class="animate-spin h-10 w-10 text-indigo-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Creating Account...</span>
                    </div>
                </div>

                <form action="{{ route('register') }}" method="POST" class="space-y-5" @submit="loading = true">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 ml-1">Full Name</label>
                        <div class="mt-2">
                            <input id="name" name="name" type="text" autocomplete="name" value="{{ old('name') }}"
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="Shykot Hasan">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-500 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 ml-1">Email address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}"
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="name@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 ml-1">Phone Number</label>
                        <div class="mt-2">
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="+8801XXXXXXXXX">
                        </div>
                        @error('phone')
                            <p class="mt-2 text-sm text-red-500 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 ml-1">Create Password</label>
                        <div class="mt-2 relative">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'"                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 pl-4 pr-12 bg-white/50"
                                placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L1 1m11 11L22.828 22.828"></path></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 ml-1">Confirm Password</label>
                        <div class="mt-2 relative">
                            <input id="password_confirmation" name="password_confirmation" :type="showPassword ? 'text' : 'password'"                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 pl-4 pr-12 bg-white/50"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" :disabled="loading" class="flex w-full justify-center rounded-2xl bg-indigo-600 px-3 py-3.5 text-sm font-bold leading-6 text-white shadow-lg hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            Create Account
                        </button>
                    </div>
                </form>

                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm font-bold leading-6">
                            <span class="bg-transparent px-6 text-slate-400 uppercase tracking-widest text-[10px]">Or join with</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('auth.google') }}" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-white px-3 py-3 text-sm font-bold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all active:scale-95">
                            <svg class="h-5 w-5" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z" fill="#EA4335" />
                            </svg>
                            <span class="text-sm font-bold leading-6">Google Account</span>
                        </a>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Already a member?</p>
                    <a href="{{ route('login') }}" class="mt-4 block font-black text-indigo-600 hover:text-indigo-500 uppercase tracking-[0.2em] text-[10px]">Sign in to your account</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
