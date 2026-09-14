<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\QuoteRequests\QuoteRequestResource;
use App\Models\QuoteRequest;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuoteStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $new = QuoteRequest::query()->where('status', 'new')->count();
        $contacted = QuoteRequest::query()->where('status', 'contacted')->count();
        $today = QuoteRequest::query()->whereDate('created_at', today())->count();
        $cash = QuoteRequest::query()->where('type', 'cash_for_cars')->where('status', 'new')->count();
        $week = QuoteRequest::query()->where('created_at', '>=', now()->subDays(7))->count();
        $closed = QuoteRequest::query()->where('status', 'closed')->count();

        return [
            Stat::make('New quotes', $new)
                ->description('Awaiting first contact')
                ->descriptionIcon(Heroicon::OutlinedBellAlert)
                ->color('danger')
                ->url(QuoteRequestResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'new']]])),
            Stat::make('Cash for cars', $cash)
                ->description('New cash offers')
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->color('warning')
                ->url(QuoteRequestResource::getUrl('index', ['tableFilters' => ['type' => ['value' => 'cash_for_cars']]])),
            Stat::make('Today', $today)
                ->description('Received today')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->color('primary'),
            Stat::make('Last 7 days', $week)
                ->description("Contacted: {$contacted} · Closed: {$closed}")
                ->descriptionIcon(Heroicon::OutlinedChartBar)
                ->color('success')
                ->url(QuoteRequestResource::getUrl('index')),
        ];
    }
}
