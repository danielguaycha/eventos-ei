<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::group(['namespace' => 'Admin'], function (){
    Route::get('postulantes/{evento}', 'PostulanteController@listar');
});



