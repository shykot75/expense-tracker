<x-app-layout>
    <div class="min-h-screen flex flex-col justify-center px-6 py-12 lg:px-8 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">
        <div class="sm:mx-auto sm:w-full sm:max-lg">
            <!-- Logo/Icon -->
            <div class="mx-auto h-20 w-20 bg-white/20 backdrop-blur-xl rounded-3xl flex items-center justify-center shadow-2xl border border-white/30">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="mt-8 text-center text-3xl font-bold tracking-tight text-white">Welcome Back</h2>
            <p class="mt-2 text-center text-sm text-white/80">Manage your 60-25-15 budget with ease</p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[400px]">
            <div class="glass-card p-8 rounded-[2rem] shadow-2xl">
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 ml-1">Email address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="name@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between ml-1">
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                            <label for="remember" class="ml-2 block text-sm text-slate-600">Remember me</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-2xl bg-indigo-600 px-3 py-3.5 text-sm font-semibold leading-6 text-white shadow-lg hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-95">
                            Sign in
                        </button>
                    </div>
                </form>

                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm font-medium leading-6">
                            <span class="bg-transparent px-6 text-slate-400">Or continue with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1">
                        <a href="#" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-white px-3 py-3 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus-visible:ring-transparent transition-all active:scale-95">
                            <svg class="h-5 w-5" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z" fill="#EA4335" />
                            </svg>
                            <span class="text-sm font-semibold leading-6">Google Account</span>
                        </a>
                    </div>
                </div>
            </div>

            <p class="mt-10 text-center text-sm text-white/90">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-semibold leading-6 text-white hover:text-indigo-100 underline decoration-2 underline-offset-4">Create one for free</a>
            </p>
        </div>
    </div>
</x-app-layout>
