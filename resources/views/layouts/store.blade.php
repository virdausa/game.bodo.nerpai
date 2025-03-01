@extends('layouts.base', [
    'navbar_left' => [
        'navbar-store-switcher',
    ],
    'navbar_right' => [
        'navbar-company-switcher',
    ],
    'navbar_dropdown_user' => [
        'navbar-user-back-lobby',
    ],
])

@section('sidebar')
    @include('layouts.sidebar-store')
@endsection

@section('main-content')
    {{ $slot }}
    @include('layouts.footer')
@endsection