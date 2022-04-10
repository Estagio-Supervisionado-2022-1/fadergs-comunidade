<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['status', 'datetime'];

    public function appointments (){
        return $this->hasMany(Address::class, 'id_address');
    }
}
