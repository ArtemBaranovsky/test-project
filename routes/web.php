<?php

use App\Http\Admin\Controllers\SubscriptionPlanController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::middleware('role:admin')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index'])->name('subscription-plans.index');
        Route::get('/subscription-plans/create', [SubscriptionPlanController::class, 'create'])->name('subscription-plans.create');
        Route::post('/subscription-plans', [SubscriptionPlanController::class, 'store'])->name('subscription-plans.store');
        Route::get('/subscription-plans/{id}/edit', [SubscriptionPlanController::class, 'edit'])->name('subscription-plans.edit');
        Route::put('/subscription-plans/{id}', [SubscriptionPlanController::class, 'update'])->name('subscription-plans.update');
        Route::delete('/subscription-plans/{id}', [SubscriptionPlanController::class, 'destroy'])->name('subscription-plans.destroy');
    });
});
