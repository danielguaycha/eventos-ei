<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Person;
use App\Providers\RouteServiceProvider;
use App\Rules\CedulaValida;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest');
    }


    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100', 'min:3'],
            'surname' => ['required', 'string', 'max:100', 'min:3'],
            'dni' => ['required', 'max:10', 'min:10', new CedulaValida, 'unique:persons,dni'],
            'role' => ['required', 'in:'.User::rolStudent.','.User::rolParticular.''],
            'email' => ['required', 'string', 'email', 'max:255',
                function ($attribute, $value, $fail) use ($data) {
                    if ($data['role'] === User::rolStudent) {
                        if (!Str::contains($value, "utmachala.edu.ec")) {
                            $fail('Si eres estudiante de la UTMACH, usa tu correo institucional');
                        }
                    }
                },'unique:users,email'],
            'password' => ['required',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                'string', 'min:8', 'confirmed'],
            'sendEmail' => 'nullable|boolean'
        ], $this->messages());
    }

    protected function create(array $data)
    {
        $person = Person::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dni' => $data['dni']
        ]);

        return User::create([
            'email' => $data['email'],
            'person_id' => $person->id,
            'password' => Hash::make($data['password']),
        ]);
    }

    private function messages() {
        return [
            'name.required' => 'Ingrese sus nombres',
            'name.string' => 'Su nombre debe ser texto',
            'name.min' => 'Su nombre debe contener al menos 3 caracteres',
            'surname.required' => 'Ingrese sus apellidos',
            'surname.string' => 'Su apellido debe contener texto',
            'surname.min' => 'Su apellido debe contener al menos 3 caracteres',
            'dni.required' => 'La cedula es requerida',
            'dni.min' => 'La cedula debe tener un mínimo de 10 caracteres',
            'dni.max' => 'La cedula debe tener un maximo de 10 caracteres',
            'dni.unique' => 'Ya existe una persona registrada con esta cedula',
            'email.required' => 'Es necesario que ingreses un correo',
            'email.unique' => 'Este correo ya ha sido tomado por un usuario',
            'password.required' => 'La contraseña es requerida',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un símbolo',
            'password.min' => 'La contraseña debe contener una longitud de al menso 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'role.required' => 'Debes escoger el modo de inscripción. Estudiante o Particular',
            'role.in' => 'El tipo de inscripción no es válido',
        ];
    }
}
