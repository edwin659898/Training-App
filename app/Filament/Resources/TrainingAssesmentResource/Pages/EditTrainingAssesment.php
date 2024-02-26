<?php

namespace App\Filament\Resources\TrainingAssesmentResource\Pages;

use App\Filament\Resources\TrainingAssesmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingAssesment extends EditRecord
{
    protected static string $resource = TrainingAssesmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('Complete Assessment')
            ->requiresConfirmation()
            ->color('success')
            ->url(fn (): string => route('training.request.submission', ['record'=> $this->record,'data'=>'completed'])),
        ];
    }
}
