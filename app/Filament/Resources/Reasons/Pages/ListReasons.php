<?php

namespace App\Filament\Resources\Reasons\Pages;

use App\Filament\Resources\Reasons\ReasonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReasons extends ListRecords
{
    protected static string $resource = ReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
