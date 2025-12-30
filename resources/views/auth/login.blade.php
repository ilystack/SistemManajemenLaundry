<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    <title>Login {{ ucfirst($role ?? 'Admin') }} - Almas Laundry</title>
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
    <!-- Toast Notification Component -->
    @include('components.toast')

    <!-- Main Card Container -->
    <div class="w-full max-w-6xl">
        <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden animate-fadeInUp border border-gray-200">
            <div class="flex flex-col-reverse md:flex-row">
                <!-- Left Panel - Login Form (40%) -->
                <div class="md:w-2/5 p-8 sm:p-10 lg:p-12 xl:p-16 border-r border-gray-200">
                    <!-- Title -->
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-10 tracking-tight text-center">
                        LOGIN {{ strtoupper($role ?? 'ADMIN') }}
                    </h1>
                    <br>

                    <!-- Trigger Toast for Errors -->
                    @if ($errors->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                @foreach ($errors->all() as $error)
                                    window.dispatchEvent(new CustomEvent('notify', {
                                        detail: {
                                            variant: 'danger',
                                            title: 'Login Gagal',
                                            message: '{{ $error }}'
                                        }
                                    }));
                                @endforeach
                                    });
                        </script>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="/login/{{ $role ?? 'admin' }}" class="space-y-6">
                        @csrf

                        <!-- Email/Nama Input -->
                        <div class="space-y-2">
                            <input type="email" id="email" name="email"
                                class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 text-base focus:outline-none focus:border-blue-400 transition-all duration-300"
                                placeholder="Email" required>
                        </div>

                        <!-- Password Input -->
                        <div class="space-y-2 relative">
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3.5 pr-12 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 text-base focus:outline-none focus:border-blue-400 transition-all duration-300"
                                placeholder="Password" required>
                            <!-- Toggle Password Visibility -->
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-2 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200">
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

                        <!-- Remember Me Checkbox -->
                        <div class="flex items-center pt-2">
                            <input type="checkbox" id="simpan-login" name="remember"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200">
                            <label for="simpan-login"
                                class="ml-2.5 text-sm font-medium text-gray-700 cursor-pointer select-none hover:text-gray-900 transition-colors duration-200">
                                Simpan login
                            </label>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full mt-6 py-3.5 px-6 bg-gradient-to-r from-blue-600 to-blue-700 hover:bg-none hover:bg-transparent hover:text-blue-700 border-2 border-transparent hover:border-blue-700 text-white text-base font-semibold rounded-lg transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            LOGIN
                        </button>
                    </form>
                </div>

                <!-- Right Panel - Branding (60%) -->
                <div
                    class="md:w-3/5 relative bg-gradient-to-br from-cyan-50 via-blue-50 to-sky-100 p-8 sm:p-10 md:p-12 lg:p-16 flex items-center justify-center overflow-hidden min-h-[400px] md:min-h-0">
                    <!-- Decorative Circles -->
                    <div
                        class="absolute top-[8%] right-[12%] w-32 h-32 sm:w-40 sm:h-40 bg-blue-200/30 rounded-full animate-float blur-sm">
                    </div>
                    <div class="absolute top-[15%] left-[15%] w-20 h-20 sm:w-28 sm:h-28 bg-blue-300/25 rounded-full animate-float blur-sm"
                        style="animation-delay: 1s;"></div>
                    <div class="absolute bottom-[10%] right-[15%] w-48 h-48 sm:w-60 sm:h-60 bg-blue-200/30 rounded-full animate-float blur-sm"
                        style="animation-delay: 2s;"></div>
                    <div class="absolute bottom-[15%] left-[8%] w-36 h-36 sm:w-48 sm:h-48 bg-blue-300/20 rounded-full animate-float blur-sm"
                        style="animation-delay: 1.5s;"></div>

                    <!-- Additional decorative elements -->
                    <div
                        class="absolute top-[35%] right-[35%] w-16 h-16 sm:w-24 sm:h-24 bg-sky-300/20 rounded-full animate-pulse-slow blur-md">
                    </div>
                    <div class="absolute bottom-[35%] left-[30%] w-24 h-24 sm:w-32 sm:h-32 bg-cyan-300/15 rounded-full animate-pulse-slow blur-md"
                        style="animation-delay: 2s;"></div>
                    <div class="absolute top-[50%] left-[50%] w-20 h-20 sm:w-28 sm:h-28 bg-blue-400/10 rounded-full animate-pulse-slow blur-lg"
                        style="animation-delay: 1s;"></div>

                    <!-- Branding Content -->
                    <div class="relative z-10 text-center">
                        <!-- Logo -->
                        <div class="transform hover:scale-105 transition-transform duration-500 ease-out">
                            <img src="{{ asset('assets/logoalmas.png') }}" alt="ALMAS LAUNDRY Logo"
                                class="w-56 sm:w-64 md:w-72 lg:w-80 h-auto mx-auto drop-shadow-2xl filter">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');

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