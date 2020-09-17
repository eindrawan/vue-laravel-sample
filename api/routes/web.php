<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::prefix('api')->group(function() {
    Route::get('accounts/{id}', 'AccountController@index');
    Route::get('accounts/{id}/transactions', 'AccountController@transactions');
    Route::get('currencies', 'CurrencyController@getAll');
    // Route::get('currencies', function () {
    //     $account = DB::table('currencies')
    //         ->get();

    //     return $account;
    // });
});

Route::prefix('api')->middleware(['jwt.verify'])->group(function() {
    Route::post('accounts/{id}/transactions', 'AccountController@transfer');
});
