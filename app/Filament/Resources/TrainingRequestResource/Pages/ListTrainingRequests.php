<?php

namespace App\Filament\Resources\TrainingRequestResource\Pages;

use App\Filament\Resources\TrainingRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingRequests extends ListRecords
{
    protected static string $resource = TrainingRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
