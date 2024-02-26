<?php

namespace App\Filament\Resources\TrainingRequestResource\Pages;

use App\Filament\Resources\TrainingRequestResource;
use App\Models\TrainingRequest;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainingRequest extends ViewRecord
{
    protected static string $resource = TrainingRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make()->visible(fn (TrainingRequest $record): bool => $record->status == 'pending' && $record->user_id == auth()->id()),
            Actions\Action::make('submit')->label('Send for Review')
                ->visible(fn (): bool => $this->record->status == 'pending')
                ->requiresConfirmation()
                ->color('success')
                ->url(fn (): string => route('training.request.submission', ['record'=> $this->record,'data'=>'in review'])),
        ];
    }
}
