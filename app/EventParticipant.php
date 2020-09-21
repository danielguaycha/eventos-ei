<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    const STATUS_INSCRITO = 0;
    const STATUS_CALIFICADO = 1;
    const STATUS_FINALIZADO = 2;

    protected $fillable = [
      'nota_3', 'nota_7', 'src', 'event_id', 'user_id', 'status'
    ];
}
