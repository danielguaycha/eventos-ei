<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = "persons";

    protected $fillable = [
      'name', 'surname', 'dni'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];

    public function user(){
        return $this->hasOne('App\User');
    }
}
