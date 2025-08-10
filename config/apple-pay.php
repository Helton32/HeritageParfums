<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Apple Pay Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour Apple Pay
    |
    */

    // Identifiant du merchant Apple Pay
    'merchant_identifier' => env('APPLE_PAY_MERCHANT_ID', 'merchant.com.heritajparfums.app'),
    
    // Nom de domaine vérifié
    'domain_name' => env('APPLE_PAY_DOMAIN', 'heritajparfums.com'),
    
    // Nom affiché lors du paiement
    'display_name' => env('APPLE_PAY_DISPLAY_NAME', 'Héritaj Parfums'),
    
    // Pays du merchant
    'country_code' => env('APPLE_PAY_COUNTRY', 'FR'),
    
    // Devise
    'currency_code' => env('APPLE_PAY_CURRENCY', 'EUR'),
    
    // Réseaux de cartes supportés
    'supported_networks' => [
        'visa',
        'masterCard',
        'amex',
        'cartesBancaires'
    ],
    
    // Capacités du merchant
    'merchant_capabilities' => [
        'supports3DS',
        'supportsEMV',
        'supportsCredit',
        'supportsDebit'
    ],
    
    // Champs requis pour la facturation
    'required_billing_contact_fields' => [
        'postalAddress',
        'name',
        'email'
    ],
    
    // Champs requis pour la livraison
    'required_shipping_contact_fields' => [
        'postalAddress',
        'name',
        'phone',
        'email'
    ],
    
    // Mode de développement
    'sandbox' => env('APPLE_PAY_SANDBOX', true),
    
    // Chemins des certificats
    'certificates' => [
        'merchant_cert' => storage_path('apple-pay/merchant_id.pem'),
        'merchant_key' => storage_path('apple-pay/merchant_id.key'),
        'wwdr_cert' => storage_path('apple-pay/AppleWWDRCA.pem'),
    ],
    
    // Méthodes de livraison par défaut
    'default_shipping_methods' => [
        [
            'label' => 'Livraison Express',
            'amount' => '9.90',
            'detail' => 'Gratuite dès 150€',
            'identifier' => 'express'
        ],
        [
            'label' => 'Livraison Gratuite',
            'amount' => '0.00',
            'detail' => 'Commandes de 150€ et plus',
            'identifier' => 'free'
        ]
    ]
];
