<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;

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
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

/*** 
 * Admin Panel Routes
 */
Route::prefix('admin')->middleware('auth')->group(function () {
    
    Route::get('dashboard', [AdminHomeController::class, 'index'])->name('home');

    //Password reset
    Route::get('password-reset', [AdminProfileController::class, 'passwordReset'])->name('admin.profile.password_reset');
    Route::post('password-reset/update_password/{id}', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.update_password');

    //Category Management
    Route::get('categories', [AdminCategoryController::class, 'index'])->name('admin.category');
    Route::post('category/fetch', [AdminCategoryController::class, 'fetch'])->name('admin.category.fetch');
    Route::get('category/create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
    Route::post('category/store', [AdminCategoryController::class, 'store'])->name('admin.category.store');
    Route::get('category/edit/{id}', [AdminCategoryController::class, 'edit'])->name('admin.category.edit');
    Route::post('category/update/{id}', [AdminCategoryController::class, 'update'])->name('admin.category.update');
    Route::get('category/delete/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.category.destroy');
    Route::get('category/show/{id}', [AdminCategoryController::class, 'show'])->name('admin.category.show');

    //Product Management
    Route::get('products', [AdminProductController::class, 'index'])->name('admin.products');
    Route::post('product/fetch', [AdminProductController::class, 'fetch'])->name('admin.product.fetch');
    Route::get('product/create/{category_id}', [AdminProductController::class, 'create'])->name('admin.product.create');
    Route::post('product/store', [AdminProductController::class, 'store'])->name('admin.product.store');
    Route::get('product/edit/{id}', [AdminProductController::class, 'edit'])->name('admin.product.edit');
    Route::post('product/update/{id}', [AdminProductController::class, 'update'])->name('admin.product.update');
    Route::get('product/delete/{id}', [AdminProductController::class, 'destroy'])->name('admin.product.destroy');
    Route::get('product/show/{id}', [AdminProductController::class, 'show'])->name('admin.product.show');

    Route::post('product/bids/fetch/{id}', [AdminProductController::class, 'fetchBids'])->name('admin.product.bids.fetch');


    Route::post('product/image/update/sequence/{id}', [AdminProductController::class, 'updateImageSequence'])->name('product.image.update.sequence');
    Route::post('product/image/delete', [AdminProductController::class, 'removeProductImage'])->name('product.image.delete');


});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
