<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['service', 'id_departament'];

    public function departaments (){
        return $this->hasMany(Departament::class, 'id_departament');
    }

}
