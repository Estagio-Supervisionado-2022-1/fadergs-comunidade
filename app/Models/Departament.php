<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departament extends Model
{
    use HasFactory, SoftDeletes;

    protected $softDelete = true;

    protected $fillable = ['name', 'description'];

    public static $attrMakeHidden = ['id', 'updated_at', 'created_at', 'deleted_at'];

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
                'data' => $model->makeHidden(Departament::$attrMakeHidden)->toJson()
           ]);
           $model->makeVisible(Departament::$attrMakeHidden);
        });

        self::updated(function ($model) {
            HistoryActionDepartament::create([
                'departament_id' => $model->id,
                'user_id' => 0,
                'action' => HistoryActionDepartament::ACTION_UPDATED,
                'data' => $model->makeHidden(Departament::$attrMakeHidden)->toJson()
           ]);
           $model->makeVisible(Departament::$attrMakeHidden);
        });

        self::deleted(function ($model) {
            HistoryActionDepartament::create([
                'departament_id' => $model->id,
                'user_id' => 0,
                'action' => HistoryActionDepartament::ACTION_DELETED,
                'data' => $model->makeHidden(Departament::$attrMakeHidden)->toJson()
           ]);
        });
    }
    public function Services () {
        return $this->hasMany(Services::class, 'departament_id');
    }

    
    public function Operator (){
        return $this->belongsTo(Operator::class, 'departament_id');
    }
}
