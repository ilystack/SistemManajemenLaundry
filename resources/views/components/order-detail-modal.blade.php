{{-- Order Detail Modal - Floating --}}
<div x-show="showDetailModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="order-detail-modal" role="dialog" aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="showDetailModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showDetailModal = false">
    </div>

    {{-- Modal --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="showDetailModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">

            {{-- Header --}}
            <div class="bg-blue-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                                <path
                                    d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Detail Order</h3>
                            <p class="text-sm text-blue-100 mt-0.5"
                                x-text="selectedOrder ? `#${selectedOrder.antrian}` : ''"></p>
                        </div>
                    </div>
                    <button @click="showDetailModal = false" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-6" x-show="selectedOrder">
                {{-- Customer Info --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                        </svg>
                        Informasi Customer
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Nama:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                x-text="selectedOrder?.user?.name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Email:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                x-text="selectedOrder?.user?.email"></span>
                        </div>
                        <div class="flex justify-between" x-show="selectedOrder?.user?.phone">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Telepon:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                x-text="selectedOrder?.user?.phone"></span>
                        </div>
                    </div>
                </div>

                {{-- Order Details --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 5v1H4.667a1.75 1.75 0 00-1.743 1.598l-.826 9.5A1.75 1.75 0 003.84 19h12.32a1.75 1.75 0 001.742-1.902l-.826-9.5A1.75 1.75 0 0015.333 6H14V5a4 4 0 00-8 0zm4-2.5A2.5 2.5 0 007.5 5v1h5V5A2.5 2.5 0 0010 2.5zM7.5 10a2.5 2.5 0 005 0V8.75a.75.75 0 011.5 0V10a4 4 0 01-8 0V8.75a.75.75 0 011.5 0V10z"
                                clip-rule="evenodd" />
                        </svg>
                        Detail Pesanan
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Paket:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                x-text="selectedOrder?.paket?.nama"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Jumlah:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                x-text="`${selectedOrder?.jumlah} ${selectedOrder?.paket?.satuan}`"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Pengantaran:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white capitalize"
                                x-text="selectedOrder?.pickup?.replace('_', ' ')"></span>
                        </div>
                        <div class="flex justify-between" x-show="selectedOrder?.pickup === 'dijemput'">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Jarak:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                x-text="`${selectedOrder?.jarak_km} km (Rp ${parseInt(selectedOrder?.biaya_pickup || 0).toLocaleString('id-ID')})`"></span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Total Harga:</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400"
                                x-text="`Rp ${parseInt(selectedOrder?.total_harga || 0).toLocaleString('id-ID')}`"></span>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M2.5 4A1.5 1.5 0 0 0 1 5.5V6h18v-.5A1.5 1.5 0 0 0 17.5 4h-15ZM19 8.5H1v6A1.5 1.5 0 0 0 2.5 16h15a1.5 1.5 0 0 0 1.5-1.5v-6ZM3 13.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75Zm4.75-.75a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        Metode Pembayaran
                    </h4>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-lg text-sm font-semibold"
                            :class="selectedOrder?.payment_method === 'cash' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400'"
                            x-text="selectedOrder?.payment_method === 'cash' ? '💵 Cash' : '📱 QRIS'"></span>
                    </div>
                </div>

                {{-- Status Update Buttons --}}
                <div
                    class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Update Status Order</h4>
                    <div class="grid grid-cols-3 gap-3">
                        {{-- Menunggu --}}
                        <button type="button" @click="updateOrderStatus('menunggu')"
                            :disabled="selectedOrder?.status === 'menunggu'"
                            :class="selectedOrder?.status === 'menunggu' ? 'bg-yellow-500 text-white ring-2 ring-yellow-600' : 'bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-300 hover:bg-yellow-50 dark:hover:bg-yellow-900/20'"
                            class="px-4 py-3 rounded-lg font-semibold text-sm transition-all disabled:opacity-100 border border-gray-200 dark:border-gray-600 flex flex-col items-center gap-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Menunggu</span>
                        </button>

                        {{-- Diproses --}}
                        <button type="button" @click="updateOrderStatus('diproses')"
                            :disabled="selectedOrder?.status === 'diproses'"
                            :class="selectedOrder?.status === 'diproses' ? 'bg-blue-500 text-white ring-2 ring-blue-600' : 'bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20'"
                            class="px-4 py-3 rounded-lg font-semibold text-sm transition-all disabled:opacity-100 border border-gray-200 dark:border-gray-600 flex flex-col items-center gap-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Diproses</span>
                        </button>

                        {{-- Selesai --}}
                        <button type="button" @click="updateOrderStatus('selesai')"
                            :disabled="selectedOrder?.status === 'selesai'"
                            :class="selectedOrder?.status === 'selesai' ? 'bg-green-500 text-white ring-2 ring-green-600' : 'bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20'"
                            class="px-4 py-3 rounded-lg font-semibold text-sm transition-all disabled:opacity-100 border border-gray-200 dark:border-gray-600 flex flex-col items-center gap-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Selesai</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 text-center">
                        Klik tombol untuk mengubah status order
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                <button @click="showDetailModal = false"
                    class="w-full px-4 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-500 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>