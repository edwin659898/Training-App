<?php

namespace App\Filament\Resources\TrainingApprovalResource\Pages;

use App\Filament\Resources\TrainingApprovalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingApprovals extends ListRecords
{
    protected static string $resource = TrainingApprovalResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
