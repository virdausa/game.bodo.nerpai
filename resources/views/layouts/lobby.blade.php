@extends('layouts.base', [
    'navbar_left' => [
        'navbar-nerpai-name',
    ],
    'navbar_dropdown_user' => [
        'navbar-user-profile',
        'navbar-user-logout',
    ],
])

@section('sidebar')
    @include('layouts.sidebar-lobby')
@endsection

@section('main-content')
    {{ $slot }}
    @include('layouts.footer')
@endsection