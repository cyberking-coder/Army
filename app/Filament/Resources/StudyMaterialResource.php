<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudyMaterialResource\Pages;
use App\Models\StudyMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudyMaterialResource extends Resource
{
    protected static ?string $model = StudyMaterial::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Library';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Study Material';
    protected static ?string $pluralModelLabel = 'Study Material';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(200)->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
            Forms\Components\TextInput::make('slug')->required()->maxLength(200)->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('subject')->maxLength(100),
            Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
            Forms\Components\FileUpload::make('file_path')->required()
                ->acceptedFileTypes(['application/pdf'])->directory('study')
                ->maxSize(20480)->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(40),
                Tables\Columns\TextColumn::make('subject')->badge()->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('subject')
                    ->options(fn () => StudyMaterial::query()->whereNotNull('subject')->distinct()->pluck('subject', 'subject')->toArray()),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageStudyMaterial::route('/')];
    }
}
