<!DOCTYPE html>
<html lang="en-AU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $settings->seo_title ?? 'Ruby100 Towing & Car Removal')</title>
    <meta name="description" content="@yield('meta_description', $settings->seo_description ?? '')">
    <link rel="canonical" href="@yield('canonical', url('/'))">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', $settings->seo_title ?? 'Ruby100')">
    <meta property="og:description" content="@yield('meta_description', $settings->seo_description ?? '')">
    <meta property="og:image" content="{{ $settings->heroImageUrl() }}">

    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden bg-snow text-ink">
    @include('partials.nav')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-3">
        @if ($settings->whatsappUrl())
            <a href="{{ $settings->whatsappUrl() }}"
               target="_blank"
               rel="noopener noreferrer"
               class="wa-fab inline-flex items-center gap-2 bg-wa px-5 py-3.5 text-sm font-bold uppercase tracking-wide text-white"
               aria-label="Message Ruby100 on WhatsApp">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M20.52 3.48A11.8 11.8 0 0012.04 0C5.5 0 .2 5.3.2 11.82c0 2.08.55 4.1 1.6 5.9L0 24l6.45-1.69a11.8 11.8 0 005.58 1.42h.01c6.54 0 11.84-5.3 11.84-11.82 0-3.16-1.23-6.13-3.36-8.43zM12.04 21.6h-.01a9.78 9.78 0 01-4.98-1.36l-.36-.21-3.83 1 1.02-3.73-.24-.38a9.76 9.76 0 01-1.5-5.2c0-5.4 4.4-9.8 9.82-9.8 2.62 0 5.09 1.02 6.94 2.87a9.72 9.72 0 012.88 6.93c0 5.4-4.4 9.8-9.82 9.8zm5.38-7.33c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.64.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.74-1.64-2.04-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.5h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.08 4.48.71.3 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.75-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35z"/>
                </svg>
                WhatsApp
            </a>
        @endif
        <a href="tel:{{ $settings->phone }}"
           class="inline-flex items-center gap-2 bg-ruby px-5 py-3.5 text-sm font-bold uppercase tracking-wide text-white md:hidden"
           aria-label="Call Ruby100 now">
            Call
        </a>
    </div>
</body>
</html>
