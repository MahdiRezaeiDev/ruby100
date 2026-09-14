<?php

namespace App\Filament\Resources\Reasons\Pages;

use App\Filament\Resources\Reasons\ReasonResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReason extends EditRecord
{
    protected static string $resource = ReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
