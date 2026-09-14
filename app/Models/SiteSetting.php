<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'phone',
        'phone_display',
        'whatsapp_number',
        'whatsapp_message',
        'address',
        'area',
        'hero_kicker',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'about_title',
        'about_body',
        'about_image',
        'seo_title',
        'seo_description',
        'notify_email',
        'google_reviews_embed',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? static::query()->create([]);
    }

    public function heroImageUrl(): ?string
    {
        return $this->imageUrl($this->hero_image);
    }

    public function reviewsEmbedUrl(): ?string
    {
        $url = trim((string) $this->google_reviews_embed);
        $parts = parse_url($url);

        return $parts && ($parts['scheme'] ?? '') === 'https'
            && ($parts['host'] ?? '') === 'www.google.com'
            && str_starts_with($parts['path'] ?? '', '/maps/embed')
            && ! isset($parts['user'], $parts['pass'])
            ? $url : null;
    }

    public function aboutImageUrl(): ?string
    {
        return $this->imageUrl($this->about_image);
    }

    public function whatsappUrl(?string $message = null): ?string
    {
        $number = preg_replace('/\D+/', '', (string) ($this->whatsapp_number ?: $this->phone));

        if (! $number) {
            return null;
        }

        $text = $message ?? $this->whatsapp_message ?? 'Hi Ruby100, I need help with towing / car removal.';

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }

    protected function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/images/fleet/')) {
            return asset(ltrim($path, '/'));
        }

        return Storage::disk('public')->url($path);
    }
}
