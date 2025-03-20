<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


use App\Http\Controllers\CandidatureController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/candidatures', [CandidatureController::class, 'postuler']);
    Route::delete('/candidatures/{id}', [CandidatureController::class, 'retirer']);
    Route::get('/recruteur/candidatures', [CandidatureController::class, 'listeRecruteur']);
});

use App\Http\Controllers\OffreController;
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/candidatures', [CandidatureController::class, 'postuler']);
    Route::delete('/candidatures/{id}', [CandidatureController::class, 'retirer']);
    Route::get('/recruteur/candidatures', [CandidatureController::class, 'getCandidaturesRecruteur']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/offres', [OffreController::class, 'creerOffre']); // Ajouter une offre
    Route::get('/offres', [OffreController::class, 'listeOffres']); // Voir toutes les offres
    Route::get('/offres/{id}', [OffreController::class, 'voirOffre']); // Voir une offre spécifique
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
