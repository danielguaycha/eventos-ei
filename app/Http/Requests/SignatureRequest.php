<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignatureRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->method() === 'POST')
            return [
                'name' => 'required|max:150',
                'cargo' => 'required|max:150',
                'image' => 'required|image|mimes:png'
            ];
        if ($this->method() === 'PUT')
            return [
                'name' => 'required|max:150',
                'cargo' => 'required|max:150',
                'image' => 'nullable|image|mimes:png'
            ];
    }
}
