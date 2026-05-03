<?php
use App\Http\Controllers\DomadorController;
use App\Http\Controllers\DragonController;
use Illuminate\Support\Facades\Route;

// GET    /api/domadores          → index
// POST   /api/domadores          → store
// GET    /api/domadores/{id}     → show
// PUT    /api/domadores/{id}     → update
// DELETE /api/domadores/{id}     → destroy
Route::apiResource('domadores', DomadorController::class);

// GET    /api/dragons            → index
// POST   /api/dragons            → store
// GET    /api/dragons/{id}       → show
// PUT    /api/dragons/{id}       → update
// DELETE /api/dragons/{id}       → destroy
Route::apiResource('dragons', DragonController::class);