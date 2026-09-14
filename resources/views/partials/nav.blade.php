<header id="site-nav"
        class="site-nav fixed inset-x-0 top-0 z-50 {{ request()->routeIs('home') ? '' : 'is-solid' }}"
        data-always-solid="{{ request()->routeIs('home') ? '0' : '1' }}">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 md:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Ruby100 home">
            <span class="grid h-10 w-10 place-items-center bg-ruby font-display text-lg font-extrabold text-white">R</span>
            <span class="nav-brand font-display text-xl font-extrabold tracking-tight">RUBY100</span>
        </a>

        <nav class="hidden items-center gap-8 text-sm font-semibold md:flex" aria-label="Primary">
            <a href="{{ route('home') }}#about" class="nav-link">About</a>
            <a href="{{ route('home') }}#services" class="nav-link">Services</a>
            <a href="{{ route('home') }}#gallery" class="nav-link">Fleet</a>
            <a href="{{ route('blog.index') }}" class="nav-link">Blog</a>
            <a href="{{ route('home') }}#quote" class="nav-link">Quote</a>
        </nav>

        <div class="hidden items-center gap-2 md:flex">
            @if ($settings->whatsappUrl())
                <a href="{{ $settings->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-wa !px-4 !py-2.5 !text-xs">
                    WhatsApp
                </a>
            @endif
            <a href="tel:{{ $settings->phone }}" class="btn-ruby !px-4 !py-2.5 !text-xs">
                Call 24/7
            </a>
        </div>

        <button id="menu-toggle" type="button"
                class="nav-menu-btn grid h-11 w-11 place-items-center border md:hidden"
                aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
    </div>

    <div id="mobile-menu" class="hidden border-t border-haze bg-white px-5 py-5 text-ink md:hidden">
        <div class="flex flex-col gap-3 text-base font-semibold">
            <a href="{{ route('home') }}#about" data-close-menu>About</a>
            <a href="{{ route('home') }}#services" data-close-menu>Services</a>
            <a href="{{ route('home') }}#gallery" data-close-menu>Fleet</a>
            <a href="{{ route('blog.index') }}" data-close-menu>Blog</a>
            <a href="{{ route('home') }}#quote" data-close-menu>Quote</a>
            @if ($settings->whatsappUrl())
                <a href="{{ $settings->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-wa mt-2 text-center">WhatsApp</a>
            @endif
            <a href="tel:{{ $settings->phone }}" class="btn-ruby text-center">Call Now</a>
        </div>
    </div>
</header>
