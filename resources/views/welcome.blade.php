<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    <title>Almas Laundry - #1 Laundry Cepat, Bersih, dan Wangi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            width: 100%;
        }

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .slide-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .slide-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .slide-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .scale-in {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .scale-in.visible {
            opacity: 1;
            transform: scale(1);
        }

        .scale-in:nth-child(1) {
            transition-delay: 0s;
        }

        .scale-in:nth-child(2) {
            transition-delay: 0.1s;
        }

        .scale-in:nth-child(3) {
            transition-delay: 0.2s;
        }

        .scale-in:nth-child(4) {
            transition-delay: 0.3s;
        }

        nav {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                background-color 0.3s ease,
                box-shadow 0.3s ease;
            backdrop-filter: blur(10px);
            background-color: transparent;
        }

        nav.scrolled {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        nav.nav-hidden {
            transform: translateY(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 1, 1);
        }

        nav.nav-visible {
            transform: translateY(0);
            transition: transform 0.4s cubic-bezier(0, 0, 0.2, 1);
        }

        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .mobile-menu.open {
            max-height: 300px;
            transition: max-height 0.3s ease-in;
        }

        /* Icon pulse animation */
        @keyframes iconPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .service-icon {
            animation: iconPulse 2s ease-in-out infinite;
        }

        .service-card:hover .service-icon {
            animation: none;
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }

        /* Loading Overlay Styles */
        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #loadingOverlay.show {
            display: flex;
            opacity: 1;
        }

        #loadingOverlay dotlottie-wc {
            width: 200px;
            height: 200px;
        }

        /* Responsive Loading Sizes */
        @media (max-width: 1024px) {
            #loadingOverlay dotlottie-wc {
                width: 150px;
                height: 150px;
            }
        }

        @media (max-width: 640px) {
            #loadingOverlay dotlottie-wc {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-b from-blue-50 to-white">
    <nav class="shadow-sm fixed w-full top-0 z-50 transition-transform duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="/assets/logoalmas.png" alt="ALMAS LAUNDRY Logo" class="h-10 sm:h-12 object-contain">
                </div>
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                    <a href="#layanan"
                        class="text-gray-700 hover:text-blue-600 transition text-sm lg:text-base">Layanan</a>
                    <a href="#harga" class="text-gray-700 hover:text-blue-600 transition text-sm lg:text-base">Harga</a>
                    <a href="#testimoni"
                        class="text-gray-700 hover:text-blue-600 transition text-sm lg:text-base">Testimoni</a>
                    <a href="#jam-operasional"
                        class="text-gray-700 hover:text-blue-600 transition text-sm lg:text-base">Jam Operasional</a>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <button onclick="window.location.href = '{{ route('admin.dashboard') }}';"
                                class="bg-blue-500 text-white px-4 py-2 lg:px-6 lg:py-2 rounded-lg hover:bg-blue-600 transition text-sm lg:text-base">
                                Pesan Sekarang
                            </button>
                        @elseif(auth()->user()->role === 'karyawan')
                            <button onclick="window.location.href = '{{ route('karyawan.dashboard') }}';"
                                class="bg-blue-500 text-white px-4 py-2 lg:px-6 lg:py-2 rounded-lg hover:bg-blue-600 transition text-sm lg:text-base">
                                Pesan Sekarang
                            </button>
                        @else
                            <button onclick="window.location.href = '{{ route('customer.dashboard') }}';"
                                class="bg-blue-500 text-white px-4 py-2 lg:px-6 lg:py-2 rounded-lg hover:bg-blue-600 transition text-sm lg:text-base">
                                Pesan Sekarang
                            </button>
                        @endif
                    @else
                        <button onclick="window.location.href = '/login';"
                            class="bg-blue-500 text-white px-4 py-2 lg:px-6 lg:py-2 rounded-lg hover:bg-blue-600 transition text-sm lg:text-base">
                            Pesan Sekarang
                        </button>
                    @endauth
                </div>
                <button id="menuBtn" class="md:hidden text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div id="mobileMenu" class="mobile-menu md:hidden bg-white mt-4 rounded-lg shadow-lg">
                <div class="py-4 px-4 space-y-4">
                    <a href="#layanan" class="block text-gray-700 hover:text-blue-600 transition">Layanan</a>
                    <a href="#harga" class="block text-gray-700 hover:text-blue-600 transition">Harga</a>
                    <a href="#testimoni" class="block text-gray-700 hover:text-blue-600 transition">Testimoni</a>
                    <a href="#jam-operasional" class="block text-gray-700 hover:text-blue-600 transition">Jam
                        Operasional</a>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <button onclick="window.location.href = '{{ route('admin.dashboard') }}';"
                                class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                Pesan Sekarang
                            </button>
                        @elseif(auth()->user()->role === 'karyawan')
                            <button onclick="window.location.href = '{{ route('karyawan.dashboard') }}';"
                                class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                Pesan Sekarang
                            </button>
                        @else
                            <button onclick="window.location.href = '{{ route('customer.dashboard') }}';"
                                class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                Pesan Sekarang
                            </button>
                        @endif
                    @else
                        <button onclick="window.location.href = '/login';"
                            class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            Pesan Sekarang
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section class="min-h-screen flex items-center pt-16 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto w-full">
            <div class="grid md:grid-cols-2 gap-8 lg:gap-12 items-center">
                <div class="slide-in-left text-center md:text-left">
                    <h1
                        class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-gray-900 mb-4 sm:mb-6 leading-tight">
                        Laundry Cepat,<br>
                        <span class="text-blue-600">Bersih</span>, dan<br>
                        <span class="text-blue-600">Wangi</span>
                    </h1>
                    <p
                        class="text-base sm:text-lg md:text-xl text-gray-600 mb-6 sm:mb-8 leading-relaxed font-normal max-w-lg mx-auto md:mx-0">
                        Layanan laundry profesional dengan harga transparan dan tracking status real-time.
                    </p>
                    <button onclick="document.getElementById('layanan').scrollIntoView({behavior: 'smooth'})"
                        class="bg-blue-500 text-white px-6 py-3 sm:px-8 sm:py-3 rounded-lg text-base sm:text-lg font-semibold hover:bg-blue-600 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                        Jelajahi
                    </button>
                </div>

                <div class="slide-in-right relative mt-8 md:mt-0">
                    <div
                        class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8">
                        <div class="relative">
                            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg">
                                <div class="flex items-center space-x-2 sm:space-x-4 mb-3 sm:mb-4">
                                    <div class="w-10 h-3 sm:w-16 sm:h-4 bg-gray-200 rounded"></div>
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300"></div>
                                    <div class="flex space-x-1 sm:space-x-2">
                                        <div class="w-8 h-2 sm:w-12 sm:h-3 bg-gray-200 rounded"></div>
                                    </div>
                                </div>
                                <div
                                    class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 mx-auto bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center shadow-inner relative overflow-hidden">

                                    <img src="/assets/heroimg.png" alt="Hero Laundry"
                                        class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 object-contain" id="spinner">
                                </div>
                                <div class="mt-3 sm:mt-4 flex justify-center space-x-1 sm:space-x-2">
                                    <div class="w-12 h-1 sm:w-16 sm:h-2 bg-gray-200 rounded"></div>
                                </div>
                            </div>

                            <div
                                class="absolute -top-4 -right-2 sm:-top-6 sm:-right-4 bg-white rounded-xl sm:rounded-2xl p-2 sm:p-4 shadow-lg">
                                <div class="flex space-x-1 sm:space-x-2">
                                    <div class="w-8 h-10 sm:w-10 sm:h-12 lg:w-12 lg:h-16 bg-blue-200 rounded-lg"></div>
                                    <div class="w-8 h-10 sm:w-10 sm:h-12 lg:w-12 lg:h-16 bg-gray-200 rounded-lg"></div>
                                    <div class="w-8 h-10 sm:w-10 sm:h-12 lg:w-12 lg:h-16 bg-blue-300 rounded-lg"></div>
                                </div>
                            </div>

                            <div
                                class="absolute bottom-0 -left-4 sm:-left-8 bg-white rounded-lg sm:rounded-xl p-2 sm:p-3 shadow-lg">
                                <div class="space-y-1 sm:space-y-2">
                                    <div class="flex space-x-1">
                                        <div class="w-6 h-2 sm:w-8 sm:h-3 bg-blue-100 rounded"></div>
                                        <div class="w-6 h-2 sm:w-8 sm:h-3 bg-gray-100 rounded"></div>
                                    </div>
                                    <div class="flex space-x-1">
                                        <div class="w-6 h-2 sm:w-8 sm:h-3 bg-gray-100 rounded"></div>
                                        <div class="w-6 h-2 sm:w-8 sm:h-3 bg-blue-200 rounded"></div>
                                    </div>
                                    <div class="flex space-x-1">
                                        <div class="w-6 h-2 sm:w-8 sm:h-3 bg-blue-100 rounded"></div>
                                        <div class="w-6 h-2 sm:w-8 sm:h-3 bg-gray-100 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-20 lg:py-24">
        <div class="text-center mb-12 sm:mb-16 fade-in">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-3 sm:mb-4">Layanan Kami
            </h2>
            <p class="text-gray-600 text-base sm:text-lg md:text-xl max-w-2xl mx-auto">Berbagai pilihan layanan untuk
                kebutuhan laundry Anda</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            <div
                class="service-card bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 sm:hover:-translate-y-2 scale-in">
                <div
                    class="service-icon w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-4 sm:mb-6">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Cuci Express</h3>
                <p class="text-sm sm:text-base text-gray-600">Laundry selesai dalam 6 jam. Cocok untuk kebutuhan
                    mendesak.</p>
            </div>

            <div
                class="service-card bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 sm:hover:-translate-y-2 scale-in">
                <div
                    class="service-icon w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-4 sm:mb-6">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Cuci Setrika</h3>
                <p class="text-sm sm:text-base text-gray-600">Pakaian dicuci bersih dan disetrika rapi. Siap pakai
                    langsung.</p>
            </div>

            <div
                class="service-card bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 sm:hover:-translate-y-2 scale-in sm:col-span-2 lg:col-span-1">
                <div
                    class="service-icon w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center mb-4 sm:mb-6">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Antar Jemput</h3>
                <p class="text-sm sm:text-base text-gray-600">Gratis layanan antar jemput untuk area tertentu.</p>
            </div>
        </div>
    </section>

    <section id="harga" class="bg-blue-50 py-16 sm:py-20 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 sm:mb-16 fade-in">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-3 sm:mb-4">Harga
                    Transparan</h2>
                <p class="text-gray-600 text-base sm:text-lg md:text-xl">Tidak ada biaya tersembunyi</p>
            </div>

            @if($paketKg->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @php
                        $sortedPakets = $paketKg->sortBy('is_express');
                        $middleIndex = 1; 
                    @endphp

                    @foreach($sortedPakets as $index => $paket)
                        @php
                            $isPopular = $index === $middleIndex || (!$paket->is_express && $sortedPakets->count() === 1);
                            $cardClass = $isPopular
                                ? 'bg-blue-600 shadow-lg transform hover:shadow-2xl sm:col-span-2 lg:col-span-1'
                                : 'bg-white shadow-sm hover:shadow-xl';
                        @endphp

                        <div class="{{$cardClass}} p-6 sm:p-8 rounded-xl sm:rounded-2xl transition-all duration-300 scale-in">
                            @if($isPopular)
                                <div class="text-white text-xs sm:text-sm font-bold mb-3 sm:mb-4">PALING POPULER</div>
                            @endif

                            <h3 class="text-lg sm:text-xl font-bold {{ $isPopular ? 'text-white' : 'text-gray-900' }} mb-2">
                                {{ $paket->nama }}
                            </h3>

                            <div
                                class="text-3xl sm:text-4xl lg:text-5xl font-black {{ $isPopular ? 'text-white' : 'text-blue-600' }} mb-4 sm:mb-6">
                                Rp {{ number_format($paket->harga, 0, ',', '.') }}
                                <span
                                    class="text-sm sm:text-lg {{ $isPopular ? 'text-blue-200' : 'text-gray-600' }} font-normal">/kg</span>
                            </div>

                            <ul
                                class="space-y-2 sm:space-y-3 text-sm sm:text-base {{ $isPopular ? 'text-white' : 'text-gray-600' }}">
                                @if($paket->is_express)
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Selesai {{ $paket->estimasi_hari }} hari
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Cuci + setrika
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Prioritas
                                    </li>
                                @else
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Cuci bersih
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Disetrika rapi
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Estimasi {{ $paket->estimasi_hari }} hari
                                    </li>
                                @endif

                                @if($paket->keterangan)
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $paket->keterangan }}
                                    </li>
                                @endif
                            </ul>

                            @if($isPopular)
                                <button onclick="scrollToContact()"
                                    class="w-full mt-4 sm:mt-6 bg-white text-blue-600 py-2 sm:py-3 rounded-lg font-semibold hover:bg-blue-50 transition text-sm sm:text-base">
                                    Pilih Paket
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Paket Kiloan</h3>
                    <p class="text-gray-600">Paket kiloan akan segera hadir!</p>
                </div>
            @endif
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-20 lg:py-24">
        <div class="text-center mb-12 sm:mb-16 fade-in">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-3 sm:mb-4">
                Atau Mau Cuci Satuan?
            </h2>
            <p class="text-3xl sm:text-4xl md:text-5xl font-black text-blue-600 mb-3">Bisa Banget!</p>
            <p class="text-gray-600 text-base sm:text-lg md:text-xl max-w-2xl mx-auto">
                Pilih layanan sesuai kebutuhan kamu, mulai dari cuci saja sampai kilat!
            </p>
        </div>

        @if($paketPcs->count() > 0)
            <div class="space-y-8">
                @php
                    $groupedPakets = $paketPcs->groupBy('nama');
                @endphp

                @foreach($groupedPakets as $namaPaket => $pakets)
                    <div
                        class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 scale-in">
                        <div class="mb-6">
                            <h3 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">
                                {{ $namaPaket }}
                            </h3>
                            <p class="text-gray-600 text-sm sm:text-base">Pilih jenis layanan yang kamu butuhkan</p>
                        </div>

                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            @foreach($pakets as $paket)
                                @php
                                    $isPopular = $paket->jenis_layanan === 'cuci_setrika';
                                    $cardClass = $isPopular
                                        ? 'bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg border-2 border-blue-500'
                                        : 'bg-white border-2 border-gray-200 hover:border-blue-300';
                                @endphp

                                <div
                                    class="{{ $cardClass }} p-5 sm:p-6 rounded-xl transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                                    @if($isPopular)
                                        <div class="inline-block bg-white text-blue-600 px-3 py-1 rounded-full text-xs font-bold mb-3">
                                            ⭐ POPULER
                                        </div>
                                    @endif

                                    <div class="mb-4">
                                        <h4
                                            class="text-lg sm:text-xl font-bold {{ $isPopular ? 'text-white' : 'text-gray-900' }} mb-1">
                                            {{ $paket->jenis_layanan_label }}
                                        </h4>
                                        <div
                                            class="text-3xl sm:text-4xl font-black {{ $isPopular ? 'text-white' : 'text-blue-600' }}">
                                            Rp {{ number_format($paket->harga, 0, ',', '.') }}
                                            <span
                                                class="text-sm {{ $isPopular ? 'text-blue-200' : 'text-gray-600' }} font-normal">/pcs</span>
                                        </div>
                                    </div>

                                    <ul class="space-y-2 text-sm {{ $isPopular ? 'text-white' : 'text-gray-600' }}">
                                        @if($paket->jenis_layanan === 'cuci_saja')
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Cuci bersih
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Dikeringkan
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $paket->estimasi_hari }} hari
                                            </li>
                                        @elseif($paket->jenis_layanan === 'cuci_setrika')
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Cuci bersih
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Disetrika rapi
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $paket->estimasi_hari }} hari
                                            </li>
                                        @elseif($paket->jenis_layanan === 'kilat')
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Selesai 3 jam
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Cuci + setrika
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-4 h-4 {{ $isPopular ? 'text-white' : 'text-blue-600' }} mr-2 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Prioritas
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Paket Satuan</h3>
                <p class="text-gray-600">Paket satuan akan segera hadir!</p>
            </div>
        @endif
    </section>

    <section id="testimoni" class="px-4 sm:px-6 py-16 sm:py-20 lg:py-24" style="background-color: #fffafa;">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12 sm:mb-16 fade-in">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-3 sm:mb-4">Kata
                    Pelanggan</h2>
                <p class="text-gray-600 text-base sm:text-lg md:text-xl">Testimoni dari pelanggan setia kami</p>
            </div>

            @if($testimonials->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($testimonials as $testimonial)
                        <div
                            class="bg-white p-5 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 scale-in">
                            <div class="flex items-center mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                                <span class="ml-2 text-xs text-gray-500">({{ $testimonial->rating }}/5)</span>
                            </div>

                            <div class="flex items-center mb-3 sm:mb-4">
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-base">
                                    {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="font-bold text-gray-900 text-sm sm:text-base">{{ $testimonial->user->name }}
                                    </div>
                                    <div class="text-xs sm:text-sm text-gray-500">{{ $testimonial->order->paket->nama }}</div>
                                </div>
                            </div>

                            @if($testimonial->comment)
                                <p class="text-sm sm:text-base text-gray-600 italic">"{{ $testimonial->comment }}"</p>
                            @else
                                <p class="text-sm sm:text-base text-gray-400 italic">Pelanggan memberikan {{ $testimonial->rating }}
                                    bintang</p>
                            @endif

                            <p class="text-xs text-gray-400 mt-3">{{ $testimonial->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Testimoni</h3>
                    <p class="text-gray-600">Jadilah yang pertama memberikan penilaian!</p>
                </div>
            @endif
        </div>
    </section>

    <section id="jam-operasional" class="bg-blue-50 py-16 sm:py-20 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 sm:mb-16 fade-in">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-3 sm:mb-4">Jam
                    Operasional</h2>
                <p class="text-gray-600 text-base sm:text-lg md:text-xl">Kami siap melayani kebutuhan laundry Anda</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto">
                <div
                    class="bg-white p-8 sm:p-10 rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 scale-in">
                    <div class="flex items-center justify-center mb-6">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="text-center">
                        <div
                            class="inline-block bg-green-100 text-green-700 px-4 py-1 rounded-full text-xs sm:text-sm font-bold mb-4">
                            BUKA
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-3">Senin - Sabtu</h3>
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <div class="text-center">
                                <p class="text-4xl sm:text-5xl font-black text-blue-600">07:00</p>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Buka</p>
                            </div>
                            <div class="text-2xl sm:text-3xl text-gray-400 font-bold">-</div>
                            <div class="text-center">
                                <p class="text-4xl sm:text-5xl font-black text-blue-600">16:00</p>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Tutup</p>
                            </div>
                        </div>
                        <p class="text-sm sm:text-base text-gray-600">
                            <span class="font-semibold">9 jam</span> pelayanan setiap hari kerja
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white p-8 sm:p-10 rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 scale-in">
                    <div class="flex items-center justify-center mb-6">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-red-400 to-red-500 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="text-center">
                        <div
                            class="inline-block bg-red-100 text-red-700 px-4 py-1 rounded-full text-xs sm:text-sm font-bold mb-4">
                            TUTUP
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-3">Minggu</h3>
                        <div class="flex items-center justify-center mb-4">
                            <p class="text-4xl sm:text-5xl font-black text-gray-400">Libur</p>
                        </div>
                        <p class="text-sm sm:text-base text-gray-600">
                            Kami tutup untuk <span class="font-semibold">istirahat</span>
                        </p>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-xs sm:text-sm text-gray-500">
                                💡 <span class="font-medium">Tips:</span> Antar laundry kamu di hari Sabtu!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 sm:mt-12 text-center fade-in">
                <div class="inline-block bg-white px-6 py-4 rounded-xl shadow-sm">
                    <p class="text-sm sm:text-base text-gray-600">
                        <svg class="w-5 h-5 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-semibold">Catatan:</span> Untuk layanan express, mohon antar sebelum jam 12:00
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="bg-blue-600 py-16 sm:py-20 lg:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center fade-in">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white mb-3 sm:mb-4">Siap Mencoba
                Layanan Kami?</h2>
            <p class="text-blue-100 text-base sm:text-lg md:text-xl mb-6 sm:mb-8 max-w-2xl mx-auto">Pesan sekarang dan
                rasakan kemudahan laundry profesional</p>
            @auth
                @if(auth()->user()->role === 'admin')
                    <button onclick="window.location.href = '{{ route('admin.dashboard') }}';"
                        class="bg-white text-blue-600 px-6 py-3 sm:px-8 sm:py-3 rounded-lg text-base sm:text-lg font-semibold hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                        Pesan Sekarang
                    </button>
                @elseif(auth()->user()->role === 'karyawan')
                    <button onclick="window.location.href = '{{ route('karyawan.dashboard') }}';"
                        class="bg-white text-blue-600 px-6 py-3 sm:px-8 sm:py-3 rounded-lg text-base sm:text-lg font-semibold hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                        Pesan Sekarang
                    </button>
                @else
                    <button onclick="window.location.href = '{{ route('customer.dashboard') }}';"
                        class="bg-white text-blue-600 px-6 py-3 sm:px-8 sm:py-3 rounded-lg text-base sm:text-lg font-semibold hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                        Pesan Sekarang
                    </button>
                @endif
            @else
                <button onclick="window.location.href = '{{ route('login') }}';"
                    class="bg-white text-blue-600 px-6 py-3 sm:px-8 sm:py-3 rounded-lg text-base sm:text-lg font-semibold hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                    Pesan Sekarang
                </button>
            @endauth
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-300 py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-800">
                <div>
                    <h3 class="text-white text-2xl sm:text-3xl font-black mb-2">Almas Laundry</h3>
                    <p class="text-gray-400 text-sm sm:text-base mb-4">Layanan Laundry Profesional</p>
                    <div class="space-y-2 text-sm">
                        <p class="text-gray-400">Jl. Raja Elon no 34</p>
                        <p class="text-gray-400">Bekasi, Jawa Barat 17110</p>
                        <p class="text-gray-400">Indonesia</p>
                    </div>
                </div>
                <div class="md:text-right">
                    <h4 class="text-white font-bold mb-4 text-sm sm:text-base">IKUTI KAMI</h4>
                    <div class="flex md:justify-end gap-4">
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="w-10 h-10 bg-gray-800 hover:bg-green-600 rounded-full flex items-center justify-center transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                        </a>
                        <a href="mailto:info@almaslaundry.com"
                            class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-full flex items-center justify-center transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                        </a>
                        <a href="https://www.tiktok.com/@almaslaundry" target="_blank"
                            class="w-10 h-10 bg-gray-800 hover:bg-black rounded-full flex items-center justify-center transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/almaslaundry" target="_blank"
                            class="w-10 h-10 bg-gray-800 hover:bg-pink-600 rounded-full flex items-center justify-center transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center text-sm text-gray-400">
                <div class="flex flex-wrap justify-center items-center gap-2">
                    <a href="#layanan" class="hover:text-white transition">Layanan</a>
                    <span>|</span>
                    <a href="#harga" class="hover:text-white transition">Harga</a>
                    <span>|</span>
                    <a href="#testimoni" class="hover:text-white transition">Testimoni</a>
                    <span>|</span>
                    <a href="#jam-operasional" class="hover:text-white transition">Jam Operasional</a>
                    <span>|</span>
                    <span>Copyright &copy; 2024 Almas Laundry</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Loading Overlay -->
    <div id="loadingOverlay">
        <dotlottie-wc src="https://lottie.host/4d0dfcd1-a0f2-4333-9db3-3a0e8a0383c6/hk6aLPbhTy.lottie" autoplay loop>
        </dotlottie-wc>
    </div>

    <script>
        // ==================== LOADING HELPER FUNCTIONS ====================

        /**
         * Show loading overlay
         */
        window.showLoading = function () {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.add('show');
            }
        };

        /**
         * Hide loading overlay
         */
        window.hideLoading = function () {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.remove('show');
            }
        };

        /**
         * Redirect with loading animation
         * @param {string} url - URL to redirect to
         */
        window.loadingRedirect = function (url) {
            // Set flag to indicate this is a forward navigation
            sessionStorage.setItem('isForwardNavigation', 'true');
            showLoading();
            setTimeout(() => {
                window.location.href = url;
            }, 100);
        };

        // Hide loading immediately if this is a back/forward navigation
        (function () {
            const navigationType = performance.getEntriesByType('navigation')[0]?.type;
            const isForwardNav = sessionStorage.getItem('isForwardNavigation') === 'true';

            // If it's back_forward navigation and NOT a forward nav we initiated, hide loading
            if (navigationType === 'back_forward' && !isForwardNav) {
                hideLoading();
            }

            // Clear the flag after checking
            sessionStorage.removeItem('isForwardNavigation');
        })();

        // Auto-detect and add loading to all forms
        document.addEventListener('DOMContentLoaded', function () {
            // Handle all form submissions
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    // Only show loading if form is valid
                    if (this.checkValidity()) {
                        sessionStorage.setItem('isForwardNavigation', 'true');
                        showLoading();
                    }
                });
            });

            // Handle links with data-loading attribute
            document.querySelectorAll('a[data-loading], button[data-loading]').forEach(element => {
                element.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    const onclick = this.getAttribute('onclick');

                    // If it's a link with href (not # or javascript:)
                    if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                        e.preventDefault();
                        loadingRedirect(href);
                    }
                    // If it has onclick that includes location.href
                    else if (onclick && onclick.includes('location.href')) {
                        sessionStorage.setItem('isForwardNavigation', 'true');
                        showLoading();
                    }
                });
            });

            // Update all "Pesan Sekarang" buttons to use loading
            document.querySelectorAll('button[onclick*="location.href"]').forEach(button => {
                const originalOnclick = button.getAttribute('onclick');
                button.setAttribute('onclick', `sessionStorage.setItem('isForwardNavigation', 'true'); showLoading(); ${originalOnclick}`);
            });
        });

        // Handle browser back/forward button and page restore from cache
        window.addEventListener('pageshow', function (event) {
            // If page is restored from cache (bfcache)
            if (event.persisted) {
                hideLoading();
                sessionStorage.removeItem('isForwardNavigation');
            }
        });

        // Additional safety: hide loading on popstate (back/forward button)
        window.addEventListener('popstate', function () {
            hideLoading();
            sessionStorage.removeItem('isForwardNavigation');
        });

        // ==================== EXISTING SCRIPTS ====================

        const observerOptions = {
            threshold: 0.25,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right, .scale-in').forEach((el) => {
            observer.observe(el);
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    mobileMenu.classList.remove('open');
                }
            });
        });

        const spinner = document.getElementById('spinner');
        let rotation = 0;
        setInterval(() => {
            rotation += 2;
            spinner.style.transform = `rotate(${rotation}deg)`;
        }, 50);

        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        let menuOpen = false;

        menuBtn.addEventListener('click', () => {
            if (menuOpen) {
                mobileMenu.classList.remove('open');
                menuOpen = false;
            } else {
                mobileMenu.classList.add('open');
                menuOpen = true;
            }
        });

        document.addEventListener('click', (e) => {
            if (menuOpen && !menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('open');
                menuOpen = false;
            }
        });

        let lastScrollTop = 0;
        const navbar = document.querySelector('nav');

        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            if (scrollTop > lastScrollTop && scrollTop > 100) {
                navbar.classList.add('nav-hidden');
                navbar.classList.remove('nav-visible');
            } else {
                navbar.classList.remove('nav-hidden');
                navbar.classList.add('nav-visible');
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });

        function openWhatsApp() {
            const phoneNumber = "6281234567890";
            const message = "Halo Almas Laundry, saya ingin memesan layanan laundry";
            const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }

        function scrollToContact() {
            document.getElementById('kontak').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            if (menuOpen) {
                mobileMenu.classList.remove('open');
                menuOpen = false;
            }
        }
    </script>
</body>

</html>