<?php

use Illuminate\Support\Facades\Route;
use ActivityLogManagement\Http\Controllers\AdminActivityLogController;
use ActivityLogManagement\Http\Controllers\AdminRequestLogController;
use ActivityLogManagement\Http\Controllers\AdminTransactionLogController;
use ActivityLogManagement\Http\Controllers\AdminThirdPartyLogController;

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


/*** 
 * Admin Panel Routes
 */
Route::prefix('admin')
->middleware(['web','auth'])
->group(function () {

    //Activity Logs
    Route::get('activity/logs', [AdminActivityLogController::class, 'index'])->name('admin.activity.logs');
    Route::post('activity/logs/fetch', [AdminActivityLogController::class, 'fetch'])->name('admin.activity.logs.fetch');

    Route::post('activity/logs/detail', [AdminActivityLogController::class, 'detail'])->name('admin.activity.logs.detail');


    //Request Logs
    Route::get('request/logs', [AdminRequestLogController::class, 'index'])->name('admin.request.logs');
    Route::post('request/logs/fetch', [AdminRequestLogController::class, 'fetch'])->name('admin.request.logs.fetch');
    Route::post('request/logs/detail', [AdminRequestLogController::class, 'detail'])->name('admin.request.logs.detail');


    //Transaction Logs
    Route::get('transaction/logs', [AdminTransactionLogController::class, 'index'])->name('admin.transaction.logs');
    Route::post('transaction/logs/fetch', [AdminTransactionLogController::class, 'fetch'])->name('admin.transaction.logs.fetch');
    Route::post('transaction/logs/detail', [AdminTransactionLogController::class, 'detail'])->name('admin.transaction.logs.detail');

    //Third Party Logs
    Route::get('third/party/logs', [AdminThirdPartyLogController::class, 'index'])->name('admin.third.party.logs');
    Route::post('third/party/logs/fetch', [AdminThirdPartyLogController::class, 'fetch'])->name('admin.third.party.logs.fetch');
    Route::post('third/party/logs/detail', [AdminThirdPartyLogController::class, 'detail'])->name('admin.third.party.logs.detail');


});