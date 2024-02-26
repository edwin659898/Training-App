<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;


    protected $guarded = ['id'];

    protected $casts = [
        'type' => 'array',
    ];


    public function trainingRequests()
    {
        return $this->hasManyJson(TrainingRequest::class, 'options->training_ids');
    }

    public function department(){
        return $this->belongsTo(Department::class,'department_id');
    }
}
