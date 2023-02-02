<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController,HomeController,ListingController,BidController};
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
    Route::post('verifyOtp', 'verifyOtp');
    Route::get('active/{user}', 'activeUser');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('home', [HomeController::class,'index']);

    //Product listing routes
    Route::group(['prefix'=>'product'], function () {
        Route::get('/', [ListingController::class,'index']);
        Route::get('/make',[ListingController::class,'makeList']);
        Route::get('/model/{make}',[ListingController::class,'modelList']);
        Route::get('/{product}', [ListingController::class,'getProductById']);
    });

    //Bid routes
    Route::group(['prefix'=>'bid'], function () {
        Route::get('/', [BidController::class,'index']);
        Route::post('/place', [BidController::class,'bidProduct']);
    });
});
