<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shop Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour les paramètres de la boutique Heritage Parfums
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Livraison
    |--------------------------------------------------------------------------
    */
    
    'shipping' => [
        // Montant minimum pour la livraison offerte
        'free_shipping_threshold' => env('FREE_SHIPPING_THRESHOLD', 250.00),
        
        // Prix de la livraison standard
        'standard_shipping_cost' => env('STANDARD_SHIPPING_COST', 9.90),
        
        // Prix de la livraison express
        'express_shipping_cost' => env('EXPRESS_SHIPPING_COST', 19.90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Promotions
    |--------------------------------------------------------------------------
    */
    
    'promotions' => [
        // Affichage des promotions
        'show_percentage' => true,
        'show_original_price' => true,
        'show_promotion_badge' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */
    
    'messages' => [
        'free_shipping_message' => 'Livraison offerte dès :amount€ d\'achat',
        'promotion_active' => 'Promotion en cours',
        'out_of_stock' => 'Rupture de stock',
    ],

];
