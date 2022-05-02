<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departament extends Model
{
    use HasFactory, SoftDeletes;

    protected $softDelete = true;

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function Operator (){
        return $this->hasMany(Operator::class, 'departament_id');
    }

    public function Services () {
        return $this->hasMany(Services::class, 'departament_id');
    }

    
}
