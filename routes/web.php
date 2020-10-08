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

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

\Illuminate\Support\Facades\Auth::routes();

Route::group(['namespace' => 'Guest'], function () {
    // redirects
    Route::get('e/{shortLink}', 'RedirectController@redirectEvent')->name('redirect.event');
});

Auth::routes(['verify' => true]);

Route::group([
    'namespace' => 'Admin', 'middleware' => ['verified']], function () {
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

    /*Route::get('mailable', function () {
        $invoice = App\Event::find(21);
        return new App\Mail\CertificateMail($invoice, request()->user());
    });*/

    /*Route::get('/prueba', function () {
        $event = App\Event::find(21);
        $user = request()->user();
        $participante = \App\EventParticipant::find(14);

        return view('docs.certificate', [
            'event' => $event,
            'design'=> \App\DocDesigns::where('event_id', $event->id)->first() ,
            'user' => $user,
            'notas' => $participante
        ]);
    });*/
});



