<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Payment Methods';
    protected static ?int $navigationSort = 6;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_payment_methods') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Info')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')
                    ->label('Short Description')
                    ->rows(2)
                    ->helperText('Shown to customers on the checkout page'),
                Forms\Components\Textarea::make('instructions')
                    ->label('Payment Instructions')
                    ->rows(4)
                    ->helperText('Detailed instructions shown after order is placed'),
                Forms\Components\FileUpload::make('icon_path')
                    ->image()->directory('payment-methods')->label('Icon / Logo'),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0)->label('Display Order'),
                Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
            ])->columns(2),

            Forms\Components\Section::make('Payment Credentials')
                ->description('Enter the account details customers need to send payment. Only fill in the fields relevant to this method.')
                ->schema([
                    Forms\Components\TextInput::make('account_email')
                        ->label('Account Email')
                        ->email()
                        ->helperText('e.g. PayPal email, Zelle email'),
                    Forms\Components\TextInput::make('account_tag')
                        ->label('Account Tag / Handle')
                        ->helperText('e.g. Cash App $cashtag, Venmo handle'),
                    Forms\Components\TextInput::make('bank_name')
                        ->label('Bank Name')
                        ->helperText('For bank transfer payments'),
                    Forms\Components\TextInput::make('account_name')
                        ->label('Account Holder Name')
                        ->helperText('Name on the bank account'),
                    Forms\Components\TextInput::make('account_number')
                        ->label('Account Number')
                        ->helperText('Bank account number'),
                    Forms\Components\TextInput::make('routing_number')
                        ->label('Routing Number')
                        ->helperText('Bank routing / ABA number'),
                ])->columns(2)->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon_path')->label('Icon')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')->limit(40),
                Tables\Columns\TextColumn::make('account_email')->label('Email')->limit(25)->placeholder('—'),
                Tables\Columns\TextColumn::make('account_tag')->label('Tag')->limit(20)->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Active'),
                Tables\Columns\TextColumn::make('sort_order')->sortable()->label('Order'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([
                Tables\Actions\Action::make('toggle')
                    ->label(fn (PaymentMethod $record) => $record->is_active ? 'Disable' : 'Enable')
                    ->icon(fn (PaymentMethod $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (PaymentMethod $record) => $record->is_active ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->action(fn (PaymentMethod $record) => $record->update(['is_active' => !$record->is_active])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
