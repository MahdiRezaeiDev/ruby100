{{-- Kept for Laravel internals; branded layout is errors.layout --}}
@extends('errors.layout')

@section('title')
    @yield('title')
@endsection

@section('code')
    @yield('code')
@endsection

@section('heading')
    @yield('message')
@endsection

@section('message', '')

@section('actions')
    <a class="btn btn-ruby" href="{{ url('/') }}">Back to Home</a>
@endsection
