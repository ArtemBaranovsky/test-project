<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

Route::post('/subscriptions', [SubscriptionController::class, 'store']);
Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show']);
Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update']);
Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy']);

Route::middleware('can:create,' . Publication::class)
    ->post('/publications', [PublicationController::class, 'store']);

Route::get('/publications', [PublicationController::class, 'index']);
Route::get('/publications/{publication}', [PublicationController::class, 'show']);
Route::get('/publications/filterByAuthor', [PublicationController::class, 'filterByAuthor']);
Route::middleware('role:publisher')->group(function () {
    Route::middleware('can:update,publication')->group(function () {
        Route::put('/publications/{publication}', [PublicationController::class, 'update']);
        Route::delete('/publications/{publication}', [PublicationController::class, 'destroy']);
    });
});

Route::post('/payment/callback', [PaymentController::class, 'handlePaymentCallback'])
    ->name('payment.callback');
