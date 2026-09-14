<?php
namespace App\Support;
class FleetImage
{
    public static function srcset(?string $url): string
    {
        if (!$url || !str_contains($url, '/images/fleet/')) return '';
        $path = parse_url($url, PHP_URL_PATH);
        $file = public_path(ltrim($path, '/'));
        if (!is_file($file)) return '';
        $width = getimagesize($file)[0];
        $base = substr($url, 0, -5);
        return $base.'-640.webp 640w, '.$base.'-960.webp 960w, '.$url.' '.$width.'w';
    }
}