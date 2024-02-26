<?php

namespace App\Filament\Resources\AttendedTrainingsResource\Pages;

use App\Filament\Resources\AttendedTrainingsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendedTrainings extends ListRecords
{
    protected static string $resource = AttendedTrainingsResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
