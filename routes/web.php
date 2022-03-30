<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactForm;
use App\Http\Controllers\ErrorHandler;

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

//Controller practice-Form Show
Route::get('/form', [ContactForm::class, 'index'])->name('form');
//Controller practice-Form Submit
Route::post('/form', [ContactForm::class, 'submit'])->name('form.store');
//Form submission log view
Route::get('/form_log', [ContactForm::class, 'form_log'])->name('form.log');
//Form submission Data view
Route::get('/form_submission', [ContactForm::class, 'show'])->name('form.submission');
//Form submission update action
Route::get('/form_update/{id}', [ContactForm::class, 'update_show'])->name('form.update');
Route::post('/form_update/{id}', [ContactForm::class, 'update'])->name('form.update.submit');

//Error handler
Route::get('/Oops!-404', [ErrorHandler::class, 'Error404'])->name('Error.404');
Route::get('/Oops!-500', [ErrorHandler::class, 'Error500'])->name('Error.500');

//Auth Breeze Practice
Route::get('/secret_page', [ContactForm::class, 'secret_page'])->middleware(['auth'])->name('secret.page');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
