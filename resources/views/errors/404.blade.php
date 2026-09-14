@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('heading', 'This page took a wrong turn')
@section('message', 'We couldn’t find what you’re looking for. Head home, request a quote, or message us on WhatsApp for immediate help.')

@section('actions')
    <a class="btn btn-ruby" href="{{ url('/') }}">Back to Home</a>
    <a class="btn btn-ink" href="{{ url('/#quote') }}">Free Quote</a>
    <a class="btn btn-wa" href="https://wa.me/61401724002?text={{ rawurlencode('Hi Ruby100, I need help.') }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
@endsection
