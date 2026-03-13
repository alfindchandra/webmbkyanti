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
        Schema::dropIfExists('price_tiers');

        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->decimal('conversion', 10, 3)->default(1);
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('min_qty')->default(1);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('minimum_stock', 15, 3)->default(0)->change();
            $table->decimal('current_stock', 15, 3)->default(0)->change();
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->decimal('quantity', 15, 3)->change();
            $table->decimal('conversion', 10, 3)->default(1)->after('quantity');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->decimal('quantity', 15, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->integer('quantity')->change();
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn('conversion');
            $table->integer('quantity')->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('minimum_stock')->default(0)->change();
            $table->integer('current_stock')->default(0)->change();
        });

        Schema::dropIfExists('product_units');

        Schema::create('price_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity_multiplier')->default(1);
            $table->decimal('price_grosir', 15, 2)->default(0);
            $table->decimal('price_retail', 15, 2)->default(0);
            $table->timestamps();
        });
    }
};
