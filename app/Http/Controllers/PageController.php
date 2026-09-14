<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::query()->active()->where('slug', $slug)->firstOrFail();

        return view('page', [
            'settings' => SiteSetting::current(),
            'page' => $page,
        ]);
    }
}
