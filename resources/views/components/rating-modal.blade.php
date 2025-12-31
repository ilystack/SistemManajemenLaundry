<div x-show="showRatingModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="rating-modal-title" role="dialog" aria-modal="true">

    <div x-show="showRatingModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity"
        @click="showRatingModal = false"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div x-show="showRatingModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">

            <div
                class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2"
                    id="rating-modal-title">
                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Beri Penilaian
                </h3>
                <button @click="showRatingModal = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitRating" class="p-6 space-y-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Bagaimana pengalaman Anda dengan layanan
                        kami?</p>

                    <div class="flex items-center justify-center gap-2 mb-2">
                        <template x-for="star in 5" :key="star">
                            <button type="button" @click="selectedRating = star"
                                class="transition-transform hover:scale-110 focus:outline-none">
                                <svg :class="star <= selectedRating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                                    class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </button>
                        </template>
                    </div>
                    <p class="text-center text-sm font-medium text-gray-700 dark:text-gray-300"
                        x-show="selectedRating > 0">
                        <span x-text="selectedRating"></span> dari 5 bintang
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Komentar (Opsional)
                    </label>
                    <textarea x-model="ratingComment" rows="4" placeholder="Ceritakan pengalaman Anda..."
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white focus:bg-white dark:focus:bg-gray-600 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 dark:focus:ring-yellow-800 transition-all resize-none"></textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal 500 karakter</p>
                </div>

                <div x-show="ratingError" x-transition
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                    <span x-text="ratingError"></span>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <button type="button" @click="showRatingModal = false"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        Batal
                    </button>
                    <button type="submit" :disabled="selectedRating === 0 || isSubmittingRating"
                        :class="selectedRating === 0 || isSubmittingRating ? 'opacity-50 cursor-not-allowed' : 'hover:from-yellow-600 hover:to-orange-600'"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg shadow-md hover:shadow-lg transition flex items-center gap-2">
                        <svg x-show="isSubmittingRating" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="isSubmittingRating ? 'Mengirim...' : 'Kirim Penilaian'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>