<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::post('/quote', [QuoteController::class, 'store'])
    ->name('quote.store')
    ->middleware('throttle:8,1');

Route::post('/cash-for-cars', [QuoteController::class, 'cashForCars'])
    ->name('cash.store')
    ->middleware('throttle:8,1');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/privacy', fn () => app(PageController::class)->show('privacy'))->name('privacy');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
