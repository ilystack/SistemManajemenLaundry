<div x-data="absensiModal()" x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">

    <div class="fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm"></div>

    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6">

            <div class="text-center mb-6">
                <div
                    class="mx-auto w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M1 8a2 2 0 0 1 2-2h.93a2 2 0 0 0 1.664-.89l.812-1.22A2 2 0 0 1 8.07 3h3.86a2 2 0 0 1 1.664.89l.812 1.22A2 2 0 0 0 16.07 6H17a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8Zm13.5 3a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM10 14a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Absensi Wajib
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    Silakan ambil foto selfie untuk absensi hari ini
                </p>
            </div>

            <div class="mb-6">
                <div class="relative bg-gray-900 rounded-lg overflow-hidden aspect-video">
                    <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"
                        x-show="!fotoTaken"></video>
                    <canvas x-ref="canvas" class="hidden"></canvas>
                    <img :src="fotoPreview" class="w-full h-full object-cover" x-show="fotoTaken">
                </div>
            </div>

            <div class="space-y-3">
                <button @click="ambilFoto()" x-show="!fotoTaken"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Ambil Foto
                </button>

                <div x-show="fotoTaken" class="space-y-3">
                    <button @click="kirimAbsensi()" :disabled="loading"
                        class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                        <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-show="!loading">Kirim Absensi</span>
                        <span x-show="loading">Mengirim...</span>
                    </button>

                    <button @click="ulangi()"
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Ambil Ulang
                    </button>
                </div>
            </div>

            <div
                class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd" />
                </svg>
                <p>Lokasi GPS akan otomatis terdeteksi</p>
            </div>
        </div>
    </div>
</div>

<script>
    function absensiModal() {
        return {
            showModal: false,
            fotoTaken: false,
            fotoPreview: null,
            fotoBase64: null,
            loading: false,
            latitude: null,
            longitude: null,

            init() {
                this.checkAbsensi();
            },

            async checkAbsensi() {
                try {
                    const response = await fetch('/absensi/check');
                    const data = await response.json();

                    if (data.dalam_jam_kerja && !data.sudah_absen) {
                        this.showModal = true;
                        this.startWebcam();
                        this.getLocation();
                    } else {
                        // Tampilkan toast welcome
                        this.showWelcomeToast();
                    }
                } catch (error) {
                    console.error('Error checking absensi:', error);
                }
            },

            async startWebcam() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'user' }
                    });
                    this.$refs.video.srcObject = stream;
                } catch (error) {
                    alert('Gagal mengakses kamera. Pastikan izin kamera diaktifkan.');
                }
            },

            ambilFoto() {
                const video = this.$refs.video;
                const canvas = this.$refs.canvas;
                const context = canvas.getContext('2d');

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0);

                this.fotoBase64 = canvas.toDataURL('image/png');
                this.fotoPreview = this.fotoBase64;
                this.fotoTaken = true;

                // Stop webcam
                const stream = video.srcObject;
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            },

            ulangi() {
                this.fotoTaken = false;
                this.fotoPreview = null;
                this.fotoBase64 = null;
                this.startWebcam();
            },

            getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                        },
                        (error) => {
                            console.error('Error getting location:', error);
                        }
                    );
                }
            },

            async kirimAbsensi() {
                this.loading = true;

                try {
                    const response = await fetch('/absensi/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            foto_selfie: this.fotoBase64,
                            latitude: this.latitude,
                            longitude: this.longitude,
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Tampilkan toast success
                        this.showSuccessToast(data.message);

                        // Tutup modal dan reload
                        this.showModal = false;
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        const errorMsg = data.error || data.message || 'Gagal mengirim absensi';
                        alert(errorMsg);
                        console.error('Server error:', data);
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            showWelcomeToast() {
                if (sessionStorage.getItem('welcome_shown')) {
                    return;
                }

                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        variant: 'success',
                        title: 'Selamat Datang!',
                        message: 'Selamat datang kembali.'
                    }
                }));

                sessionStorage.setItem('welcome_shown', 'true');
            },

            showSuccessToast(message) {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        variant: 'success',
                        title: 'Berhasil!',
                        message: message
                    }
                }));
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>