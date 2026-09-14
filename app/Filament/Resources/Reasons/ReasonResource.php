<?php

namespace App\Filament\Resources\Reasons;

use App\Filament\Resources\Reasons\Pages\CreateReason;
use App\Filament\Resources\Reasons\Pages\EditReason;
use App\Filament\Resources\Reasons\Pages\ListReasons;
use App\Filament\Resources\Reasons\Schemas\ReasonForm;
use App\Filament\Resources\Reasons\Tables\ReasonsTable;
use App\Models\Reason;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReasonResource extends Resource
{
    protected static ?string $model = Reason::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ReasonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReasonsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReasons::route('/'),
            'create' => CreateReason::route('/create'),
            'edit' => EditReason::route('/{record}/edit'),
        ];
    }
}
