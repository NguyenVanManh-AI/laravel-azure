<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Admin page management
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => [],
], function () {
    // Example management
    Route::resource('examples', ExampleController::class);
    Route::get('something', [ExampleController::class, 'somethingMethod'])->name('something.get');

    // Other management
    // TODO: Handle route management
});

Route::get('verify-email/{token}', [UserController::class, 'verifyEmail'])->name('verify_email');
Route::get('forgot-form', [UserController::class, 'forgotForm'])->name('form_reset_password');
Route::post('forgot-update', [UserController::class, 'forgotUpdate'])->name('forgot_update');

Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('verify-email/{token}', 'verifyEmail')->name('admin_verify_email');
    Route::get('forgot-form', 'forgotForm')->name('admin_form_reset_password');
    Route::post('forgot-update', 'forgotUpdate')->name('admin_forgot_update');
});
