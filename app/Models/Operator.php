<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Operator extends Model
{
    use HasRoles;
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_departament',
    ];

    public function addresses () {
        return $this->hasMany(Address::class, 'departament_id');
    }
}
