<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['status', 'datetime'];

    public function addresses (){
        return $this->hasMany(Address::class, 'address_id');
    }

    public function services () {
        return $this->hasMany(Service::class, 'service_id');
    }

    public function operators () {
        return $this->belongsToMany(Operator::class,'appointment_operator', 'appointment_id', 'user_id' );
    }

    public function users (){
        return $this->hasMany(User::class, 'user_id');
    }


}
