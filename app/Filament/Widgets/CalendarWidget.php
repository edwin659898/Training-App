<?php

namespace App\Filament\Widgets;

use App\Models\Training;
use App\Models\TrainingRequest;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{

    public function fetchEvents(array $fetchInfo): array
    {
            $trainings = TrainingRequest::WhereHas('trainees', function ($query) {
                $query->where(['user_id' => auth()->id()]);
            })->whereNotNull('start_time')->get();


        $data = $trainings->map(function($training, $key) {
            return [
                'id' => $training->id,
                'title' => Training::find($training->trainings[0])->name,
                'start' => $training->start_time,
                'end' => $training->end_time,
            ];
        })->toArray();
     
        return $data;
    }

}
