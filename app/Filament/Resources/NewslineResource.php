<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewslineResource\Pages;
use App\Models\Newsline;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewslineResource extends Resource
{
    protected static ?string $model = Newsline::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('headline')->required()->maxLength(300)->columnSpanFull(),
            Forms\Components\TextInput::make('link_url')->url()->maxLength(500)->columnSpanFull(),
            Forms\Components\DateTimePicker::make('starts_at'),
            Forms\Components\DateTimePicker::make('ends_at'),
            Forms\Components\TextInput::make('sort')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('headline')->searchable()->limit(60),
                Tables\Columns\TextColumn::make('starts_at')->dateTime('d M Y')->placeholder('—'),
                Tables\Columns\TextColumn::make('ends_at')->dateTime('d M Y')->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageNewslines::route('/')];
    }
}
