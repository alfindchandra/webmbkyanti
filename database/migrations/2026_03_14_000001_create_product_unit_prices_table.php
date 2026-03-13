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
        Schema::create('product_unit_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_unit_id')->constrained()->cascadeOnDelete();
            $table->integer('min_quantity')->default(1);
            $table->integer('max_quantity')->nullable();
            $table->decimal('price', 15, 2);
            $table->timestamps();

            // Unique constraint ensure no overlapping quantities
            $table->unique(['product_unit_id', 'min_quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_unit_prices');
    }
};
