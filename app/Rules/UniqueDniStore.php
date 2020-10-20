<?php

namespace App\Rules;

use App\Person;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueDniStore implements Rule
{

    private $id;
    public function __construct($id = null)
    {
        $this->id = $id;
    }


    public function passes($attribute, $value)
    {
        if ($this->id !== null) {

            $user = User::findOrFail($this->id);

            $p = Person::where([
                ['dni', $value],
                ['id', '<>', $user->person_id]
            ])->first();

            return !$p;
        }

        $p = Person::where('dni', $value)->first();
        return !$p;
    }


    public function message()
    {
        return 'Ya existe un usuario registrado con ese número de cedula.';
    }
}
