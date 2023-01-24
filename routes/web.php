<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminHomeController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*** 
 * Admin Panel Routes
 */
Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('dashboard', [AdminHomeController::class, 'index'])->name('home');

    //Password reset
    // Route::get('password-reset', [AdminProfileController::class, 'passwordReset'])->name('admin.profile.password_reset');
    // Route::post('password-reset/update_password/{id}', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.update_password');


});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
