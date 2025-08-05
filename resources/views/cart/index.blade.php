@extends('layouts.app')

@section('title', 'Mon Panier - Éternelle Rose')
@section('description', 'Finalisez votre commande d\'Éternelle Rose, le parfum signature Heritage Parfums.')

@push('styles')
<style>
    /* Cart Page - Style Guerlain */
    .page-header {
        background: var(--guerlain-cream);
        padding: 10rem 0 6rem;
        margin-top: 0;
        padding-top: 120px;
    }

    .page-header h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 4rem;
        font-weight: 300;
        color: var(--guerlain-black);
        margin-bottom: 2rem;
        letter-spacing: 2px;
        text-align: center;
    }

    .page-header .lead {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: var(--guerlain-text-gray);
        text-align: center;
        letter-spacing: 1px;
    }

    .cart-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .cart-section {
        padding: 6rem 0;
        background: var(--guerlain-white);
    }

    .product-showcase {
        background: var(--guerlain-white);
        border-radius: 0;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--guerlain-border);
        padding: 4rem;
        margin-bottom: 3rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .product-showcase:hover {
        transform: translateY(-3px);
        box-shadow: 0 35px 80px rgba(0, 0, 0, 0.12);
    }

    .product-image-large {
        width: 250px;
        height: 250px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        margin: 0 auto 3rem;
        position: relative;
        animation: gentleFloat 6s ease-in-out infinite;
        filter: drop-shadow(0 15px 35px rgba(0, 0, 0, 0.1));
    }

    @keyframes gentleFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(1deg); }
        50% { transform: translateY(-10px); }
    }

    .product-badge {
        position: absolute;
        top: -15px;
        right: -15px;
        background: var(--guerlain-gold);
        color: var(--guerlain-white);
        padding: 8px 15px;
        border-radius: 0;
        font-size: 0.8rem;
        font-weight: 400;
        font-family: 'Montserrat', sans-serif;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 2px solid var(--guerlain-white);
    }

    .product-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 3rem;
        font-weight: 300;
        color: var(--guerlain-black);
        margin-bottom: 1rem;
        letter-spacing: 1px;
    }

    .product-subtitle {
        color: var(--guerlain-text-gray);
        font-family: 'Montserrat', sans-serif;
        font-size: 1.2rem;
        font-weight: 300;
        margin-bottom: 2rem;
        letter-spacing: 0.5px;
    }

    .quantity-section {
        background: var(--guerlain-light-gray);
        border-radius: 0;
        border: 1px solid var(--guerlain-border);
        padding: 3rem;
        margin: 3rem 0;
    }

    .quantity-section h4 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 400;
        color: var(--guerlain-black);
        margin-bottom: 2rem;
        text-align: center;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3rem;
        margin: 2rem 0;
    }

    .quantity-btn {
        width: 50px;
        height: 50px;
        background: var(--guerlain-white);
        border: 2px solid var(--guerlain-gold);
        color: var(--guerlain-gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .quantity-btn:hover {
        background: var(--guerlain-gold);
        color: var(--guerlain-white);
        transform: translateY(-2px);
    }

    .quantity-display {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 300;
        color: var(--guerlain-black);
        min-width: 60px;
        text-align: center;
    }

    .price-section {
        background: var(--guerlain-cream);
        padding: 3rem;
        border: 1px solid var(--guerlain-border);
        margin: 3rem 0;
    }

    .price-breakdown {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
    }

    .price-breakdown .row {
        margin-bottom: 1rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--guerlain-border);
    }

    .price-breakdown .row:last-child {
        border-bottom: 2px solid var(--guerlain-gold);
        font-weight: 400;
        font-size: 1.1rem;
    }

    .total-price {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 300;
        color: var(--guerlain-gold);
        text-align: center;
        margin: 2rem 0;
    }
        border: 2px solid var(--primary-gold);
        background: white;
        color: var(--primary-gold);
        border-radius: 50%;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background: var(--primary-gold);
        color: white;
    }

    .quantity-display {
        font-size: 2rem;
        font-weight: 600;
        min-width: 80px;
        text-align: center;
    }

    .price-summary {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
    }

    .price-row.total {
        border-top: 2px solid var(--primary-gold);
        padding-top: 1rem;
        margin-top: 1rem;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--primary-gold);
    }

    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .empty-cart-icon {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 2rem;
    }

    .btn-checkout {
        background: linear-gradient(45deg, var(--primary-gold), var(--secondary-gold));
        border: none;
        color: white;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 50px;
        transition: all 0.3s ease;
        width: 100%;
        margin-bottom: 1rem;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(184, 134, 11, 0.3);
        color: white;
    }

    .btn-continue {
        background: transparent;
        border: 2px solid var(--primary-gold);
        color: var(--primary-gold);
        padding: 12px 30px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 50px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-continue:hover {
        background: var(--primary-gold);
        color: white;
        transform: translateY(-2px);
    }

    .perks {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .perk {
        text-align: center;
        padding: 1rem;
        background: var(--light-gray);
        border-radius: 10px;
    }

    .perk i {
        font-size: 2rem;
        color: var(--primary-gold);
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="text-center">
            <h1>Mon Panier</h1>
            <p class="lead">Finalisez votre acquisition d'Éternelle Rose</p>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="cart-section">
    <div class="container">
        <div class="cart-container">
            @if($cartData['quantity'] > 0)
                <!-- Product Showcase -->
                <div class="product-showcase">
                    <div class="product-image-large" style="background-image: url('{{ $cartData['product']->main_image }}');">
                        <div class="product-badge">Édition Limitée</div>
                    </div>
                    <h2 class="product-title">{{ $cartData['product']->name }}</h2>
                    <p class="product-subtitle">{{ $cartData['product']->type }} - {{ $cartData['product']->size }}</p>
                    
                    <!-- Quantity Section -->
                    <div class="quantity-section">
                        <h4>Quantité</h4>
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity({{ $cartData['quantity'] - 1 }})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <div class="quantity-display">{{ $cartData['quantity'] }}</div>
                            <button class="quantity-btn" onclick="updateQuantity({{ $cartData['quantity'] + 1 }})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <p style="color: var(--guerlain-text-gray); font-family: 'Montserrat', sans-serif; font-weight: 300; margin-top: 1rem;">Maximum 5 flacons par commande</p>
                        <button class="btn btn-guerlain-outline btn-sm mt-3" onclick="clearCart()">
                            <i class="fas fa-trash me-2"></i>Vider le panier
                        </button>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="price-summary">
                    <h4 class="font-playfair text-gold mb-4">Récapitulatif de Commande</h4>
                    
                    <div class="price-row">
                        <span>{{ $cartData['product']->name }} × {{ $cartData['quantity'] }}</span>
                        <span>{{ number_format($cartData['subtotal'], 2) }}€</span>
                    </div>
                    
                    <div class="price-row">
                        <span>TVA (20%)</span>
                        <span>{{ number_format($cartData['tax_amount'], 2) }}€</span>
                    </div>
                    
                    <div class="price-row">
                        <span>Livraison Express</span>
                        <span>
                            @if($cartData['shipping_amount'] > 0)
                                {{ number_format($cartData['shipping_amount'], 2) }}€
                            @else
                                <span class="text-success">Gratuite</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="price-row total">
                        <span>Total</span>
                        <span>{{ number_format($cartData['total'], 2) }}€</span>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('payment.checkout') }}" class="btn btn-checkout">
                            <i class="fas fa-lock me-2"></i>Procéder au Paiement Sécurisé
                        </a>
                        <a href="/" class="btn btn-continue">
                            <i class="fas fa-arrow-left me-2"></i>Retour à Éternelle Rose
                        </a>
                    </div>

                    <!-- Perks -->
                    <div class="perks">
                        <div class="perk">
                            <i class="fas fa-gift"></i>
                            <div><strong>Emballage Cadeau</strong></div>
                            <small class="text-muted">Offert</small>
                        </div>
                        <div class="perk">
                            <i class="fas fa-shipping-fast"></i>
                            <div><strong>Livraison 48h</strong></div>
                            <small class="text-muted">Gratuite dès 150€</small>
                        </div>
                        <div class="perk">
                            <i class="fas fa-certificate"></i>
                            <div><strong>Authenticité</strong></div>
                            <small class="text-muted">Garantie</small>
                        </div>
                        <div class="perk">
                            <i class="fas fa-undo"></i>
                            <div><strong>Retours</strong></div>
                            <small class="text-muted">30 jours gratuits</small>
                        </div>
                    </div>
                </div>

            @else
                <!-- Empty Cart -->
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3 class="font-playfair text-gold mb-3">Votre Panier est Vide</h3>
                    <p class="text-muted mb-4">Découvrez Éternelle Rose, notre parfum signature d'exception.</p>
                    <a href="/" class="btn btn-checkout">
                        <i class="fas fa-eye me-2"></i>Découvrir Éternelle Rose
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function updateQuantity(newQuantity) {
    if (newQuantity < 0 || newQuantity > 5) return;
    
    if (newQuantity === 0) {
        clearCart();
        return;
    }

    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count in header
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
            }
            
            // Reload page to update prices
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour');
    });
}

function clearCart() {
    if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
        fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du vidage du panier');
        });
    }
}

// Update cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    fetch('/cart/count')
    .then(response => response.json())
    .then(data => {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = data.count;
        }
    })
    .catch(error => console.error('Error updating cart count:', error));
});
</script>
@endpush