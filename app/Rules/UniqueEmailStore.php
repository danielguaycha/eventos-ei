<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueEmailStore implements Rule
{
    private $id;
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function passes($attribute, $value)
    {
        if ($this->id!==null) {
            $u = User::where([
                ['email', $value],
                ['id', '<>', $this->id]
            ])->first();
            return !$u;
        }
        $u = User::where("email", $value)->first();

        return !$u;
    }

    public function message()
    {
        return 'Ya existe un usuario registrado con este correo.';
    }
}
