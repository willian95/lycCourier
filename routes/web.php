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
})->middleware("guest")->name("login");

Route::get("/logout", "LoginController@logout")->middleware("auth");

Route::post("/login", "LoginController@login");

Route::get("/register", "RegisterController@index")->middleware("guest");
Route::get("/register/validate/{registerHash}", "RegisterController@verify")->middleware("guest");
Route::post("/register", "RegisterController@register");

Route::post("/password/verify", "PasswordRestoreController@verifyEmail")->middleware("guest");
Route::get("/password/restore/{recovery_hash}", "PasswordRestoreController@index")->middleware("guest");
Route::post("/password/change", "PasswordRestoreController@change")->middleware("guest");

Route::get("/departments", "DepartmentController@fetch");
Route::get("/provinces/{department_id}", "ProvinceController@fetch");
Route::get("/districts/{department_id}/{province_id}", "DistrictController@fetch");

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
//Route::get('/recipients/export/pdf', "RecipientController@exportPDF")->middleware("auth");
Route::post("/recipients/list/search", "RecipientController@searchList")->middleware("auth");
Route::get("/recipients/profile/{id}", "RecipientController@showProfile")->middleware("auth");

Route::get('/packages', "BoxController@index")->name("packages")->middleware("auth");
Route::get('/packages/fetch/{page}', "BoxController@fetch")->middleware("auth");
Route::post('/packages/store', "BoxController@store")->middleware("auth");
Route::post('/packages/update', "BoxController@update")->middleware("auth");
Route::post('/packages/erase', "BoxController@erase")->middleware("auth");
Route::post('/packages/search', "BoxController@search")->middleware("auth");
Route::get('/packages/export/excel', "BoxController@exportExcel")->middleware("auth");
//Route::get('/packages/export/pdf', "BoxController@exportPDF")->middleware("auth");

Route::get('/shippings', "ShippingController@index")->name("shippings.list")->middleware("auth");
Route::get('/shippings/fetch/{page}', "ShippingController@fetch")->middleware("auth");
Route::get('/shippings/create', "ShippingController@create")->name("shippings.create")->middleware("auth");
Route::get('/shippings/show/{id}', "ShippingController@show")->middleware("auth");
Route::get("shippings/statuses", "ShippingController@getAllStatuses")->middleware("auth");
Route::post('/shippings/store', "ShippingController@store")->middleware("auth");
Route::post('/shippings/update', "ShippingController@update")->middleware("auth");
Route::post('/shippings/update-info', "ShippingController@updateInfo")->middleware("auth");
Route::post('/shippings/erase', "ShippingController@delete")->middleware("auth");
Route::get("/shippings/qr/{id}", "ShippingController@showQrOptions")->middleware("auth");
Route::get("/shippings/qr/{id}/{label}/{bill}/{anonymous}", "ShippingController@downloadQR")->middleware("auth");
Route::get("/shippings/receipt/{id}", "ShippingController@receiptPdf")->middleware("auth");
Route::post("/shippings/search", "ShippingController@search")->middleware("auth");
Route::get('/shippings/export/excel/{start_date}/{end_date}', "ShippingController@exportExcel")->middleware("auth");
Route::post('/shippings/process', "ShippingController@process")->middleware("auth");
//Route::get('/shippings/export/pdf/{start_date}/{end_date}', "ShippingController@exportPDF")->middleware("auth");

Route::post("shippings/mass/update", "ShippingController@massUpdate")->middleware("auth");

/*Route::get("shippings/pending", "ShippingController@shippingsPending")->name("shippings.pending")->middleware("auth");
Route::get('/shippings/pending/fetch/{page}', "ShippingController@pendingFetch")->middleware("auth");
Route::get('shippings/pending/edit/{id}', "ShippingController@pendingEdit")->middleware("auth");
Route::post("/shippings/pending/search", "ShippingController@pendingSearch")->middleware("auth");
Route::post("/shippings/pending/update", "ShippingController@pendingUpdate")->middleware("auth");*/

Route::get("/user", "UserController@index")->name("user")->middleware("auth");
Route::post("/users/store", "UserController@store")->middleware("auth");
Route::post("/users/update", "UserController@update")->middleware("auth");
Route::post("/users/erase", "UserController@delete")->middleware("auth");
Route::get('/users/fetch/{page}', "UserController@fetch")->middleware("auth");
Route::post('/users/search', "UserController@search")->middleware("auth");

Route::get('/binnacle', "BinnacleController@index")->name("binnacle")->middleware("admin");
Route::post('/binnacle/fetch', "BinnacleController@fetch")->middleware("admin");
Route::post('/binnacle/search', "BinnacleController@search")->middleware("admin");

Route::get("/resellers/fetch/{recipient_id}", "ResellerController@fetch");
Route::get("/resellers/fetch-all", "ResellerController@fetchAll");

Route::get("/reseller/recipient/create", "ResellerController@createRecipient");
Route::get("/reseller/recipient/edit/{id}", "ResellerController@editRecipient");
Route::post("/reseller/recipient/store", "ResellerController@storeRecipient");
Route::post("/reseller/recipient/update", "ResellerController@updateRecipient");

Route::get("/tracking", "TrackingController@search");

Route::get("profile", "ProfileController@index")->name("profile")->middleware("auth");
Route::post("profile/update", "ProfileController@update")->middleware("auth");

Route::get("clients/shipping/create", "ClientShippingController@create")->name("client.shippings.create")->middleware("auth");
Route::post("clients/shipping/store", "ClientShippingController@store")->middleware("auth");
Route::get("clients/shipping/list", "ClientShippingController@list")->name("client.shippings.list")->middleware("auth");
Route::get("clients/shipping/fetch/{page}", "ClientShippingController@fetch")->middleware("auth");
Route::get("clients/shipping/{tracking}", "ClientShippingController@edit")->middleware("auth");
Route::post('/clients/shipping/search', "ClientShippingController@search")->middleware("auth");
Route::post("clients/shipping/update", "ClientShippingController@update")->middleware("auth");

Route::get("/admin-email", "AdminMailController@index")->name("admin.email");
Route::post("admin-email/store", "AdminMailController@store");
Route::get("/admin-email/fetch", "AdminMailController@fetch");
Route::post("/admin-email/update", "AdminMailController@update");
Route::post("/admin-email/delete", "AdminMailController@delete");

Route::get("test-email", function(){

    $data = ["messageMail" => "Hola User, haz click en el siguiente enlace para validar tu cuenta", "registerHash" => "sdfsdf"];
    
    \Mail::send("emails.register2", $data, function($message) {

        $message->to("nolape7134@1092df.com")->subject("¡Valida tu correo!");
        $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));

    });

});

Route::get("account/import", "AccountImportController@import");

