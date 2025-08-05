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
        Schema::create('shipping_carriers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // colissimo, chronopost, mondialrelay
            $table->string('name'); // Nom affiché
            $table->string('logo_path')->nullable();
            $table->json('methods'); // Standard, Express, etc.
            $table->json('pricing'); // Structure de prix
            $table->json('zones'); // Zones de livraison
            $table->json('api_config')->nullable(); // Configuration API
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_carriers');
    }
};
