<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PdfResource\Pages;
use App\Models\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PdfResource extends Resource
{
    protected static ?string $model = Pdf::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';
    protected static ?string $navigationGroup = 'Library';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(200)->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
            Forms\Components\TextInput::make('slug')->required()->maxLength(200)->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('category')->maxLength(100),
            Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
            Forms\Components\FileUpload::make('file_path')->required()
                ->acceptedFileTypes(['application/pdf'])->directory('pdfs')
                ->maxSize(20480)->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(40),
                Tables\Columns\TextColumn::make('category')->badge()->placeholder('—'),
                Tables\Columns\TextColumn::make('downloads_count')->label('Downloads')->sortable(),
                Tables\Columns\TextColumn::make('file_size_kb')->label('Size')
                    ->formatStateUsing(fn ($s) => $s ? round($s / 1024, 1) . ' MB' : '—'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn () => Pdf::query()->whereNotNull('category')->distinct()->pluck('category', 'category')->toArray()),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPdfs::route('/'),
            'create' => Pages\CreatePdf::route('/create'),
            'edit'   => Pages\EditPdf::route('/{record}/edit'),
        ];
    }
}
