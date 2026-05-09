<x-app-layout>
    <div class="min-h-screen flex flex-col justify-center px-6 py-12 lg:px-8 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">
        <div class="sm:mx-auto sm:w-full sm:max-lg">
            <h2 class="text-center text-3xl font-bold tracking-tight text-white">Start Your Journey</h2>
            <p class="mt-2 text-center text-sm text-white/80">Join thousands managing their wealth better</p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[400px]">
            <div class="glass-card p-8 rounded-[2rem] shadow-2xl">
                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 ml-1">Full Name</label>
                        <div class="mt-2">
                            <input id="name" name="name" type="text" autocomplete="name" required 
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="Shykot Hasan">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

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
                        <label for="phone" class="block text-sm font-medium text-slate-700 ml-1">Phone Number</label>
                        <div class="mt-2">
                            <input id="phone" name="phone" type="tel" required 
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="+8801XXXXXXXXX">
                        </div>
                        @error('phone')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 ml-1">Create Password</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" required 
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 ml-1">Confirm Password</label>
                        <div class="mt-2">
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                class="block w-full rounded-2xl border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-4 bg-white/50"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="flex w-full justify-center rounded-2xl bg-indigo-600 px-3 py-3.5 text-sm font-semibold leading-6 text-white shadow-lg hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-95">
                            Create Account
                        </button>
                    </div>
                </form>

                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm font-medium leading-6">
                            <span class="bg-transparent px-6 text-slate-400">Already a member?</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('login') }}" class="flex w-full justify-center rounded-2xl bg-white px-3 py-3 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all active:scale-95">
                            Sign in to your account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
