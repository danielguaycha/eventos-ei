<?php

namespace App\Http\Requests;

use App\Rules\CedulaValida;
use App\Rules\UniqueDniStore;
use App\Rules\UniqueEmailStore;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
                'dni' => ['required', new CedulaValida, new UniqueDniStore],
                'email' => ['required', 'string', 'email', 'max:255', new UniqueEmailStore],
                'password' => ['required',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                    'string', 'min:8'],
                'type' => ['required', 'in:'.User::rolStudent.','.User::rolParticular]
            ];
        if ($this->method() === 'PUT')
            return [
                'name' => ['required', 'string', 'max:100', 'min:3'],
                'surname' => ['required', 'string', 'max:100', 'min:3'],
                'dni' => ['required', new CedulaValida, new UniqueDniStore($this->student)],
                'email' => ['required', 'string', 'email', 'max:255', new UniqueEmailStore($this->student)],
                'password' => ['nullable',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                    'string', 'min:8'],
                'type' => ['required', 'in:'.User::rolStudent.','.User::rolParticular],
                'role' => "required|exists:roles,id|not_in:1"
            ];
    }
}
