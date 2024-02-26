<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingRequestResource\Pages;
use App\Filament\Resources\TrainingRequestResource\RelationManagers;
use App\Models\Department;
use App\Models\Trainer;
use App\Models\Training;
use App\Models\TrainingRequest;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Illuminate\Support\Str;

class TrainingRequestResource extends Resource
{
    protected static ?string $model = TrainingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Trainings';

    protected static ?int $navigationSort = 1;

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('trainee');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('trainings')
                            ->label('Training')
                            ->options(Training::pluck('name', 'id'))
                            ->preload()
                            ->required()
                            ->multiple(),
                        Select::make('department_ids')
                            ->label('Department')
                            ->options(Department::pluck('name','id'))
                            ->multiple()
                            ->required()
                            ->preload(),
                    ])->columns(2),
                Card::make()
                    ->schema([
                        DateTimePicker::make('start_time'),
                        DateTimePicker::make('end_time'),
                        Select::make('specialists')
                            ->label('Trainer')
                            ->options(Trainer::pluck('name', 'id'))
                            ->preload()
                            ->multiple(),
                    ])->columns(3),
                Card::make()
                    ->schema([
                        FileUpload::make('evidence')
                        ->multiple()
                        ->enableDownload()
                        ->disk('public')
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Date Request'),
                ViewColumn::make('Trainings')->view('tables.columns.training-name'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'primary' => 'in review',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('success'),
                Tables\Actions\EditAction::make()->visible(fn (TrainingRequest $record): bool => $record->status == 'pending' || $record->status == 'rejected'),
                Tables\Actions\DeleteAction::make()->visible(fn (TrainingRequest $record): bool => $record->status == 'pending' || $record->status == 'rejected'),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TraineesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingRequests::route('/'),
            'create' => Pages\CreateTrainingRequest::route('/create'),
            'edit' => Pages\EditTrainingRequest::route('/{record}/edit'),
            'view' => Pages\ViewTrainingRequest::route('/{record}/view'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('trainings')
            ->where('user_id',auth()->id())
            ->orderBy('created_at','Desc');
    }
    
}
