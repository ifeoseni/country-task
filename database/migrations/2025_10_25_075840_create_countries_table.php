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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->string('capital')->nullable();
            $table->string('region')->nullable();
            $table->unsignedBigInteger('population');
            $table->string('currency_code')->nullable()->index(); // Can be null if no currencies
            $table->decimal('exchange_rate', 18, 6)->nullable(); // Can be null if currency not found
            $table->decimal('estimated_gdp', 30, 6)->nullable(); // Can be 0 or null based on rules
            $table->string('flag_url')->nullable();
            $table->timestamp('last_refreshed_at')->nullable()->index();
            $table->timestamps();
            
            $table->index(['region', 'currency_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};