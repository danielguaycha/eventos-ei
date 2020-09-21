<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventPostulant extends Model
{
    const STATUS_OK = 1;
    const STATUS_PENDIENTE = 0;
    protected $table = 'event_postulant';

    protected $fillable = [
        'event_id', 'user_id', 'status'
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function getStatus() {
        switch ($this->status) {
            case self::STATUS_PENDIENTE:
                return 'PENDIENTE';
            case self::STATUS_OK:
                return 'APROBADO';
            default:
                return 'DESCONOCIDO';
        }
    }
}
