<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    const STATUS_ACTIVE = 1;

    protected $fillable = [
      'name', 'logo', 'status'
    ];
}
