<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Commerce';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Customer')->columns(2)->schema([
                Forms\Components\TextInput::make('order_number')->disabled()->dehydrated(false),
                Forms\Components\Select::make('status')
                    ->options(['pending' => 'Pending', 'successful' => 'Successful', 'rejected' => 'Rejected'])
                    ->required(),
                Forms\Components\TextInput::make('full_name')->required()->maxLength(150),
                Forms\Components\TextInput::make('mobile')->tel()->required()->maxLength(20),
                Forms\Components\TextInput::make('email')->email()->required()->maxLength(150),
                Forms\Components\TextInput::make('consignment_number')->maxLength(100),
            ]),
            Forms\Components\Section::make('Shipping')->columns(2)->schema([
                Forms\Components\Textarea::make('shipping_address')->required()->rows(2)->columnSpanFull(),
                Forms\Components\TextInput::make('city')->required()->maxLength(80),
                Forms\Components\TextInput::make('state')->required()->maxLength(80),
                Forms\Components\TextInput::make('pincode')->required()->maxLength(10),
            ]),
            Forms\Components\Section::make('Pricing')->columns(3)->schema([
                Forms\Components\TextInput::make('subtotal')->numeric()->prefix('₹')->required(),
                Forms\Components\TextInput::make('discount')->numeric()->prefix('₹')->default(0),
                Forms\Components\TextInput::make('final_cost')->numeric()->prefix('₹')->required(),
            ]),
            Forms\Components\Textarea::make('notes')->rows(3)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('full_name')->searchable()->limit(25),
                Tables\Columns\TextColumn::make('mobile')->searchable(),
                Tables\Columns\TextColumn::make('final_cost')->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors(['warning' => 'pending', 'success' => 'successful', 'danger' => 'rejected']),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pending' => 'Pending', 'successful' => 'Successful', 'rejected' => 'Rejected']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_successful')
                    ->icon('heroicon-o-check-circle')->color('success')
                    ->visible(fn (Order $r) => $r->status === 'pending')
                    ->action(fn (Order $r) => $r->markSuccessful()),
                Tables\Actions\Action::make('mark_rejected')
                    ->icon('heroicon-o-x-circle')->color('danger')
                    ->visible(fn (Order $r) => $r->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn (Order $r) => $r->markRejected()),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Order::where('status', 'pending')->count();
    }
}
