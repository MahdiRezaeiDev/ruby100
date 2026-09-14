@extends('layouts.app')

@section('title', $page->title.' | Ruby100')
@section('canonical', route('pages.show', $page->slug))

@section('content')
<section class="mx-auto max-w-3xl px-5 pb-20 pt-32 md:px-8">
    <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">Info</p>
    <h1 class="mt-3 font-display text-4xl font-extrabold tracking-tight md:text-5xl">{{ $page->title }}</h1>
    <div class="mt-8 whitespace-pre-line leading-relaxed text-mist">{{ $page->body }}</div>
</section>
@endsection
