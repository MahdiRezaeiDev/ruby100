@extends('layouts.app')

@section('title', 'Privacy Policy | Ruby100 Towing & Car Removal')
@section('meta_description', 'Privacy Policy for Ruby100 — how we collect, use, and protect your personal information.')
@section('canonical', route('privacy'))
@section('og_title', 'Privacy Policy | Ruby100')

@section('content')
<section class="mx-auto max-w-3xl px-5 pb-20 pt-32">
    <p class="section-kicker mb-3">Legal</p>
    <h1 class="font-headline text-4xl uppercase md:text-5xl">Privacy Policy</h1>
    <p class="mt-4 text-mist">Last updated: August 2026</p>

    <div class="mt-10 space-y-8 leading-relaxed text-mist">
        <section>
            <h2 class="font-headline text-2xl uppercase text-snow">Who we are</h2>
            <p class="mt-3">
                Ruby100 (“we”, “us”) provides towing and car removal services in Australia. Contact:
                <a class="text-amber hover:underline" href="tel:{{ $phone }}">{{ $phoneDisplay }}</a>.
            </p>
        </section>

        <section>
            <h2 class="font-headline text-2xl uppercase text-snow">Information we collect</h2>
            <p class="mt-3">
                When you request a quote or contact us, we may collect your name, phone number, email address,
                service details, and message content. We also receive basic technical data such as IP address
                when you submit a form.
            </p>
        </section>

        <section>
            <h2 class="font-headline text-2xl uppercase text-snow">How we use your information</h2>
            <p class="mt-3">
                We use your details solely to respond to enquiries, provide quotes, deliver services, and improve
                our operations. We do not sell your personal information.
            </p>
        </section>

        <section>
            <h2 class="font-headline text-2xl uppercase text-snow">Retention &amp; security</h2>
            <p class="mt-3">
                Quote requests are stored securely in our application database and may trigger an internal email
                notification. We keep personal information only as long as needed or as required by Australian law.
            </p>
        </section>

        <section>
            <h2 class="font-headline text-2xl uppercase text-snow">Your rights</h2>
            <p class="mt-3">
                Under the Australian Privacy Principles, you may request access to or correction of your personal
                information. Call
                <a class="text-amber hover:underline" href="tel:{{ $phone }}">{{ $phoneDisplay }}</a>
                to make a request.
            </p>
        </section>
    </div>
</section>
@endsection
