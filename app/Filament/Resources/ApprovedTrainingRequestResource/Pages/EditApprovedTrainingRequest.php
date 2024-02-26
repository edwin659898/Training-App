<?php

namespace App\Filament\Resources\ApprovedTrainingRequestResource\Pages;

use App\Filament\Resources\ApprovedTrainingRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovedTrainingRequest extends EditRecord
{
    protected static string $resource = ApprovedTrainingRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('Mark as Complete')
            ->requiresConfirmation()
            ->color('success')
            ->url(fn (): string => route('training.request.submission', ['record'=> $this->record,'data'=>'training done'])),
        ];
    }
}
