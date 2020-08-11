<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const rolStudent = 'student';
    const rolParticular = 'particular';
    const rolAdmin = "admin";
    const rolRoot = "root";
    const publicRoles = [self::rolStudent, self::rolParticular];
    const roles = [self::rolRoot, self::rolAdmin, self::rolStudent, self::rolParticular];

    protected $fillable = [
        'name','password', 'person_id', 'role', 'person_id', 'email'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function person() {
        return $this->belongsTo('App\Person');
    }

    public function hasRole(string $role) : bool {
        return $this->role === $role;
    }

    public function isAdmin() : bool {
        if ($this->role === self::rolAdmin || $this->role === self::rolRoot) {
            return true;
        }
        return false;
    }

    public function isRoot() : bool {
        return $this->role === self::rolRoot;
    }

    public function isStudent() : bool {
        return $this->role === self::rolStudent || $this->role === self::rolParticular;
    }
}
