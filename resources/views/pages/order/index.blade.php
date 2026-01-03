<x-sidetop :role="Auth::user()->role" title="Daftar Order">
    <div x-data="{ 
        showModal: false, 
        showDetailModal: false, 
        selectedOrder: null,
        async openDetailModal(orderId) {
            try {
                const response = await fetch('/order/' + orderId);
                this.selectedOrder = await response.json();
                this.showDetailModal = true;
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat detail order');
            }
        },
        updateOrderStatus(newStatus) {
            if (!this.selectedOrder) return;

            if (confirm('Ubah status order #' + this.selectedOrder.antrian + ' menjadi ' + newStatus + '?')) {
                fetch('/order/' + this.selectedOrder.id + '/status', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.selectedOrder.status = newStatus;

                        if (typeof window.showToast === 'function') {
                            window.showToast(data.message || 'Status berhasil diupdate!', 'success');
                        }

                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Gagal update status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof window.showToast === 'function') {
                        window.showToast(error.message || 'Terjadi kesalahan', 'error');
                    } else {
                        alert(error.message || 'Terjadi kesalahan');
                    }
                });
            }
        }
    }" @open-order-modal.window="showModal = true" class="space-y-6">

        @if(session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Order ID</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Customer</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Paket</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($orders as $order)
                            <tr
                                class="{{ $order->status === 'selesai' ? 'bg-gray-100 dark:bg-gray-700/50 hover:bg-gray-200 dark:hover:bg-gray-700' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $order->status === 'selesai' ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white' }}">
                                    #{{ $order->id }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm {{ $order->status === 'selesai' ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white' }}">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 rounded-full {{ $order->status === 'selesai' ? 'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' }} flex items-center justify-center font-bold text-xs mr-3">
                                            {{ substr($order->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $order->user->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm {{ $order->status === 'selesai' ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $order->paket->nama }}
                                    <span class="text-xs text-gray-500 block">{{ $order->jumlah }}
                                        {{ $order->paket->satuan }}</span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $order->status === 'selesai' ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white' }}">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($order->status === 'menunggu')
                                        <span
                                            class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 rounded-full text-xs font-medium">
                                            Menunggu</span>
                                    @elseif($order->status === 'diproses')
                                        <span
                                            class="px-2 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 rounded-full text-xs font-medium">
                                            Diproses</span>
                                    @elseif($order->status === 'selesai')
                                        <span
                                            class="px-2 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 rounded-full text-xs font-medium">
                                            Selesai</span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-200 rounded-full text-xs font-medium">
                                            Diambil</span>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm {{ $order->status === 'selesai' ? 'text-gray-500 dark:text-gray-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($order->status === 'selesai')
                                        <a href="{{ route('order.receipt', $order->id) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50 rounded-lg font-medium transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Cetak Resi
                                        </a>
                                    @else
                                        <button @click="openDetailModal({{ $order->id }})"
                                            class="px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 rounded-lg font-medium transition-colors">
                                            Detail
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Belum ada order</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Order baru akan muncul di
                                            sini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity" @click="showModal = false">
            </div>

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

        {{-- Order Detail Modal --}}
        @include('components.order-detail-modal')
    </div>

    {{-- Midtrans Snap Script --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</x-sidetop>