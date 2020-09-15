<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    const ACTIVO = 1;
    public $timestamps = false;

    protected $fillable = [
        'name', 'cargo', 'image', 'status'
    ];
}
