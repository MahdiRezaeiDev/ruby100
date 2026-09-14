<footer class="bg-ink text-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-16 md:grid-cols-[1.4fr_1fr] md:px-8">
        <div>
            <p class="font-display text-5xl font-extrabold tracking-tight">RUBY100</p>
            <p class="mt-4 max-w-md text-white/65">{{ $settings->area }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="tel:{{ $settings->phone }}" class="btn-ruby">{{ $settings->phone_display }}</a>
                @if ($settings->whatsappUrl())
                    <a href="{{ $settings->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-wa">WhatsApp Us</a>
                @endif
            </div>
        </div>
        <div class="flex flex-col justify-end gap-3 text-sm text-white/55 md:items-end">
            <a href="{{ route('privacy') }}" class="hover:text-white">Privacy Policy</a>
            <a href="{{ route('blog.index') }}" class="hover:text-white">Blog</a>
            <p>&copy; {{ date('Y') }} Ruby100. All rights reserved.</p>
        </div>
    </div>
</footer>
