<?php

namespace App\Filament\Resources\QuoteRequests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuoteRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options([
                        'quote' => 'Free Quote',
                        'cash_for_cars' => 'Cash for Cars',
                    ])
                    ->required(),
                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'closed' => 'Closed',
                    ])
                    ->required(),
                TextInput::make('name')->required(),
                TextInput::make('phone')->tel()->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('service')->required(),
                Textarea::make('message')->columnSpanFull(),
                Textarea::make('admin_notes')->columnSpanFull(),
                TextInput::make('ip_address')->disabled(),
            ]);
    }
}
