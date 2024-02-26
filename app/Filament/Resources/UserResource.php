<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Department;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Training Resources';

    protected static ?string $navigationLabel = 'Trainees';

    protected static ?int $navigationSort = 1;

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
        'tableColumnSearchQueries',
    ];

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('super admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('job_title')->required(),
                TextInput::make('email')->required()->unique(ignoreRecord: true),
                TextInput::make('personal_email')->required()->unique(ignoreRecord: true),
                Select::make('department_id')
                    ->preload()
                    ->options(Department::pluck('name', 'id'))->required(),
                Select::make('site')
                    ->options([
                        'Dokolo' => 'Dokolo',
                        'Head Office' => 'Head Office',
                        'Kampala' => 'Kampala',
                        'Kiambere' => 'Kiambere',
                        'Nyongoro' => 'Nyongoro',
                        '7 Forks' => '7 Forks',
                    ])->required(),
                TextInput::make('phone_number')
                    ->label('phone no')->tel()->required(),
                Select::make('country')
                    ->label('Country')
                    ->options([
                        'KE' => 'Kenya',
                        'UG' => 'Uganda',
                        'TZ' => 'Tanzania',
                    ])
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->visibleOn('create'),
                Select::make('role.name')
                    ->relationship('roles', 'name')
                    ->label('Has Roles')
                    ->multiple()
                    ->preload(),
                FileUpload::make('signature')
                    ->enableDownload()
                    ->disk('public')
                    ->directory('signatures')
                    ->imageResizeTargetWidth('200')
                    ->imageResizeTargetHeight('200'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->Searchable(),
                Tables\Columns\TextColumn::make('name')->sortable()->Searchable(),
                Tables\Columns\TextColumn::make('job_title')->sortable()->Searchable(),
                Tables\Columns\TextColumn::make('site')->sortable()->Searchable(),
                Tables\Columns\TextColumn::make('status')->sortable()->Searchable(),
                SelectColumn::make('status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make()->visible(fn (User $record) => auth()->id() != $record->id),
                Tables\Actions\DeleteAction::make()->visible(fn (User $record) => auth()->id() != $record->id),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
