<?php

namespace App\Http\Controllers\Guest;

use App\Event;
use App\Http\Controllers\Controller;

class RedirectController extends Controller
{
    public function redirectEvent($shortLink) {

        $e = Event::where('short_link', $shortLink)->first();
        if (!$e) {
            abort(404);
        }
        return redirect(route('evento.mostrar', ['event' => $e->slug]));
    }
}
