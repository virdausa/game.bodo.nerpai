@props([
    'icon' => 'icon-sidebar',
    'route' => '#',
    'text' => 'Lobby',
    'route_params' => [],
])

<a href="{{ route($route, $route_params) }}"
    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
    @if ($icon)
        @if ($icon == '')
            {{ $icon = 'icon-sidebar' }}
        @endif
        <x-dynamic-component :component="'icons.' . $icon" class="flex-shrink-0 w-4 h-4 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" />
    @endif
    <div class='sidebar-text'>
        <span class="flex-1 ms-3 whitespace-nowrap">{{ $text }}</span>
    </div>
</a>