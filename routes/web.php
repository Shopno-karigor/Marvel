<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view('/vehicle', 'project.vehicle');
Route::view('/project', 'project.project');

Route::get('/project/{id}', function ($id) {
    return view('project.project');
});

Route::get(md5('this is for name route practice'), function () {
    return view('project.nameroute');
})->name('nameroute.route');

//Named Route Middleware
Route::get('/fruits', function () {
    return view('project.fruits');
})->middleware(['Fruits'])->name('fruits');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
