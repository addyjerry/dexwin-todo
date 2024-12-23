<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::apiResource('todos', Controller::class);


Route::get('/', function () {
    return view('welcome');

});


