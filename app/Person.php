<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    protected $table = "persons";

    protected $fillable = [
      'name', 'surname', 'dni'
    ];

    protected $hidden = [
        'updated_at', 'created_at', 'deleted_at'
    ];

    public function user(){
        return $this->hasOne('App\User');
    }
}
