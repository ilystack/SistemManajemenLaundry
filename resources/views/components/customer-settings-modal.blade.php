{{-- Customer Settings Modal - Floating --}}
<div x-show="showSettingsModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="customer-settings-modal" role="dialog" aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="showSettingsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showSettingsModal = false">
    </div>

    {{-- Modal --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="showSettingsModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Pengaturan</h3>
                            <p class="text-sm text-blue-100 mt-0.5">Kelola akun Anda</p>
                        </div>
                    </div>
                    <button @click="showSettingsModal = false" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-6">
                {{-- Change Password Section --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        Ubah Password
                    </h4>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-3">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Password Lama
                            </label>
                            <input type="password" name="current_password" required
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Password Baru
                            </label>
                            <input type="password" name="password" required
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-sm transition-colors">
                            Ubah Password
                        </button>
                    </form>
                </div>

                {{-- Delete Account Section --}}
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <h4 class="text-sm font-semibold text-red-700 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Zona Bahaya
                    </h4>
                    <p class="text-xs text-red-600 mb-3">
                        Setelah akun dihapus, semua data akan hilang secara permanen.
                    </p>

                    <form method="POST" action="{{ route('profile.destroy') }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold text-sm transition-colors">
                            Hapus Akun
                        </button>
                    </form>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <button @click="showSettingsModal = false"
                    class="w-full px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>