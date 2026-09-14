<?php

namespace App\Filament\Resources\QuoteRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuoteRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable()->label('Received'),
                TextColumn::make('type')->badge(),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'danger',
                        'contacted' => 'warning',
                        'closed' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('service')->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'new' => 'New',
                    'contacted' => 'Contacted',
                    'closed' => 'Closed',
                ]),
                SelectFilter::make('type')->options([
                    'quote' => 'Free Quote',
                    'cash_for_cars' => 'Cash for Cars',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
