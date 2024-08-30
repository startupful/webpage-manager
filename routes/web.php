<?php

use Illuminate\Support\Facades\Route;
use Startupful\WebpageManager\Http\Controllers\PageBuilderController;
use Startupful\WebpageManager\Http\Controllers\PageViewController;

Route::get('/page-builder/{page}', [PageBuilderController::class, 'edit'])->name('page.builder');
Route::put('/page-builder/{page}', [PageBuilderController::class, 'update'])->name('page.builder.update');

// Add a new route for the root domain
Route::get('/', [PageViewController::class, 'showMain'])->name('page.main');

// Keep the existing catch-all route, but exclude the root path
Route::get('/{slug}', [PageViewController::class, 'show'])
    ->name('page.view')
    ->where('slug', '(?!/).*') // This regex excludes the root path
    ->fallback();