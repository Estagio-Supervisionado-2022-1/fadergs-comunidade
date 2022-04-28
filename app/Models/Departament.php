<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departament extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function Operator (){
        return $this->belongsTo(Operator::class, 'departament_id');
    }

    public function Services () {
        return $this->hasMany(Services::class, 'id');
    }

    
}
