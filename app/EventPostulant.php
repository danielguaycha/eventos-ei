<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventPostulant extends Model
{
    protected $table = 'event_postulant';

    protected $fillable = [
        'event_id', 'user_id'
    ];
}
