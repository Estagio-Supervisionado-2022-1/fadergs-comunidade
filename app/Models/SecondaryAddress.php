<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecondaryAddress extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'secondary_addresses';

    protected $softDelete = true;

    protected $fillable = [
        'building_number',
        'floor', 
        'room', 
        'description',
        'address_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function addresses() {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    
}
