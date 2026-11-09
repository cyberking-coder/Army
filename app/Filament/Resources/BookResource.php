<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Library';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(200)->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                Forms\Components\TextInput::make('slug')->required()->maxLength(200)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('author')->maxLength(150),
                Forms\Components\TextInput::make('stock')->numeric()->default(0),
                Forms\Components\TextInput::make('price')->numeric()->prefix('₹')->required(),
                Forms\Components\TextInput::make('discount_price')->numeric()->prefix('₹'),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]),
            Forms\Components\Section::make('Content')->schema([
                Forms\Components\Textarea::make('description')->rows(4)->columnSpanFull(),
                Forms\Components\FileUpload::make('cover_image_path')->image()->directory('books/covers')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image_path')->label('Cover')->square()->height(50),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(40),
                Tables\Columns\TextColumn::make('author')->searchable()->placeholder('—'),
                Tables\Columns\TextColumn::make('price')->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('discount_price')->money('INR')->placeholder('—'),
                Tables\Columns\TextColumn::make('stock')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([Tables\Filters\TernaryFilter::make('is_active')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit'   => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
