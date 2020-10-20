<?php

namespace App\Rules;

use App\Person;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class PersonUnique implements Rule
{
    private $dni;

    public function __construct($dni)
    {
        $this->dni = $dni;
    }

    public function passes($attribute, $value)
    {
        $person = Person::where('dni', $this->dni)->first();
        if (!$person) return true;


        $exists = User::where([
            ['person_id', $person->id],
            ['email_verified_at', '<>', null]
        ])->first();

        if ($exists) return false;

        return true;
    }

    public function message()
    {
        return 'Ya existe una persona registrada con esta cedula';
    }
}
