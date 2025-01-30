<textarea x-data="{ value: @js($value ?? '') }" x-model="value"
    {{ $attributes->merge(['class' => 'w-full bg-gray-50 border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300']) }}>
</textarea>

<style>
    input:-webkit-autofill {
        background-color: #2d3748 !important;
        /* Tailwind bg-gray-700 in hex */
        color: white !important;
        /* Ensures text remains visible */
    }

    /* Optional: for Firefox, it uses :-moz-autofill */
    input:-moz-autofill {
        background-color: #2d3748 !important;
        /* Tailwind bg-gray-700 in hex */
        color: white !important;
        /* Ensures text remains visible */
    }
</style>
