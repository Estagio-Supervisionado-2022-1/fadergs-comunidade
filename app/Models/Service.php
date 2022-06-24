<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = ['id', 'name', 'departament_id'];
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    public function departaments (){
        return $this->belongsTo(Departament::class, 'departament_id');
    }

}
