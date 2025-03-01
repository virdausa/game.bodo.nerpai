<div class="mr-3 relative" x-data="{ open: false }">
    <button @click="open = !open"
        class="flex items-center p-2 text-sm text-gray-500 rounded-lg dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700">
        <span>{{ session('company_name') }}</span>
        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div x-show="open" @click.outside="open = false"
    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 dark:bg-gray-700">
        @foreach (\App\Models\User::find(auth()->user()->id)->approvedCompanies as $company)
            <form method="POST" action="{{ route('companies.switch', $company->id) }}">
            @csrf
                <button type="submit" name="company_id" value="{{ $company->id }}"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">
                    {{ $company->name }}
                </button>
            </form>
        @endforeach
        <hr class="dark:border-gray-600 border-gray-200">
        <a href="{{ route('exit.company', 'companies.index') }}">
            <button type="button"
            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">
                {{ __('List Perusahaan') }}
            </button>
        </a>
    </div>
</div>