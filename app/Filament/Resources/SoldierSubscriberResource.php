<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoldierSubscriberResource\Pages;
use App\Models\SoldierSubscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SoldierSubscriberResource extends Resource
{
    protected static ?string $model = SoldierSubscriber::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Subscribers';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Soldier Subscribers';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('first_name')->required()->maxLength(100),
            Forms\Components\TextInput::make('last_name')->required()->maxLength(100),
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
                Tables\Columns\TextColumn::make('first_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('mobile')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date entered')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('resend_otp')
                    ->icon('heroicon-o-envelope')->color('warning')
                    ->visible(fn (SoldierSubscriber $r) => $r->status === 'unverified')
                    ->requiresConfirmation()
                    ->action(function (SoldierSubscriber $r) {
                        $otp = (string) random_int(100000, 999999);
                        $r->update(['otp' => $otp, 'otp_expires_at' => now()->addMinutes(15)]);
                        \Illuminate\Support\Facades\Mail::to($r->email)
                            ->queue(new \App\Mail\OtpMail($otp, $r->first_name));
                        app(\App\Services\SmsService::class)->sendOtp($r->mobile, $otp);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSoldierSubscribers::route('/'),
            'create' => Pages\CreateSoldierSubscriber::route('/create'),
            'edit'   => Pages\EditSoldierSubscriber::route('/{record}/edit'),
        ];
    }
}
