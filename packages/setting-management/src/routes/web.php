<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\AdminSettingsController;
use SettingManagement\Http\Controllers\AdminSettingController;


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
// Route::get('/testpackage', function () {
//     return "This is test user management package";
// });

Route::prefix('admin')->middleware(['web','auth'])->group(function () {

    //Setting Management
    Route::get('settings', [AdminSettingController::class, 'edit'])->name('admin.settings.edit');
    Route::post('setting/update', [AdminSettingController::class, 'update'])->name('admin.settings.update');
    
    Route::get('cronjob/listing', [AdminSettingController::class, 'cronjobListing'])->name('admin.cronjob.listing');

});