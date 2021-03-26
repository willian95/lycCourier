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

Route::post("/login/functionary", "API\AuthController@loginFunctionary");

Route::post("/password/verify", "API\AuthController@verifyEmail");

Route::group(['middleware'=>'jwt.auth'], function () {

    Route::get("/me", "API\AuthController@me");

    Route::post("clients/shipping/update", "API\ClientShippingController@update");
    Route::post("clients/shipping/store", "API\ClientShippingController@store");
    Route::post("clients/shipping/search", "API\ClientShippingController@search");
    Route::get("clients/shipping/fetch/{page}", "API\ClientShippingController@fetch");

    Route::post("profile/update", "API\ProfileController@update");

    Route::post("/reseller/client/create", "API\ResellerController@storeRecipient");
    Route::post("/reseller/client/update", "API\ResellerController@updateRecipient");
    Route::get("/reseller/client/fetch/{page}", "API\ResellerController@fetch");

    Route::post("recipients/search", "API\RecipientController@search");
    Route::post("recipients/store", "API\RecipientController@store");
    Route::get("recipients/resellers/{recipient_id}", "API\ResellerController@fetchByUser");

    Route::get("/resellers/all", "API\ResellerController@all");

    Route::get("/box/all", "API\BoxController@all");

    Route::post("/shipping/store", "API\AdminShippingController@store");
    Route::post("/shipping/update", "API\AdminShippingController@updateInfo");
    
    Route::post("/reseller/client/update", "API\ResellerController@updateRecipient");
    Route::get("/reseller/client/fetch/{page}", "API\ResellerController@fetch");

    Route::get("shipping-guide/fetch/{page}", "API\ShippingGuideController@fetch");
    Route::post("shipping-guide/search", "API\ShippingGuideController@search");

});

Route::get("departments", "API\DepartmentController@fetch");
Route::get("/provinces/{department_id}", "API\ProvinceController@fetch");
Route::get("/districts/{department_id}/{province_id}", "API\DistrictController@fetch");

Route::get('/shippings/fetch/{page}', "API\AdminShippingController@fetch");
Route::post("/shippings/search", "API\AdminShippingController@search");
Route::post("/shippings/update", "API\AdminShippingController@update");
Route::get("/shippings/tracking/{tracking}", "API\AdminShippingController@fetchByTracking");

