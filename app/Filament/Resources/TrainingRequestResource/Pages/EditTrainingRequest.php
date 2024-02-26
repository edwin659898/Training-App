<?php

namespace App\Filament\Resources\TrainingRequestResource\Pages;

use App\Filament\Resources\TrainingRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingRequest extends EditRecord
{
    protected static string $resource = TrainingRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('submit')->label('Send for Review')
            ->visible(fn (): bool => $this->record->status == 'pending' || $this->record->status == 'rejected')
            ->requiresConfirmation()
            ->color('success')
            ->url(fn (): string => route('training.request.submission', ['record'=> $this->record,'data'=>'in review'])),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
