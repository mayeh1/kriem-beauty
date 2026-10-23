<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('account_email')->nullable()->after('instructions');
            $table->string('account_tag')->nullable()->after('account_email');
            $table->string('bank_name')->nullable()->after('account_tag');
            $table->string('account_name')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('account_name');
            $table->string('routing_number')->nullable()->after('account_number');
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn(['account_email', 'account_tag', 'bank_name', 'account_name', 'account_number', 'routing_number']);
        });
    }
};
