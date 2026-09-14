@extends('layouts.app')

@section('canonical', url('/'))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => 'Ruby100',
    'url' => url('/'),
    'telephone' => $settings->phone,
    'image' => $settings->heroImageUrl(),
    'description' => $settings->seo_description,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $settings->address,
        'addressLocality' => 'Endeavour Hills',
        'addressRegion' => 'VIC',
        'postalCode' => '3802',
        'addressCountry' => 'AU',
    ],
    'areaServed' => 'Australia',
], JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
{{-- Hero: full-bleed photo, brand-first, sharp utility CTAs --}}
<section class="relative min-h-[100svh] overflow-hidden bg-ink">
    @if ($settings->heroImageUrl())
        <img loading="eager" fetchpriority="high" decoding="async" width="1600" height="1200"
            src="{{ $settings->heroImageUrl() }}"
            alt="Ruby100 tow truck roadside assistance in Melbourne"
            class="absolute inset-0 h-full w-full object-cover"
        >
    @endif
    <div class="absolute inset-0 bg-[linear-gradient(115deg,rgb(15_20_25/0.92)_0%,rgb(15_20_25/0.55)_48%,rgb(15_20_25/0.25)_100%)]"></div>

    <div class="relative mx-auto flex min-h-[100svh] max-w-7xl flex-col justify-end px-5 pb-16 pt-28 md:justify-center md:px-8 md:pb-24">
        <p class="rise r1 text-xs font-bold uppercase tracking-[0.35em] text-white/70">{{ $settings->hero_kicker }}</p>
        <h1 class="rise r2 mt-4 font-display text-[clamp(4rem,14vw,9.5rem)] font-extrabold leading-[0.85] tracking-tight text-white">
            {{ $settings->hero_title }}
        </h1>
        <div class="rise r2 mt-4 h-1.5 w-28 bg-ruby"></div>
        <p class="rise r3 mt-6 max-w-lg text-lg text-white/85 md:text-xl">{{ $settings->hero_subtitle }}</p>
        <div class="rise r4 mt-9 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
            <a href="#quote" class="btn-ruby">Free Quote</a>
            @if ($settings->whatsappUrl())
                <a href="{{ $settings->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-wa">WhatsApp Now</a>
            @endif
            <a href="tel:{{ $settings->phone }}" class="btn-ghost-light">Call {{ $settings->phone_display }}</a>
        </div>
    </div>
</section>

{{-- Signal strip --}}
<section class="bg-ruby">
    <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-4 font-display text-sm font-bold uppercase tracking-[0.2em] text-white sm:flex-row sm:justify-between md:px-8">
        <span>24/7 Dispatch</span>
        <span>Cash for Cars</span>
        <span>Endeavour Hills Â· VIC</span>
    </div>
</section>

{{-- About: edge-bleed photo --}}
<section id="about" class="overflow-hidden py-20 md:py-28">
    <div class="mx-auto grid max-w-7xl items-stretch gap-0 md:grid-cols-2">
        <div class="reveal flex flex-col justify-center px-5 py-6 md:px-8 md:py-10">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">About</p>
            <h2 class="mt-4 font-display text-4xl font-extrabold leading-tight tracking-tight md:text-5xl">
                {{ $settings->about_title }}
            </h2>
            <div class="mt-6 whitespace-pre-line text-mist leading-relaxed">{{ $settings->about_body }}</div>
            <div class="mt-8 flex flex-wrap gap-3">
                @if ($settings->whatsappUrl())
                    <a href="{{ $settings->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-wa">Message on WhatsApp</a>
                @endif
                <a href="tel:{{ $settings->phone }}" class="btn-ink">Call Us</a>
            </div>
        </div>
        <div class="reveal d1 min-h-80 md:min-h-full">
            @if ($settings->aboutImageUrl())
                <img loading="lazy" decoding="async" width="1200" height="800"
                    src="{{ $settings->aboutImageUrl() }}"
                    alt="Ruby100 professional vehicle assistance"
                    class="h-full w-full object-cover"
                >
            @endif
        </div>
    </div>
</section>

