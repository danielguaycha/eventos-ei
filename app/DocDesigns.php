<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocDesigns extends Model
{
    protected $fillable = [
        'sponsor',
        'description',
        'signature_a',
        'signature_b',
        'signature_c',
        'logo_b',
        'event_id',
        'date'
    ];

    public function signatures(){
        return $this->belongsToMany(Signature::class, 'event_signatures', 'signature_id', 'event_id');
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
