@extends('layouts.base')

@section('sidebar')
    @include('layouts.sidebar-lobby')
@endsection

@section('main-content')
    {{ $slot }}
    @include('layouts.footer')
@endsection