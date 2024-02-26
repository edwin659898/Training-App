<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingApprovalResource\Pages;
use App\Filament\Resources\TrainingApprovalResource\RelationManagers;
use App\Models\Department;
use App\Models\Trainer;
use App\Models\Training;
use App\Models\TrainingApproval;
use App\Models\TrainingRequest;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
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

class TrainingApprovalResource extends Resource
{
    protected static ?string $model = TrainingRequest::class;

    protected static ?string $title = 'Submitted Training Requests';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $slug = 'trainings-review-and-approval';

    protected static ?string $navigationLabel = 'Training Requests Approval';

    protected static ?string $navigationGroup = 'Trainings';

    protected static ?int $navigationSort = 2;
    
    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('hod');
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
                        TinyEditor::make('sub_topics')->label('Sub Topics')
                    ])->columns(1),
                Card::make()
                    ->schema([
                        FileUpload::make('evidence')
                        ->multiple()
                        ->enableDownload()
                        ->disk('public')
                    ])->columns(1),
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
                        Select::make('venue')
                            ->label('Venue of Training')
                            ->options([
                                'Dokolo' => 'Dokolo',
                                'Kiambere' => 'Kiambere',
                                'Head Office' => 'Head Office',
                                'Nyongoro' => 'Nyongoro',
                                'External' => 'External',
                            ])
                            ->preload(),
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
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'in review',
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
            'index' => Pages\ListTrainingApprovals::route('/'),
            //'create' => Pages\CreateTrainingApproval::route('/create'),
            'edit' => Pages\EditTrainingApproval::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('trainings')
            ->where('status','in review')
            ->orderBy('created_at','Desc');
    }
}
