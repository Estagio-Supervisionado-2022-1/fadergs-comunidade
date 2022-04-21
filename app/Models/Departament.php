<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departament extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function history() {
        return $this->hasMany(HistoryActionDepartament::class, 'departament_id');
    }

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            HistoryActionDepartament::create([
                'departament_id' => $model->id,
                'user_id' => 0,
                'action' => HistoryActionDepartament::ACTION_INSETED,
                'data' => $model->makeHidden(['id', 'updated_at', 'created_at', 'deleted_at'])->toJson()
           ]);
        });

        self::updated(function ($model) {
            HistoryActionDepartament::create([
                'departament_id' => $model->id,
                'user_id' => 0,
                'action' => HistoryActionDepartament::ACTION_UPDATED,
                'data' => $model->makeHidden(['id', 'updated_at', 'created_at', 'deleted_at'])->toJson()
           ]);
        });

        self::deleted(function ($model) {
            HistoryActionDepartament::create([
                'departament_id' => $model->id,
                'user_id' => 0,
                'action' => HistoryActionDepartament::ACTION_DELETED,
                'data' => $model->makeHidden(['id', 'updated_at', 'created_at', 'deleted_at'])->toJson()
           ]);
        });
    }
}
