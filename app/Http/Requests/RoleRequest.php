<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
       return [
            'name' => 'required|string|max:50|min:2',
            'perms' => 'required|array|min:1',
            'perms.*' => 'required|numeric|exists:permissions,id'
        ];
    }

    public function messages()
    {
        return [
            'perms.required' => 'Seleccione al menos un permiso para crear el rol'
        ];

    }
}
