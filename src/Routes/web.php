<?php

use atikullahnasar\faq\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('beft')->group(function () {
    Route::resource('faqs', FaqController::class);
    Route::get('faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus']);
});