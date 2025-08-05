<?php

namespace Database\Seeders;

use App\Models\ShippingCarrier;
use Illuminate\Database\Seeder;

class ShippingCarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Colissimo
        ShippingCarrier::create([
            'code' => 'colissimo',
            'name' => 'Colissimo',
            'logo_path' => 'images/carriers/colissimo.png',
            'methods' => [
                'standard' => [
                    'name' => 'Colissimo Standard',
                    'description' => 'Livraison en 2-3 jours ouvrés',
                    'delivery_time' => '2-3 jours'
                ],
                'express' => [
                    'name' => 'Colissimo Express',
                    'description' => 'Livraison en 24-48h',
                    'delivery_time' => '24-48h'
                ],
                'pickup' => [
                    'name' => 'Colissimo Point Relais',
                    'description' => 'Livraison en point relais',
                    'delivery_time' => '2-3 jours'
                ]
            ],
            'pricing' => [
                'france' => [
                    'standard' => [
                        ['max_weight' => 0.5, 'price' => 4.95],
                        ['max_weight' => 1.0, 'price' => 6.90],
                        ['max_weight' => 2.0, 'price' => 8.90],
                        ['max_weight' => 5.0, 'price' => 12.90],
                    ],
                    'express' => [
                        ['max_weight' => 0.5, 'price' => 9.90],
                        ['max_weight' => 1.0, 'price' => 12.90],
                        ['max_weight' => 2.0, 'price' => 15.90],
                        ['max_weight' => 5.0, 'price' => 19.90],
                    ],
                    'pickup' => [
                        ['max_weight' => 0.5, 'price' => 3.95],
                        ['max_weight' => 1.0, 'price' => 4.95],
                        ['max_weight' => 2.0, 'price' => 6.95],
                        ['max_weight' => 5.0, 'price' => 8.95],
                    ]
                ],
                'europe' => [
                    'standard' => [
                        ['max_weight' => 0.5, 'price' => 12.90],
                        ['max_weight' => 1.0, 'price' => 16.90],
                        ['max_weight' => 2.0, 'price' => 22.90],
                        ['max_weight' => 5.0, 'price' => 28.90],
                    ]
                ]
            ],
            'zones' => ['france', 'europe', 'europe_proche'],
            'api_config' => [
                'sandbox' => [
                    'endpoint' => 'https://ws.colissimo.fr/sls-ws/SlsServiceWS/2.0',
                    'login' => 'test_login',
                    'password' => 'test_password'
                ],
                'production' => [
                    'endpoint' => 'https://ws.colissimo.fr/sls-ws/SlsServiceWS/2.0',
                    'login' => env('COLISSIMO_LOGIN'),
                    'password' => env('COLISSIMO_PASSWORD')
                ]
            ],
            'active' => true,
            'sort_order' => 1
        ]);

        // Chronopost
        ShippingCarrier::create([
            'code' => 'chronopost',
            'name' => 'Chronopost',
            'logo_path' => 'images/carriers/chronopost.png',
            'methods' => [
                'standard' => [
                    'name' => 'Chronopost Standard',
                    'description' => 'Livraison en 24h',
                    'delivery_time' => '24h'
                ],
                'express' => [
                    'name' => 'Chronopost Express',
                    'description' => 'Livraison avant 13h',
                    'delivery_time' => 'Avant 13h'
                ],
                'saturday' => [
                    'name' => 'Chronopost Samedi',
                    'description' => 'Livraison le samedi',
                    'delivery_time' => 'Samedi'
                ]
            ],
            'pricing' => [
                'france' => [
                    'standard' => [
                        ['max_weight' => 0.5, 'price' => 12.90],
                        ['max_weight' => 1.0, 'price' => 15.90],
                        ['max_weight' => 2.0, 'price' => 18.90],
                        ['max_weight' => 5.0, 'price' => 24.90],
                    ],
                    'express' => [
                        ['max_weight' => 0.5, 'price' => 19.90],
                        ['max_weight' => 1.0, 'price' => 24.90],
                        ['max_weight' => 2.0, 'price' => 29.90],
                        ['max_weight' => 5.0, 'price' => 34.90],
                    ],
                    'saturday' => [
                        ['max_weight' => 0.5, 'price' => 24.90],
                        ['max_weight' => 1.0, 'price' => 29.90],
                        ['max_weight' => 2.0, 'price' => 34.90],
                        ['max_weight' => 5.0, 'price' => 39.90],
                    ]
                ],
                'europe' => [
                    'standard' => [
                        ['max_weight' => 0.5, 'price' => 24.90],
                        ['max_weight' => 1.0, 'price' => 29.90],
                        ['max_weight' => 2.0, 'price' => 34.90],
                        ['max_weight' => 5.0, 'price' => 39.90],
                    ]
                ]
            ],
            'zones' => ['france', 'europe', 'europe_proche'],
            'api_config' => [
                'sandbox' => [
                    'endpoint' => 'https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS',
                    'account' => 'test_account',
                    'password' => 'test_password'
                ],
                'production' => [
                    'endpoint' => 'https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS',
                    'account' => env('CHRONOPOST_ACCOUNT'),
                    'password' => env('CHRONOPOST_PASSWORD')
                ]
            ],
            'active' => true,
            'sort_order' => 2
        ]);

        // Mondial Relay
        ShippingCarrier::create([
            'code' => 'mondialrelay',
            'name' => 'Mondial Relay',
            'logo_path' => 'images/carriers/mondialrelay.png',
            'methods' => [
                'point_relais' => [
                    'name' => 'Point Relais',
                    'description' => 'Livraison en point relais',
                    'delivery_time' => '2-4 jours'
                ],
                'domicile' => [
                    'name' => 'Livraison à domicile',
                    'description' => 'Livraison à domicile',
                    'delivery_time' => '2-4 jours'
                ]
            ],
            'pricing' => [
                'france' => [
                    'point_relais' => [
                        ['max_weight' => 0.5, 'price' => 3.90],
                        ['max_weight' => 1.0, 'price' => 4.90],
                        ['max_weight' => 2.0, 'price' => 5.90],
                        ['max_weight' => 5.0, 'price' => 7.90],
                    ],
                    'domicile' => [
                        ['max_weight' => 0.5, 'price' => 6.90],
                        ['max_weight' => 1.0, 'price' => 8.90],
                        ['max_weight' => 2.0, 'price' => 10.90],
                        ['max_weight' => 5.0, 'price' => 14.90],
                    ]
                ],
                'europe' => [
                    'point_relais' => [
                        ['max_weight' => 0.5, 'price' => 8.90],
                        ['max_weight' => 1.0, 'price' => 11.90],
                        ['max_weight' => 2.0, 'price' => 14.90],
                        ['max_weight' => 5.0, 'price' => 18.90],
                    ]
                ]
            ],
            'zones' => ['france', 'europe', 'europe_proche'],
            'api_config' => [
                'sandbox' => [
                    'endpoint' => 'https://api.mondialrelay.com/Web_Services.asmx',
                    'enseigne' => '2N0001',
                    'private_key' => 'test_private_key'
                ],
                'production' => [
                    'endpoint' => 'https://api.mondialrelay.com/Web_Services.asmx',
                    'enseigne' => env('MONDIALRELAY_ENSEIGNE'),
                    'private_key' => env('MONDIALRELAY_PRIVATE_KEY')
                ]
            ],
            'active' => true,
            'sort_order' => 3
        ]);
    }
}
