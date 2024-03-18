<?php

declare(strict_types=1);

use App\Http\Controllers\API\RootController;
use App\Http\Middleware\JSONAPI;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware(JSONAPI::class)->group(static function (): void {
    Route::controller(RootController::class)->group(static function (): void {
        Route::name('root')->get('/', 'index');
        Route::post('/', 'payload');
    });
});
