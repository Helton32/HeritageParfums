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
        Schema::table('orders', function (Blueprint $table) {
            // Informations transporteur
            $table->enum('shipping_carrier', ['colissimo', 'chronopost', 'mondialrelay'])->nullable()->after('shipping_country');
            $table->string('shipping_method')->nullable()->after('shipping_carrier'); // Standard, Express, etc.
            $table->string('tracking_number')->nullable()->after('shipping_method');
            $table->string('carrier_reference')->nullable()->after('tracking_number'); // Référence interne transporteur
            $table->json('carrier_options')->nullable()->after('carrier_reference'); // Options spécifiques (point relais, etc.)
            $table->json('carrier_response')->nullable()->after('carrier_options'); // Réponse API transporteur
            $table->string('shipping_label_path')->nullable()->after('carrier_response'); // Chemin vers l'étiquette
            $table->decimal('shipping_weight', 8, 3)->nullable()->after('shipping_label_path'); // Poids en kg
            $table->json('package_dimensions')->nullable()->after('shipping_weight'); // Dimensions du colis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_carrier',
                'shipping_method', 
                'tracking_number',
                'carrier_reference',
                'carrier_options',
                'carrier_response',
                'shipping_label_path',
                'shipping_weight',
                'package_dimensions'
            ]);
        });
    }
};
