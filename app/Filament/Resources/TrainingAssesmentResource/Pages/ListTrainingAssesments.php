<?php

namespace App\Filament\Resources\TrainingAssesmentResource\Pages;

use App\Filament\Resources\TrainingAssesmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingAssesments extends ListRecords
{
    protected static string $resource = TrainingAssesmentResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
