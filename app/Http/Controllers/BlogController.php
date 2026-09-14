<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('blog.index', [
            'settings' => SiteSetting::current(),
            'posts' => Post::query()->published()->select(['title', 'slug', 'excerpt', 'cover_image', 'published_at'])->paginate(9),
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::query()->published()->where('slug', $slug)->firstOrFail();

        return view('blog.show', [
            'settings' => SiteSetting::current(),
            'post' => $post,
        ]);
    }
}
