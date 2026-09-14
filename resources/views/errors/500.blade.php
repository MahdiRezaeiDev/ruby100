@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('heading', 'Something broke on our side')
@section('message', 'We’re already on it. Please try again shortly — for roadside emergencies, call or WhatsApp us now.')

@section('actions')
    <a class="btn btn-ruby" href="{{ url('/') }}">Back to Home</a>
    <a class="btn btn-ink" href="tel:+61401724002">Call 24/7</a>
    <a class="btn btn-wa" href="https://wa.me/61401724002?text={{ rawurlencode('Hi Ruby100, I need urgent help.') }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
@endsection
