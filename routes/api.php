<?php

use App\Http\Controllers\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('stripe/list/customer', [StripeController::class, 'listCustomer']);
Route::post('stripe/read/customer', [StripeController::class, 'readCustomer']);
Route::post('stripe/create/customer', [StripeController::class, 'createCustomer']);
Route::post('stripe/update/customer', [StripeController::class, 'updateCustomer']);
Route::post('stripe/delete/customer', [StripeController::class, 'deleteCustomer']);

Route::post('stripe/create/setup-intent', [StripeController::class, 'createSetupIntent']);

Route::post('stripe/list/product', [StripeController::class, 'listProduct']);
Route::post('stripe/read/product', [StripeController::class, 'readProduct']);
Route::post('stripe/create/product', [StripeController::class, 'createProduct']);
Route::post('stripe/update/product', [StripeController::class, 'updateProduct']);
Route::post('stripe/delete/product', [StripeController::class, 'deleteProduct']);

Route::post('stripe/list/price', [StripeController::class, 'listPrice']);
Route::post('stripe/read/price', [StripeController::class, 'readPrice']);
Route::post('stripe/create/price', [StripeController::class, 'createPrice']);
Route::post('stripe/update/price', [StripeController::class, 'updatePrice']);

Route::post('stripe/list/subscription', [StripeController::class, 'listSubscription']);
Route::post('stripe/read/subscription', [StripeController::class, 'readSubscription']);
Route::post('stripe/create/subscription', [StripeController::class, 'createSubscription']);
Route::post('stripe/update/subscription', [StripeController::class, 'updateSubscription']);
