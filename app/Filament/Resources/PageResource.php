<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(200)->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                Forms\Components\TextInput::make('slug')->required()->maxLength(200)->unique(ignoreRecord: true),
                Forms\Components\Select::make('parent_id')->label('Parent page')
                    ->relationship('parent', 'title', fn ($query, $record) => $record ? $query->where('id', '!=', $record->id) : $query)
                    ->searchable()->nullable(),
                Forms\Components\TextInput::make('menu_name')->required()->maxLength(100),
                Forms\Components\Select::make('status')
                    ->options(['draft' => 'Draft', 'publish' => 'Publish'])->required()->default('draft'),
                Forms\Components\TextInput::make('sort')->numeric()->default(0),
            ]),
            Forms\Components\Section::make('Content')->schema([
                Forms\Components\RichEditor::make('description')->required()->columnSpanFull(),
            ]),
            Forms\Components\Section::make('SEO')->columns(2)->collapsed()->schema([
                Forms\Components\TextInput::make('meta_title')->maxLength(160),
                Forms\Components\TextInput::make('meta_description')->maxLength(300),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(40),
                Tables\Columns\TextColumn::make('parent.title')->label('Parent')->placeholder('—'),
                Tables\Columns\TextColumn::make('menu_name')->label('Menu'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->colors(['success' => 'publish', 'gray' => 'draft']),
                Tables\Columns\TextColumn::make('sort')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('sort')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'publish' => 'Publish']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
