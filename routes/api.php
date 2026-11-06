<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/healthz', function () {
    return response('OK', 200)
        ->header('Content-Type', 'text/plain');
});