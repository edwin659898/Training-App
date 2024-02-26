<?php

namespace App\Filament\Resources\AttendedTrainingsResource\Pages;

use App\Filament\Resources\AttendedTrainingsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendedTrainings extends EditRecord
{
    protected static string $resource = AttendedTrainingsResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
}
