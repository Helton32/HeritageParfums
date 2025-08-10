<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ajouter des ordres de test pour les expéditions
        if (Schema::hasTable('orders')) {
            // Vérifier si nous avons déjà des ordres
            if (\App\Models\Order::count() === 0) {
                $orders = [
                    [
                        'order_number' => 'HP-2025-00001',
                        'status' => 'processing',
                        'payment_status' => 'paid',
                        'customer_name' => 'Marie Dubois',
                        'customer_email' => 'marie.dubois@example.com',
                        'customer_phone' => '+33 1 23 45 67 89',
                        'billing_address_line_1' => '123 Rue de la Paix',
                        'billing_city' => 'Paris',
                        'billing_postal_code' => '75001',
                        'billing_country' => 'FR',
                        'shipping_address_line_1' => '123 Rue de la Paix',
                        'shipping_city' => 'Paris',
                        'shipping_postal_code' => '75001',
                        'shipping_country' => 'FR',
                        'subtotal' => 89.90,
                        'tax_amount' => 17.98,
                        'shipping_amount' => 9.90,
                        'total_amount' => 117.78,
                        'currency' => 'EUR',
                        'created_at' => now()->subDays(2),
                        'updated_at' => now()->subDays(2),
                    ],
                    [
                        'order_number' => 'HP-2025-00002',
                        'status' => 'processing',
                        'payment_status' => 'paid',
                        'customer_name' => 'Jean Martin',
                        'customer_email' => 'jean.martin@example.com',
                        'customer_phone' => '+33 6 12 34 56 78',
                        'billing_address_line_1' => '456 Avenue des Champs',
                        'billing_city' => 'Lyon',
                        'billing_postal_code' => '69001',
                        'billing_country' => 'FR',
                        'shipping_address_line_1' => '456 Avenue des Champs',
                        'shipping_city' => 'Lyon',
                        'shipping_postal_code' => '69001',
                        'shipping_country' => 'FR',
                        'subtotal' => 159.90,
                        'tax_amount' => 31.98,
                        'shipping_amount' => 0.00,
                        'total_amount' => 191.88,
                        'currency' => 'EUR',
                        'created_at' => now()->subDays(1),
                        'updated_at' => now()->subDays(1),
                    ],
                ];

                foreach ($orders as $orderData) {
                    \App\Models\Order::create($orderData);
                }
            }
        }
    }

    public function down()
    {
        // Ne rien faire lors du rollback
    }
};
