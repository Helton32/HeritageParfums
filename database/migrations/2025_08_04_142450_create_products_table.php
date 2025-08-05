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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('category'); // femme, homme, exclusifs, nouveautes
            $table->string('type'); // Eau de Parfum, Eau de Toilette, Parfum
            $table->string('size')->default('100ml'); // 50ml, 100ml, 125ml
            $table->json('images')->nullable(); // Array d'URLs d'images
            $table->json('notes')->nullable(); // Notes de tête, cœur, fond
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('badge')->nullable(); // Nouveau, Bestseller, Exclusif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};