<x-sidetop :role="Auth::user()->role" title="{{ $paymentType === 'dp' ? 'Pembayaran DP' : 'Pembayaran Laundry' }}">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                {{ $paymentType === 'dp' ? 'Pembayaran DP Penjemputan' : 'Pembayaran Laundry' }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400">Scan QRIS untuk melanjutkan order Anda</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                    <path
                        d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                </svg>
                Detail Order
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Nomor Antrian</span>
                    <span class="font-semibold text-gray-900 dark:text-white">#{{ $order->antrian }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Paket</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $order->paket->nama }}</span>
                </div>
                @if($order->pickup === 'dijemput')
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Jarak Penjemputan</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $order->jarak_km }} km</span>
                    </div>
                @endif
                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between">
                    <span class="text-gray-900 dark:text-white font-semibold">
                        {{ $paymentType === 'dp' ? 'Biaya DP' : 'Total Pembayaran' }}
                    </span>
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <div x-data="paymentStatus({{ $payment->id }})"
            class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-center">
                <div x-show="status === 'pending'" class="space-y-4">
                    <div
                        class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400 animate-pulse" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Menunggu Pembayaran</h3>
                    <p class="text-gray-600 dark:text-gray-400">Klik tombol di bawah untuk scan QRIS</p>

                    <button @click="openPayment()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        Bayar dengan QRIS
                    </button>
                </div>

                <div x-show="status === 'success'" class="space-y-4">
                    <div
                        class="w-16 h-16 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-600 dark:text-green-400">Pembayaran Berhasil!</h3>
                    <p class="text-gray-600 dark:text-gray-400">DP Anda sudah kami terima. Karyawan akan segera
                        menjemput laundry Anda.</p>

                    <a href="{{ route('customer.dashboard') }}"
                        class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-all">
                        Kembali ke Dashboard
                    </a>
                </div>

                <div x-show="status === 'failed'" class="space-y-4">
                    <div
                        class="w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-red-600 dark:text-red-400">Pembayaran Gagal</h3>
                    <p class="text-gray-600 dark:text-gray-400">Silakan coba lagi atau hubungi admin</p>

                    <button @click="openPayment()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all">
                        Coba Lagi
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-sm text-blue-800 dark:text-blue-300">
                    <p class="font-semibold mb-1">Informasi Pembayaran:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @if($paymentType === 'dp')
                            <li>DP ini untuk biaya penjemputan laundry</li>
                            <li>Pembayaran sisa akan dilakukan saat pengambilan</li>
                        @else
                            <li>Pembayaran ini sudah termasuk semua biaya</li>
                            <li>Tidak ada pembayaran tambahan saat pengambilan</li>
                        @endif
                        <li>Gunakan aplikasi e-wallet untuk scan QRIS</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        function paymentStatus(paymentId) {
            return {
                status: '{{ $payment->status }}',

                init() {
                    setInterval(() => {
                        this.checkStatus();
                    }, 3000);
                },

                async checkStatus() {
                    try {
                        const response = await fetch(`/payment/${paymentId}/status`);
                        const data = await response.json();

                        if (data.status !== this.status) {
                            this.status = data.status;

                            if (data.status === 'success') {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            }
                        }
                    } catch (error) {
                        console.error('Error checking status:', error);
                    }
                },

                openPayment() {
                    window.snap.pay('{{ $payment->snap_token }}', {
                        onSuccess: (result) => {
                            this.status = 'success';
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        },
                        onPending: (result) => {
                            console.log('Payment pending:', result);
                        },
                        onError: (result) => {
                            this.status = 'failed';
                            alert('Pembayaran gagal. Silakan coba lagi.');
                        },
                        onClose: () => {
                            console.log('Payment popup closed');
                        }
                    });
                }
            }
        }
    </script>
</x-sidetop>