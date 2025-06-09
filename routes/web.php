<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BookWebController;
use App\Http\Controllers\AuthorWebController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SubjectWebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rotas para CRUD web
Route::resource('authors', AuthorWebController::class);
Route::resource('subjects', SubjectWebController::class);
Route::resource('books', BookWebController::class);

// Rotas para relatórios
Route::prefix('reports')->group(function () {
    Route::get('/books-by-author', [ReportController::class, 'booksByAuthor'])->name('reports.books-by-author');
    Route::get('/books-by-author/pdf', [ReportController::class, 'booksByAuthorPdf'])->name('reports.books-by-author.pdf');
    Route::get('/books-by-author/from-view', [ReportController::class, 'booksByAuthorFromView'])->name('reports.books-by-author.from-view');
});

// Rotas para e-mail
Route::get('/send-email', [EmailController::class, 'showEmailForm'])->name('email.form');

