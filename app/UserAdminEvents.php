<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAdminEvents extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'event_id'
    ];
}
