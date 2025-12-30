{{-- Profile Completion Modal - Mandatory for Karyawan --}}
@if(session('show_profile_completion_modal') && auth()->user()->isKaryawan() && !auth()->user()->is_profile_complete)
    <div id="profileCompletionModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: block;">
        {{-- Backdrop - Cannot be closed --}}
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

        {{-- Modal --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">

                {{-- Header --}}
                <div class="bg-blue-600 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Lengkapi Profil Anda</h3>
                            <p class="text-sm text-blue-100 mt-0.5">Wajib diisi untuk melanjutkan</p>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <form id="profileCompletionForm" class="p-6 space-y-6">
                    @csrf

                    {{-- Alert Info --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-sm text-blue-800 dark:text-blue-300">
                                <p class="font-semibold mb-1">Selamat datang! 👋</p>
                                <p>Silakan lengkapi data profil Anda terlebih dahulu. <strong>Foto profil wajib menampilkan
                                        wajah dengan jelas</strong> untuk keperluan identifikasi.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Name Field --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Masukkan nama lengkap sesuai KTP</p>
                    </div>

                    {{-- Phone Field --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all"
                            placeholder="08xxxxxxxxxx">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nomor yang dapat dihubungi</p>
                    </div>

                    {{-- Address Field --}}
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="address" name="address" rows="3" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all resize-none"
                            placeholder="Masukkan alamat lengkap">{{ auth()->user()->address }}</textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Alamat tempat tinggal saat ini</p>
                    </div>

                    {{-- Photo Upload with Face Detection --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Foto Profil <span class="text-red-500">*</span>
                        </label>

                        {{-- Upload Button --}}
                        <div class="flex items-center gap-4">
                            <label for="profile_photo"
                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Pilih Foto
                            </label>
                            <input type="file" id="profile_photo" name="profile_photo"
                                accept="image/jpeg,image/jpg,image/png" required class="hidden">
                            <span id="fileName" class="text-sm text-gray-500 dark:text-gray-400">Belum ada file
                                dipilih</span>
                        </div>

                        {{-- Preview Container --}}
                        <div id="previewContainer" class="mt-4 hidden">
                            <div class="relative inline-block">
                                <img id="photoPreview"
                                    class="w-48 h-48 object-cover rounded-lg border-2 border-gray-300 dark:border-gray-600">
                                <canvas id="faceCanvas" class="absolute top-0 left-0 w-48 h-48"></canvas>
                            </div>
                            <div id="detectionStatus" class="mt-3"></div>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <strong>Penting:</strong> Foto harus menampilkan wajah dengan jelas. Format: JPG, JPEG, PNG.
                            Maksimal 5MB.
                        </p>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" id="submitBtn" disabled
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                            <span id="submitText">Simpan Profil</span>
                            <span id="submitLoading" class="hidden">
                                <svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Load face-api.js from CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const photoInput = document.getElementById('profile_photo');
            const photoPreview = document.getElementById('photoPreview');
            const previewContainer = document.getElementById('previewContainer');
            const fileName = document.getElementById('fileName');
            const faceCanvas = document.getElementById('faceCanvas');
            const detectionStatus = document.getElementById('detectionStatus');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('profileCompletionForm');

            let faceDetected = false;
            let modelsLoaded = false;

            // Prevent closing modal with ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });

            // Load face-api models
            async function loadModels() {
                try {
                    const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    ]);
                    modelsLoaded = true;
                    console.log('Face detection models loaded');
                } catch (error) {
                    console.error('Error loading models:', error);
                    showToast('Gagal memuat model deteksi wajah', 'error');
                }
            }

            // Load models on page load
            await loadModels();

            // Handle photo selection
            photoInput.addEventListener('change', async function (e) {
                const file = e.target.files[0];
                if (!file) return;

                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showToast('Ukuran file maksimal 5MB', 'error');
                    photoInput.value = '';
                    return;
                }

                // Validate file type
                if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                    showToast('Format file harus JPG, JPEG, atau PNG', 'error');
                    photoInput.value = '';
                    return;
                }

                fileName.textContent = file.name;

                // Show preview
                const reader = new FileReader();
                reader.onload = async function (event) {
                    photoPreview.src = event.target.result;
                    previewContainer.classList.remove('hidden');

                    // Wait for image to load
                    photoPreview.onload = async function () {
                        if (modelsLoaded) {
                            await detectFace();
                        } else {
                            showDetectionStatus('loading', 'Memuat model deteksi wajah...');
                            await loadModels();
                            await detectFace();
                        }
                    };
                };
                reader.readAsDataURL(file);
            });

            // Detect face in uploaded photo
            async function detectFace() {
                try {
                    showDetectionStatus('loading', 'Mendeteksi wajah...');

                    const detections = await faceapi.detectAllFaces(
                        photoPreview,
                        new faceapi.TinyFaceDetectorOptions()
                    ).withFaceLandmarks();

                    // Clear previous canvas
                    const ctx = faceCanvas.getContext('2d');
                    ctx.clearRect(0, 0, faceCanvas.width, faceCanvas.height);

                    if (detections.length === 0) {
                        faceDetected = false;
                        submitBtn.disabled = true;
                        showDetectionStatus('error', '❌ Wajah tidak terdeteksi! Pastikan foto menampilkan wajah dengan jelas.');
                    } else if (detections.length > 1) {
                        faceDetected = false;
                        submitBtn.disabled = true;
                        showDetectionStatus('error', '⚠️ Terdeteksi lebih dari 1 wajah! Gunakan foto dengan 1 wajah saja.');
                    } else {
                        faceDetected = true;
                        submitBtn.disabled = false;
                        showDetectionStatus('success', '✅ Wajah terdeteksi! Foto dapat digunakan.');

                        // Draw detection box
                        const displaySize = { width: photoPreview.width, height: photoPreview.height };
                        faceapi.matchDimensions(faceCanvas, displaySize);
                        const resizedDetections = faceapi.resizeResults(detections, displaySize);

                        // Draw box around face
                        resizedDetections.forEach(detection => {
                            const box = detection.detection.box;
                            ctx.strokeStyle = '#10b981'; // green
                            ctx.lineWidth = 3;
                            ctx.strokeRect(box.x, box.y, box.width, box.height);
                        });
                    }
                } catch (error) {
                    console.error('Face detection error:', error);
                    showDetectionStatus('error', '⚠️ Gagal mendeteksi wajah. Silakan coba foto lain.');
                    faceDetected = false;
                    submitBtn.disabled = true;
                }
            }

            // Show detection status
            function showDetectionStatus(type, message) {
                const colors = {
                    loading: 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300',
                    success: 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-300',
                    error: 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-300'
                };

                detectionStatus.className = `p-3 rounded-lg border text-sm font-medium ${colors[type]}`;
                detectionStatus.textContent = message;
            }

            // Handle form submission
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (!faceDetected) {
                    showToast('Foto profil harus menampilkan wajah dengan jelas', 'error');
                    return;
                }

                // Disable submit button
                submitBtn.disabled = true;
                document.getElementById('submitText').classList.add('hidden');
                document.getElementById('submitLoading').classList.remove('hidden');

                // Create FormData
                const formData = new FormData(form);

                try {
                    const response = await fetch('{{ route('profile.complete') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        },
                        body: formData
                    });

                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text);
                        throw new Error('Server mengembalikan response yang tidak valid. Silakan cek console untuk detail.');
                    }

                    const data = await response.json();

                    if (response.ok && data.success) {
                        showToast(data.message || 'Profil berhasil dilengkapi!', 'success');

                        // Reload page after 1 second
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            const errorMessages = Object.values(data.errors).flat().join(', ');
                            throw new Error(errorMessages);
                        }
                        throw new Error(data.message || 'Gagal menyimpan profil');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast(error.message || 'Terjadi kesalahan saat menyimpan profil', 'error');

                    // Re-enable submit button
                    submitBtn.disabled = false;
                    document.getElementById('submitText').classList.remove('hidden');
                    document.getElementById('submitLoading').classList.add('hidden');
                }
            });

            // Toast notification function
            function showToast(message, type = 'info') {
                // Check if toast component exists
                if (typeof window.showToast === 'function') {
                    window.showToast(message, type);
                } else {
                    alert(message);
                }
            }
        });
    </script>
@endif