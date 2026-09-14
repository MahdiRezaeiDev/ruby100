<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ContentStatsOverview;
use App\Filament\Widgets\LatestQuotesWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\QuotesPerDayChart;
use App\Filament\Widgets\QuoteStatsOverview;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard';

    public function getHeading(): string|Htmlable
    {
        $name = auth()->user()?->name ?? 'Admin';

        return "Welcome back, {$name}";
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Ruby100 operations overview — quotes, content, and quick actions.';
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 4,
        ];
    }

    public function getWidgets(): array
    {
        return [
            QuoteStatsOverview::class,
            ContentStatsOverview::class,
            QuotesPerDayChart::class,
            QuickActionsWidget::class,
            LatestQuotesWidget::class,
        ];
    }
}
