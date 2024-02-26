<?php

namespace App\Filament\Resources\TrainerResource\Pages;

use App\Filament\Resources\TrainerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTrainers extends ManageRecords
{
    protected static string $resource = TrainerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
