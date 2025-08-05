<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCarrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'logo_path',
        'methods',
        'pricing',
        'zones',
        'api_config',
        'active',
        'sort_order'
    ];

    protected $casts = [
        'methods' => 'array',
        'pricing' => 'array',
        'zones' => 'array',
        'api_config' => 'array',
        'active' => 'boolean'
    ];

    /**
     * Scope pour les transporteurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('sort_order');
    }

    /**
     * Calculer le prix de livraison
     */
    public function calculateShippingPrice($weight, $zone = 'france', $method = 'standard')
    {
        $pricing = $this->pricing;
        
        if (!isset($pricing[$zone][$method])) {
            return 0;
        }

        $rates = $pricing[$zone][$method];
        
        // Recherche du bon tarif basé sur le poids
        foreach ($rates as $rate) {
            if ($weight <= $rate['max_weight']) {
                return $rate['price'];
            }
        }
        
        return 0;
    }

    /**
     * Obtenir les méthodes disponibles pour une zone
     */
    public function getAvailableMethodsForZone($zone = 'france')
    {
        $availableMethods = [];
        
        foreach ($this->methods as $methodCode => $methodData) {
            if (isset($this->pricing[$zone][$methodCode])) {
                $availableMethods[$methodCode] = $methodData;
            }
        }
        
        return $availableMethods;
    }

    /**
     * Vérifier si le transporteur dessert une zone
     */
    public function servesZone($zone)
    {
        return in_array($zone, $this->zones);
    }

    /**
     * Obtenir la configuration API pour l'environnement actuel
     */
    public function getApiConfig()
    {
        $env = app()->environment('production') ? 'production' : 'sandbox';
        return $this->api_config[$env] ?? null;
    }
}
