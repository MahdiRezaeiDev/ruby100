@extends('errors.layout')

@section('title', 'Unauthorized')
@section('code', '401')
@section('heading', 'Sign in required')
@section('message', 'This area is for authorised Ruby100 staff only. Please sign in to continue.')

@section('actions')
    <a class="btn btn-ruby" href="{{ url('/admin/login') }}">Admin Login</a>
    <a class="btn btn-ghost" href="{{ url('/') }}">Back to Home</a>
@endsection
