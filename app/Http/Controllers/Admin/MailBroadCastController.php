<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\Http\Controllers\Controller;
use App\Jobs\MailJobs;
use Illuminate\Http\Request;

class MailBroadCastController extends Controller
{
    public function send($event, Request $request) {
        $e = Event::findOrFail($event);

        MailJobs::dispatchAfterResponse($e);

        return back()->with('ok', 'Se ha programado el envío de correos');
    }
}
