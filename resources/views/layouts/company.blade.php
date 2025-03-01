@extends('layouts.base', [
    'navbar_left' => [
        'navbar-company-name',
    ],
    'navbar_right' => [
        'navbar-company-switcher',
    ],
    'navbar_dropdown_user' => [
        'navbar-user-back-lobby',
    ],
])

@section('sidebar')
    @include('layouts.sidebar-company')
@endsection

@section('main-content')
    {{ $slot }}
    @include('layouts.footer')
@endsection