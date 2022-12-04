<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Mail\OperatorAccountResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\PasswordReset;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class Operator extends Authenticatable implements JWTSubject
{
    use HasRoles, SoftDeletes, Notifiable;

    protected $softDelete = true;
    protected $guard_name = 'api';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'departament_id',
    ];
    

    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at', 'email_verified_at'
    ];

    public function Departament () {
        return $this->belongsTo(Departament::class, 'id');
    }

    public function appointment() {
        return $this->hasMany(Appointment::class);
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
        Mail::to($this->attributes['email'])
        ->send(new OperatorAccountResetPassword ($token));
    }
}
