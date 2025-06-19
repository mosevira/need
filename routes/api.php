<?php

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

Route::get('/products/by-barcode/{barcode}', function($barcode) {
    $product = App\Models\Product::findByBarcode($barcode);

    if (!$product) {
        return response()->json([
            'error' => 'Товар не найден',
            'action' => 'create'
        ], 404);
    }

    return response()->json($product);
})->middleware('auth:sanctum');
