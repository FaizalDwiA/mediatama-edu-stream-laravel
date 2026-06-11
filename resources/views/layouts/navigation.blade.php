<style>
    /* YouTube Style Search Bar Container */
    .yt-search-container {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 600px;
    }

    .yt-search-box {
        display: flex;
        flex: 1;
        align-items: center;
        background-color: #0f0f0f !important;
        border: 1px solid #303030 !important;
        border-right: none !important;
        border-radius: 40px 0 0 40px !important;
        padding: 0 4px 0 16px !important;
        height: 40px;
        position: relative;
        transition: border-color 0.15s ease;
    }

    .yt-search-box:focus-within {
        border-color: #1c62b9 !important;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.4) !important;
    }

    .yt-search-input {
        width: 100%;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        color: #f1f5f9 !important;
        font-size: 0.95rem !important;
        padding: 0 !important;
        margin-right: 32px !important; /* Space for clear button */
    }

    .yt-search-input::placeholder {
        color: #888888 !important;
    }

    .yt-search-input:focus {
        ring: 0 !important;
        outline: none !important;
    }

    /* Search icon inside input box (revealed on focus) */
    .yt-input-search-icon {
        display: none;
        color: #a0a0a0;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .yt-search-box:focus-within .yt-input-search-icon {
        display: flex;
        align-items: center;
    }

    /* Clear query button (X) */
    .yt-clear-btn {
        position: absolute;
        right: 12px;
        color: #aaaaaa;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 6px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .yt-clear-btn:hover {
        background-color: #272727;
        color: #ffffff;
    }

    /* YouTube Search Button */
    .yt-search-btn {
        background-color: #222222 !important;
        border: 1px solid #303030 !important;
        border-radius: 0 40px 40px 0 !important;
        width: 64px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f1f5f9 !important;
        cursor: pointer;
        transition: background-color 0.15s ease;
        flex-shrink: 0;
    }

    .yt-search-btn:hover {
        background-color: #272727 !important;
    }

    /* YouTube Microphone Button */
    .yt-mic-btn {
        width: 40px;
        height: 40px;
        border-radius: 50% !important;
        background-color: #181818 !important;
        border: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff !important;
        cursor: pointer;
        margin-left: 8px;
        transition: background-color 0.15s ease;
        flex-shrink: 0;
    }

    .yt-mic-btn:hover {
        background-color: #272727 !important;
    }

    /* Mobile Search Bar styles */
    .yt-mobile-search-container {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .yt-mobile-search-box {
        display: flex;
        flex: 1;
        align-items: center;
        background-color: #0f0f0f !important;
        border: 1px solid #303030 !important;
        border-radius: 40px !important;
        padding: 0 8px 0 12px !important;
        height: 36px;
        position: relative;
        overflow: hidden;
    }

    .yt-mobile-search-box:focus-within {
        border-color: #1c62b9 !important;
    }

    .yt-mobile-search-input {
        width: 100%;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        color: #f1f5f9 !important;
        font-size: 0.875rem !important;
        padding: 0 !important;
    }
</style>

<nav x-data="{ open: false }" class="sticky top-0 z-50 shadow-lg transition-all duration-300" style="background-color: #0b0f19 !important; border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo & Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <x-application-logo class="block h-8 w-auto transition-transform group-hover:scale-105" />
                        <span class="text-white font-extrabold text-lg tracking-wider transition-colors hidden min-[480px]:inline" style="background: linear-gradient(to right, #ffffff, #e2e8f0, #c7d2fe); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Edustream
                        </span>
                    </a>
                </div>
            </div>

            <!-- Search Bar (YouTube Style) -->
            <div class="hidden sm:flex items-center justify-center flex-1 max-w-xl mx-auto px-4">
                <form action="{{ route('dashboard') }}" method="GET" class="w-full flex items-center justify-center">
                    <div class="yt-search-container">
                        <div class="yt-search-box">
                            <!-- Input Search Icon (Hidden until focused) -->
                            <span class="yt-input-search-icon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Telusuri" class="yt-search-input">
                            @if (request('search'))
                                <a href="{{ route('dashboard') }}" class="yt-clear-btn" title="Bersihkan Pencarian">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="yt-search-btn" title="Cari">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 19px; height: 19px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
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
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition-all shadow-md flex items-center justify-center text-white font-bold text-sm uppercase cursor-pointer select-none overflow-hidden" style="border: 1px solid rgba(255, 255, 255, 0.12) !important;">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover" alt="{{ Auth::user()->name }}">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
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

            <!-- Mobile: Search Bar + Hamburger (YouTube style) -->
            <div class="flex items-center justify-end gap-3 flex-1 ml-4 sm:hidden">
                <!-- Mobile Search Bar -->
                <form action="{{ route('dashboard') }}" method="GET" class="w-full max-w-[180px] min-[400px]:max-w-[240px] flex items-center justify-center">
                    <div class="yt-mobile-search-container">
                        <div class="yt-mobile-search-box">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Telusuri" class="yt-mobile-search-input">
                            @if (request('search'))
                                <a href="{{ route('dashboard') }}" class="yt-clear-btn" style="position: relative; right: auto; padding: 4px;" title="Bersihkan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            @endif
                            <button type="submit" class="text-slate-400 hover:text-white ml-2 flex items-center justify-center" title="Cari">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
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
