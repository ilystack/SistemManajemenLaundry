<!DOCTYPE html>
<html lang="id" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    <title>Dashboard Customer - Almas Laundry</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }

        .fc {
            font-family: 'Inter', sans-serif;
        }

        .fc .fc-button {
            background-color: #2563eb;
            border-color: #2563eb;
            text-transform: capitalize;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        .fc .fc-button:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .fc-daygrid-day-number {
            color: #374151;
            font-weight: 500;
        }

        .fc-day-today {
            background-color: #dbeafe !important;
        }

        .fc .fc-col-header-cell-cushion {
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-cyan-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen" 
    x-data="{ 
        showModal: false, 
        showSettingsModal: false, 
        showProfileModal: false,
        showRatingModal: false,
        selectedOrderId: null,
        selectedRating: 0,
        ratingComment: '',
        ratingError: '',
        isSubmittingRating: false,
        isDark: localStorage.getItem('darkMode') === 'true',
        toggleDarkMode() {
            this.isDark = !this.isDark;
            localStorage.setItem('darkMode', this.isDark);
            const html = document.documentElement;
            if (this.isDark) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        },
        openRatingModal(orderId) {
            this.selectedOrderId = orderId;
            this.selectedRating = 0;
            this.ratingComment = '';
            this.ratingError = '';
            this.showRatingModal = true;
        },
        async submitRating() {
            if (this.selectedRating === 0) {
                this.ratingError = 'Silakan pilih rating terlebih dahulu!';
                return;
            }

            this.isSubmittingRating = true;
            this.ratingError = '';

            try {
                const response = await fetch('{{ route('testimonial.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: this.selectedOrderId,
                        rating: this.selectedRating,
                        comment: this.ratingComment
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.showRatingModal = false;
                    window.location.reload();
                } else {
                    this.ratingError = data.message || 'Gagal mengirim penilaian!';
                }
            } catch (error) {
                this.ratingError = 'Terjadi kesalahan. Silakan coba lagi!';
            } finally {
                this.isSubmittingRating = false;
            }
        }
    }"
    x-init="
        const html = document.documentElement;
        if (isDark) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
    ">
    @include('components.toast')

    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logoalmas.png') }}" alt="Almas Laundry" class="h-10 w-auto">
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Halo, {{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold shadow-md hover:shadow-lg transition cursor-pointer">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50"
                            style="display: none;">

                            <button @click="showProfileModal = true"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition w-full text-left">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Edit Profil</span>
                            </button>

                            <button @click="showSettingsModal = true"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition w-full text-left">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Pengaturan</span>
                            </button>

                            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">Tema</p>
                                <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 rounded-lg p-1 relative">
                                    <div class="absolute top-1 bottom-1 left-1 w-[calc(50%-6px)] bg-white dark:bg-gray-600 rounded-md shadow-sm transition-transform duration-300 ease-in-out"
                                        :style="isDark ? 'transform: translateX(calc(100% + 4px))' : 'transform: translateX(0)'"></div>
                                    
                                    <button @click="if(isDark) toggleDarkMode()" 
                                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-md transition-colors relative z-10"
                                        :class="!isDark ? 'text-gray-900' : 'text-gray-500 dark:text-gray-400'">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" 
                                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" 
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-xs font-medium">Light</span>
                                    </button>
                                    
                                    <button @click="if(!isDark) toggleDarkMode()" 
                                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-md transition-colors relative z-10"
                                        :class="isDark ? 'text-gray-900 dark:text-white' : 'text-gray-500'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                        </svg>
                                        <span class="text-xs font-medium">Dark</span>
                                    </button>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition w-full text-left">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Kalender</h2>
                <div id="calendar"></div>
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Orderanku</h2>
                    <button @click="showModal = true"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Order</span>
                    </button>
                </div>

                @php
                    $userId = Auth::id();
                    $menungguCount = \App\Models\Order::where('user_id', $userId)->where('status', 'menunggu')->count();
                    $prosesCount = \App\Models\Order::where('user_id', $userId)->where('status', 'diproses')->count();
                    $selesaiCount = \App\Models\Order::where('user_id', $userId)->whereIn('status', ['selesai', 'diambil'])->count();
                @endphp

                <div x-data="{ selectedStatus: 'menunggu' }" class="space-y-6">
                    <div class="grid grid-cols-3 gap-4">
                        <button @click="selectedStatus = 'menunggu'"
                            :class="selectedStatus === 'menunggu' ? 'ring-2 ring-yellow-500' : ''"
                            class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-200 hover:shadow-md transition cursor-pointer text-left">
                            <p class="text-xs text-yellow-700 font-medium mb-1">Menunggu</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $menungguCount }}</p>
                        </button>
                        <button @click="selectedStatus = 'diproses'"
                            :class="selectedStatus === 'diproses' ? 'ring-2 ring-blue-500' : ''"
                            class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200 hover:shadow-md transition cursor-pointer text-left">
                            <p class="text-xs text-blue-700 font-medium mb-1">Proses</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $prosesCount }}</p>
                        </button>
                        <button @click="selectedStatus = 'selesai'"
                            :class="selectedStatus === 'selesai' ? 'ring-2 ring-green-500' : ''"
                            class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200 hover:shadow-md transition cursor-pointer text-left">
                            <p class="text-xs text-green-700 font-medium mb-1">Selesai</p>
                            <p class="text-2xl font-bold text-green-900">{{ $selesaiCount }}</p>
                        </button>
                    </div>

                    <div class="px-8">
                        <div class="border-t-2 border-gray-300"></div>
                    </div>

                    <div class="space-y-3">
                        @php
                            $menungguOrders = \App\Models\Order::where('user_id', $userId)
                                ->where('status', 'menunggu')
                                ->with('paket')
                                ->latest()
                                ->get();

                            $prosesOrders = \App\Models\Order::where('user_id', $userId)
                                ->where('status', 'diproses')
                                ->with('paket')
                                ->latest()
                                ->get();

                            $selesaiOrders = \App\Models\Order::where('user_id', $userId)
                                ->whereIn('status', ['selesai', 'diambil'])
                                ->with('paket')
                                ->latest()
                                ->get();
                        @endphp

                        <div x-show="selectedStatus === 'menunggu'">
                            @if($menungguOrders->count() > 0)
                                @foreach($menungguOrders as $order)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition bg-white dark:bg-gray-800">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $order->paket->nama }}</h4>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y') }} •
                                                        {{ $order->jumlah }} {{ $order->paket->satuan }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-gray-900 dark:text-white">Rp
                                                    {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                                <span
                                                    class="inline-block px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-semibold rounded-full mt-1">Menunggu</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400 font-medium">Tidak ada order menunggu</p>
                                </div>
                            @endif
                        </div>

                        <div x-show="selectedStatus === 'diproses'">
                            @if($prosesOrders->count() > 0)
                                @foreach($prosesOrders as $order)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition bg-white dark:bg-gray-800">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $order->paket->nama }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }} •
                                                        {{ $order->jumlah }} {{ $order->paket->satuan }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-gray-900">Rp
                                                    {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                                <span
                                                    class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-semibold rounded-full mt-1">Diproses</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400 font-medium">Tidak ada order diproses</p>
                                </div>
                            @endif
                        </div>

                        <div x-show="selectedStatus === 'selesai'">
                            @if($selesaiOrders->count() > 0)
                                @foreach($selesaiOrders as $order)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition bg-white dark:bg-gray-800">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $order->paket->nama }}</h4>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y') }} •
                                                        {{ $order->jumlah }} {{ $order->paket->satuan }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-gray-900 dark:text-white">Rp
                                                    {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                                <div class="flex items-center justify-end gap-2 mt-1">
                                                    <span
                                                        class="inline-block px-3 py-1 {{ $order->status === 'selesai' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' }} text-xs font-semibold rounded-full">
                                                        {{ $order->status === 'selesai' ? 'Selesai' : 'Diambil' }}
                                                    </span>
                                                    
                                                    @php
                                                        $hasTestimonial = \App\Models\Testimonial::where('order_id', $order->id)->exists();
                                                    @endphp
                                                    
                                                    @if(!$hasTestimonial)
                                                        <button @click="openRatingModal({{ $order->id }})"
                                                            class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-semibold rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition">
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                            Beri Penilaian
                                                        </button>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-semibold rounded-full">
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Sudah Dinilai
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400 font-medium">Tidak ada order selesai</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Riwayat Transaksi Keuangan</h2>

            @php
                $payments = \App\Models\Payment::whereHas('order', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->with('order.paket')->latest()->get();
            @endphp

            <div class="space-y-4">
                @if($payments->count() > 0)
                    @foreach($payments as $payment)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition bg-white dark:bg-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 {{ $payment->status === 'success' ? 'bg-green-100 dark:bg-green-900/30' : ($payment->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30' : 'bg-red-100 dark:bg-red-900/30') }} rounded-lg flex items-center justify-center">
                                        @if($payment->status === 'success')
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($payment->status === 'pending')
                                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">DP Penjemputan -
                                            {{ $payment->order->paket->nama }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $payment->created_at->format('d M Y, H:i') }} •
                                            {{ $payment->payment_type }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                    <span
                                        class="inline-block px-3 py-1 {{ $payment->status === 'success' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : ($payment->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400') }} text-xs font-semibold rounded-full mt-1">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-16 bg-gray-50 dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                        <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-lg font-medium text-gray-600 dark:text-gray-400">Belum ada transaksi pembayaran</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Riwayat pembayaran DP akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-gray-200">

                <div
                    class="px-6 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                        Buat Order Baru
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @include('pages.order.form-modal')
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: ''
                },
                height: 'auto',
                firstDay: 0,
                buttonText: {
                    today: 'Hari Ini'
                },
                dayHeaderFormat: { weekday: 'short' },
                events: [
                    @foreach(\App\Models\Order::where('user_id', Auth::id())->get() as $order)
                    {
                        title: '{{ $order->paket->nama ?? "Order" }}',
                        start: '{{ $order->created_at->format('Y-m-d') }}',
                        color: '#3b82f6'
                    },
                    @endforeach
                ]
            });
            calendar.render();
        });
    </script>

    {{-- Edit Profile Modal --}}
    @include('components.edit-profile-modal')

    {{-- Customer Settings Modal --}}
    @include('components.customer-settings-modal')

    {{-- Rating Modal --}}
    @include('components.rating-modal')

    {{-- Midtrans Snap Script --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</body>

</html>