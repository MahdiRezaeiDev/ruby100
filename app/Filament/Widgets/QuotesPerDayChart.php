<?php

namespace App\Filament\Widgets;

use App\Models\QuoteRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class QuotesPerDayChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    protected ?string $heading = 'Quotes — last 14 days';

    protected ?string $description = 'Daily volume of quote & cash-for-cars requests';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn (int $i) => now()->subDays($i)->startOfDay());

        $counts = $days->map(function (Carbon $day) {
            return QuoteRequest::query()
                ->whereDate('created_at', $day)
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Requests',
                    'data' => $counts->values()->all(),
                    'borderColor' => '#C8102E',
                    'backgroundColor' => 'rgba(200, 16, 46, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $days->map(fn (Carbon $day) => $day->format('d M'))->values()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
