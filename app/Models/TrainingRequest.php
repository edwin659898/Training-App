<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class TrainingRequest extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $guarded = ['id'];

    protected $casts = [
        'evidence' => 'array',
        'options' => 'json',
        'trainings' => 'json',
        'department_ids' => 'json',
        'trainers' => 'json',
        'specialists' => 'json',
        'trainer_ids' => 'json',
    ];

    public function trainings()
    {
        return $this->belongsToJson(Training::class, 'options->training_ids');
    }

    public function trainerss()
    {
        return $this->belongsToJson(Trainer::class, 'trainers->trainer_ids');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_ids');
    }

    public function trainees(){
        return $this->hasMany(TrainingRequestUser::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }


    public static function boot() {
        parent::boot();

        self::creating(function($data) { 
            $data->trainings()->attach($data->trainings);
        });

        self::updating(function($data) { 
            $trainingRequest = TrainingRequest::find($data->id);
            $data->trainings()->sync($data->trainings);
            if($trainingRequest->trainers != ''){
                $data->trainerss()->sync($data->specialists);
            }else{
                $data->trainerss()->attach($data->specialists);
            }
        });

        static::deleting(function($training) { // before delete() method call this
            TrainingRequestUser::where('training_request_id',$training->id)->delete();
       });
    }

}
