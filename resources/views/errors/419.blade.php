@extends('errors.layout')

@section('title', 'Page Expired')
@section('code', '419')
@section('heading', 'Session expired')
@section('message', 'Your form session timed out for security. Refresh the page and try again — or WhatsApp us if you’re in a hurry.')

@section('actions')
    <a class="btn btn-ruby" href="{{ url()->previous() ?: url('/') }}">Try Again</a>
    <a class="btn btn-wa" href="https://wa.me/61401724002?text={{ rawurlencode('Hi Ruby100, I need help.') }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
    <a class="btn btn-ghost" href="{{ url('/') }}">Home</a>
@endsection
