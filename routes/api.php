<?php

use App\Http\Controllers\DepartementController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembresController;
use App\Http\Controllers\ActivityLogsController;
use App\Http\Controllers\FonctionnaliteController;
use App\Http\Controllers\RoleController;


Route::post('/login', [UserController::class, 'login']);
Route::resource('inscription321234', UserController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function (){
    Route::get('membres/paginate', [MembresController::class, 'paginateMembres']);
     Route::get('membres/count-by-departement/{departementId}', [MembresController::class, 'countByDepartement']);
    Route::get('membres/count-all-departements', [MembresController::class, 'countAllDepartements']);
    Route::get('membres/by-departement/{departementId}', [MembresController::class, 'getMembresByDepartement']);
    Route::get('membres/paginate-by-departement/{departementId}', [MembresController::class, 'paginateMembresByDepartement']);
    Route::get('activity', [ActivityLogsController::class, 'index']);
    Route::post('activity', [ActivityLogsController::class, 'store']);
    Route::post('roles/{id}/assign-fonctionnalites', [RoleController::class, 'assignFonctionnalites']);
    Route::get('fonctionnalites/paginate', [FonctionnaliteController::class, 'paginate']);
    
    Route::resource('membres', MembresController::class);
    Route::resource('departements', DepartementController::class);
    Route::resource('fonctionnalites', FonctionnaliteController::class);
    Route::resource('roles', RoleController::class);
});


