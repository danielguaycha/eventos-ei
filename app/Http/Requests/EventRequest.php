<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Event;
use PHPUnit\Util\Type;

class EventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:150|min:3',
            'description' => 'nullable|max:255',
            'type' => 'required|in:'.Event::TypeAsistencia.",".Event::TypeAprovacion.",".Event::TypeAsistenciaAprovation,
            'sponsor_id' => 'required|exists:sponsors,id',
            'f_inicio' => 'required|date|date_format:Y-m-d',
            'f_fin' => 'required|date|after:f_inicio|date_format:Y-m-d',
            'matricula_inicio' => 'required|date|date_format:Y-m-d',
            'matricula_fin' => 'required|date|after:matricula_inicio|date_format:Y-m-d',
            'signatures' => 'required|array|min:1|max:4',
            'signatures.*' => 'exists:signatures,id',
            'hours' => 'required|numeric|min:0|max:9999'
        ];
    }

    public function messages()
    {
        return [
            'f_fin.after'=> 'La fecha fin del evento debe ser posterior a la fecha de inicio',
            'matricula_fin.after'=> 'La fecha fin de matricula debe ser posterior a la fecha de inicio'
        ];
    }
}
