<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\ShippingService;
use Illuminate\Console\Command;

class TestShippingLabels extends Command
{
    protected $signature = 'shipping:test-labels';
    protected $description = 'Créer des commandes de test et générer les bons de livraison pour tous les transporteurs';

    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        parent::__construct();
        $this->shippingService = $shippingService;
    }

    public function handle()
    {
        $this->info('🚛 Test de génération des bons de livraison');
        $this->newLine();

        // Vérifier qu'on a au moins un produit
        $product = Product::first();
        if (!$product) {
            $this->error('Aucun produit trouvé. Veuillez d\'abord seeder les produits.');
            return 1;
        }

        $transporteurs = ['colissimo', 'chronopost', 'mondialrelay'];

        foreach ($transporteurs as $transporteur) {
            $this->info("Création d'une commande test pour {$transporteur}...");
            
            // Créer une commande de test
            $order = $this->createTestOrder($product, $transporteur);
            
            try {
                // Générer le bon de livraison
                $labelPath = $this->shippingService->createShippingLabel($order);
                $trackingNumber = $this->shippingService->generateTrackingNumber($order);
                
                $this->info("✅ Bon de livraison généré: {$labelPath}");
                $this->info("📦 Numéro de suivi: {$trackingNumber}");
                $this->info("🔗 URL d'accès: " . route('admin.shipping.show', $order));
                
            } catch (\Exception $e) {
                $this->error("❌ Erreur pour {$transporteur}: " . $e->getMessage());
            }
            
            $this->newLine();
        }

        $this->info('✨ Test terminé !');
        $this->info('📋 Accédez à l\'administration: ' . route('admin.shipping.index'));
        
        return 0;
    }

    private function createTestOrder($product, $transporteur)
    {
        // Données de test différentes pour chaque transporteur
        $testData = [
            'colissimo' => [
                'name' => 'Marie Dubois',
                'email' => 'marie.dubois@example.com',
                'phone' => '+33 6 12 34 56 78',
                'address' => '15 Rue de Rivoli',
                'city' => 'Paris',
                'postal_code' => '75001',
                'method' => 'standard'
            ],
            'chronopost' => [
                'name' => 'Pierre Martin',
                'email' => 'pierre.martin@example.com', 
                'phone' => '+33 6 87 65 43 21',
                'address' => '42 Boulevard Haussmann',
                'city' => 'Paris',
                'postal_code' => '75009',
                'method' => 'express'
            ],
            'mondialrelay' => [
                'name' => 'Sophie Leroy',
                'email' => 'sophie.leroy@example.com',
                'phone' => '+33 6 98 76 54 32',
                'address' => '8 Place de la République',
                'city' => 'Lyon',
                'postal_code' => '69002',
                'method' => 'point_relais'
            ]
        ];

        $data = $testData[$transporteur];

        // Créer la commande
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'status' => 'processing',
            'payment_status' => 'paid',
            'customer_email' => $data['email'],
            'customer_name' => $data['name'],
            'customer_phone' => $data['phone'],
            'billing_address_line_1' => $data['address'],
            'billing_city' => $data['city'],
            'billing_postal_code' => $data['postal_code'],
            'billing_country' => 'FR',
            'shipping_address_line_1' => $data['address'],
            'shipping_city' => $data['city'],
            'shipping_postal_code' => $data['postal_code'],
            'shipping_country' => 'FR',
            'shipping_carrier' => $transporteur,
            'shipping_method' => $data['method'],
            'subtotal' => 89.00,
            'tax_amount' => 17.80,
            'shipping_amount' => 4.90,
            'total_amount' => 111.70,
            'currency' => 'EUR',
        ]);

        // Ajouter un article
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_type' => 'Eau de Parfum',
            'product_size' => '50ml',
            'product_price' => 89.00,
            'quantity' => 1,
            'total_price' => 89.00,
        ]);

        // Ajouter les options spécifiques pour Mondial Relay
        if ($transporteur === 'mondialrelay') {
            $order->carrier_options = [
                'point_relais' => [
                    'code' => 'MR001',
                    'name' => 'Tabac de la Gare',
                    'address' => '45 Avenue de la Gare',
                    'postal_code' => $data['postal_code'],
                    'city' => $data['city'],
                    'phone' => '04 78 12 34 56',
                    'hours' => 'Lun-Sam: 7h-19h'
                ]
            ];
            $order->save();
        }

        return $order;
    }
}
