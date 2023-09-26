<?php

use App\Http\Controllers\AuthController;
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
// Route::group(['domain' => env('FRONT_DOMAIN', 'app.weldapp.co')], function () {
//     Route::get('/{any}', function () {
//         return view('app');
//     })->where('any', '.*');
// });

// Route::get('/', function () {
//     return view('home');
// });
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!nova|adminxxtzyfar135|horizon|api|nova-vendor|nova-api|sanctum|social|storage|email-test|privacy|terms-of-use|cookie-policy).*$');

Route::group(['prefix' => 'sanctum'], function () {
    Route::post('register', [UserController::class, 'create']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// Email test route
Route::get('email-test', [UserController::class, 'test']);

// Policy pages
Route::get('/privacy', function() {
    return view('privacy');
});
Route::get('/terms-of-use', function() {
    return view('terms');
});
Route::get('/cookie-policy', function() {
    return view('cookie');
});
