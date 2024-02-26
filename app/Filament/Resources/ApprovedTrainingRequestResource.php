<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovedTrainingRequestResource\Pages;
use App\Filament\Resources\ApprovedTrainingRequestResource\RelationManagers;
use App\Models\ApprovedTrainingRequest;
use App\Models\Department;
use App\Models\Trainer;
use App\Models\Training;
use App\Models\TrainingRequest;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class ApprovedTrainingRequestResource extends Resource
{
    protected static ?string $model = TrainingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $slug = 'approved-training-requests';

    protected static ?string $navigationLabel = 'Approved Training Requests';

    protected static ?string $navigationGroup = 'Trainings';

    protected static ?string $title = 'Approved Requests';

    protected static ?int $navigationSort = 3;

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
                        DateTimePicker::make('start_time')->disabled(),
                        DateTimePicker::make('end_time')->disabled(),
                        Select::make('specialists')
                            ->label('Trainer')
                            ->options(Trainer::pluck('name', 'id'))
                            ->disabled()
                            ->multiple(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Date Request'),
                TextColumn::make('user.name')->label('Requested by'),
                ViewColumn::make('Trainings')->view('tables.columns.training-name'),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'accepted',
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
            'index' => Pages\ListApprovedTrainingRequests::route('/'),
            'create' => Pages\CreateApprovedTrainingRequest::route('/create'),
            'edit' => Pages\EditApprovedTrainingRequest::route('/{record}/edit'),
        ];
    }  
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('trainings')
            ->where('status','accepted')
            ->orderBy('created_at','Desc');
    }
}