{{-- Services as numbered bands --}}
<section id="services" class="bg-white py-20 md:py-28">
    <div class="mx-auto max-w-7xl px-5 md:px-8">
        <div class="reveal mb-12 max-w-xl">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">Services</p>
            <h2 class="mt-3 font-display text-4xl font-extrabold tracking-tight md:text-5xl">What we do, fast</h2>
        </div>

        <div class="divide-y divide-haze border-y border-haze">
            @foreach ($services as $index => $service)
                <article class="reveal {{ $index === 1 ? 'd1' : ($index === 2 ? 'd2' : '') }} grid gap-6 py-8 md:grid-cols-12 md:items-center">
                    <div class="font-display text-4xl font-extrabold text-ruby md:col-span-1">
                        {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="md:col-span-3">
                        @if ($service->imageUrl())
                            <img loading="lazy" decoding="async" width="1200" height="800" src="{{ $service->imageUrl() }}" alt="{{ $service->title }}" class="aspect-4/3 w-full object-cover">
                        @endif
                    </div>
                    <div class="md:col-span-3">
                        <h3 class="font-display text-2xl font-extrabold tracking-tight">{{ $service->title }}</h3>
                    </div>
                    <p class="text-mist md:col-span-5">{{ $service->summary }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Why us --}}
<section id="why-us" class="bg-steel py-20 text-white md:py-28">
    <div class="mx-auto max-w-7xl px-5 md:px-8">
        <div class="reveal mb-12 max-w-xl">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">Why Ruby100</p>
            <h2 class="mt-3 font-display text-4xl font-extrabold tracking-tight md:text-5xl">Built for urgent moments</h2>
        </div>
        <div class="grid gap-px bg-white/10 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($reasons as $reason)
                <article class="reveal bg-steel p-7">
                    <h3 class="font-display text-xl font-extrabold">{{ $reason->title }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-white/65">{{ $reason->copy }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Gallery --}}
@if ($gallery->isNotEmpty())
<section id="gallery" class="py-20 md:py-28">
    <div class="mx-auto max-w-7xl px-5 md:px-8">
        <div class="reveal mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">Fleet</p>
                <h2 class="mt-3 font-display text-4xl font-extrabold tracking-tight md:text-5xl">On the job</h2>
            </div>
            @if ($settings->whatsappUrl())
                <a href="{{ $settings->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-wa">Need a tow? WhatsApp</a>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-2 md:grid-cols-4 md:gap-3">
            @foreach ($gallery as $i => $image)
                <figure class="reveal group relative overflow-hidden {{ $i === 0 ? 'col-span-2 aspect-16/10 md:row-span-2 md:aspect-auto md:h-full' : 'aspect-square' }}">
                    <img loading="lazy" decoding="async" width="1200" height="800"
                        src="{{ $image->imageUrl() }}"
                        alt="{{ $image->caption ?: 'Ruby100 fleet' }}"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    >
                    @if ($image->caption)
                        <figcaption class="absolute inset-x-0 bottom-0 bg-linear-to-t from-ink/80 to-transparent p-3 text-xs font-semibold text-white">
                            {{ $image->caption }}
                        </figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Areas --}}
@if ($areas->isNotEmpty())
<section id="areas" class="border-y border-haze bg-white py-16">
    <div class="mx-auto max-w-7xl px-5 md:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">Coverage</p>
        <h2 class="mt-3 font-display text-3xl font-extrabold tracking-tight md:text-4xl">Suburbs we serve</h2>
        <div class="reveal mt-8 flex flex-wrap gap-2">
            @foreach ($areas as $area)
                <span class="bg-snow px-3 py-1.5 text-sm font-medium text-steel">{{ $area->name }}</span>
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($settings->reviewsEmbedUrl())
<section id="reviews" class="py-16">
    <div class="mx-auto max-w-7xl px-5 md:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">Reviews</p>
        <iframe class="mt-6 h-80 w-full border-0" src="{{ $settings->reviewsEmbedUrl() }}" title="Ruby100 on Google Maps" loading="lazy" referrerpolicy="no-referrer" sandbox="allow-scripts allow-same-origin allow-popups"></iframe>
    </div>
</section>
@endif

