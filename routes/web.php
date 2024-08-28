<?php

use Illuminate\Support\Facades\Route;
use Startupful\WebpageManager\Http\Controllers\PageBuilderController;
use Startupful\WebpageManager\Http\Controllers\PageViewController;

Route::get('/page-builder/{page}', [PageBuilderController::class, 'edit'])->name('page.builder');
Route::put('/page-builder/{page}', [PageBuilderController::class, 'update'])->name('page.builder.update');

Route::get('/{slug}', [PageViewController::class, 'show'])
    ->name('page.view')
    ->where('slug', '.*')
    ->fallback();