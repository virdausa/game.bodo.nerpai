<a href="{{ route('dashboard') }}" class="flex ms-2 mr-4">
    <img src="{{ asset('svg/hehe.svg') }}" class="h-8 me-3 dark:invert" alt="HEHE" />
    <!-- <img src="{{ asset('svg/'.session('company_id').'.svg') }}" class="h-8 me-3 dark:invert" alt="HEHE" /> -->
    <span class="self-center text-xl font-semibold sm:text-2xl dark:text-white">{{ session('company_name'); }}</span>
</a>