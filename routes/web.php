<?php

use App\Http\Controllers\ExampleController;
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
