<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Events\OrderPaid;
use App\Events\OrderShipped;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'stripe_payment_intent_id',
        'stripe_session_id',
        'status',
        'payment_status',
        'payment_method',
        'customer_email',
        'customer_name',
        'customer_phone',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_city',
        'billing_postal_code',
        'billing_country',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_city',
        'shipping_postal_code',
        'shipping_country',
        'shipping_carrier',
        'shipping_method',
        'tracking_number',
        'carrier_reference',
        'carrier_options',
        'carrier_response',
        'shipping_label_path',
        'shipping_weight',
        'package_dimensions',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'total_amount',
        'currency',
        'notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_weight' => 'decimal:3',
        'carrier_options' => 'array',
        'carrier_response' => 'array',
        'package_dimensions' => 'array',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingCarrier()
    {
        return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier', 'code');
    }

    // Accessors
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2) . ' €';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'En attente',
            'processing' => 'En traitement',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'En attente',
            'paid' => 'Payée',
            'failed' => 'Échec',
            'refunded' => 'Remboursée',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    // Methods
    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'HP-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('total_price');
        $this->tax_amount = $this->subtotal * 0.20; // TVA 20%
        $this->shipping_amount = $this->subtotal >= 150 ? 0 : 9.90; // Livraison gratuite dès 150€
        $this->total_amount = $this->subtotal + $this->tax_amount + $this->shipping_amount;
        $this->save();
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function markAsPaid()
    {
        $this->payment_status = 'paid';
        $this->status = 'processing';
        $this->save();
        
        // Déclencher l'événement pour l'envoi de l'email de confirmation
        event(new OrderPaid($this));
    }

    public function markAsShipped()
    {
        $this->status = 'shipped';
        $this->shipped_at = now();
        $this->save();
        
        // Déclencher l'événement pour l'envoi de l'email d'expédition
        event(new OrderShipped($this));
    }

    public function markAsDelivered()
    {
        $this->status = 'delivered';
        $this->delivered_at = now();
        $this->save();
    }

    // Nouvelles méthodes pour la livraison
    public function calculatePackageWeight()
    {
        $weight = 0;
        foreach ($this->items as $item) {
            // Poids standard d'un parfum : 200g pour 50ml, 300g pour 100ml
            $productWeight = match($item->product_size) {
                '30ml' => 0.15,
                '50ml' => 0.2,
                '100ml' => 0.3,
                default => 0.2
            };
            $weight += $productWeight * $item->quantity;
        }
        
        // Ajouter le poids de l'emballage (100g)
        $weight += 0.1;
        
        return round($weight, 3);
    }

    public function getPackageDimensions()
    {
        // Dimensions standard pour nos parfums
        $itemCount = $this->items->sum('quantity');
        
        if ($itemCount == 1) {
            return ['length' => 15, 'width' => 10, 'height' => 8]; // cm
        } elseif ($itemCount <= 3) {
            return ['length' => 20, 'width' => 15, 'height' => 10];
        } else {
            return ['length' => 25, 'width' => 20, 'height' => 12];
        }
    }

    public function getShippingZone()
    {
        // Déterminer la zone de livraison basée sur le pays
        $country = strtolower($this->shipping_country);
        
        if ($country === 'fr') {
            return 'france';
        } elseif (in_array($country, ['be', 'lu', 'mc', 'ad'])) {
            return 'europe_proche';
        } elseif (in_array($country, ['de', 'es', 'it', 'nl', 'pt', 'ch', 'at'])) {
            return 'europe';
        } else {
            return 'international';
        }
    }

    public function canUseCarrier($carrierCode)
    {
        $carrier = ShippingCarrier::where('code', $carrierCode)->active()->first();
        
        if (!$carrier) {
            return false;
        }
        
        return $carrier->servesZone($this->getShippingZone());
    }

    public function generateCarrierReference()
    {
        return 'HP-' . $this->id . '-' . date('Ymd');
    }

    public function hasTrackingNumber()
    {
        return !empty($this->tracking_number);
    }


    /**
     * Méthode spécifique pour Hostinger
     * Force l'envoi direct des emails si les queues ne fonctionnent pas
     */
    public function markAsShippedHostinger()
    {
        $this->status = 'shipped';
        $this->shipped_at = now();
        $this->save();
        
        // Sur Hostinger, envoyer directement l'email
        try {
            \Mail::to($this->customer_email)
                ->send(new \App\Mail\OrderShipped($this));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email expédition: ' . $e->getMessage());
        }
        
        // Déclencher aussi l'événement normal
        event(new \App\Events\OrderShipped($this));
    }

    public function getCarrierNameAttribute()
    {
        $carriers = [
            'colissimo' => 'Colissimo',
            'chronopost' => 'Chronopost',
            'mondialrelay' => 'Mondial Relay'
        ];
        
        return $carriers[$this->shipping_carrier] ?? $this->shipping_carrier;
    }
}