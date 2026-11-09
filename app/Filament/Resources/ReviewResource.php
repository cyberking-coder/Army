<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(100),
            Forms\Components\Select::make('rating')
                ->options([1 => '1 ★', 2 => '2 ★★', 3 => '3 ★★★', 4 => '4 ★★★★', 5 => '5 ★★★★★'])->required(),
            Forms\Components\Textarea::make('comment')->required()->rows(4)->columnSpanFull(),
            Forms\Components\Toggle::make('is_published'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('rating')->formatStateUsing(fn ($s) => str_repeat('★', (int) $s)),
                Tables\Columns\TextColumn::make('comment')->limit(60),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('submitted_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([Tables\Filters\TernaryFilter::make('is_published')])
            ->actions([
                Tables\Actions\Action::make('publish')->icon('heroicon-o-check')->color('success')
                    ->visible(fn (Review $r) => !$r->is_published)
                    ->action(fn (Review $r) => $r->update(['is_published' => true])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageReviews::route('/')];
    }
}
