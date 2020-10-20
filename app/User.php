<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles, SoftDeletes;

    const rolStudent = 'student';
    const rolParticular = 'particular';
    const rolAdmin = "admin";
    const rolRoot = "root";
    const publicRoles = [self::rolStudent, self::rolParticular, 'other'];
    const roles = [self::rolRoot, self::rolAdmin, self::rolStudent, self::rolParticular];

    protected $fillable = [
        'name', 'password', 'person_id', 'role', 'person_id', 'email', 'email_verified_at'
    ];

    protected $hidden = [
        'password', 'remember_token','pivot', 'deleted_at', 'email_verified_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime', 'deleted_at',
    ];

    public function person() {
        return $this->belongsTo('App\Person');
    }

    public function admins() {
        return $this->roles()->where([
            ['name', '<>', User::rolStudent],
            ['name', '<>', User::rolRoot],
        ]);
    }

    public function isAdmin() : bool {
        if ($this->hasAnyRole([self::rolAdmin, self::rolRoot])) {
            return true;
        }
        return false;
    }

    public function isRoot() : bool {
        return $this->hasRole(self::rolRoot);
    }

    public function isStudent() : bool {
        return$this->hasRole(self::rolStudent);
    }

    public function events() {
        return $this->belongsToMany(Event::class, "user_admin_events", "user_id", "event_id");
    }

    public function events_aprobados() {
        return $this->hasMany(EventParticipant::class, "user_id");
    }
}
