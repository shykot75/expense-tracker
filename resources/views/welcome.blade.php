<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WislySpend — Spend Smart, Live Wisly</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,600,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .aurora-text { background: linear-gradient(to right, #10b981, #6366f1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-[#0f172a] text-white selection:bg-indigo-500 selection:text-white overflow-x-hidden">
    <!-- Hero Background -->
    <div class="fixed inset-0 z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-600/20 rounded-full blur-[120px] -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-600/10 rounded-full blur-[120px] -ml-48 -mb-48"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col">
        <!-- Navigation -->
        <header class="max-w-7xl mx-auto w-full px-6 py-8 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" class="h-10 w-10 object-contain" alt="WislySpend Logo">
            </div>
            
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-white text-slate-900 rounded-full text-xs font-black uppercase tracking-widest hover:scale-105 transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2.5 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-white transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20 active:scale-95 transition-all">Get Started</a>
                @endauth
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="flex-1 flex items-center justify-center px-6">
            <div class="max-w-4xl w-full text-center space-y-12">
                <div>
                    <span class="px-4 py-1.5 rounded-full glass text-[10px] font-black uppercase tracking-[0.3em] text-indigo-400 border border-indigo-500/20 inline-block mb-6">Introducing Financial Mastery</span>
                    <h1 class="text-6xl md:text-8xl font-black tracking-tighter leading-[1.1] mb-8">
                        Spend <span class="aurora-text italic">Smart</span>,<br>
                        Live <span class="text-white underline decoration-indigo-500/50 underline-offset-8">Wisly</span>.
                    </h1>
                    <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed">
                        The ultimate financial companion with a built-in <span class="text-white font-bold">Time Machine</span> and <span class="text-white font-bold">Wealth Forecast</span>. Take control of your money, once and for all.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="w-full md:w-auto px-10 py-5 bg-indigo-600 hover:bg-indigo-700 rounded-2xl text-sm font-black uppercase tracking-widest shadow-2xl shadow-indigo-500/30 transition-all flex items-center justify-center gap-3 group">
                        Start for Free
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                    <a href="#features" class="w-full md:w-auto px-10 py-5 glass hover:bg-white/5 rounded-2xl text-sm font-black uppercase tracking-widest transition-all">Explore Features</a>
                </div>

                <!-- App Preview Mockup -->
                <div class="mt-20 relative px-4">
                    <div class="max-w-2xl mx-auto p-4 glass rounded-[3rem] shadow-[0_0_100px_rgba(99,102,241,0.15)] relative group hover:scale-[1.02] transition-all duration-700">
                        <div class="bg-[#0f172a] rounded-[2.5rem] overflow-hidden border border-white/5 aspect-video flex items-center justify-center">
                            <div class="text-center space-y-4">
                                <div class="w-24 h-24 mx-auto flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('images/logo.png') }}" class="h-full w-full object-contain" alt="WislySpend">
                                </div>
                                <div class="flex gap-2 justify-center">
                                    <div class="h-1.5 w-12 bg-indigo-500 rounded-full"></div>
                                    <div class="h-1.5 w-6 bg-slate-800 rounded-full"></div>
                                    <div class="h-1.5 w-6 bg-slate-800 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Features Grid -->
        <section id="features" class="max-w-7xl mx-auto w-full px-6 py-32 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-10 glass rounded-[2.5rem] border border-white/5 space-y-6 hover:border-indigo-500/30 transition-colors">
                <div class="h-14 w-14 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black">Time Machine</h3>
                <p class="text-slate-400 leading-relaxed">Travel through your financial history and see exactly where every penny went. Learn from the past to master the future.</p>
            </div>
            <div class="p-10 glass rounded-[2.5rem] border border-white/5 space-y-6 hover:border-emerald-500/30 transition-colors">
                <div class="h-14 w-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <h3 class="text-2xl font-black">Wealth Forecast</h3>
                <p class="text-slate-400 leading-relaxed">AI-powered predictions show your net worth growth over the next 12 months. Watch your future self get richer.</p>
            </div>
            <div class="p-10 glass rounded-[2.5rem] border border-white/5 space-y-6 hover:border-purple-500/30 transition-colors">
                <div class="h-14 w-14 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-2xl font-black">Multi-Budget</h3>
                <p class="text-slate-400 leading-relaxed">Infinite flexibility with multiple budget layers. Needs, Wants, and Savings - all perfectly balanced for your lifestyle.</p>
            </div>
        </section>

        <!-- Footer -->
        <footer class="max-w-7xl mx-auto w-full px-6 py-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-slate-500 text-sm font-bold">&copy; {{ date('Y') }} WislySpend. All rights reserved.</p>
            <div class="flex gap-8">
                <a href="#" class="text-slate-500 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest">Privacy</a>
                <a href="#" class="text-slate-500 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest">Terms</a>
                <a href="#" class="text-slate-500 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest">Twitter</a>
            </div>
        </footer>
    </div>
</body>
</html>
