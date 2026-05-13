<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WislySpend') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Performance Hints -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
            padding-top: var(--safe-area-inset-top, 0px);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Ensure auth screens have enough top margin */
        .auth-container {
            padding-top: calc(var(--safe-area-inset-top, 0px) + 2rem);
        }
    </style>
</head>
<body class="h-full antialiased text-slate-900">
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Global Loading Overlay -->
        <div x-data="{ loading: false }" 
             @submit.window="loading = true" 
             @page-finished.window="loading = false"
             x-show="loading" 
             class="fixed inset-0 z-[200] flex flex-col items-center justify-center bg-slate-900/60 backdrop-blur-md"
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
        >
            <div class="relative">
                <div class="h-20 w-20 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="h-10 w-10 bg-white rounded-2xl rotate-45 animate-pulse"></div>
                </div>
            </div>
            <p class="mt-8 text-white text-[10px] font-black uppercase tracking-[0.3em] animate-pulse">Processing Transaction</p>
        </div>

        <!-- Universal Top Header -->
        @auth
            @if(!request()->routeIs(['welcome', 'onboarding']))
                @include('layouts.header')
            @endif
        @endauth

        <!-- Main Content -->
        <main class="flex-grow pb-32">
            {{ $slot }}
        </main>

        <!-- Smart Alerts (Success/Error Notifications) -->
        <div x-data="{ 
                show: false, 
                msg: '', 
                type: 'success',
                timer: null,
                init() {
                    @if(session('success'))
                        this.trigger('{{ session('success') }}', 'success');
                    @elseif(session('error'))
                        this.trigger('{{ session('error') }}', 'error');
                    @endif
                },
                trigger(msg, type) {
                    this.msg = msg;
                    this.type = type;
                    this.show = true;
                    if(this.timer) clearTimeout(this.timer);
                    this.timer = setTimeout(() => this.show = false, 5000);
                }
            }" 
            @notify.window="trigger($event.detail.msg, $event.detail.type || 'success')"
            x-show="show" 
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-y-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-10 scale-95"
            class="fixed top-6 left-6 right-6 z-[100] pointer-events-none"
            style="display: none;"
        >
            <div class="max-w-md mx-auto pointer-events-auto">
                <div :class="type === 'success' ? 'bg-emerald-600 shadow-emerald-200' : 'bg-rose-600 shadow-rose-200'" 
                    class="p-4 rounded-[2rem] shadow-2xl flex items-center gap-4 text-white border border-white/20">
                    <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <template x-if="type === 'success'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </template>
                        <template x-if="type === 'error'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </template>
                    </div>
                    <p class="text-[11px] font-black uppercase tracking-widest leading-tight" x-text="msg"></p>
                    <button @click="show = false" class="ml-auto opacity-50 hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Dynamic Bottom Navigation (Mobile-first) -->
        @auth
            @if(!request()->routeIs(['onboarding', 'expenses.create', 'expenses.edit']))
                @include('layouts.navigation')
            @endif
        @endauth

        <!-- Custom Confirmation Modal -->
        <div x-data="{ 
            show: false, 
            title: '', 
            message: '', 
            confirmText: 'Confirm', 
            cancelText: 'Cancel',
            formToSubmit: null,
            confirmAction() {
                if (this.formToSubmit) {
                    this.formToSubmit.submit();
                }
                this.show = false;
            }
        }" 
        @confirm.window="show = true; title = $event.detail.title; message = $event.detail.message; confirmText = $event.detail.confirmText || 'Confirm'; formToSubmit = $event.detail.form;"
        x-show="show" 
        class="fixed inset-0 z-[200] flex items-center justify-center p-6" x-cloak style="display: none;">
            <div x-show="show" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>
            <div x-show="show" x-transition.scale.origin.center class="bg-white w-full max-w-sm rounded-[3.5rem] p-10 shadow-2xl relative z-10 text-center border border-white/20">
                <div class="h-20 w-20 bg-rose-50 text-rose-500 rounded-[2.2rem] flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2" x-text="title"></h3>
                <p class="text-[10px] font-bold text-slate-500 mb-10 leading-relaxed uppercase tracking-widest" x-text="message"></p>
                <div class="flex flex-col gap-3">
                    <button @click="confirmAction()" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl active:scale-95 transition-all" x-text="confirmText"></button>
                    <button @click="show = false" class="w-full bg-slate-100 text-slate-400 py-4 rounded-2xl font-black text-[9px] uppercase tracking-widest active:scale-95 transition-all" x-text="cancelText"></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            
            // Dispatch event to show global loader
            window.dispatchEvent(new CustomEvent('submit'));

            // Disable buttons to prevent double click
            submitButtons.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });
        });

        // Hide loader when page is restored from cache (back button)
        window.addEventListener('pageshow', function(event) {
            window.dispatchEvent(new CustomEvent('page-finished'));
        });
    </script>
</body>
</html>
