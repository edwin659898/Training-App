<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingAssesmentResource\Pages;
use App\Filament\Resources\TrainingAssesmentResource\RelationManagers;
use App\Models\Department;
use App\Models\Trainer;
use App\Models\Training;
use App\Models\TrainingAssesment;
use App\Models\TrainingRequest;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class TrainingAssesmentResource extends Resource
{
    protected static ?string $model = TrainingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $slug = 'training-assesment';

    protected static ?string $navigationLabel = 'Supervisor Assesment';

    protected static ?string $navigationGroup = 'Trainings';

    protected static ?int $navigationSort = 5;

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('supervisor');
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Hidden::make('id'),
            Card::make()
                ->schema([
                    Select::make('trainings')
                        ->label('Training')
                        ->options(Training::pluck('name', 'id'))
                        ->disabled()
                        ->multiple(),
                    Select::make('department_ids')
                        ->label('Department')
                        ->options(Department::pluck('name','id'))
                        ->multiple()
                        ->disabled(),
                ])->columns(2),
            Card::make()
                ->schema([
                    TinyEditor::make('sub_topics')->label('Sub Topics')->disabled()
                ])->columns(1),
            Card::make()
                ->schema([
                    FileUpload::make('evidence')
                    ->multiple()
                    ->enableDownload()
                    ->disk('public')
                    ->disabled()
                ])->columns(1),
            Card::make()
                ->schema([
                    DateTimePicker::make('start_time'),
                    DateTimePicker::make('end_time'),
                    Select::make('specialists')
                        ->label('Trainer')
                        ->options(Trainer::pluck('name', 'id'))
                        ->disabled()
                        ->multiple(),
                ])->columns(3),
            Card::make()
                ->schema([
                    Select::make('training_rating')
                            ->label('Training Rating')
                            ->options([
                                '1' => 'Satisfactory',
                                '2' => 'Not Satisfactory',
                            ])->required(),
                    TinyEditor::make('rating_comment')->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Date Request'),
                TextColumn::make('user.name')->label('Requested by'),
                ViewColumn::make('Trainings')->view('tables.columns.training-name'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'training done',
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTrainingAssesments::route('/'),
            'create' => Pages\CreateTrainingAssesment::route('/create'),
            'edit' => Pages\EditTrainingAssesment::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('trainings')
            ->where('status','training done')
            ->orderBy('created_at','Desc');
    }
}
