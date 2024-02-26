<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => 'array',
    ];

    public function trainings()
    {
        return $this->hasManyJson(TrainingRequest::class, 'trainers->trainer_ids');
    }
}
