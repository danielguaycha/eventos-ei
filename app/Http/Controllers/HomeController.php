<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['contact']);
    }

    public function index()
    {
        return view('home');
    }

    public function contact(Request $request)
    {
        $request->validate([
            'name' => 'required|max:75',
            'topic' => 'required|max:100',
            'from' => 'required|mail',
            'message' => 'required|max:150',
        ]);
    }
}
