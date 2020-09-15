<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SponsorRequest;
use App\Sponsor;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SponsorController extends Controller
{
    use UploadTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = '';

        if ($request->query('q')) {
            $search = $request->query('q');
        }

        $s = Sponsor::where([
            ['status', '>', 0],
            ['name', 'like', "$search%"]
        ])
            ->orderBy('id', 'desc')->paginate(20);
        return view('sponsor.index', ['sponsors' => $s]);
    }

    public function create()
    {
        return view('sponsor.create');
    }

    public function store(SponsorRequest $request)
    {
        $s = new Sponsor();
        $s->name = Str::upper($request->name);
        if ($request->hasFile('image')) {
            $s->logo = $this->uploadImg($request->file('image'), 'sponsors', 400, 400);
        }
        $s->save();

        return back()->with('ok', 'Organizador agregado con éxito');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $s = Sponsor::findOrFail($id);

        return view('sponsor.edit', ['sponsor' => $s]);
    }

    public function update(SponsorRequest $request, $id)
    {
        $s = Sponsor::findOrFail($id);
        $s->name = $request->name;

        //dd($request->hasFile("logo"));
        if ($request->hasFile('logo')) {
            if (Storage::disk('public')->exists($s->logo)) {
                Storage::disk('public')->delete($s->logo);
            }
            $s->logo = $this->uploadImg($request->file('logo'), 'sponsors', 400,400);
        }

        $s->save();

        return back()->with('ok', "Organizador actualizado con éxito");
    }

    public function destroy($id)
    {
        $s = Sponsor::findOrFail($id);
        $s->status = 0;
        $s->save();

        return back()->with('ok', 'Organizador dado de baja con éxito');
    }
}
