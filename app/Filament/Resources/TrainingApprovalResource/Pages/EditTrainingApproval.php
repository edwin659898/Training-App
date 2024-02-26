<?php

namespace App\Filament\Resources\TrainingApprovalResource\Pages;

use App\Filament\Resources\TrainingApprovalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingApproval extends EditRecord
{
    protected static string $resource = TrainingApprovalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('approve')->label('Approve')
                ->requiresConfirmation()
                ->color('success')
                ->url(fn (): string => route('training.request.submission', ['record'=> $this->record,'data'=>'accepted'])),
            Actions\Action::make('reject')->label('Reject')
                ->requiresConfirmation()
                ->color('danger')
                ->url(fn (): string => route('training.request.submission', ['record'=> $this->record,'data'=>'rejected'])),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['approved_by'] = auth()->id();
    
        return $data;
    }
}
