<?php

declare(strict_types=1);

use App\Http\Controllers\API\RootController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(static function (): void {
    Route::controller(RootController::class)->group(static function (): void {
        Route::name('root')->get('/', 'index');
    });
});
