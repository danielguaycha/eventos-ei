<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Tavo\ValidadorEc;

class CedulaValida implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $validate = new ValidadorEc();
        return $validate->validarCedula($value);
    }


    public function message()
    {
        return 'El campo DNI/Cedula es incorrecto, verifique!';
    }
}
