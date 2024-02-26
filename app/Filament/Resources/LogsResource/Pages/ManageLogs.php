<?php

namespace App\Filament\Resources\LogsResource\Pages;

use App\Filament\Resources\LogsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLogs extends ManageRecords
{
    protected static string $resource = LogsResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
