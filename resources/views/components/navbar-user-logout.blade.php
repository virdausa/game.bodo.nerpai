
<form method="POST" action="{{ route('logout') }}">
    @csrf

    <x-responsive-nav-link-danger :href="route('logout')" onclick="event.preventDefault();
        this.closest('form').submit();">
        {{ __('Log Out') }}
        </x-responsive-nav-link>
</form>