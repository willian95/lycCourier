<?php

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
    return view('login');
})->middleware("guest");

Route::get("/logout", "LoginController@logout")->name("login")->middleware("guest");

Route::post("/login", "LoginController@login");

Route::get('/home', function(){
    return view('welcome');
})->middleware('auth');

Route::get('/recipients', "RecipientController@index")->name("recipient")->middleware("auth");
Route::get('/recipients/fetch/{page}', "RecipientController@fetch")->middleware("auth");
Route::post('/recipients/store', "RecipientController@store")->middleware("auth");
Route::post('/recipients/update', "RecipientController@update")->middleware("auth");
Route::post('/recipients/erase', "RecipientController@erase")->middleware("auth");
Route::post('/recipients/search', "RecipientController@search")->middleware("auth");
Route::get("/recipients/shipping/{recipient}", "RecipientController@shippingList")->middleware("auth");
Route::get("/recipients/shipping/{recipient}/fetch/{page}", "RecipientController@shippingFetch")->middleware("auth");
Route::post("/recipients/shippings/search", "RecipientController@searchShipping")->middleware("auth");
Route::get('/recipients/export/excel', "RecipientController@exportExcel")->middleware("auth");
Route::get('/recipients/export/pdf', "RecipientController@exportPDF")->middleware("auth");

Route::get('/packages', "BoxController@index")->name("packages")->middleware("auth");
Route::get('/packages/fetch/{page}', "BoxController@fetch")->middleware("auth");
Route::post('/packages/store', "BoxController@store")->middleware("auth");
Route::post('/packages/update', "BoxController@update")->middleware("auth");
Route::post('/packages/erase', "BoxController@erase")->middleware("auth");
Route::post('/packages/search', "BoxController@search")->middleware("auth");
Route::get('/packages/export/excel', "BoxController@exportExcel")->middleware("auth");
Route::get('/packages/export/pdf', "BoxController@exportPDF")->middleware("auth");

Route::get('/shippings', "ShippingController@index")->name("shippings.list")->middleware("auth");
Route::get('/shippings/fetch/{page}', "ShippingController@fetch")->middleware("auth");
Route::get('/shippings/create', "ShippingController@create")->name("shippings.create")->middleware("auth");
Route::get('/shippings/show/{tracking}', "ShippingController@show")->middleware("auth");
Route::get("shippings/statuses", "ShippingController@getAllStatuses")->middleware("auth");
Route::post('/shippings/store', "ShippingController@store")->middleware("auth");
Route::post('/shippings/update', "ShippingController@update")->middleware("auth");
Route::post('/shippings/erase', "ShippingController@erase")->middleware("auth");
Route::get("/shippings/qr/{id}", "ShippingController@downloadQR")->middleware("auth");
Route::post("/shippings/search", "ShippingController@search")->middleware("auth");
Route::get('/shippings/export/excel/{start_date}/{end_date}', "ShippingController@exportExcel")->middleware("auth");
Route::get('/shippings/export/pdf/{start_date}/{end_date}', "ShippingController@exportPDF")->middleware("auth");

Route::get("/user", "UserController@index")->name("user")->middleware("auth");
Route::post("/users/store", "UserController@store")->middleware("auth");
Route::post("/users/update", "UserController@update")->middleware("auth");
Route::post("/users/erase", "UserController@delete")->middleware("auth");
Route::get('/users/fetch/{page}', "UserController@fetch")->middleware("auth");

Route::get("/tracking", "TrackingController@search");

/*Route::get("/create/shipping/step-1", function(){
    return view("shippings.create.step1");
})->name("create.shipping.step.1");*/

/*Route::get("/shipping/list", function(){
    return view("shippings.list");
})->name("shipping.list");*/

/*Route::get("/shipping/show", function(){
    return view("shippings.show");
});*/

