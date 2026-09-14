@extends('layouts.app')

@section('title', $post->title.' | Ruby100 Blog')
@section('meta_description', $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 150))
@section('canonical', route('blog.show', $post->slug))

@section('content')
<article class="pt-28">
    @if ($post->coverImageUrl())
        <div class="mx-auto max-w-5xl px-5 md:px-8">
            <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="aspect-21/9 w-full object-cover">
        </div>
    @endif
    <div class="mx-auto max-w-3xl px-5 py-12 md:px-8">
        <a href="{{ route('blog.index') }}" class="text-sm font-bold uppercase tracking-wide text-ruby">← Blog</a>
        <p class="mt-6 text-xs font-bold uppercase tracking-wider text-mist">{{ optional($post->published_at)->format('d M Y') }}</p>
        <h1 class="mt-2 font-display text-4xl font-extrabold tracking-tight md:text-5xl">{{ $post->title }}</h1>
        <div class="mt-8 whitespace-pre-line leading-relaxed text-steel">{{ $post->body }}</div>
        @if ($settings->whatsappUrl())
            <a href="{{ $settings->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-wa mt-10">Need help? WhatsApp us</a>
        @endif
    </div>
</article>
@endsection
