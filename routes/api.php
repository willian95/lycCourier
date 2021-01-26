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

Route::post("/register", "API\RegisterController@register");
Route::post("/login", "API\AuthController@login");

Route::post("/password/verify", "API\AuthController@verifyEmail");

Route::group(['middleware'=>'jwt.auth'], function () {

    Route::get("/me", "API\AuthController@me");

    Route::post("clients/shipping/update", "API\ClientShippingController@update");
    Route::post("clients/shipping/store", "API\ClientShippingController@store");
    Route::post("clients/shipping/search", "API\ClientShippingController@search");
    Route::get("clients/shipping/fetch/{page}", "API\ClientShippingController@fetch");

    Route::post("profile/update", "API\ProfileController@update");

});

Route::get("departments", "API\DepartmentController@fetch");
Route::get("/provinces/{department_id}", "API\ProvinceController@fetch");
Route::get("/districts/{department_id}/{province_id}", "API\DistrictController@fetch");

