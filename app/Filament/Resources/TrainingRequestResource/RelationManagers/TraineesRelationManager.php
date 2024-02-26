<?php

namespace App\Filament\Resources\TrainingRequestResource\RelationManagers;

use App\Models\TrainingRequestUser;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TraineesRelationManager extends RelationManager
{
    protected static string $relationship = 'trainees';

    protected static ?string $recordTitleAttribute = 'training_request_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Create Item')
                    ->visible(fn (RelationManager $livewire): bool => $livewire->ownerRecord->user_id == auth()->id() && $livewire->ownerRecord->status == 'pending'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (TrainingRequestUser $record): bool => $record->trainingRequest->user_id == auth()->id() && $record->trainingRequest->status == 'pending'),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (TrainingRequestUser $record): bool => $record->trainingRequest->user_id == auth()->id() && $record->trainingRequest->status == 'pending'),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
