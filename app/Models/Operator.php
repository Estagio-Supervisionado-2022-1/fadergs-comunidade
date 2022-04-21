<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\PasswordReset;
use Illuminate\Notifications\Notifiable;

class Operator extends Authenticatable implements JWTSubject
{
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'departament_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Departament () {
        return $this->hasMany(Departament::class, 'departament_id');
    }

    public function setPasswordAttribute($password){
        if (!empty($password)){
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }
}
