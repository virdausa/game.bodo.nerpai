<div x-data="{ isOpen: false, item: {} }" class="relative">
    <!-- Trigger Button -->
    <button @click="isOpen = true" type="button" class="inline-block p-2 bg-yellow-300 rounded-lg">
        <svg class="w-5 h-5 text-stone-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M21 6.75736L19 8.75736V4H10V9H5V20H19V17.2426L21 15.2426V21.0082C21 21.556 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5501 3 20.9932V8L9.00319 2H19.9978C20.5513 2 21 2.45531 21 2.9918V6.75736ZM21.7782 8.80761L23.1924 10.2218L15.4142 18L13.9979 17.9979L14 16.5858L21.7782 8.80761Z"></path>
        </svg>
    </button>

    <!-- Modal Dialog -->
    <div x-cloak x-show="isOpen"
         class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $title }}</h3>
                <button @click="isOpen = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    &times;
                </button>
            </div>

            <!-- Modal Content -->
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
