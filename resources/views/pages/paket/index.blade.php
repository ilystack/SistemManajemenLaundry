<x-sidetop role="admin" title="Daftar Paket Laundry">
    <div x-data="{ 
        showModal: false, 
        showEditModal: false,
        editPaket: {},
        filterJenis: null, // null = tampil semua, 'cuci_saja', 'cuci_setrika', 'kilat'
        openEditModal(paket) {
            this.editPaket = paket;
            this.showEditModal = true;
        },
        toggleFilter(jenis) {
            this.filterJenis = this.filterJenis === jenis ? null : jenis;
        },
        shouldShowPaket(paketJenis) {
            return this.filterJenis === null || this.filterJenis === paketJenis;
        }
    }" class="space-y-6">
        <div class="flex justify-center items-center">
            <button @click="showModal = true"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Paket
            </button>
        </div>



        @php
            $paketKg = $pakets->where('satuan', 'kg');
            $paketPcs = $pakets->where('satuan', 'pcs');
        @endphp

        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-transparent border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                    </svg>
                    Paket Kiloan (KG)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Paket</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Harga</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estimasi</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Express</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($paketKg as $paket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $paket->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    Rp {{ number_format($paket->harga, 0, ',', '.') }}/kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $paket->estimasi_hari }} hari
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($paket->is_express)
                                        <span
                                            class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 rounded-full text-xs font-medium">
                                            Express</span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full text-xs font-medium">Regular</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <button @click="openEditModal({{ json_encode($paket) }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Detail
                                        </button>
                                        <form action="{{ route('paket.destroy', $paket->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus paket {{ $paket->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Paket"
                                                class="inline-flex items-center justify-center p-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Belum ada paket
                                            kiloan</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tambahkan paket dengan
                                            satuan KG</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-transparent border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 5v1H4.667a1.75 1.75 0 00-1.743 1.598l-.826 9.5A1.75 1.75 0 003.84 19h12.32a1.75 1.75 0 001.742-1.902l-.826-9.5A1.75 1.75 0 0015.333 6H14V5a4 4 0 00-8 0zm4-2.5A2.5 2.5 0 007.5 5v1h5V5A2.5 2.5 0 0010 2.5zM7.5 10a2.5 2.5 0 005 0V8.75a.75.75 0 011.5 0V10a4 4 0 01-8 0V8.75a.75.75 0 011.5 0V10z"
                            clip-rule="evenodd" />
                    </svg>
                    Paket Satuan (PCS)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Paket</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2" x-data="{ showFilterDropdown: false }">
                                    <span>Jenis Layanan</span>
                                    <div class="relative">
                                        <button type="button" @click="showFilterDropdown = !showFilterDropdown"
                                            class="p-1 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition">
                                            <svg class="w-4 h-4"
                                                :class="filterJenis ? 'text-purple-600 dark:text-purple-400' : 'text-gray-400'"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                            </svg>
                                        </button>

                                        <div x-show="showFilterDropdown" @click.away="showFilterDropdown = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10"
                                            style="display: none;">
                                            <button type="button"
                                                @click="toggleFilter('cuci_saja'); showFilterDropdown = false"
                                                :class="filterJenis === 'cuci_saja' ? 'bg-cyan-50 dark:bg-cyan-900/20' : ''"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                <span class="w-3 h-3 rounded-full bg-cyan-400"></span>
                                                <span class="text-gray-700 dark:text-gray-300">Cuci Saja</span>
                                                <svg x-show="filterJenis === 'cuci_saja'"
                                                    class="w-4 h-4 ml-auto text-cyan-600 dark:text-cyan-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button type="button"
                                                @click="toggleFilter('cuci_setrika'); showFilterDropdown = false"
                                                :class="filterJenis === 'cuci_setrika' ? 'bg-purple-50 dark:bg-purple-900/20' : ''"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                <span class="w-3 h-3 rounded-full bg-purple-400"></span>
                                                <span class="text-gray-700 dark:text-gray-300">Cuci + Setrika</span>
                                                <svg x-show="filterJenis === 'cuci_setrika'"
                                                    class="w-4 h-4 ml-auto text-purple-600 dark:text-purple-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button type="button"
                                                @click="toggleFilter('kilat'); showFilterDropdown = false"
                                                :class="filterJenis === 'kilat' ? 'bg-orange-50 dark:bg-orange-900/20' : ''"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                                                <span class="text-gray-700 dark:text-gray-300">Kilat</span>
                                                <svg x-show="filterJenis === 'kilat'"
                                                    class="w-4 h-4 ml-auto text-orange-600 dark:text-orange-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                            <button type="button"
                                                @click="filterJenis = null; showFilterDropdown = false"
                                                class="w-full px-4 py-2 text-left text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                Reset Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Harga</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estimasi</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($paketPcs as $paket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                x-show="shouldShowPaket('{{ $paket->jenis_layanan }}')" x-transition>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $paket->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($paket->jenis_layanan === 'cuci_saja')
                                        <span
                                            class="px-2 py-1 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 rounded-full text-xs font-medium">
                                            Cuci Saja
                                        </span>
                                    @elseif($paket->jenis_layanan === 'cuci_setrika')
                                        <span
                                            class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-xs font-medium">
                                            Cuci + Setrika
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-xs font-medium">
                                            Kilat
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    Rp {{ number_format($paket->harga, 0, ',', '.') }}/pcs
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($paket->estimasi_hari == 0)
                                        <span class="text-orange-600 dark:text-orange-400 font-medium">3 jam</span>
                                    @else
                                        {{ $paket->estimasi_hari }} hari
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <button @click="openEditModal({{ json_encode($paket) }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-lg hover:bg-purple-200 dark:hover:bg-purple-900/50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Detail
                                        </button>
                                        <form action="{{ route('paket.destroy', $paket->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus paket {{ $paket->nama }} ({{ $paket->jenis_layanan_label }})?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Paket"
                                                class="inline-flex items-center justify-center p-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Belum ada paket
                                            satuan</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tambahkan paket dengan
                                            satuan PCS</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity"
                @click="showModal = false"></div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-gray-200 dark:border-gray-700">

                    <div
                        class="px-4 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">
                            Tambah Paket Laundry
                        </h3>
                        <button @click="showModal = false"
                            class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('paket.store') }}" method="POST" class="p-6 space-y-4"
                        x-data="{ selectedSatuan: 'kg' }">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Satuan
                                Paket</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="satuan" value="kg" x-model="selectedSatuan"
                                        class="peer sr-only" checked>
                                    <div
                                        class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-white dark:hover:bg-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30 peer-checked:text-blue-600 dark:peer-checked:text-blue-400 transition-all text-center">
                                        <div class="text-2xl mb-1">⚖️</div>
                                        <span class="block font-bold">KG (Kilogram)</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">Paket
                                            kiloan</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="satuan" value="pcs" x-model="selectedSatuan"
                                        class="peer sr-only">
                                    <div
                                        class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-white dark:hover:bg-gray-600 peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/30 peer-checked:text-purple-600 dark:peer-checked:text-purple-400 transition-all text-center">
                                        <div class="text-2xl mb-1">👕</div>
                                        <span class="block font-bold">PCS (Satuan)</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">Paket
                                            satuan</span>
                                    </div>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2" x-show="selectedSatuan === 'pcs'">
                                💡 <strong>Info:</strong> Paket satuan akan otomatis dibuatkan 3 jenis layanan (Cuci
                                Saja, Cuci+Setrika, Kilat)
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                                    Paket</label>
                                <input type="text" name="nama" required placeholder="Contoh: Cuci Kering"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode
                                    Paket</label>
                                <input type="text" name="kode" required placeholder="Contoh: CK01"
                                    class="uppercase w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Harga (Rp)
                                    <span x-show="selectedSatuan === 'pcs'"
                                        class="text-xs text-purple-600 dark:text-purple-400">
                                        - Harga Dasar (Cuci+Setrika)
                                    </span>
                                </label>
                                <input type="number" name="harga" required min="0"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                    x-show="selectedSatuan === 'pcs'">
                                    Cuci Saja: -Rp 500 | Kilat: +Rp 2.000
                                </p>
                            </div>
                            <div x-show="selectedSatuan === 'kg'">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estimasi
                                    (Hari)</label>
                                <input type="number" name="estimasi_hari" :required="selectedSatuan === 'kg'" min="1"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>
                            <div x-show="selectedSatuan === 'pcs'" class="flex items-center">
                                <div
                                    class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3 border border-purple-200 dark:border-purple-800 w-full">
                                    <p class="text-xs font-medium text-purple-900 dark:text-purple-300">Estimasi
                                        Otomatis:</p>
                                    <p class="text-xs text-purple-700 dark:text-purple-400 mt-1">
                                        • Cuci Saja & Cuci+Setrika: 2-3 hari<br>
                                        • Kilat: 3 jam
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div x-show="selectedSatuan === 'kg'">
                            <div class="flex items-center gap-2 mb-2">
                                <input type="checkbox" id="is_express" name="is_express" value="1"
                                    class="rounded border-gray-300 bg-gray-50 focus:bg-white text-blue-600 focus:ring-blue-500 transition-colors">
                                <label for="is_express"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">Layanan Express (Lebih
                                    Cepat)</label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan
                                (Opsional)</label>
                            <textarea name="keterangan" rows="2"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors"></textarea>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="showModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                                Simpan Paket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="edit-modal-title" role="dialog" aria-modal="true">

            <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity"
                @click="showEditModal = false"></div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-gray-200 dark:border-gray-700">

                    <div
                        class="px-4 py-4 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border-b border-gray-200 dark:border-gray-700 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="edit-modal-title">
                            Edit Paket Laundry
                        </h3>
                        <button @click="showEditModal = false"
                            class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form :action="`{{ url('paket') }}/${editPaket.id}`" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                                    Paket</label>
                                <input type="text" name="nama" required x-model="editPaket.nama"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode
                                    Paket</label>
                                <input type="text" name="kode" required x-model="editPaket.kode"
                                    class="uppercase w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga
                                    (Rp)</label>
                                <input type="number" name="harga" required min="0" x-model="editPaket.harga"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estimasi
                                    (Hari)</label>
                                <input type="number" name="estimasi_hari" required min="1"
                                    x-model="editPaket.estimasi_hari"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    💡 Untuk paket satuan (PCS), estimasi kilat otomatis 3 jam
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan
                                (Opsional)</label>
                            <textarea name="keterangan" rows="2" x-model="editPaket.keterangan"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors"></textarea>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="showEditModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                                Update Paket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-sidetop>