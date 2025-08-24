<?php

use Illuminate\Support\Facades\Route;
use App\Models\Machine;
use App\Models\Reading;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/machines', function () {
    $machines = Machine::with('readings')->get();
    return view('machines.index', compact('machines'));
});
