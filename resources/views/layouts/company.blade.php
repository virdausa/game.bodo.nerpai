@extends('layouts.base')

@section('navbar')
    @include('layouts.company-navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar-company')
@endsection

@section('main-content')
    {{ $slot }}
    @include('layouts.footer')
@endsection