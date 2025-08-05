@extends('layouts.app')

@section('title', $product->name . ' - Heritage Parfums')
@section('description', $product->short_description)

@push('styles')
<style>
    .product-hero {
        padding: 8rem 0 4rem;
        margin-top: 80px;
    }

    .product-image-main {
        width: 100%;
        height: 500px;
        background-size: cover;
        background-position: center;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
    }

    .product-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: var(--primary-gold);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 500;
    }

    .product-info {
        padding-left: 3rem;
    }

    .product-title {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        color: var(--primary-gold);
        margin-bottom: 1rem;
    }

    .product-type {
        color: #666;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .product-price {
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--deep-black);
        margin-bottom: 2rem;
    }

    .notes-section {
        background: var(--cream);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
    }

    .note-category {
        margin-bottom: 1.5rem;
    }

    .note-title {
        font-weight: 600;
        color: var(--primary-gold);
        margin-bottom: 0.5rem;
    }

    .note-list {
        color: #666;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .quantity-input {
        width: 80px;
        text-align: center;
        border: 2px solid var(--primary-gold);
        border-radius: 8px;
        padding: 10px;
    }

    .btn-quantity {
        background: var(--primary-gold);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-quantity:hover {
        background: var(--dark-gold);
        color: white;
    }

    .btn-add-cart {
        background: linear-gradient(45deg, var(--primary-gold), var(--secondary-gold));
        border: none;
        color: white;
        padding: 15px 40px;
        font-size: 1.2rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(184, 134, 11, 0.3);
        color: white;
    }

    .stock-info {
        margin-bottom: 2rem;
    }

    .stock-available {
        color: #28a745;
    }

    .stock-low {
        color: #ffc107;
    }

    .stock-out {
        color: #dc3545;
    }

    .related-products {
        background: var(--light-gray);
        padding: 4rem 0;
    }

    .related-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: inherit;
    }

    .related-image {
        height: 200px;
        background-size: cover;
        background-position: center;
    }

    @media (max-width: 768px) {
        .product-info {
            padding-left: 0;
            margin-top: 2rem;
        }
        
        .product-title {
            font-size: 2rem;
        }
        
        .product-price {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Product Details -->
<section class="product-hero">
    <div class="container">
        <div class="row align-items-center">
            <!-- Product Image -->
            <div class="col-lg-6">
                <div class="product-image-main" style="background-image: url('{{ $product->main_image }}');">
                    @if($product->badge)
                        <div class="product-badge">{{ $product->badge }}</div>
                    @endif
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <p class="product-type">{{ $product->type }} - {{ $product->size }}</p>
                    <p class="product-price">{{ $product->formatted_price }}</p>
                    
                    <!-- Stock Info -->
                    <div class="stock-info">
                        @if($product->stock > 10)
                            <span class="stock-available">
                                <i class="fas fa-check-circle me-1"></i>En stock
                            </span>
                        @elseif($product->stock > 0)
                            <span class="stock-low">
                                <i class="fas fa-exclamation-triangle me-1"></i>Stock limité ({{ $product->stock }} restants)
                            </span>
                        @else
                            <span class="stock-out">
                                <i class="fas fa-times-circle me-1"></i>Rupture de stock
                            </span>
                        @endif
                    </div>
                    
                    @if($product->isInStock())
                        <!-- Quantity Selector -->
                        <div class="quantity-selector">
                            <label for="quantity">Quantité:</label>
                            <button type="button" class="btn btn-quantity" onclick="decrementQuantity()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" class="quantity-input" value="1" min="1" max="{{ min($product->stock, 10) }}">
                            <button type="button" class="btn btn-quantity" onclick="incrementQuantity()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <button class="btn btn-add-cart me-3" id="add-to-cart-btn" data-product-id="{{ $product->id }}">
                            <i class="fas fa-shopping-bag me-2"></i>Ajouter au Panier
                        </button>
                    @else
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-times me-2"></i>Produit Indisponible
                        </button>
                    @endif
                    
                    <!-- Services -->
                    <div class="mt-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-truck me-2 text-gold"></i>
                            <span>Livraison gratuite dès 150€</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-gift me-2 text-gold"></i>
                            <span>Emballage cadeau offert</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-undo me-2 text-gold"></i>
                            <span>Retours gratuits sous 30 jours</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Description -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="font-playfair text-gold mb-4">Description</h3>
                <p class="lead">{{ $product->short_description }}</p>
                <p>{{ $product->description }}</p>
                
                @if($product->notes)
                    <div class="notes-section">
                        <h4 class="font-playfair text-gold mb-4">Notes Olfactives</h4>
                        
                        @if(isset($product->notes['head']))
                            <div class="note-category">
                                <div class="note-title">Notes de Tête</div>
                                <div class="note-list">{{ implode(', ', $product->notes['head']) }}</div>
                            </div>
                        @endif
                        
                        @if(isset($product->notes['heart']))
                            <div class="note-category">
                                <div class="note-title">Notes de Cœur</div>
                                <div class="note-list">{{ implode(', ', $product->notes['heart']) }}</div>
                            </div>
                        @endif
                        
                        @if(isset($product->notes['base']))
                            <div class="note-category">
                                <div class="note-title">Notes de Fond</div>
                                <div class="note-list">{{ implode(', ', $product->notes['base']) }}</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            
            <div class="col-lg-4">
                <div class="bg-light p-4 rounded">
                    <h5 class="font-playfair text-gold mb-3">Informations Produit</h5>
                    <div class="mb-2">
                        <strong>Catégorie:</strong> {{ $product->category_label }}
                    </div>
                    <div class="mb-2">
                        <strong>Type:</strong> {{ $product->type }}
                    </div>
                    <div class="mb-2">
                        <strong>Contenance:</strong> {{ $product->size }}
                    </div>
                    <div class="mb-2">
                        <strong>Stock:</strong> 
                        @if($product->stock > 10)
                            Disponible
                        @elseif($product->stock > 0)
                            {{ $product->stock }} unités
                        @else
                            Rupture de stock
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="related-products">
    <div class="container">
        <h3 class="font-playfair text-gold text-center mb-5">Produits Similaires</h3>
        
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6">
                <a href="/product/{{ $relatedProduct->slug }}" class="related-card d-block">
                    <div class="related-image" style="background-image: url('{{ $relatedProduct->main_image }}');"></div>
                    <div class="p-3">
                        <h6 class="font-playfair text-gold mb-1">{{ $relatedProduct->name }}</h6>
                        <p class="text-muted small mb-1">{{ $relatedProduct->type }}</p>
                        <p class="fw-bold mb-0">{{ $relatedProduct->formatted_price }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
function incrementQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.max);
    
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const minValue = parseInt(input.min);
    
    if (currentValue > minValue) {
        input.value = currentValue - 1;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const quantity = document.getElementById('quantity').value;
            
            // Disable button temporarily
            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Ajout en cours...';
            
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: parseInt(quantity)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                    }
                    
                    // Show success message
                    this.innerHTML = '<i class="fas fa-check me-2"></i>Ajouté au Panier !';
                    this.classList.add('btn-success');
                    this.classList.remove('btn-add-cart');
                    
                    // Show notification
                    showNotification(data.message, 'success');
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-add-cart');
                        this.disabled = false;
                    }, 3000);
                } else {
                    showNotification(data.message, 'error');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Erreur lors de l\'ajout au panier', 'error');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }
});

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close float-end" aria-label="Close"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto hide after 4 seconds
    setTimeout(() => {
        notification.remove();
    }, 4000);
    
    // Close button functionality
    notification.querySelector('.btn-close').addEventListener('click', () => {
        notification.remove();
    });
}
</script>
@endpush