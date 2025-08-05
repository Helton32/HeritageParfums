@extends('layouts.app')

@section('title', 'Mon Panier - Heritage Parfums')
@section('description', 'Finalisez votre commande Heritage Parfums.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cart-modern.css') }}">
@endpush

@section('content')
<!-- Page Header -->
<section class="cart-header">
    <div class="container">
        <div class="header-content">
            <h1>Mon Panier</h1>
            <div class="breadcrumb">
                <a href="/">Accueil</a>
                <span>/</span>
                <span>Panier</span>
            </div>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="cart-section">
    <div class="container">
        @if($cartData['count'] > 0)
            <div class="cart-layout">
                <!-- Cart Items -->
                <div class="cart-items">
                    <div class="cart-header-row">
                        <h3>Vos Produits ({{ $cartData['count'] }} {{ $cartData['count'] > 1 ? 'articles' : 'article' }})</h3>
                        <button class="clear-cart-btn" onclick="clearCart()">
                            <i class="fas fa-trash"></i> Vider le panier
                        </button>
                    </div>
                    
                    @foreach($cartData['items'] as $item)
                    <div class="cart-item" data-product-id="{{ $item['product_id'] }}">
                        <div class="item-image">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                        </div>
                        
                        <div class="item-info">
                            <h4>{{ $item['name'] }}</h4>
                            <div class="item-meta">
                                <span class="item-type">{{ $item['type'] }}</span>
                                <span class="item-size">{{ $item['size'] }}</span>
                            </div>
                            <div class="item-category">{{ ucfirst($item['category']) }}</div>
                        </div>
                        
                        <div class="item-controls">
                            <div class="quantity-controls">
                                <button class="qty-btn" onclick="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] - 1 }})">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="quantity">{{ $item['quantity'] }}</span>
                                <button class="qty-btn" onclick="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] + 1 }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="item-price">
                            <div class="unit-price">{{ number_format($item['price'], 2) }}€</div>
                            <div class="total-price">{{ number_format($item['total'], 2) }}€</div>
                        </div>
                        
                        <div class="item-actions">
                            <button class="remove-btn" onclick="removeItem({{ $item['product_id'] }})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <div class="summary-card">
                        <h3>Récapitulatif</h3>
                        
                        <div class="summary-row">
                            <span>Sous-total</span>
                            <span>{{ number_format($cartData['subtotal'], 2) }}€</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>TVA (20%)</span>
                            <span>{{ number_format($cartData['tax_amount'], 2) }}€</span>
                        </div>
                        
                        <div class="summary-row shipping">
                            <span>
                                Livraison
                                @if($cartData['shipping_amount'] == 0)
                                    <small class="free-shipping">Gratuite !</small>
                                @endif
                            </span>
                            <span>
                                @if($cartData['shipping_amount'] > 0)
                                    {{ number_format($cartData['shipping_amount'], 2) }}€
                                @else
                                    <span class="free-text">Gratuite</span>
                                @endif
                            </span>
                        </div>
                        
                        @if($cartData['subtotal'] < 150 && $cartData['shipping_amount'] > 0)
                        <div class="free-shipping-notice">
                            <i class="fas fa-info-circle"></i>
                            Plus que {{ number_format(150 - $cartData['subtotal'], 2) }}€ pour la livraison gratuite !
                        </div>
                        @endif
                        
                        <div class="summary-total">
                            <span>Total</span>
                            <span>{{ number_format($cartData['total'], 2) }}€</span>
                        </div>
                        
                        <div class="checkout-actions">
                            <a href="{{ route('payment.checkout') }}" class="btn-checkout">
                                <i class="fas fa-lock"></i>
                                Paiement Sécurisé
                            </a>
                            <a href="/" class="btn-continue">
                                <i class="fas fa-arrow-left"></i>
                                Continuer mes achats
                            </a>
                        </div>
                    </div>                    
                    <!-- Trust Badges -->
                    <div class="trust-badges">
                        <div class="trust-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Paiement Sécurisé</strong>
                                <small>SSL & 3D Secure</small>
                            </div>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-truck"></i>
                            <div>
                                <strong>Livraison Express</strong>
                                <small>24-48h en France</small>
                            </div>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-undo"></i>
                            <div>
                                <strong>Retours Gratuits</strong>
                                <small>30 jours satisfait</small>
                            </div>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-certificate"></i>
                            <div>
                                <strong>Authenticité</strong>
                                <small>100% Garantie</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h2>Votre panier est vide</h2>
                <p>Découvrez nos parfums d'exception et créez votre collection personnelle.</p>
                
                <div class="empty-cart-actions">
                    <a href="/" class="btn-discover">
                        <i class="fas fa-search"></i>
                        Découvrir nos parfums
                    </a>
                </div>
                
                <!-- Featured Products for Empty Cart -->
                <div class="suggested-products">
                    <h3>Nos recommandations</h3>
                    <div class="suggestions-grid">
                        <!-- This would be populated with featured products -->
                        <div class="suggestion-card">
                            <div class="suggestion-image">
                                <img src="https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Parfum">
                            </div>
                            <div class="suggestion-info">
                                <h4>Éternelle Rose</h4>
                                <p>Eau de Parfum - 50ml</p>
                                <div class="suggestion-price">89,00€</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 0 || newQuantity > 10) return;
    
    if (newQuantity === 0) {
        removeItem(productId);
        return;
    }

    // Show loading state
    const cartItem = document.querySelector(`[data-product-id="${productId}"]`);
    cartItem.style.opacity = '0.6';

    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count in header
            updateCartCount(data.cart_count);
            
            // Reload page to update all prices and totals
            location.reload();
        } else {
            showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
            cartItem.style.opacity = '1';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur lors de la mise à jour', 'error');
        cartItem.style.opacity = '1';
    });
}

function removeItem(productId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;

    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cart_count);
            location.reload();
        } else {
            showNotification(data.message || 'Erreur lors de la suppression', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur lors de la suppression', 'error');
    });
}

function clearCart() {
    if (!confirm('Êtes-vous sûr de vouloir vider votre panier ?')) return;

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
            updateCartCount(0);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur lors du vidage du panier', 'error');
    });
}

function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'flex' : 'none';
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 350px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #28a745, #20c997)' : 'linear-gradient(135deg, #dc3545, #e74c3c)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="font-size: 1.2rem;"></i>
            <span style="flex: 1;">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.style.transform = 'translateX(0)', 100);
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

// Update cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => updateCartCount(data.count))
        .catch(error => console.error('Error updating cart count:', error));
});
</script>
@endpush