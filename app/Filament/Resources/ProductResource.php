<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Product Info')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')->required()->preload(),
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name')->required()->preload(),
                Forms\Components\Textarea::make('description')->rows(3),
            ])->columns(2),

            Forms\Components\Section::make('Pricing & Inventory')->schema([
                Forms\Components\TextInput::make('price_cents')
                    ->label('Price (cents)')
                    ->required()->numeric()->minValue(1),
                Forms\Components\TextInput::make('stock')
                    ->required()->numeric()->minValue(0)->default(50),
                Forms\Components\TextInput::make('sku')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('model_no'),
                Forms\Components\TextInput::make('catalog_id')->numeric(),
            ])->columns(3),

            Forms\Components\Section::make('Details')->schema([
                Forms\Components\TextInput::make('weight_g')->numeric()->label('Weight (g)'),
                Forms\Components\TextInput::make('unit_size_cm')->label('Unit Size (cm)'),
                Forms\Components\TextInput::make('carton_qty')->numeric()->label('Carton Qty'),
                Forms\Components\TextInput::make('carton_size_cm')->label('Carton Size (cm)'),
            ])->columns(4),

            Forms\Components\Section::make('Display')->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->image()->directory('products')->label('Product Image'),
                Forms\Components\Toggle::make('is_featured')->label('Featured'),
                Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->label('Image')->circular(),
                Tables\Columns\TextColumn::make('sku')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->limit(30),
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\TextColumn::make('brand.name')->sortable(),
                Tables\Columns\TextColumn::make('price_cents')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => '$' . number_format($state / 100, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')->sortable()
                    ->color(fn ($state) => $state < 10 ? 'danger' : 'success'),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('brand')->relationship('brand', 'name'),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
