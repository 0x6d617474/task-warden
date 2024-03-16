<?php

use Illuminate\Support\Facades\Route;

// Simple route for testing bare minimum functionality
Route::name('test')->get('/test', function() {
    return 'Hello world!';
});
