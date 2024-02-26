<?php

namespace App\Filament\Resources\ApprovedTrainingRequestResource\Pages;

use App\Filament\Resources\ApprovedTrainingRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovedTrainingRequests extends ListRecords
{
    protected static string $resource = ApprovedTrainingRequestResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
