<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\GalleryImages\GalleryImageResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\GalleryImage;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceArea;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Active services', Service::query()->where('is_active', true)->count())
                ->description('Shown on website')
                ->descriptionIcon(Heroicon::OutlinedWrenchScrewdriver)
                ->color('primary')
                ->url(ServiceResource::getUrl('index')),
            Stat::make('Gallery photos', GalleryImage::query()->where('is_active', true)->count())
                ->description('Fleet & work images')
                ->descriptionIcon(Heroicon::OutlinedPhoto)
                ->color('gray')
                ->url(GalleryImageResource::getUrl('index')),
            Stat::make('Published posts', Post::query()->published()->count())
                ->description('Live on blog')
                ->descriptionIcon(Heroicon::OutlinedNewspaper)
                ->color('success')
                ->url(PostResource::getUrl('index')),
            Stat::make('Service areas', ServiceArea::query()->where('is_active', true)->count())
                ->description('Coverage suburbs')
                ->descriptionIcon(Heroicon::OutlinedMapPin)
                ->color('warning'),
        ];
    }
}
