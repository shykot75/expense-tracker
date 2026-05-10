<x-app-layout>
    <div class="min-h-screen flex flex-col justify-center px-6 py-12 lg:px-8 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500"
        x-data="{ 
            loading: false, 
            showPassword: false,
            email: '{{ old('email') }}',
            password: '',
            validate(e) {
                if (!this.email) {
                    $dispatch('notify', { msg: 'Email address is required', type: 'error' });
                    e.preventDefault(); return false;
                }
                if (!this.password) {
                    $dispatch('notify', { msg: 'Please enter your password', type: 'error' });
                    e.preventDefault(); return false;
                }
                this.loading = true;
                return true;
            }
        }">
        <div class="sm:mx-auto sm:w-full sm:max-lg">
            <!-- Logo/Icon -->
            <div
                class="mx-auto h-20 w-20 bg-white/20 backdrop-blur-xl rounded-3xl flex items-center justify-center shadow-2xl border border-white/30">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <h2 class="mt-8 text-center text-3xl font-bold tracking-tight text-white">Welcome Back</h2>
            <p class="mt-2 text-center text-sm text-white/80">Manage your 60-25-15 budget with ease</p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[400px]">
            <div class="glass-card p-8 rounded-[2rem] shadow-2xl relative overflow-hidden">
                <!-- Loading Overlay -->
                <div x-show="loading" x-transition.opacity
                    class="absolute inset-0 bg-white/60 backdrop-blur-sm z-50 flex items-center justify-center">
                    <div class="flex flex-col items-center">
                        <svg class="animate-spin h-10 w-10 text-indigo-600 mb-2" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span
                            class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Authenticating...</span>
                    </div>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-6" @submit="validate($event)">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 ml-1">Email address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" x-model="email"
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="name@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between ml-1">
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        </div>
                        <div class="mt-2 relative">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password" x-model="password"
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 pl-4 pr-12 bg-white/50"
                                placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L1 1m11 11L22.828 22.828">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                            <label for="remember" class="ml-2 block text-sm font-bold text-slate-600">Remember
                                me</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-bold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" :disabled="loading"
                            class="flex w-full justify-center rounded-2xl bg-indigo-600 px-3 py-3.5 text-sm font-bold leading-6 text-white shadow-lg hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            Sign in
                        </button>
                    </div>
                </form>

                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm font-bold leading-6">
                            <span class="bg-transparent px-6 text-slate-400 uppercase tracking-widest text-[10px]">Or
                                continue with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1">
                        <a href="#"
                            class="flex w-full items-center justify-center gap-3 rounded-2xl bg-white px-3 py-3 text-sm font-bold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus-visible:ring-transparent transition-all active:scale-95">
                            <svg class="h-5 w-5" viewBox="0 0 24 24">
                                <path
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                    fill="#4285F4" />
                                <path
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                    fill="#34A853" />
                                <path
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                    fill="#FBBC05" />
                                <path
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z"
                                    fill="#EA4335" />
                            </svg>
                            <span class="text-sm font-bold leading-6">Google Account</span>
                        </a>
                    </div>
                </div>
            </div>

            <p class="mt-10 text-center text-sm text-white/90">
                Don't have an account?
                <a href="{{ route('register') }}"
                    class="font-bold leading-6 text-white hover:text-indigo-100 underline decoration-2 underline-offset-4">Create
                    one for free</a>
            </p>
        </div>
    </div>
</x-app-layout>