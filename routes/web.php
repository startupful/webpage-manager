<?php

use Illuminate\Support\Facades\Route;
use Startupful\WebpageManager\Http\Controllers\PreviewController;

Route::group(['middleware' => ['web']], function () {
    Route::post('/preview/render', [PreviewController::class, 'render'])->name('preview.render');
});