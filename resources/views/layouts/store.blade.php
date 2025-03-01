@extends('layouts.base')

@section('navbar')
    @include('layouts.company-navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar-store')
@endsection

@section('main-content')
    {{ $slot }}
    @include('layouts.footer')
@endsection