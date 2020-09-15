<?php

namespace App\Http\Controllers\Admin;

use App\DocDesigns;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class DocGenerateController extends Controller
{
    public function make() {

        $data = [
            'titulo' => 'Hi'
        ];

        return \PDF::loadView('docs.index', $data)
            ->setPaper('a4', 'landscape')
            ->stream('archivo.pdf');
    }

    public function viewDoc(){
        return view("docs.index", ['titulo' => 'Hi']);
    }
}
