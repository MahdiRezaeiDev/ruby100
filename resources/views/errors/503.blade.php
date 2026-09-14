@extends('errors.layout')

@section('title', 'Service Unavailable')
@section('code', '503')
@section('heading', 'We’ll be back shortly')
@section('message', 'Ruby100’s website is temporarily down for maintenance. Towing & car removal are still available by phone and WhatsApp.')

@section('actions')
    <a class="btn btn-ink" href="tel:+61401724002">Call 24/7</a>
    <a class="btn btn-wa" href="https://wa.me/61401724002?text={{ rawurlencode('Hi Ruby100, I need help.') }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
    <a class="btn btn-ghost" href="{{ url('/') }}">Try Home</a>
@endsection
