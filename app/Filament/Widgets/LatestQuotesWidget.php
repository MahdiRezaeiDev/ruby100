<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\QuoteRequests\QuoteRequestResource;
use App\Models\QuoteRequest;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestQuotesWidget extends TableWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest quote requests';

    public function table(Table $table): Table
    {
        return $table
            ->query(QuoteRequest::query()->latest())
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('created_at')
                    ->label('When')
                    ->since()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'cash_for_cars' ? 'Cash for Cars' : 'Quote'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'contacted' => 'warning',
                        'closed' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('service')->wrap(),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Open')
                    ->url(fn (QuoteRequest $record): string => QuoteRequestResource::getUrl('edit', ['record' => $record])),
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->color('success')
                    ->url(function (QuoteRequest $record): string {
                        $number = preg_replace('/\D+/', '', $record->phone);
                        if (str_starts_with($number, '0')) {
                            $number = '61'.substr($number, 1);
                        }

                        return 'https://wa.me/'.$number.'?text='.rawurlencode(
                            "Hi {$record->name}, this is Ruby100 regarding your {$record->service} request."
                        );
                    })
                    ->openUrlInNewTab(),
                Action::make('mark_contacted')
                    ->label('Contacted')
                    ->color('warning')
                    ->visible(fn (QuoteRequest $record): bool => $record->status === 'new')
                    ->action(fn (QuoteRequest $record) => $record->update(['status' => 'contacted'])),
            ])
            ->headerActions([
                Action::make('view_all')
                    ->label('View all quotes')
                    ->url(QuoteRequestResource::getUrl('index')),
            ]);
    }
}
