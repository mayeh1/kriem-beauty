<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Mail\OrderShipped;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Details')->schema([
                Forms\Components\TextInput::make('number')->disabled(),
                Forms\Components\TextInput::make('status')->disabled(),
                Forms\Components\TextInput::make('email')->disabled(),
                Forms\Components\TextInput::make('name')->disabled(),
            ])->columns(2),

            Forms\Components\Section::make('Address')->schema([
                Forms\Components\TextInput::make('address_line1')->disabled(),
                Forms\Components\TextInput::make('address_line2')->disabled(),
                Forms\Components\TextInput::make('city')->disabled(),
                Forms\Components\TextInput::make('state')->disabled(),
                Forms\Components\TextInput::make('zip')->disabled(),
            ])->columns(3),

            Forms\Components\Section::make('Totals')->schema([
                Forms\Components\TextInput::make('subtotal_cents')
                    ->label('Subtotal')
                    ->formatStateUsing(fn ($state) => '$' . number_format($state / 100, 2))
                    ->disabled(),
                Forms\Components\TextInput::make('shipping_cents')
                    ->label('Shipping')
                    ->formatStateUsing(fn ($state) => $state == 0 ? 'Free' : '$' . number_format($state / 100, 2))
                    ->disabled(),
                Forms\Components\TextInput::make('total_cents')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => '$' . number_format($state / 100, 2))
                    ->disabled(),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('total_cents')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => '$' . number_format($state / 100, 2))
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'info' => 'shipped',
                    ]),
                Tables\Columns\TextColumn::make('paid_at')->dateTime('M j, Y g:ia')->placeholder('—'),
                Tables\Columns\TextColumn::make('shipped_at')->dateTime('M j, Y g:ia')->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M j, Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'shipped' => 'Shipped',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('ship')
                    ->label('Mark Shipped')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->status === 'paid')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => 'shipped',
                            'shipped_at' => now(),
                        ]);
                        Mail::to($record->email)->send(new OrderShipped($record->load('items')));
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
