<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResultRegistrationResource\Pages;
use App\Models\Exam;
use App\Models\ResultRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResultRegistrationResource extends Resource
{
    protected static ?string $model = ResultRegistration::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Subscribers';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Result Registrations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('exam_id')->label('Exam')
                ->relationship('exam', 'name')->required(),
            Forms\Components\TextInput::make('name')->required()->maxLength(100),
            Forms\Components\TextInput::make('mobile')->tel()->required()->maxLength(20),
            Forms\Components\TextInput::make('email')->email()->required()->maxLength(150),
            Forms\Components\TextInput::make('roll_no')->required()->maxLength(50),
            Forms\Components\TextInput::make('zone')->required()->maxLength(100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('mobile')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->limit(28),
                Tables\Columns\TextColumn::make('roll_no')->label('Roll No')->searchable(),
                Tables\Columns\TextColumn::make('exam.name')->label('Exam')->limit(30),
                Tables\Columns\TextColumn::make('zone')->badge(),
                Tables\Columns\TextColumn::make('submitted_at')->label('Submitted')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('exam_id')->label('Exam')
                    ->options(fn () => Exam::pluck('name', 'id')->toArray()),
                Tables\Filters\SelectFilter::make('zone')
                    ->options(fn () => ResultRegistration::query()->distinct()->pluck('zone', 'zone')->toArray()),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListResultRegistrations::route('/'),
            'create' => Pages\CreateResultRegistration::route('/create'),
            'edit'   => Pages\EditResultRegistration::route('/{record}/edit'),
        ];
    }
}
