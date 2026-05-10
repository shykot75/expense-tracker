<div class="absolute top-0 inset-x-0 z-50 px-6 pt-8 pointer-events-none">
    <div class="flex justify-between items-center pointer-events-auto">
        <!-- Brand Logo (Top Left) -->
        <div class="h-10 w-10 flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/logo.png') }}" class="h-full w-full object-contain" alt="WislySpend Logo">
        </div>
        
        <!-- Settings (Top Right) -->
        <div class="flex items-center gap-2">
            <a href="{{ route('settings.index') }}" class="h-10 w-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/10 text-white active:scale-90 transition-all hover:bg-white/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
            </a>
        </div>
    </div>
</div>
