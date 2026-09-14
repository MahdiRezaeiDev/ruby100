@extends('errors.layout')

@section('title', 'Forbidden')
@section('code', '403')
@section('heading', 'Access denied')
@section('message', 'You don’t have permission to view this page. If you think this is a mistake, call or WhatsApp Ruby100.')

@section('actions')
    <a class="btn btn-ruby" href="{{ url('/') }}">Back to Home</a>
    <a class="btn btn-ink" href="tel:+61401724002">Call 24/7</a>
    <a class="btn btn-ghost" href="{{ url('/admin/login') }}">Admin Login</a>
@endsection
