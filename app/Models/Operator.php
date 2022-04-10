<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_departament',
    ];

    public function addresses () {
        return $this->hasMany(Address::class, 'id_departament');
    }
}
