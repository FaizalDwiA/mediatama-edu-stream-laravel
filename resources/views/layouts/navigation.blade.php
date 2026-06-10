<nav x-data="{ open: false }" class="sticky top-0 z-50 shadow-lg transition-all duration-300" style="background-color: #0b0f19 !important; border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo & Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <x-application-logo class="block h-8 w-auto transition-transform group-hover:scale-105" />
                        <span class="text-white font-extrabold text-lg tracking-wider transition-colors" style="background: linear-gradient(to right, #ffffff, #e2e8f0, #c7d2fe); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Edustream
                        </span>
                    </a>
                </div>
            </div>

            <!-- Search Bar (YouTube Style - Solid Dark Theme) -->
            <div class="hidden sm:flex items-center justify-center flex-1 max-w-lg mx-auto px-4">
                <form action="{{ route('dashboard') }}" method="GET" class="w-full">
                    <div class="flex items-center rounded-full overflow-hidden focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition-all duration-200 w-full relative" style="background-color: #12121a !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Telusuri"
                            class="w-full text-sm placeholder-slate-500 bg-transparent border-0 focus:ring-0 focus:outline-none"
                            style="border: none !important; background: transparent !important; box-shadow: none !important; outline: none !important; padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; padding-left: 1rem !important; padding-right: 3.5rem !important; color: #f1f5f9 !important;">
                        @if (request('search'))
                            <a href="{{ route('dashboard') }}"
                                class="absolute right-16 text-slate-400 hover:text-red-500 p-1 rounded-full hover:bg-slate-800 transition-all flex items-center justify-center"
                                title="Bersihkan Pencarian" style="top: 50%; transform: translateY(-50%);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                        <button type="submit"
                            class="border-slate-800 text-slate-300 hover:text-indigo-400 px-6 py-2 transition-colors flex items-center justify-center shrink-0"
                            style="background-color: #222222 !important; border-left: 1px solid rgba(255, 255, 255, 0.1) !important;"
                            title="Cari">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Controls: Dropdown Avatar Bulat -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48" contentClasses="p-0 bg-transparent rounded-md shadow-2xl">
                    <x-slot name="trigger">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition-all shadow-md flex items-center justify-center text-white font-bold text-sm uppercase cursor-pointer select-none" style="border: 1px solid rgba(255, 255, 255, 0.12) !important;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        <div style="background-color: #1f1f1f !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius: 8px !important; overflow: hidden !important; padding: 4px 0 !important;">
                            <a href="{{ route('profile.edit') }}" style="color: #cbd5e1 !important; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#2a2a2a'" onmouseout="this.style.backgroundColor='transparent'" class="block w-full px-4 py-2 text-start text-sm leading-5">
                                {{ __('Profile') }}
                            </a>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    this.closest('form').submit();"
                                    style="color: #cbd5e1 !important; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#2a2a2a'" onmouseout="this.style.backgroundColor='transparent'" class="block w-full px-4 py-2 text-start text-sm leading-5">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile: Search Bar + Hamburger (Solid Dark Theme) -->
            <div class="flex items-center justify-between gap-3 w-full sm:hidden">
                <!-- Mobile Search Bar (inline in navbar) -->
                <form action="{{ route('dashboard') }}" method="GET" class="relative w-full max-w-[70%] mx-auto">
                    <div class="flex items-center rounded-full overflow-hidden focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition-all duration-200" style="background-color: #12121a !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Telusuri" class="text-sm placeholder-slate-500 bg-transparent border-0 focus:ring-0 focus:outline-none"
                            style="border: none !important; background: transparent !important; box-shadow: none !important; outline: none !important; padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; padding-left: 0.75rem !important; padding-right: 0.5rem !important; width: 90% !important; color: #f1f5f9 !important;">
                        @if (request('search'))
                            <a href="{{ route('dashboard') }}"
                                class="text-slate-400 hover:text-red-500 p-1 rounded-full flex items-center justify-center"
                                title="Bersihkan">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                        <button type="submit"
                            class="text-slate-400 hover:text-indigo-400 px-2.5 py-1.5 transition-colors flex items-center justify-center shrink-0"
                            style="background-color: #222222 !important; border-left: 1px solid rgba(255, 255, 255, 0.1) !important;"
                            title="Cari">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Hamburger -->
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none focus:bg-slate-800 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Solid Dark Theme) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-slate-800/80" style="background-color: #1f1f1f !important;">
        <div class="pt-2 pb-3 space-y-1" style="display: none">
            <a href="{{ route('dashboard') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-500 text-start text-base font-bold text-white bg-slate-850 transition duration-150 ease-in-out">
                {{ __('Dashboard') }}
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-800">
            <div class="px-4">
                <div class="font-medium text-base text-slate-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" style="color: #cbd5e1 !important;" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium hover:text-white hover:bg-slate-800 transition duration-150 ease-in-out">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();"
                        style="color: #cbd5e1 !important;"
                        class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium hover:text-white hover:bg-slate-800 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
