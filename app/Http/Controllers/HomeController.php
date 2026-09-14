<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\Post;
use App\Models\Reason;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\SiteSetting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $settings = SiteSetting::current();

        return view('home', [
            'settings' => $settings,
            'services' => Service::query()->active()->get(),
            'reasons' => Reason::query()->active()->get(),
            'gallery' => GalleryImage::query()->active()->get(),
            'areas' => ServiceArea::query()->active()->get(),
            'posts' => Post::query()->published()->select(['title', 'slug', 'excerpt', 'cover_image', 'published_at'])->limit(4)->get(),
        ]);
    }
}
