<?php

namespace App\Http\Requests;

use App\Rules\CedulaValida;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->method() === 'POST')
            return [
                'name' => ['required', 'string', 'max:100', 'min:3'],
                'surname' => ['required', 'string', 'max:100', 'min:3'],
                'dni' => ['required', new CedulaValida],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                    'string', 'min:8'],
            ];
        if ($this->method() === 'PUT')
            return [
                    'name' => ['required', 'string', 'max:100', 'min:3'],
                    'surname' => ['required', 'string', 'max:100', 'min:3'],
                    'dni' => ['required', new CedulaValida],
                    'email' => ['required', 'string', 'email', 'max:255',  'unique:users,email,'.$this->admin],
                    'password' => ['nullable',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                        'string', 'min:8'],
                    'role' => "required|in:".User::rolAdmin.",".User::rolStudent.",".User::rolParticular
                ];
    }

    public function messages() {
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
            'email.unique' => 'Ya existe un usuario registrado con este email',
            'password.required' => 'La contraseña es requerida',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un símbolo, genere una nueva',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres'
        ];
    }
}
