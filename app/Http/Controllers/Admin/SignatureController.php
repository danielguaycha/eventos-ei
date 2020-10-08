<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignatureRequest;
use App\Signature;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpseclib\Crypt\RC4;

class SignatureController extends Controller
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

        $s = Signature::where([
            ['status', '>', 0],
            ['name', 'like', "$search%"]
        ])
            ->orderBy('id', 'desc')->paginate(20);
        return view('signature.index', ['signatures'=> $s]);
    }

    public function create()
    {
        return view('signature.create');
    }

    public function store(SignatureRequest $request)
    {
        $s = new Signature();
        $s->name = $request->name;
        $s->cargo = Str::upper($request->cargo);
        $s->image = $this->uploadImg($request->file('image'), 'signatures', 300, 300);
        $s->save();

        return back()->with('ok', 'Firma agregada con éxito');
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $s = Signature::findOrFail($id);

        return view('signature.edit', ['signature' => $s]);
    }

    public function update(SignatureRequest $request, $id)
    {
        $s = Signature::findOrFail($id);
        $s->name = $request->name;
        $s->cargo = $request->cargo;

        if ($request->hasFile('image')) {
            //if (Storage::disk('public')->exists($s->image)) {
              //  Storage::disk('public')->delete($s->image);
            //}
            $s->image = $this->uploadImg($request->file('image'), 'signatures', 300, 300);
        }

        $s->save();

        return back()->with('ok', "Firma actualizada con éxito");
    }

    public function destroy($id)
    {
        $s = Signature::findOrFail($id);
        $s->status = 0;
        $s->save();

        return back()->with('ok', 'Firma dada de baja con éxito');
    }
}
