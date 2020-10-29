<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

// index page
Route::get('/', function () {
    return view('index');
});

// home before login
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

// user routes
Route::get('user/profile', 'UserController@profile')->name('user.profile');
Route::put('user/profile', 'UserController@updateProfile')->name('user.update');
Route::get('user/password', 'UserController@password')->name('user.password');
Route::put('user/password', 'UserController@updatePassword')->name('user.update_password');

// rutas publicas
Route::get('evento/{event}', 'Admin\EventController@show')->name('evento.mostrar');

Route::group(['namespace' => 'Guest'], function () {
    // redirects
    Route::get('e/{shortLink}', 'RedirectController@redirectEvent')->name('redirect.event');
    Route::get('certificado/{id}', 'CertificadoController@show');
});

Route::group([
    'namespace' => 'Admin', 'middleware' => ['verified', 'role:root|admin']], function () {
    // roles
    Route::resource('rol', 'RoleController')->except('show');
    // admins
    Route::get('user/admins/perms/{userId}', 'AdminController@showPerms')->name('admins.perms');
    Route::post('user/admins/perms/{userId}', 'AdminController@savePerms')->name('admins.perms_save');
    Route::resource("user/admins", 'AdminController');
    Route::get('user/students/search', 'StudentController@search');
    Route::resource("user/students", 'StudentController');

    // events
    Route::get('events/broadcast/email/{event}', 'MailBroadCastController@send')->name('events.send_mail');
    Route::get('events/postular/{event}', 'EventController@postular')->name('events.postular');
    Route::resource("events", 'EventController');

    // administradores de eventos
    Route::post('events/admins/add', 'EventController@addAdmins');
    Route::delete('events/admins/{event}/{user}', 'EventController@destroyAdmins');
    Route::get('events/admins/api/{event}', 'EventController@listAdmins');
    Route::get('events/admins/{event}',  'EventController@indexAdmins')->name('events.admins');

    // postulaciones
    Route::put('postulantes/accept/all', 'PostulanteController@acceptAll')->name('postulante.acceptAll');
    Route::get('postulantes/accept/{id}', 'PostulanteController@acceptOrDeny')->name('postulante.accept');
    Route::get('postulantes/listar/{event}', 'PostulanteController@list')->name('postulates.listar');
    Route::get('postulantes/{event}', 'PostulanteController@index')->name('postulates.index');

    // participantes

    Route::get('events/sendmail/{participante}', 'ParticipantController@sendOneEmail');
    Route::get('participantes/aprobados/{event}', 'ParticipantController@aprobados');
    Route::post('participantes/add', 'ParticipantController@add');
    Route::get('participantes/listar/{event}', 'ParticipantController@list');
    Route::delete('participantes/{id}', 'ParticipantController@destroy');
    Route::get('participantes/{evento}', 'ParticipantController@index')->name('participantes.index');

    // calificaciones
    Route::get('events/notas/{event}/editar', 'ParticipantController@editNotas')->name('events.notas_edit');
    Route::get('events/notas/{event}', 'ParticipantController@calificar')->name('events.notas');
    Route::post('events/notas/save/{event}', 'ParticipantController@saveNotas');
    Route::post('events/notas/finish/{event}', 'ParticipantController@confirmNotas');
    Route::get('notas/{event}', 'ParticipantController@listForNotas');

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



