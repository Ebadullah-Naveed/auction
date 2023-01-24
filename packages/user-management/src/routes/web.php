<?php

use Illuminate\Support\Facades\Route;
use UserManagement\Http\Controllers\AdminUserController;
use UserManagement\Http\Controllers\AdminWhitelistUserController;
use UserManagement\Http\Controllers\AdminCustomerController;

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

    //User Management
    Route::get('users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('users/fetch', [AdminUserController::class, 'fetch'])->name('admin.users.fetch');
    Route::get('user/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('user/store', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('user/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::post('user/update/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::get('user/delete/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('user/show/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');

    //Customer Management
    Route::get('customers', [AdminCustomerController::class, 'index'])->name('admin.customers');
    Route::post('customers/fetch', [AdminCustomerController::class, 'fetch'])->name('admin.customers.fetch');
    // Route::get('customer/create', [AdminCustomerController::class, 'create'])->name('admin.customer.create');
    // Route::post('customer/store', [AdminCustomerController::class, 'store'])->name('admin.customer.store');
    // Route::get('customer/edit/{id}', [AdminCustomerController::class, 'edit'])->name('admin.customer.edit');
    // Route::post('customer/update/{id}', [AdminCustomerController::class, 'update'])->name('admin.customer.update');
    // Route::get('customer/delete/{id}', [AdminCustomerController::class, 'destroy'])->name('admin.customer.destroy');
    Route::get('customer/show/{id}', [AdminCustomerController::class, 'show'])->name('admin.customer.show');
    Route::post('customer/status/update/{id}', [AdminCustomerController::class, 'updateStatus'])->name('admin.customer.update.status');
    
    Route::get('customers/dropdown/list', [AdminCustomerController::class, 'dropdownData'])->name('admin.customers.dropdown.list');



});