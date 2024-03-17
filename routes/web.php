<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Simple route for testing bare minimum functionality
Route::name('test')->get('/test', static fn () => 'Hello world!');
