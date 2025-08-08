<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'slug',
        'description',
        'short_description',
        'price',
        'is_on_promotion',
        'promotion_price',
        'promotion_start_date',
        'promotion_end_date',
        'promotion_description',
        'category',
        'product_type',
        'type',
        'size',
        'images',
        'notes',
        'stock',
        'is_active',
        'is_featured',
        'badge',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'promotion_price' => 'decimal:2',
        'promotion_start_date' => 'date',
        'promotion_end_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_on_promotion' => 'boolean',
    ];

    // Mutators pour s'assurer que les arrays contiennent bien des strings
    public function setNotesAttribute($value)
    {
        if (is_array($value)) {
            // Si c'est une structure avec head/heart/base, la préserver
            if (isset($value['head']) || isset($value['heart']) || isset($value['base'])) {
                $structuredNotes = [];
                foreach (['head', 'heart', 'base'] as $level) {
                    if (isset($value[$level]) && is_array($value[$level])) {
                        $cleanNotes = array_filter(array_map(function($note) {
                            return is_string($note) ? trim($note) : '';
                        }, $value[$level]));
                        if (!empty($cleanNotes)) {
                            $structuredNotes[$level] = array_values($cleanNotes);
                        }
                    }
                }
                $this->attributes['notes'] = !empty($structuredNotes) ? json_encode($structuredNotes) : null;
            } else {
                // Ancienne logique pour compatibilité
                $cleanNotes = array_filter(array_map(function($note) {
                    return is_string($note) ? trim($note) : '';
                }, $value));
                $this->attributes['notes'] = !empty($cleanNotes) ? json_encode(array_values($cleanNotes)) : null;
            }
        } else {
            $this->attributes['notes'] = $value;
        }
    }

    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            // Filtrer et nettoyer les images
            $cleanImages = array_filter(array_map(function($image) {
                return is_string($image) ? trim($image) : '';
            }, $value));
            $this->attributes['images'] = json_encode(array_values($cleanImages));
        } else {
            $this->attributes['images'] = $value;
        }
    }

    // Relationships
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors pour s'assurer que les arrays retournés contiennent des strings
    public function getNotesAttribute($value)
    {
        if (empty($value)) {
            return ['head' => [], 'heart' => [], 'base' => []];
        }
        
        $notes = json_decode($value, true) ?: [];
        
        // Si c'est déjà une structure avec head/heart/base
        if (isset($notes['head']) || isset($notes['heart']) || isset($notes['base'])) {
            $structuredNotes = ['head' => [], 'heart' => [], 'base' => []];
            
            foreach (['head', 'heart', 'base'] as $level) {
                if (isset($notes[$level]) && is_array($notes[$level])) {
                    $structuredNotes[$level] = array_filter(array_map(function($note) {
                        return is_string($note) ? trim($note) : (string) $note;
                    }, $notes[$level]));
                }
            }
            
            return $structuredNotes;
        }
        
        // Si c'est un ancien format (array simple), retourner une structure vide
        // Les anciens produits devront être modifiés pour avoir des notes structurées
        return ['head' => [], 'heart' => [], 'base' => []];
    }

    public function getImagesAttribute($value)
    {
        $images = json_decode($value, true) ?: [];
        return array_filter(array_map('strval', $images));
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' €';
    }

    public function getMainImageAttribute()
    {
        return $this->images[0] ?? 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
    }

    public function getCategoryLabelAttribute()
    {
        if ($this->product_type === 'parfum') {
            $labels = [
                'niche' => 'Parfums de Niche',
                'exclusifs' => 'Collections Exclusives',
                'nouveautes' => 'Nouveautés',
            ];
        } else {
            $labels = [
                'soins_visage' => 'Soins du Visage',
                'soins_corps' => 'Soins du Corps',
                'nouveautes_cosmetiques' => 'Nouveautés Cosmétiques',
            ];
        }

        return $labels[$this->category] ?? $this->category;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeParfums($query)
    {
        return $query->where('product_type', 'parfum');
    }

    public function scopeCosmetiques($query)
    {
        return $query->where('product_type', 'cosmetique');
    }

    public function scopeByProductType($query, $productType)
    {
        return $query->where('product_type', $productType);
    }

    // Methods
    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function getNotesForLevel($level)
    {
        $notes = $this->notes;
        
        if (!is_array($notes) || !isset($notes[$level]) || !is_array($notes[$level])) {
            return [];
        }
        
        return array_filter(array_map(function($note) {
            return is_string($note) ? trim($note) : (string) $note;
        }, $notes[$level]));
    }

    public function hasNotesForLevel($level)
    {
        return count($this->getNotesForLevel($level)) > 0;
    }

    public function decrementStock($quantity = 1)
    {
        $this->decrement('stock', $quantity);
    }

    public function incrementStock($quantity = 1)
    {
        $this->increment('stock', $quantity);
    }

    // Méthodes pour les promotions
    public function isOnValidPromotion()
    {
        if (!$this->is_on_promotion || !$this->promotion_price) {
            return false;
        }

        $now = now()->toDateString();
        
        // Vérifier les dates si elles sont définies
        if ($this->promotion_start_date && $now < $this->promotion_start_date->toDateString()) {
            return false;
        }
        
        if ($this->promotion_end_date && $now > $this->promotion_end_date->toDateString()) {
            return false;
        }
        
        return true;
    }

    public function getCurrentPrice()
    {
        return $this->isOnValidPromotion() ? $this->promotion_price : $this->price;
    }

    public function getDiscountPercentage()
    {
        if (!$this->isOnValidPromotion()) {
            return 0;
        }
        
        return round((($this->price - $this->promotion_price) / $this->price) * 100);
    }

    public function getFormattedCurrentPriceAttribute()
    {
        return number_format($this->getCurrentPrice(), 2) . ' €';
    }

    public function getFormattedPromotionPriceAttribute()
    {
        if (!$this->promotion_price) {
            return null;
        }
        return number_format($this->promotion_price, 2) . ' €';
    }

    public function hasValidPromotion()
    {
        return $this->isOnValidPromotion();
    }
}