<?php

use Illuminate\Support\Facades\Route;
use RoleManagement\Http\Controllers\AdminRoleController;

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

// Route::get('/test', function(){
//     return 'hello';
// });
/*** 
 * Admin Panel Routes
 */
Route::prefix('admin')
->middleware(['web','auth'])
->group(function () {

    //Role Management
    Route::get('roles', [AdminRoleController::class, 'index'])->name('admin.roles');
    Route::get('role/create', [AdminRoleController::class, 'create'])->name('admin.roles.create');
    Route::post('role/store', [AdminRoleController::class, 'store'])->name('admin.roles.store');
    Route::get('role/edit/{id}', [AdminRoleController::class, 'edit'])->name('admin.roles.edit');
    Route::post('role/update/{id}', [AdminRoleController::class, 'update'])->name('admin.roles.update');
    Route::get('role/delete/{id}', [AdminRoleController::class, 'destroy'])->name('admin.roles.destroy');

});