<x-sidetop :role="Auth::user()->role" title="Kelola Jam Kerja">
    <div class="space-y-6">
        <div class="flex justify-center items-center">
            <button @click="$dispatch('open-modal', 'add-jam-kerja')"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Shift
            </button>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jamKerjas as $jamKerja)
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6 relative">
                    @if ($jamKerja->is_active)
                        <div class="absolute top-4 right-4">
                            <span
                                class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 text-xs font-semibold rounded-full">
                                ✓ Aktif
                            </span>
                        </div>
                    @else
                        <div class="absolute top-4 right-4">
                            <span
                                class="px-3 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-400 text-xs font-semibold rounded-full">
                                ✗ Libur
                            </span>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $jamKerja->hari }}
                        </h3>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mt-1">
                            {{ $jamKerja->nama }}
                        </p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Jam Masuk</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($jamKerja->jam_masuk)->format('H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Jam Keluar</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($jamKerja->jam_keluar)->format('H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Toleransi</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $jamKerja->toleransi_menit }} Menit
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <form action="{{ route('jam-kerja.toggle', $jamKerja) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2 {{ $jamKerja->is_active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-semibold rounded-lg transition duration-200">
                                {{ $jamKerja->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <button @click="editJamKerja({{ $jamKerja }})"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                            Edit
                        </button>

                        @if (!$jamKerja->is_active)
                            <form action="{{ route('jam-kerja.destroy', $jamKerja) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus shift ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition duration-200">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada jam kerja. Tambahkan shift baru!</p>
                </div>
            @endforelse
        </div>
    </div>

    <div x-data="{ open: false }" @open-modal.window="if ($event.detail === 'add-jam-kerja') open = true"
        @close-modal.window="open = false" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">

        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="open = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6"
                @click.away="open = false">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Tambah Shift Baru</h3>

                <form action="{{ route('jam-kerja.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Shift
                        </label>
                        <input type="text" name="nama" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                            placeholder="Shift Hari / Waktu">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Hari
                        </label>
                        <select name="hari" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Hari</option>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jam Masuk
                            </label>
                            <input type="time" name="jam_masuk" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jam Keluar
                            </label>
                            <input type="time" name="jam_keluar" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Toleransi (Menit)
                        </label>
                        <input type="number" name="toleransi_menit" required min="0" value="15"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="open = false"
                            class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-data="jamKerjaEdit()" x-show="showEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">

        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="showEdit = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Shift</h3>

                <form :action="`/jam-kerja/${editData.id}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Shift
                        </label>
                        <input type="text" name="nama" x-model="editData.nama" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Hari
                        </label>
                        <select name="hari" x-model="editData.hari" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Hari</option>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jam Masuk
                            </label>
                            <input type="time" name="jam_masuk" x-model="editData.jam_masuk" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jam Keluar
                            </label>
                            <input type="time" name="jam_keluar" x-model="editData.jam_keluar" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Toleransi (Menit)
                        </label>
                        <input type="number" name="toleransi_menit" x-model="editData.toleransi_menit" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="showEdit = false"
                            class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function jamKerjaEdit() {
            return {
                showEdit: false,
                editData: {
                    id: null,
                    nama: '',
                    jam_masuk: '',
                    jam_keluar: '',
                    toleransi_menit: 15,
                    hari: ''
                }
            }
        }

        function editJamKerja(data) {
            Alpine.store('jamKerjaEdit', {
                showEdit: true,
                editData: {
                    id: data.id,
                    nama: data.nama,
                    jam_masuk: data.jam_masuk,
                    jam_keluar: data.jam_keluar,
                    toleransi_menit: data.toleransi_menit,
                    hari: data.hari
                }
            });

            const event = new CustomEvent('show-edit-modal', {
                detail: data
            });
            window.dispatchEvent(event);
        }

        window.addEventListener('show-edit-modal', (e) => {
            const component = Alpine.$data(document.querySelector('[x-data*="jamKerjaEdit"]'));
            if (component) {
                component.editData = e.detail;
                component.showEdit = true;
            }
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-sidetop>