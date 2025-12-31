<div x-show="showProfileModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="profile-modal-title" role="dialog" aria-modal="true">

    <div x-show="showProfileModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity"
        @click="showProfileModal = false"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div x-show="showProfileModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200 dark:border-gray-700">

            <div
                class="px-6 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="profile-modal-title">
                    Edit Profil
                </h3>
                <button @click="showProfileModal = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white focus:bg-white dark:focus:bg-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white focus:bg-white dark:focus:bg-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nomor
                            WhatsApp</label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}" pattern="[0-9]{10,13}"
                            placeholder="08123456789"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white focus:bg-white dark:focus:bg-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                        <input type="text" name="address" value="{{ Auth::user()->address }}"
                            placeholder="Alamat lengkap"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white focus:bg-white dark:focus:bg-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Foto Profil
                            @if(Auth::user()->isKaryawan())
                                <span class="text-red-500">*</span>
                            @else
                                <span class="text-gray-500 text-xs font-normal">(Opsional)</span>
                            @endif
                        </label>

                        @if(Auth::user()->profile_photo)
                            <div class="mb-3">
                                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Current Profile Photo"
                                    class="w-24 h-24 rounded-lg object-cover border-2 border-gray-300 dark:border-gray-600">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Foto saat ini</p>
                            </div>
                        @endif

                        <input type="file" name="profile_photo" accept="image/jpeg,image/jpg,image/png"
                            id="editProfilePhoto"
                            class="w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-all">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Format: JPG, JPEG, PNG. Maksimal 5MB.
                            @if(Auth::user()->isKaryawan())
                                <strong>Foto harus menampilkan wajah dengan jelas.</strong>
                            @endif
                        </p>

                        {{-- Preview --}}
                        <div id="editPhotoPreview" class="mt-3 hidden">
                            <img id="editPreviewImage"
                                class="w-32 h-32 rounded-lg object-cover border-2 border-gray-300 dark:border-gray-600">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <button type="button" @click="showProfileModal = false"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg hover:from-blue-700 hover:to-cyan-700 shadow-md hover:shadow-lg transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const photoInput = document.getElementById('editProfilePhoto');
                    const previewContainer = document.getElementById('editPhotoPreview');
                    const previewImage = document.getElementById('editPreviewImage');

                    if (photoInput) {
                        photoInput.addEventListener('change', function (e) {
                            const file = e.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function (event) {
                                    previewImage.src = event.target.result;
                                    previewContainer.classList.remove('hidden');
                                };
                                reader.readAsDataURL(file);
                            } else {
                                previewContainer.classList.add('hidden');
                            }
                        });
                    }
                });
            </script>
        </div>
    </div>
</div>