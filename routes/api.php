<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AnimalesController;
use App\Http\Controllers\Api\V1\AlimentosController;
use App\Http\Controllers\Api\V1\DietasController;
use App\Http\Controllers\Api\V1\DietItemsController;
use App\Http\Controllers\Api\V1\AlmacenVegetalController;
use App\Http\Controllers\Api\V1\AlmacenCarneController;
use App\Http\Controllers\Api\V1\UsuariosController;
use App\Http\Controllers\Api\V1\AuthGoogleController;
use App\Http\Controllers\Api\V1\MedicalCheckupsController;
use App\Http\Controllers\Api\V1\CitasVetController;
use App\Http\Controllers\Api\V1\VacunasController;
use App\Http\Controllers\Api\V1\DocumentosController;

Route::options('/{any}', function () {
    return response()->noContent();
})->where('any', '.*');

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    
    //Animales
    Route::get('/animales', [AnimalesController::class, 'index']);
    Route::get('/animales/{id}', [AnimalesController::class, 'show']);
    Route::post('/animales', [AnimalesController::class, 'store']);
    Route::put('/animales/{id}', [AnimalesController::class, 'update']);
    Route::delete('/animales/{id}', [AnimalesController::class, 'destroy']);
    
    // Alimentos
    Route::get('/alimentos', [AlimentosController::class, 'index']);
    Route::get('/alimentos/{id}', [AlimentosController::class, 'show']);
    Route::post('/alimentos/batch', [AlimentosController::class, 'batch']);

    // Dietas
    Route::get('/dietas', [DietasController::class, 'index']);
    Route::get('/dietas/{id}', [DietasController::class, 'show']);
    Route::post('/dietas', [DietasController::class, 'store']);
    Route::put('/dietas/{id}', [DietasController::class, 'update']);
    Route::delete('/dietas/{id}', [DietasController::class, 'destroy']);

    // Diet items
    Route::get('/dietas/{id_dieta}/items', [DietItemsController::class, 'indexByDieta']);
    Route::post('/dietas/{id_dieta}/items', [DietItemsController::class, 'storeForDieta']);
    Route::put('/diet-items/{id}', [DietItemsController::class, 'update']);
    Route::delete('/diet-items/{id}', [DietItemsController::class, 'destroy']);

    // Almacén vegetal
    Route::get('/almacen/vegetal/enums', [AlmacenVegetalController::class, 'enums']);
    Route::get('/almacen/vegetal', [AlmacenVegetalController::class, 'index']);
    Route::get('/almacen/vegetal/{id}', [AlmacenVegetalController::class, 'show']);
    Route::post('/almacen/vegetal', [AlmacenVegetalController::class, 'store']);
    Route::put('/almacen/vegetal/{id}', [AlmacenVegetalController::class, 'update']);
    Route::delete('/almacen/vegetal/{id}', [AlmacenVegetalController::class, 'destroy']);

    // Almacén carne
    Route::get('/almacen/carne/enums', [AlmacenCarneController::class, 'enums']);
    Route::get('/almacen/carne', [AlmacenCarneController::class, 'index']);
    Route::get('/almacen/carne/{id}', [AlmacenCarneController::class, 'show']);
    Route::post('/almacen/carne', [AlmacenCarneController::class, 'store']);
    Route::put('/almacen/carne/{id}', [AlmacenCarneController::class, 'update']);
    Route::delete('/almacen/carne/{id}', [AlmacenCarneController::class, 'destroy']);

    // Usuarios
    Route::post('/register', [UsuariosController::class, 'register']);
    Route::post('/change-password', [UsuariosController::class, 'changePassword']);
    Route::get('/usuarios/by-email', [UsuariosController::class, 'byEmail']);
    Route::get('/usuarios/{id}', [UsuariosController::class, 'show']);
    Route::put('/usuarios/{id}', [UsuariosController::class, 'update']);

    //Auth Google
    Route::post('/auth/google', [\App\Http\Controllers\Api\V1\AuthGoogleController::class, 'login']);

    //Medical Checkup
    Route::get('medical-checkups', [MedicalCheckupsController::class, 'index']);
    Route::get('medical-checkups/latest', [MedicalCheckupsController::class, 'latest']);
    Route::post('medical-checkups', [MedicalCheckupsController::class, 'store']);

    //Citas
    Route::get('citas-vet', [CitasVetController::class, 'index']);
    Route::get('citas-vet/next', [CitasVetController::class, 'next']);
    Route::post('citas-vet', [CitasVetController::class, 'store']);

    //Vacunas
    Route::get('/vacunas', [VacunasController::class, 'index']);
    Route::post('/vacunas', [VacunasController::class, 'store']);

    //Documentos
    Route::get('documentos', [DocumentosController::class, 'index']);
    Route::post('documentos', [DocumentosController::class, 'store']);
    Route::delete('documentos/{id}', [DocumentosController::class, 'destroy']);

});