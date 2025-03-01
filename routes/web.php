<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;

Route::get('/', function () {
    return view('notes');
});

// These resource routes will be called via AJAX from our front end
Route::resource('notes', NoteController::class)->only([
    'index',
    'store',
    'update',
    'destroy'
]);
