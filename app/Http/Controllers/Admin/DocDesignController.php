<?php

namespace App\Http\Controllers\Admin;

use App\DocDesigns;
use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocDesignRequest;
use App\Signature;
use App\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocDesignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:events.design.view')->only(['preview']);
        $this->middleware('permission:events.design.edit')->only(['update', 'edit']);
    }

    public function preview($eventId){

        $e = Event::with('signatures')->findOrFail($eventId);
        $design = DocDesigns::where('event_id', $eventId)->first();

        return \PDF::loadView('design.preview', ['event' => $e, 'data'=> $design, ])
            ->setPaper('a4', 'landscape')
            ->stream('PreviewDesign.pdf');
    }

    public function update(DocDesignRequest $request, $id) {

        $doc = DocDesigns::findOrFail($id);
        $event = Event::findOrFail($doc->event_id);
        $doc->otorga = $request->get('otorga');
        $doc->certificado = $request->get('certificado');
        $doc->description = $request->get('description');

        if ($request->get('hide_date'))
            $doc->show_date = 0;
        else {
            $doc->date = $request->get('date');
            $doc->show_date = 1;
        }

        if ($request->get('sponsor_logo')) {
            $path = storage_path('app/public/'.$request->get('sponsor_logo'));
            if (!File::exists($path)) {
                return back()->with("err", "El logo del organizador es invalido");
            }
            $doc->sponsor_logo = $request->get('sponsor_logo');
        } else {
            $doc->sponsor_logo = null;
        }

        $event->signatures()->sync($request->get('signatures'));
        $doc->save();

        return back()->with('ok', 'Certificado actualizado con éxito');
    }

    public function edit($id) {
        $doc = DocDesigns::findOrFail($id);
        $event = Event::with('signatures')->findOrFail($doc->event_id);

        $signatures = Signature::where('status', Signature::ACTIVO)->get();
        $sponsor = Sponsor::where([
            ['logo', '<>', null],
            ['status', Sponsor::STATUS_ACTIVE]
        ])->get();

        return view('docs.edit', [
            'doc' => $doc,
            'sponsors' => $sponsor,
            'event' => $event,
            'signatures' => $signatures
        ]);
    }
}
