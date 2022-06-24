<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Address extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['zipcode', 'streetName', 'district', 'city', 'stateAbbr'];
    protected $hidden = ['deleted_at', 'updated_at', 'created_at', 'departament_id'];

    public function secondary_addresses (){
        return $this->hasMany(SecondaryAddress::class, 'address_id');
    }
}
