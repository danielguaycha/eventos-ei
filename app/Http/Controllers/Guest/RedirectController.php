<?php

namespace App\Http\Controllers\Guest;

use App\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirectEvent($shortLink) {
        $e = Event::where('short_link', $shortLink)->first();
        if (!$e) {
            abort(404);
        }
        return redirect(route('events.show', ['event' => $e->slug]));
    }
}
