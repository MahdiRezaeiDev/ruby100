<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageSiteSettings;
use App\Filament\Resources\GalleryImages\GalleryImageResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\QuoteRequests\QuoteRequestResource;
use App\Filament\Resources\Services\ServiceResource;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    protected string $view = 'filament.widgets.quick-actions';

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'New quotes',
                    'description' => 'Review & update status',
                    'url' => QuoteRequestResource::getUrl('index'),
                    'color' => '#C8102E',
                ],
                [
                    'label' => 'Site settings',
                    'description' => 'Phone, WhatsApp, SEO, hero',
                    'url' => ManageSiteSettings::getUrl(),
                    'color' => '#1c2733',
                ],
                [
                    'label' => 'Add service',
                    'description' => 'Towing / removal / scrap',
                    'url' => ServiceResource::getUrl('create'),
                    'color' => '#0f1419',
                ],
                [
                    'label' => 'Upload gallery',
                    'description' => 'Fleet photos',
                    'url' => GalleryImageResource::getUrl('create'),
                    'color' => '#25D366',
                ],
                [
                    'label' => 'Write blog post',
                    'description' => 'Local SEO content',
                    'url' => PostResource::getUrl('create'),
                    'color' => '#5c6b7a',
                ],
                [
                    'label' => 'View website',
                    'description' => 'Open public site',
                    'url' => url('/'),
                    'color' => '#C8102E',
                    'external' => true,
                ],
            ],
        ];
    }
}
