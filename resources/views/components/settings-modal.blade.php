<div x-show="showSettingsModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="settings-modal-title" role="dialog" aria-modal="true">

    <div x-show="showSettingsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity"
        @click="showSettingsModal = false"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div x-show="showSettingsModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200 dark:border-gray-700">

            <div
                class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="settings-modal-title">
                        Pengaturan Laundry
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola identitas dan lokasi laundry Anda
                    </p>
                </div>
                <button @click="showSettingsModal = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6"
                x-data="{ 
                      // Location Logic
                      gettingLocation: false,
                      useCurrentLocation: false,
                      getLocation() {
                          if (navigator.geolocation) {
                              this.gettingLocation = true;
                              navigator.geolocation.getCurrentPosition(
                                  (position) => {
                                      document.getElementById('settings_latitude').value = position.coords.latitude;
                                      document.getElementById('settings_longitude').value = position.coords.longitude;
                                      this.gettingLocation = false;
                                      this.useCurrentLocation = true;
                                  },
                                  (error) => {
                                      alert('Gagal mendapatkan lokasi: ' + error.message);
                                      this.gettingLocation = false;
                                  }
                              );
                          } else {
                              alert('Browser Anda tidak mendukung Geolocation');
                          }
                      },

                      // Logo Preview Logic
                      photoPreview: null,
                      updatePreview() {
                          const file = this.$refs.photo.files[0];
                          if (!file) return;
                          const reader = new FileReader();
                          reader.onload = (e) => {
                              this.photoPreview = e.target.result;
                          };
                          reader.readAsDataURL(file);
                      }
                  }">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <div
                            class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-700 rounded-xl p-6 border border-purple-100 dark:border-gray-600">
                            <h4 class="font-bold text-gray-800 dark:text-white mb-4">Logo & Identitas</h4>

                            <div class="mb-6 text-center">
                                <div class="relative inline-block">
                                    <div
                                        class="w-32 h-32 rounded-full overflow-hidden border-4 border-white dark:border-gray-600 shadow-lg mx-auto bg-gray-100 flex items-center justify-center">
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!photoPreview">
                                            @if(Auth::user()->laundry_logo)
                                                <img src="{{ Storage::url(Auth::user()->laundry_logo) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <img src="{{ asset('assets/logoalmas.png') }}"
                                                    class="w-full h-full object-contain p-2">
                                            @endif
                                        </template>
                                    </div>

                                    <label for="settings_laundry_logo"
                                        class="absolute bottom-0 right-0 bg-purple-600 hover:bg-purple-700 text-white p-2 rounded-full cursor-pointer shadow-md transition transform hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </label>
                                    <input type="file" id="settings_laundry_logo" name="laundry_logo" class="hidden"
                                        x-ref="photo" @change="updatePreview()" accept="image/*">
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">JPG, PNG. Max: 2MB</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama
                                    Laundry</label>
                                <input type="text" name="laundry_name"
                                    value="{{ Auth::user()->laundry_name ?? 'Almas Laundry' }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div
                            class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-700 rounded-xl p-6 border border-blue-100 dark:border-gray-600 h-full">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h4 class="font-bold text-gray-800 dark:text-white">Lokasi Laundry</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Untuk perhitungan jarak pickup
                                        otomatis</p>
                                </div>
                                <button type="button" @click="getLocation()" :disabled="gettingLocation"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white text-sm font-semibold rounded-lg transition flex items-center gap-2 shadow-md">
                                    <svg class="w-4 h-4" :class="{'animate-spin': gettingLocation}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span x-text="gettingLocation ? 'Mengambil...' : 'Ambil Lokasi'"></span>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Latitude</label>
                                        <input type="text" id="settings_latitude" name="latitude"
                                            value="{{ Auth::user()->latitude }}" placeholder="-6.200000"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-mono">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Longitude</label>
                                        <input type="text" id="settings_longitude" name="longitude"
                                            value="{{ Auth::user()->longitude }}" placeholder="106.816666"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-all text-sm font-mono">
                                    </div>
                                </div>
                                <div x-show="useCurrentLocation" x-transition
                                    class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-3">
                                    <div class="bg-green-100 p-1.5 rounded-full text-green-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-green-800 dark:text-green-300">Lokasi
                                            berhasil diperbarui!</p>
                                        <p class="text-xs text-green-600 dark:text-green-400">Koordinat akurat dari GPS
                                            perangkat Anda.</p>
                                    </div>
                                </div>

                                <div
                                    class="p-4 bg-blue-100 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                    <h5 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-1">💡 Tips</h5>
                                    <p class="text-xs text-blue-700 dark:text-blue-400">Pastikan Anda berada di lokasi
                                        fisik laundry saat menekan tombol "Ambil Lokasi" untuk hasil perhitungan jarak
                                        yang akurat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-600">
                    <button type="button" @click="showSettingsModal = false"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg hover:from-purple-700 hover:to-pink-700 shadow-md hover:shadow-lg transition">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>