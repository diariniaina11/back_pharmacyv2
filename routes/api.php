<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\DemandeProduitController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class);
Route::apiResource('fournisseurs', FournisseurController::class);
Route::apiResource('utilisateurs', UtilisateurController::class);
Route::apiResource('produits', ProduitController::class);
Route::apiResource('ventes', VenteController::class);
Route::apiResource('demandes-produits', DemandeProduitController::class);
