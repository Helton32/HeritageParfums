<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingCarrier;

class ShippingCarrierSeeder extends Seeder
{
    public function run()
    {
        $carriers = [
            [
                'code' => 'mondialrelay',
                'name' => 'Mondial Relay',
                'logo_path' => 'https://via.placeholder.com/120x40/e31837/ffffff?text=Mondial+Relay',
                'methods' => [
                    'point_relais' => [
                        'name' => 'Point Relais',
                        'description' => 'Livraison en point relais Mondial Relay',
                        'delivery_time' => '2-3 jours ouvrés',
                        'features' => ['point_relais', 'tracking']
                    ],
                    'domicile' => [
                        'name' => 'Livraison à domicile',
                        'description' => 'Livraison directement chez vous',
                        'delivery_time' => '2-4 jours ouvrés',
                        'features' => ['domicile', 'tracking']
                    ]
                ],
                'pricing' => [
                    'france' => [
                        'point_relais' => [
                            ['max_weight' => 0.5, 'price' => 3.85],
                            ['max_weight' => 1.0, 'price' => 4.20],
                            ['max_weight' => 2.0, 'price' => 4.95],
                            ['max_weight' => 5.0, 'price' => 6.90]
                        ],
                        'domicile' => [
                            ['max_weight' => 0.5, 'price' => 6.90],
                            ['max_weight' => 1.0, 'price' => 7.40],
                            ['max_weight' => 2.0, 'price' => 8.20],
                            ['max_weight' => 5.0, 'price' => 9.90]
                        ]
                    ]
                ],
                'zones' => ['france'],
                'api_config' => [
                    'sandbox' => [
                        'endpoint' => 'https://api.mondialrelay.com/Web_Services.asmx?WSDL',
                        'brand_code' => 'BDTEST13',
                        'client_code' => 'TEST_CLIENT'
                    ],
                    'production' => [
                        'endpoint' => 'https://api.mondialrelay.com/Web_Services.asmx?WSDL',
                        'brand_code' => 'YOUR_BRAND_CODE',
                        'client_code' => 'YOUR_CLIENT_CODE'
                    ]
                ],
                'active' => true,
                'sort_order' => 1
            ],
            [
                'code' => 'colissimo',
                'name' => 'Colissimo',
                'logo_path' => 'https://via.placeholder.com/120x40/ffcc00/0066cc?text=Colissimo',
                'methods' => [
                    'domicile' => [
                        'name' => 'Colissimo Domicile',
                        'description' => 'Livraison à domicile avec signature',
                        'delivery_time' => '1-2 jours ouvrés',
                        'features' => ['domicile', 'signature', 'tracking']
                    ],
                    'point_retrait' => [
                        'name' => 'Point de Retrait',
                        'description' => 'Livraison en bureau de poste',
                        'delivery_time' => '1-2 jours ouvrés',
                        'features' => ['point_relais', 'tracking']
                    ]
                ],
                'pricing' => [
                    'france' => [
                        'domicile' => [
                            ['max_weight' => 0.5, 'price' => 5.50],
                            ['max_weight' => 1.0, 'price' => 6.90],
                            ['max_weight' => 2.0, 'price' => 8.30],
                            ['max_weight' => 5.0, 'price' => 11.45]
                        ],
                        'point_retrait' => [
                            ['max_weight' => 0.5, 'price' => 4.95],
                            ['max_weight' => 1.0, 'price' => 5.85],
                            ['max_weight' => 2.0, 'price' => 6.85],
                            ['max_weight' => 5.0, 'price' => 9.35]
                        ]
                    ]
                ],
                'zones' => ['france', 'europe', 'international'],
                'api_config' => [
                    'sandbox' => [
                        'endpoint' => 'https://ws.colissimo.fr/test-sls-ws/SlsServiceWS',
                        'contract_number' => 'TEST_CONTRACT',
                        'password' => 'TEST_PASSWORD'
                    ],
                    'production' => [
                        'endpoint' => 'https://ws.colissimo.fr/sls-ws/SlsServiceWS', 
                        'contract_number' => 'YOUR_CONTRACT',
                        'password' => 'YOUR_PASSWORD'
                    ]
                ],
                'active' => true,
                'sort_order' => 2
            ],
            [
                'code' => 'chronopost',
                'name' => 'Chronopost',
                'logo_path' => 'https://via.placeholder.com/120x40/ff6600/ffffff?text=Chronopost',
                'methods' => [
                    'express_domicile' => [
                        'name' => 'Chrono Domicile',
                        'description' => 'Livraison express à domicile avant 13h',
                        'delivery_time' => 'Livraison le lendemain avant 13h',
                        'features' => ['domicile', 'express', 'signature', 'tracking']
                    ],
                    'point_relais' => [
                        'name' => 'Chrono Relais',
                        'description' => 'Livraison en point relais Chronopost',
                        'delivery_time' => 'Livraison le lendemain',
                        'features' => ['point_relais', 'express', 'tracking']
                    ]
                ],
                'pricing' => [
                    'france' => [
                        'express_domicile' => [
                            ['max_weight' => 0.5, 'price' => 12.90],
                            ['max_weight' => 1.0, 'price' => 14.90],
                            ['max_weight' => 2.0, 'price' => 17.90],
                            ['max_weight' => 5.0, 'price' => 22.90]
                        ],
                        'point_relais' => [
                            ['max_weight' => 0.5, 'price' => 8.90],
                            ['max_weight' => 1.0, 'price' => 9.90],
                            ['max_weight' => 2.0, 'price' => 11.90],
                            ['max_weight' => 5.0, 'price' => 14.90]
                        ]
                    ]
                ],
                'zones' => ['france', 'europe'],
                'api_config' => [
                    'sandbox' => [
                        'endpoint' => 'https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS',
                        'account_number' => 'TEST_ACCOUNT',
                        'password' => 'TEST_PASSWORD'
                    ],
                    'production' => [
                        'endpoint' => 'https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS',
                        'account_number' => 'YOUR_ACCOUNT',
                        'password' => 'YOUR_PASSWORD'
                    ]
                ],
                'active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($carriers as $carrierData) {
            ShippingCarrier::updateOrCreate(
                ['code' => $carrierData['code']],
                $carrierData
            );
        }
    }
}