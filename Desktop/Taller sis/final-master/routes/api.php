	<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Registro usuario
Route::post('/register', 'UserController@register');

//
Route::post('/login', 'UserController@login');
Route::resource('/cars', 'CarController');

// Cache
Route::get('/clear-cache', function(){
    $code = Artisan::call('cache:clear');
});


//Mail
Route::post('/enviarCorreo', 'EmailController@enviarCorreo');

