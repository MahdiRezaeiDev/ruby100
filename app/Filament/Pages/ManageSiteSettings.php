<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

/**
 * @property-read Schema $form
 */
class ManageSiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->isAdmin() ?? false;
    }

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone')->required()->helperText('E.g. +61401724002'),
                        TextInput::make('phone_display')->required(),
                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp number')
                            ->helperText('Digits with country code, e.g. 61401724002')
                            ->required(),
                        TextInput::make('whatsapp_message')
                            ->label('Default WhatsApp message')
                            ->columnSpanFull(),
                        TextInput::make('address')->columnSpanFull(),
                        TextInput::make('area')->columnSpanFull(),
                        TextInput::make('notify_email')->email()->label('Quote notify email'),
                    ]),
                Section::make('Hero')
                    ->columns(2)
                    ->schema([
                        TextInput::make('hero_kicker'),
                        TextInput::make('hero_title')->required(),
                        Textarea::make('hero_subtitle')->rows(3)->columnSpanFull(),
                        FileUpload::make('hero_image')
                            ->image()
                            ->disk('public')
                            ->directory('hero')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),
                Section::make('About')
                    ->schema([
                        TextInput::make('about_title'),
                        Textarea::make('about_body')->rows(8),
                        FileUpload::make('about_image')
                            ->image()
                            ->disk('public')
                            ->directory('about')
                            ->imageEditor(),
                    ]),
                Section::make('SEO & Reviews')
                    ->schema([
                        TextInput::make('seo_title'),
                        Textarea::make('seo_description')->rows(3),
                        Textarea::make('google_reviews_embed')
                            ->label('Google Maps embed URL')
                            ->rows(5)
                            ->helperText('Use an HTTPS www.google.com/maps/embed URL. HTML and scripts are not accepted.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        abort_unless(static::canAccess(), 403);
        $settings = SiteSetting::current();
        $settings->update($this->form->getState());

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
