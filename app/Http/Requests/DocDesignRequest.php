<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocDesignRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'otorga' => 'required|string|max:100|min:3|in:'.$this->otorgaValid(),
            'certificado' => 'required|string|max:100|min:2|in:'.$this->certificatedValid(),
            'sponsor_logo' => 'nullable|string',
            'description' => 'required|string|max:500|min:10',
            'date' => 'required_if:hide_date,false|date_format:Y-m-d',
            'signatures' => 'required|array|min:1|max:4',
            'signatures.*' => 'exists:signatures,id',
            'hide_date' => 'nullable|boolean'
        ];
    }

    private function otorgaValid(){
        return 'Otorga el presente,Otorga la presente';
    }
    private function certificatedValid() {
        return 'CERTIFICADO,MENCIÓN,MENCIÓN DE HONOR';
    }
}
