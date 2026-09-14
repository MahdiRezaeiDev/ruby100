@extends('layouts.app')

@section('title', 'Blog | Ruby100 Towing & Car Removal')
@section('meta_description', 'Tips and guides on towing, car removal, and roadside help around Melbourne.')
@section('canonical', route('blog.index'))

@section('content')
<section class="bg-ink px-5 pb-12 pt-32 text-white md:px-8">
    <div class="mx-auto max-w-7xl">
        <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">Blog</p>
        <h1 class="mt-3 font-display text-5xl font-extrabold tracking-tight">Guides &amp; tips</h1>
    </div>
</section>

<section class="mx-auto max-w-7xl px-5 py-14 md:px-8">
    <div class="grid gap-8 md:grid-cols-3">
        @forelse ($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block">
                @if ($post->coverImageUrl())
                    <div class="aspect-16/10 overflow-hidden">
                        <img loading="lazy" decoding="async" width="1200" height="800" src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition group-hover:scale-105">
                    </div>
                @endif
                <p class="mt-4 text-xs font-bold uppercase tracking-wider text-mist">{{ optional($post->published_at)->format('d M Y') }}</p>
                <h2 class="mt-2 font-display text-2xl font-extrabold tracking-tight group-hover:text-ruby">{{ $post->title }}</h2>
                <p class="mt-2 text-sm text-mist">{{ $post->excerpt }}</p>
            </a>
        @empty
            <p class="text-mist">No posts published yet.</p>
        @endforelse
    </div>
    <div class="mt-10">{{ $posts->links() }}</div>
</section>
@endsection

