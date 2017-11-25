<?php

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


use App\User;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');
Route::get('/logout', 'Auth\LoginController@logout');
Route::group(["middleware" => ["admin_mi","auth"]],function(){

    Route::group(["namespace" => "Admin"],function(){

        Route::get("/site-ayarlari","AyarController@index");
        Route::put("/site-ayarlari/guncelle","AyarController@guncelle");
        Route::resource("user","UserController");
        Route::resource("kategori","KategoriController");
        Route::resource("makale","MakaleController");
        Route::post("/makale/durum-degis","MakaleController@durumDegis");
        Route::get("/talep","YazarlikTalepController@index");
        Route::post("/talep/durum-degis","YazarlikTalepController@durumDegis");
        Route::delete("/talep/{id}","YazarlikTalepController@destroy")->name("talep.destroy");
    });

});

Route::group(["middleware" => ["yazar_mi","auth"]],function(){

    Route::group(["namespace" => "Yazar"],function(){

        Route::resource("makalem","MakaleController");

    });
});

Route::get("/yazarlik-talebi","YazarlikTalepController@index");
Route::post("/yazarlik-talebi/gonder","YazarlikTalepController@gonder");


Route::get("/yayinlanan-makale/{slug}","MakaleController@index");
Route::get("/yayinlanan-kategori/{slug}","KategoriController@index");




