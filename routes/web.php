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
    return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home');

\Illuminate\Support\Facades\Auth::routes();

Route::group(['namespace' => 'Guest'], function () {
    // redirects
    Route::get('e/{shortLink}', 'RedirectController@redirectEvent')->name('redirect.event');
});

Route::group([
    'namespace' => 'Admin'], function () {
    // admins
    Route::resource("user/admins", 'AdminController');
    Route::resource("user/students", 'StudentController');

    // events
    Route::get('events/postular/{event}', 'EventController@postular')->name('events.postular');
    Route::resource("events", 'EventController');
    // signatures
    Route::resource("signatures", 'SignatureController')->except(['show']);
    // sponsors
    Route::resource('sponsor', 'SponsorController')->except(['show']);

    //Diseño de certificado
    Route::get('design/edit/{id}', 'DocDesignController@edit')->name('doc.edit');
    Route::get('design/preview/{eventId}', 'DocDesignController@preview')->name('design.preview');
    Route::put('design/{id}', 'DocDesignController@update')->name('design.update');

    // certs
    Route::get('/doc', 'DocGenerateController@make');
    Route::get('/docs', 'DocGenerateController@viewDoc');

    // img
    Route::get('/img/{pathFile}/{filename}/{h?}', 'UtilController@showImg')->name('img')->middleware('auth');
});



