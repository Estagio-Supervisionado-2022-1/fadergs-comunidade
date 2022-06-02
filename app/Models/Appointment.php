<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $softDelete = true;
    protected $fillable = ['service_id', 'datetime', 'service_id', 'address_id', 'user_id'];

    public function addresses (){
        return $this->hasMany(Address::class, 'address_id');
    }

    public function services () {
        return $this->hasMany(Service::class, 'service_id');
    }

    public function operators () {
        return $this->belongsTo(Operator::class);
    }

    public function users (){
        return $this->hasMany(User::class, 'user_id');
    }


}
