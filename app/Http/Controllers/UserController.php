<?php

namespace App\Http\Controllers;


use App\Rules\UniqueEmailStore;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile(Request $request)
    {
        return view('auth.user.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'min:3'],
            'surname' => ['required', 'string', 'max:100', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', new UniqueEmailStore($request->user()->id)],
        ]);


        $request->user()->update([
            'email' => $request->get('email')
        ]);

        $request->user()->person()->update([
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
        ]);

        return back()->with('ok', 'Datos actualizados con éxito');
    }

    public function password()
    {
        return view('auth.user.change-pw');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_now' => 'required|string',
            'password' => ['nullable',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                'string', 'min:8', 'confirmed'],
        ], [
            'password_now.required' => 'Ingrese su contraseña actual',
            'password.required' => 'La contraseña es requerida',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un símbolo, genere una nueva',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres'
        ]);

        $u = User::findOrFail($request->user()->id);

        if (!Hash::check($request->get('password_now'), $u->password)) {
            return back()->with('err', 'La contraseña actual es incorrecta');
        }

        $u->password = Hash::make($request->get('password'));

        $u->save();

        return back()->with('ok', 'Contraseña actualizada con éxito');
    }
}
