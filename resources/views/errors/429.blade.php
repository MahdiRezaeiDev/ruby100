@extends('errors.layout')

@section('title', 'Too Many Requests')
@section('code', '429')
@section('heading', 'Slow down for a moment')
@section('message', 'Too many requests came through at once. Wait a few seconds, then try again — or call / WhatsApp for urgent help.')

@section('actions')
    <a class="btn btn-ruby" href="{{ url('/') }}">Back to Home</a>
    <a class="btn btn-ink" href="tel:+61401724002">Call 24/7</a>
    <a class="btn btn-wa" href="https://wa.me/61401724002?text={{ rawurlencode('Hi Ruby100, I need urgent help.') }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
@endsection
