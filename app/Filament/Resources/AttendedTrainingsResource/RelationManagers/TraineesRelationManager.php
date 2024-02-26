<?php

namespace App\Filament\Resources\AttendedTrainingsResource\RelationManagers;

use App\Models\TrainingRequestUser;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

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
                        ->disabled(),
                    ])->columns(1),
                Card::make()
                    ->schema([
                        Select::make('rating')
                            ->label('Training')
                            ->options([
                                '1' => 'Satisfactory',
                                '2' => 'Not Satisfactory',
                            ])->required(),
                        RichEditor::make('comment')->required(),
                    ]),
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
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->visible(fn (TrainingRequestUser $record): bool => $record->user_id == auth()->id() && $record->trainingRequest->status == 'training done'),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
