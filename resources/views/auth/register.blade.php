<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    <title>Register - Almas Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-20px) scale(1.05);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-pulse-slow {
            animation: pulse 3s ease-in-out infinite;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body style="background-color: #fffafa;" class="min-h-screen flex items-center justify-center p-4 sm:p-6">
    @include('components.toast')

    <div class="w-full max-w-6xl">
        <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden animate-fadeInUp border border-gray-200">
            <div class="flex flex-col md:flex-row">

                <div
                    class="md:w-3/5 relative bg-gradient-to-br from-cyan-50 via-blue-50 to-sky-100 p-8 sm:p-10 md:p-12 lg:p-16 flex flex-col justify-between overflow-hidden min-h-[300px] md:min-h-0">
                    <div
                        class="absolute top-[8%] left-[12%] w-32 h-32 sm:w-40 sm:h-40 bg-blue-200/30 rounded-full animate-float blur-sm">
                    </div>
                    <div class="absolute top-[15%] right-[15%] w-20 h-20 sm:w-28 sm:h-28 bg-blue-300/25 rounded-full animate-float blur-sm"
                        style="animation-delay: 1s;"></div>
                    <div class="absolute bottom-[10%] left-[15%] w-48 h-48 sm:w-60 sm:h-60 bg-blue-200/30 rounded-full animate-float blur-sm"
                        style="animation-delay: 2s;"></div>
                    <div class="absolute bottom-[15%] right-[8%] w-36 h-36 sm:w-48 sm:h-48 bg-blue-300/20 rounded-full animate-float blur-sm"
                        style="animation-delay: 1.5s;"></div>

                    <div
                        class="absolute top-[35%] left-[35%] w-16 h-16 sm:w-24 sm:h-24 bg-sky-300/20 rounded-full animate-pulse-slow blur-md">
                    </div>
                    <div class="absolute bottom-[35%] right-[30%] w-24 h-24 sm:w-32 sm:h-32 bg-cyan-300/15 rounded-full animate-pulse-slow blur-md"
                        style="animation-delay: 2s;"></div>
                    <div class="absolute top-[50%] right-[50%] w-20 h-20 sm:w-28 sm:h-28 bg-blue-400/10 rounded-full animate-pulse-slow blur-lg"
                        style="animation-delay: 1s;"></div>

                    <div class="relative z-10 w-full flex justify-center -mt-10">
                        <div>
                            <img src="{{ asset('assets/logoalmas.png') }}" alt="ALMAS LAUNDRY Logo"
                                class="w-40 sm:w-48 md:w-56 h-auto">
                        </div>
                    </div>

                    <div class="relative z-10 text-center flex-grow flex flex-col justify-center">
                        <div class="mt-10">
                            <img src="{{ asset('assets/logoregister.png') }}" alt="Welcome to Almas Laundry"
                                class="w-full max-w-md mx-auto">
                        </div>
                    </div>
                </div>

                <div class="md:w-2/5 p-8 sm:p-10 lg:p-12 xl:p-16 border-l border-gray-200">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2 tracking-tight text-center">
                        BUAT AKUN BARU
                    </h1>
                    <p class="text-center text-gray-500 mb-8 sm:mb-10 text-sm">Silakan isi data diri Anda</p>

                    @if ($errors->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                @foreach ($errors->all() as $error)
                                    window.dispatchEvent(new CustomEvent('notify', {
                                        detail: {
                                            variant: 'danger',
                                            title: 'Registrasi Gagal',
                                            message: '{{ $error }}'
                                        }
                                    }));
                                @endforeach
                                                            });
                        </script>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div class="space-y-2">
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 text-base focus:outline-none focus:border-blue-400 transition-all duration-300"
                                placeholder="Nama Lengkap" required autofocus>
                        </div>

                        <div class="space-y-2">
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 text-base focus:outline-none focus:border-blue-400 transition-all duration-300"
                                placeholder="Nomor WhatsApp (contoh: 08123456789)" required pattern="[0-9]{10,13}">
                        </div>

                        <div class="space-y-2">
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 text-base focus:outline-none focus:border-blue-400 transition-all duration-300"
                                placeholder="Alamat Email" required>
                        </div>

                        <div class="space-y-2 relative">
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3 pr-12 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 text-base focus:outline-none focus:border-blue-400 transition-all duration-300"
                                placeholder="Password" required>
                            <button type="button" onclick="togglePassword('password', 'eye-icon', 'eye-slash-icon')"
                                class="absolute right-4 top-1 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200">
                                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-slash-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-2 relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-3 pr-12 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 text-base focus:outline-none focus:border-blue-400 transition-all duration-300"
                                placeholder="Konfirmasi Password" required>
                            <button type="button"
                                onclick="togglePassword('password_confirmation', 'eye-icon-confirm', 'eye-slash-icon-confirm')"
                                class="absolute right-4 top-1 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200">
                                <svg id="eye-icon-confirm" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-slash-icon-confirm" class="w-5 h-5 hidden" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        <button type="submit"
                            class="w-full mt-2 py-3.5 px-6 bg-gradient-to-r from-blue-600 to-blue-700 hover:bg-none hover:bg-transparent hover:text-blue-700 border-2 border-transparent hover:border-blue-700 text-white text-base font-semibold rounded-lg transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md">
                            DAFTAR SEKARANG
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId, slashIconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            const eyeSlashIcon = document.getElementById(slashIconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>
</body>

</html>