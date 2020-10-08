<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    const TypeAsistencia = 'asistencia';
    const TypeAprovacion = 'aprobacion';
    const TypeAsistenciaAprovation = 'asistencia_aprobacion';

    const STATUS_ACTIVO = 1;
    const STATUS_CALIFICADO = 2;
    const STATUS_FINALIZADO = 3;

    use SoftDeletes;

    protected $hidden = ['pivot'];

    protected $fillable = [
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
        'sponsor_id' => 'integer'
    ];


    public function design () {
        return $this->hasOne(DocDesigns::class);
    }

    public function sponsor(){
        return $this->belongsTo(Sponsor::class);
    }

    public function signatures(){
        return $this->belongsToMany(Signature::class, 'event_signatures');
    }

    public function postulants() {
        return $this->belongsToMany(User::class, 'event_postulant');
    }

    public function participantes() {
        return $this->belongsToMany(User::class, 'event_participants');
    }

    public function admins() {
        return $this->belongsToMany(User::class, "user_admin_events", "event_id", "user_id");
    }

    public function isAdmin($userId){
        return $this->admins()->where([
            ['user_id', $userId],
            ['event_id', $this->id]
        ])->exists();
    }

    // obtener tipo
    public function type() {
        switch ($this->type) {
            case Event::TypeAsistenciaAprovation:
                return "Asistencia y Aprobación";
            case Event::TypeAsistencia:
                return "Asistencia";
            case Event::TypeAprovacion:
                return "Aprobación";
        }
        return "";
    }

    // fechas
    public function matriculaDate(){
        return $this->formatDates($this->matricula_inicio, $this->matricula_fin);
    }
    public function eventDate() {
        return $this->formatDates($this->f_inicio, $this->f_fin);
    }
    public function eventDateForDoc(){
        return $this->humanizeDate($this->f_inicio, $this->f_fin);
    }

    // formatear fechas
    public function formatDates($ini, $end) {
        $ini = Carbon::parse($ini);
        $end = Carbon::parse($end);

        return $ini->isoFormat("D/MMM/YY")." - ".$end->isoFormat("D/MMM/YY");
    }

    public function humanizeDate($ini, $end) {
        $ini = Carbon::parse($ini);
        $end = Carbon::parse($end);
        if ($ini->isSameMonth($end)) {
            return "del $ini->day al $end->day de $ini->monthName de $ini->year";
        } else {
            return "$ini->day/$ini->shortMonthName/$ini->year al $end->day/$end->shortMonthName/$end->year";
        }
    }
}
