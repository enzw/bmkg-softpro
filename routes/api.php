<?php

// use App\Http\Controllers\DialogflowWebhookController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;

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

Route::get('/berita', [BeritaController::class, 'api']);

// Chatbot API Routes (Public)
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::post('/chatbot/rate', [ChatbotController::class, 'rate'])->name('chatbot.rate'); // Accept feedback from both authed and guest users

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// File Management API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/file/{filename}', [FileController::class, 'delete']);
    Route::post('/file/delete-by-path', [FileController::class, 'deleteByPath']);
    Route::get('/files/folder/{serviceType}', [FileController::class, 'listByFolder']);
    Route::get('/files/stats/{serviceType}', [FileController::class, 'folderStats']);
    Route::delete('/files/folder/{serviceType}', [FileController::class, 'deleteFolderContents']);
});

// Route::post('/webhook', [DialogflowWebhookController::class, 'handleWebhook']);
