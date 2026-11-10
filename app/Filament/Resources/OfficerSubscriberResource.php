<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficerSubscriberResource\Pages;
use App\Models\OfficerSubscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OfficerSubscriberResource extends Resource
{
    protected static ?string $model = OfficerSubscriber::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Subscribers';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Officer Subscribers';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('full_name')->required()->maxLength(150),
            Forms\Components\TextInput::make('mobile')->tel()->required()->maxLength(20),
            Forms\Components\TextInput::make('email')->email()->required()->maxLength(150),
            Forms\Components\Select::make('status')
                ->options(['subscribed' => 'Subscribed', 'unverified' => 'Unverified', 'unsubscribed' => 'Unsubscribed'])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->badge()
                    ->colors(['success' => 'subscribed', 'warning' => 'unverified', 'danger' => 'unsubscribed']),
                Tables\Columns\TextColumn::make('full_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('mobile')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date entered')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('resend_otp')
                    ->icon('heroicon-o-envelope')->color('warning')
                    ->visible(fn (OfficerSubscriber $r) => $r->status === 'unverified')
                    ->requiresConfirmation()
                    ->action(function (OfficerSubscriber $r) {
                        $r->update([
                            'otp' => (string) random_int(100000, 999999),
                            'otp_expires_at' => now()->addMinutes(15),
                        ]);
                        // Phase 6 will wire the actual email send
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOfficerSubscribers::route('/'),
            'create' => Pages\CreateOfficerSubscriber::route('/create'),
            'edit'   => Pages\EditOfficerSubscriber::route('/{record}/edit'),
        ];
    }
}
