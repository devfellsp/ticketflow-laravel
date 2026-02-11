<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rotas públicas (sem autenticação)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rotas protegidas (requer autenticação)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Tickets (CRUD completo)
    Route::apiResource('tickets', TicketController::class);
    
    // Ações especiais em tickets
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'changeStatus']);
    Route::patch('tickets/{ticket}/assign', [TicketController::class, 'assignResponsible']);
    
    // Logs de auditoria de um ticket
    Route::get('tickets/{ticket}/logs', [TicketController::class, 'logs']);
    
    // Dashboard
    Route::get('dashboard/tickets', [TicketController::class, 'dashboard']);
});