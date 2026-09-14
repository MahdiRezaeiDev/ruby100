<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Textarea::make('excerpt')->rows(3)->columnSpanFull(),
                Textarea::make('body')->required()->rows(12)->columnSpanFull(),
                FileUpload::make('cover_image')
                    ->image()
                    ->disk('public')
                    ->directory('posts')
                    ->imageEditor()
                    ->columnSpanFull(),
                DateTimePicker::make('published_at')->default(now()),
                Toggle::make('is_published')->default(false),
            ]);
    }
}
