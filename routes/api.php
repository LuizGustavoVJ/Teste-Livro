<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\EmailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas públicas para teste
Route::prefix('v1')->group(function () {
    // Rotas para Autores
    Route::apiResource('authors', AuthorController::class);
    
    // Rotas para Assuntos
    Route::apiResource('subjects', SubjectController::class);
    
    // Rotas para Livros
    Route::apiResource('books', BookController::class);
    
    // Rota para envio de e-mail
    Route::post('send-email-report', [EmailController::class, 'sendBookReport']);
});

// Rotas protegidas por autenticação OAuth2
Route::middleware('auth:api')->prefix('v1')->group(function () {
    // Aqui seriam adicionadas rotas protegidas, se necessário
});

