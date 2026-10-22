<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('catalog_id')->nullable();
            $table->string('sku')->unique();
            $table->string('model_no')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->unsignedInteger('price_cents');
            $table->decimal('weight_g', 8, 1)->nullable();
            $table->string('unit_size_cm')->nullable();
            $table->unsignedInteger('carton_qty')->nullable();
            $table->string('carton_size_cm')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
