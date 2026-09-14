@extends('errors.layout')

@section('title', 'Payment Required')
@section('code', '402')
@section('heading', 'Payment required')
@section('message', 'This request can’t continue without payment authorisation. Contact Ruby100 if you need help.')

@section('actions')
    <a class="btn btn-ruby" href="{{ url('/') }}">Back to Home</a>
    <a class="btn btn-ink" href="tel:+61401724002">Call Us</a>
@endsection
