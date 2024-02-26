<?php

namespace App\Filament\Resources\LogsResource\RelationManagers;

use Filament\Forms;
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
                Forms\Components\TextInput::make('training_request_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Name'),
                Tables\Columns\TextColumn::make('user.site')->label('Site'),
                Tables\Columns\TextColumn::make('user.department.name')->label('Deparment'),
                Tables\Columns\BadgeColumn::make('rating')
                ->enum([
                    '1' => 'Satisfactory',
                    '2' => 'Non Satisfactory',
                ]),
                Tables\Columns\TextColumn::make('comment')->html(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
