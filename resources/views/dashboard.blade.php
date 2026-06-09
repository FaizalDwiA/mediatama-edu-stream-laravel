<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Katalog Video EduStream') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($videos as $video)
                    @php
                        $userRequest = $requests->get($video->id);
                        $isExpired = $userRequest && $userRequest->valid_until && \Carbon\Carbon::now()->gt($userRequest->valid_until);
                    @endphp

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-full h-40 bg-gray-200 dark:bg-gray-700 rounded mb-4 flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $video->title }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1 mb-4">{{ Str::limit($video->description, 100) }}</p>
                        </div>

                        <div class="mt-4">
                            @if(!$userRequest)
                                <form action="{{ route('video.request', $video->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                        Minta Akses Menonton
                                    </button>
                                </form>
                            @elseif($userRequest->status === 'pending')
                                <button disabled class="w-full bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                    Menunggu Persetujuan Admin
                                </button>
                            @elseif($userRequest->status === 'rejected')
                                <form action="{{ route('video.request', $video->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition">
                                        Akses Ditolak - Minta Ulang
                                    </button>
                                </form>
                            @elseif($userRequest->status === 'approved' && !$isExpired)
                                <div class="mb-2 text-xs text-green-600 dark:text-green-400 font-semibold">
                                    Batas Akses: {{ $userRequest->valid_until->format('d M Y H:i') }}
                                </div>
                                <a href="{{ route('video.watch', $video->id) }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                                    Tonton Video
                                </a>
                            @elseif($isExpired)
                                <div class="mb-2 text-xs text-red-600 dark:text-red-400 font-semibold">
                                    Waktu Akses Anda Sudah Habis!
                                </div>
                                <form action="{{ route('video.request', $video->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                                        Minta Akses Ulang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-8">
                        Belum ada video yang diupload oleh Admin.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>