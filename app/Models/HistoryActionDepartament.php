<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryActionDepartament extends Model
{
    use HasFactory;

    const ACTION_INSETED = 1;
    const ACTION_UPDATED = 2;
    const ACTION_DELETED = 3;

    const ACTIONS = [
        self::ACTION_INSETED,
        self::ACTION_UPDATED,
        self::ACTION_DELETED
    ];

    protected $fillable = ['departament_id', 'user_id', 'action', 'data'];

    public function setActionAttribute($value) 
    {   
        if (!in_array($value, self::ACTIONS)) {
            new Exception('value action error');
        }
        $this->attributes['action'] = $value; 
    }

    public function scopeFindNames($query, $name)
    {
        return $query->whereJsonContains('data', ['name' => $name]);
    }
}
