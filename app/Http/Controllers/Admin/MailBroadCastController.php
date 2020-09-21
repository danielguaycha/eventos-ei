<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\SendCert;
use Illuminate\Http\Request;

class MailBroadCastController extends Controller
{
    public function send($event, Request $request) {
        $request->user()->notify(new SendCert());
    }
}
