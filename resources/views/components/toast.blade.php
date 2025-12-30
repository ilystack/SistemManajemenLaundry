<div x-data="{ 
        notifications: [], 
        displayDuration: 5000,
        soundEffect: true,
        addNotification({ variant = 'info', title = null, message = null }) {
            const id = Date.now();
            const notification = { id, variant, title, message };
            
            // Keep only the most recent 5 notifications
            if (this.notifications.length >= 5) {
                this.notifications.splice(0, this.notifications.length - 4);
            }
            
            // Add the new notification to the stack
            this.notifications.push(notification);
            
            // Play notification sound
            if (this.soundEffect) {
                try {
                    const notificationSound = new Audio('https://res.cloudinary.com/ds8pgw1pf/video/upload/v1728571480/penguinui/component-assets/sounds/ding.mp3');
                    notificationSound.play().catch((error) => {
                        console.log('Sound autoplay blocked by browser');
                    });
                } catch (error) {
                    console.log('Sound not available');
                }
            }
        },
        removeNotification(id) {
            setTimeout(() => {
                this.notifications = this.notifications.filter(
                    (notification) => notification.id !== id
                );
            }, 400);
        }
    }" x-on:notify.window="addNotification({ 
        variant: $event.detail.variant, 
        title: $event.detail.title, 
        message: $event.detail.message 
    })" class="fixed top-4 right-4 z-50 space-y-3 max-w-sm w-full pointer-events-none">
    <div x-on:mouseenter="$dispatch('pause-auto-dismiss')" x-on:mouseleave="$dispatch('resume-auto-dismiss')"
        class="space-y-3">
        <template x-for="notification in notifications" :key="notification.id">
            <div class="pointer-events-auto">
                <template x-if="notification.variant === 'success'">
                    <div x-data="{ isVisible: false, timeout: null }" x-cloak x-show="isVisible" role="alert"
                        x-on:pause-auto-dismiss.window="clearTimeout(timeout)"
                        x-on:resume-auto-dismiss.window="timeout = setTimeout(() => { isVisible = false; removeNotification(notification.id); }, displayDuration)"
                        x-init="$nextTick(() => { isVisible = true }); timeout = setTimeout(() => { isVisible = false; removeNotification(notification.id); }, displayDuration)"
                        x-transition:enter="transition duration-300 ease-out"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:enter-start="translate-y-8 opacity-0"
                        x-transition:leave="transition duration-300 ease-in"
                        x-transition:leave-end="translate-x-24 opacity-0"
                        x-transition:leave-start="translate-x-0 opacity-100"
                        class="bg-green-50 border border-green-500 text-green-900 rounded-lg p-4 flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 x-show="notification.title" class="text-sm font-semibold text-green-900"
                                x-text="notification.title">
                            </h3>
                            <p x-show="notification.message" class="text-sm mt-1 text-green-700"
                                x-text="notification.message"></p>
                        </div>
                        <button type="button"
                            class="flex-shrink-0 text-green-600 hover:text-green-800 transition-colors"
                            aria-label="dismiss notification"
                            x-on:click="isVisible = false; removeNotification(notification.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                fill="none" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                <!-- Danger Notification -->
                <template x-if="notification.variant === 'danger'">
                    <div x-data="{ isVisible: false, timeout: null }" x-cloak x-show="isVisible" role="alert"
                        x-on:pause-auto-dismiss.window="clearTimeout(timeout)"
                        x-on:resume-auto-dismiss.window="timeout = setTimeout(() => { isVisible = false; removeNotification(notification.id); }, displayDuration)"
                        x-init="$nextTick(() => { isVisible = true }); timeout = setTimeout(() => { isVisible = false; removeNotification(notification.id); }, displayDuration)"
                        x-transition:enter="transition duration-300 ease-out"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:enter-start="translate-y-8 opacity-0"
                        x-transition:leave="transition duration-300 ease-in"
                        x-transition:leave-end="translate-x-24 opacity-0"
                        x-transition:leave-start="translate-x-0 opacity-100"
                        class="bg-red-50 border border-red-500 text-red-900 rounded-lg p-4 flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 x-show="notification.title" class="text-sm font-semibold text-red-900"
                                x-text="notification.title">
                            </h3>
                            <p x-show="notification.message" class="text-sm mt-1 text-red-700"
                                x-text="notification.message"></p>
                        </div>
                        <button type="button" class="flex-shrink-0 text-red-600 hover:text-red-800 transition-colors"
                            aria-label="dismiss notification"
                            x-on:click="isVisible = false; removeNotification(notification.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                fill="none" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                <!-- Warning Notification -->
                <template x-if="notification.variant === 'warning'">
                    <div x-data="{ isVisible: false, timeout: null }" x-cloak x-show="isVisible" role="alert"
                        x-on:pause-auto-dismiss.window="clearTimeout(timeout)"
                        x-on:resume-auto-dismiss.window="timeout = setTimeout(() => { isVisible = false; removeNotification(notification.id); }, displayDuration)"
                        x-init="$nextTick(() => { isVisible = true }); timeout = setTimeout(() => { isVisible = false; removeNotification(notification.id); }, displayDuration)"
                        x-transition:enter="transition duration-300 ease-out"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:enter-start="translate-y-8 opacity-0"
                        x-transition:leave="transition duration-300 ease-in"
                        x-transition:leave-end="translate-x-24 opacity-0"
                        x-transition:leave-start="translate-x-0 opacity-100"
                        class="bg-amber-50 border border-amber-500 text-amber-900 rounded-lg p-4 flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 x-show="notification.title" class="text-sm font-semibold text-amber-900"
                                x-text="notification.title">
                            </h3>
                            <p x-show="notification.message" class="text-sm mt-1 text-amber-700"
                                x-text="notification.message"></p>
                        </div>
                        <button type="button"
                            class="flex-shrink-0 text-amber-600 hover:text-amber-800 transition-colors"
                            aria-label="dismiss notification"
                            x-on:click="isVisible = false; removeNotification(notification.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                fill="none" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                <!-- Info Notification -->
                <template x-if="notification.variant === 'info'">
                    <div x-data="{ isVisible: false, timeout: null }" x-cloak x-show="isVisible" role="alert"
                        x-on:pause-auto-dismiss.window="clearTimeout(timeout)"
                        x-on:resume-auto-dismiss.window="timeout = setTimeout(() => { isVisible = false; removeNotification(notification.id); }, displayDuration)"
                        x-init="$nextTick(() => { isVisible = true }); timeout = setTimeout(() => { isVisible = false; removeNotification(notification.id); }, displayDuration)"
                        x-transition:enter="transition duration-300 ease-out"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:enter-start="translate-y-8 opacity-0"
                        x-transition:leave="transition duration-300 ease-in"
                        x-transition:leave-end="translate-x-24 opacity-0"
                        x-transition:leave-start="translate-x-0 opacity-100"
                        class="bg-sky-50 border border-sky-500 text-sky-900 rounded-lg p-4 flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 x-show="notification.title" class="text-sm font-semibold text-sky-900"
                                x-text="notification.title">
                            </h3>
                            <p x-show="notification.message" class="text-sm mt-1 text-sky-700"
                                x-text="notification.message"></p>
                        </div>
                        <button type="button" class="flex-shrink-0 text-sky-600 hover:text-sky-800 transition-colors"
                            aria-label="dismiss notification"
                            x-on:click="isVisible = false; removeNotification(notification.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                fill="none" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>

<!-- Auto-trigger from Laravel Session Flash -->
@if(session('toast'))
    <script>
        (function () {
            if (window.toastTriggered) return;
            window.toastTriggered = true;

            document.addEventListener('DOMContentLoaded', function () {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        variant: '{{ session('toast.variant', 'info') }}',
                        title: '{{ session('toast.title') }}',
                        message: '{{ session('toast.message') }}'
                    }
                }));
            });
        })();
    </script>
@endif