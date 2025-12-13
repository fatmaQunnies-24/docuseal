<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\DocuSealWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/webhook/docuseal/{token}', [DocuSealWebhookController::class, 'handle'])
    ->name('webhook.docuseal');
