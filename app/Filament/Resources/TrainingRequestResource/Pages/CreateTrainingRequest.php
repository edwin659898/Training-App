<?php

namespace App\Filament\Resources\TrainingRequestResource\Pages;

use App\Filament\Resources\TrainingRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainingRequest extends CreateRecord
{
    protected static string $resource = TrainingRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
    
}
