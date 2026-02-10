<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Requests\StoreTicketRequest;

Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando!', 'timestamp' => now()]);
});

// TESTE COM FORMREQUEST mas SEM Controller
Route::post('/tickets-formrequest', function (StoreTicketRequest $request) {
    return response()->json([
        'message' => 'FormRequest funcionou!',
        'validated' => $request->validated()
    ]);
});

Route::post('/tickets', [TicketController::class, 'store']);
Route::get('/tickets', [TicketController::class, 'index']);
Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
Route::patch('/tickets/{ticket}', [TicketController::class, 'update']);
Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');