<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('Ruby100 Admin')
            ->brandLogo(fn (): HtmlString => new HtmlString(
                '<div style="display:flex;align-items:center;gap:.65rem">'
                .'<span style="display:grid;place-items:center;width:2.1rem;height:2.1rem;background:#c8102e;color:#fff;font-weight:800;font-size:.95rem">R</span>'
                .'<span style="font-weight:800;letter-spacing:-.02em;color:#0f1419">RUBY100</span>'
                .'</div>'
            ))
            ->brandLogoHeight('2.1rem')
            ->favicon(asset('favicon.svg'))
            ->colors([
                'primary' => Color::hex('#C8102E'),
                'danger' => Color::hex('#C8102E'),
                'success' => Color::hex('#25D366'),
            ])
            ->darkMode(false)
            ->maxContentWidth(Width::Full)
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => Blade::render(
                    '<p class="fi-login-footer" style="margin-top:1.25rem;text-align:center;font-size:.8rem;color:#5c6b7a">'
                    .'Need the site? <a href="{{ url(\'/\') }}" style="color:#c8102e;font-weight:700">View Ruby100 website</a>'
                    .'<br>Emergency: <a href="tel:+61401724002" style="color:#0f1419;font-weight:700">+61 401 724 002</a>'
                    .'</p>'
                ),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
