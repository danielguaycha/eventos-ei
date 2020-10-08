<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocDesigns extends Model
{
    protected $fillable = [
        'sponsor',
        'description',
        'signatures',
        'event_id',
        'date',
        'sponsor_logo'
    ];

    protected $casts = [
      'signatures' => 'collection'
    ];

    public function signatures(){
        return $this->belongsToMany(Signature::class, 'event_signatures', 'signature_id', 'event_id');
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