{{-- Quote --}}
<section id="quote" class="bg-snow py-20 md:py-28">
    <div class="mx-auto grid max-w-7xl gap-12 px-5 md:grid-cols-2 md:px-8">
        <div class="reveal">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">Free Quote</p>
            <h2 class="mt-3 font-display text-4xl font-extrabold tracking-tight md:text-5xl">Send details. Get a clear answer.</h2>
            <p class="mt-4 text-mist">Prefer chat? Message us on WhatsApp for the fastest reply.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                @if ($settings->whatsappUrl())
                    <a href="{{ $settings->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-wa">WhatsApp {{ $settings->phone_display }}</a>
                @endif
                <a href="tel:{{ $settings->phone }}" class="btn-ink">Call Now</a>
            </div>
        </div>

        <div class="reveal d1 bg-white p-6 md:p-8">
            @if (session('quote_success'))
                <div class="mb-5 border-l-4 border-wa bg-snow px-4 py-3 text-sm text-steel" role="status">
                    Thanks â€” weâ€™ve received your request and will be in touch shortly.
                </div>
            @endif

            <form action="{{ route('quote.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="hidden" aria-hidden="true">
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-mist">Name *
                        <input class="field mt-1" type="text" name="name" value="{{ old('name') }}" required>
                    </label>
                    <label class="block text-xs font-bold uppercase tracking-wider text-mist">Phone *
                        <input class="field mt-1" type="tel" name="phone" value="{{ old('phone') }}" required>
                    </label>
                </div>
                <label class="block text-xs font-bold uppercase tracking-wider text-mist">Email *
                    <input class="field mt-1" type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label class="block text-xs font-bold uppercase tracking-wider text-mist">Service *
                    <select class="field mt-1" name="service" required>
                        <option value="" disabled @selected(! old('service'))>Select a service</option>
                        @foreach (['Towing Services','Car Removal','Scrap Metal Collection','Emergency Assistance','Machinery Transport'] as $option)
                            <option value="{{ $option }}" @selected(old('service') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block text-xs font-bold uppercase tracking-wider text-mist">Message
                    <textarea class="field mt-1 min-h-28 resize-y" name="message">{{ old('message') }}</textarea>
                </label>
                @if ($errors->any())
                    <div class="text-sm text-ruby">{{ $errors->first() }}</div>
                @endif
                <button type="submit" class="btn-ruby w-full">Send Quote Request</button>
            </form>
        </div>
    </div>
</section>

{{-- Cash for cars --}}
<section id="cash-for-cars" class="bg-ruby py-20 text-white md:py-24">
    <div class="mx-auto grid max-w-7xl gap-10 px-5 md:grid-cols-2 md:items-center md:px-8">
        <div class="reveal">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-white/70">Cash for Cars</p>
            <h2 class="mt-3 font-display text-4xl font-extrabold tracking-tight md:text-5xl">Unwanted car? Get paid.</h2>
            <p class="mt-4 text-white/85">Same-day removal available. WhatsApp photos of the vehicle for a faster offer.</p>
            @if ($settings->whatsappUrl('Hi Ruby100, I want a cash offer for my car.'))
                <a href="{{ $settings->whatsappUrl('Hi Ruby100, I want a cash offer for my car.') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="btn-wa mt-8 !bg-white !text-wa-deep hover:!bg-snow">
                    WhatsApp Cash Offer
                </a>
            @endif
        </div>
        <div class="reveal d1 bg-white p-6 text-ink">
            @if (session('cash_success'))
                <div class="mb-4 border-l-4 border-wa px-4 py-3 text-sm" role="status">
                    Request received â€” weâ€™ll contact you with a cash offer soon.
                </div>
            @endif
            <form action="{{ route('cash.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="hidden" aria-hidden="true">
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>
                <input type="hidden" name="service" value="Cash for Cars">
                <input class="field" type="text" name="name" aria-label="Your name" autocomplete="name" maxlength="120" placeholder="Your name *" required>
                <input class="field" type="tel" name="phone" aria-label="Phone number" autocomplete="tel" maxlength="40" placeholder="Phone *" required>
                <input class="field" type="email" name="email" aria-label="Email address" autocomplete="email" maxlength="180" placeholder="Email *" required>
                <textarea class="field min-h-24" name="message" aria-label="Vehicle details" maxlength="2000" placeholder="Car make, model, year, conditionâ€¦"></textarea>
                <button type="submit" class="btn-ink w-full">Get a Cash Offer</button>
            </form>
        </div>
    </div>
</section>

@if ($posts->isNotEmpty())
<section class="py-20 md:py-28">
    <div class="mx-auto max-w-7xl px-5 md:px-8">
        <div class="mb-10 flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-ruby">Blog</p>
                <h2 class="mt-3 font-display text-4xl font-extrabold tracking-tight">Guides</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-bold uppercase tracking-wide text-ruby hover:underline">View all</a>
        </div>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="reveal group block">
                    @if ($post->coverImageUrl())
                        <div class="aspect-16/10 overflow-hidden">
                            <img loading="lazy" decoding="async" width="1200" height="800" src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        </div>
                    @endif
                    <h3 class="mt-4 font-display text-xl font-extrabold tracking-tight group-hover:text-ruby">{{ $post->title }}</h3>
                    <p class="mt-2 text-sm text-mist">{{ $post->excerpt }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

