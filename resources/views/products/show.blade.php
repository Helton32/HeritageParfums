@extends('layouts.app')

@section('title', $product->name . ' - Héritaj Parfums')
@section('description', $product->short_description)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endpush

@section('content')
<!-- Product Details -->
<section class="product-hero">
    <div class="container">
        <div class="row">
            <!-- Product Images Gallery -->
            <div class="col-lg-7">
                <div class="product-gallery">
                    <!-- Main Image -->
                    <div class="main-image-container">
                        <div class="main-image" id="mainImage">
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="img-fluid">
                            @if($product->badge)
                                <div class="product-badge">{{ $product->badge }}</div>
                            @endif
                        </div>
                        
                        <!-- Image Navigation -->
                        <div class="image-nav">
                            <button class="nav-btn prev-btn" id="prevBtn">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="nav-btn next-btn" id="nextBtn">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Thumbnail Images -->
                    <div class="thumbnail-container">
                        <div class="thumbnail-wrapper">
                            @php
                                $allImages = array_merge([$product->main_image], $product->images ?? []);
                                $allImages = array_unique($allImages);
                            @endphp
                            
                            @foreach($allImages as $index => $image)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                 data-index="{{ $index }}" 
                                 onclick="changeMainImage('{{ $image }}', {{ $index }})">
                                <img src="{{ $image }}" alt="{{ $product->name }} - Image {{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-5">
                <div class="product-info">
                    <!-- Breadcrumb -->
                    <nav class="product-breadcrumb">
                        <a href="/">Accueil</a>
                        <span>/</span>
                        <span>{{ $product->category_label }}</span>
                        <span>/</span>
                        <span>{{ $product->name }}</span>
                    </nav>
                    
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <div class="product-meta">
                        <span class="product-type">{{ $product->type }}</span>
                        <span class="product-size">{{ $product->size }}</span>
                    </div>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="rating-text">(4.9/5 - 127 avis)</span>
                    </div>
                    
                    @if($product->hasValidPromotion())
                        <div class="product-promotion">
                            <h5 class="promotion-title">🎯 Offre Spéciale</h5>
                            <div class="promotion-prices">
                                <span class="original-price-detail">{{ $product->formatted_price }}</span>
                                <span class="promotion-price-detail">{{ $product->formatted_current_price }}</span>
                                <span class="discount-badge-detail">-{{ $product->getDiscountPercentage() }}%</span>
                            </div>
                            @if($product->promotion_description)
                                <p class="promotion-description">{{ $product->promotion_description }}</p>
                            @endif
                        </div>
                    @else
                        <div class="product-price">{{ $product->formatted_price }}</div>
                    @endif
                    
                    <!-- Stock Info -->
                    <div class="stock-info">
                        @if($product->stock > 10)
                            <span class="stock-available">
                                <i class="fas fa-check-circle"></i>En stock
                            </span>
                        @elseif($product->stock > 0)
                            <span class="stock-low">
                                <i class="fas fa-exclamation-triangle"></i>Stock limité ({{ $product->stock }} restants)
                            </span>
                        @else
                            <span class="stock-out">
                                <i class="fas fa-times-circle"></i>Rupture de stock
                            </span>
                        @endif
                    </div>                    
                    @if($product->isInStock())
                        <!-- Quantity Selector -->
                        <div class="quantity-section">
                            <label>Quantité:</label>
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn minus" onclick="decrementQuantity()">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" class="qty-input" value="1" 
                                       min="1" max="{{ min($product->stock, 10) }}" readonly>
                                <button type="button" class="qty-btn plus" onclick="incrementQuantity()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button class="btn-add-cart" id="addToCartBtn" data-product-id="{{ $product->id }}">
                                <i class="fas fa-shopping-bag"></i>
                                <span>Ajouter au Panier</span>
                            </button>
                            <button class="btn-apple-pay" id="applePayBtn" data-product-id="{{ $product->id }}">
                                <i class="fab fa-apple"></i>
                                <span>Payer avec Apple Pay</span>
                            </button>
                            <button class="btn-wishlist" onclick="toggleWishlist({{ $product->id }})">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    @else
                        <button class="btn-unavailable" disabled>
                            <i class="fas fa-times"></i>Produit Indisponible
                        </button>
                    @endif
                    
                    <!-- Product Features -->
                    <div class="product-features">
                        <div class="feature">
                            <i class="fas fa-truck"></i>
                            <div>
                                <strong>Livraison Express</strong>
                                <small>Gratuite dès 150€</small>
                            </div>
                        </div>
                        <div class="feature">
                            <i class="fas fa-undo"></i>
                            <div>
                                <strong>Retours Gratuits</strong>
                                <small>Sous 30 jours</small>
                            </div>
                        </div>
                        <div class="feature">
                            <i class="fas fa-certificate"></i>
                            <div>
                                <strong>Authenticité</strong>
                                <small>100% Garantie</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Tabs -->
<section class="product-tabs-section">
    <div class="container">
        <div class="product-tabs">
            <nav class="tab-nav">
                <button class="tab-btn active" data-tab="description">Description</button>
                @if($product->product_type === 'parfum')
                    <button class="tab-btn" data-tab="notes">Notes Olfactives</button>
                @endif
                <button class="tab-btn" data-tab="details">Détails</button>
                <button class="tab-btn" data-tab="reviews">Avis (127)</button>
            </nav>
            
            <div class="tab-content">
                <!-- Description Tab -->
                <div class="tab-pane active" id="description">
                    <div class="row">
                        <div class="{{ $product->product_type === 'parfum' ? 'col-lg-8' : 'col-lg-12' }}">
                            <p class="lead">{{ $product->short_description }}</p>
                            <div class="description-text">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                        @if($product->product_type === 'parfum')
                            <div class="col-lg-4">
                                <div class="product-highlights">
                                    <h5>Points Forts</h5>
                                    <ul>
                                        <li>Création artisanale française</li>
                                        <li>Ingrédients de haute qualité</li>
                                        <li>Longue tenue (8-12h)</li>
                                        <li>Sillage exceptionnel</li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($product->product_type === 'parfum')
                    <!-- Notes Tab -->
                    <div class="tab-pane" id="notes">
                        @include('components.olfactory-notes', ['product' => $product])
                    </div>
                @endif                
                <!-- Details Tab -->
                <div class="tab-pane" id="details">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="product-specs">
                                <tr>
                                    <td>Marque</td>
                                    <td>Héritaj Parfums</td>
                                </tr>
                                <tr>
                                    <td>Catégorie</td>
                                    <td>{{ $product->category_label }}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{ $product->type }}</td>
                                </tr>
                                <tr>
                                    <td>Contenance</td>
                                    <td>{{ $product->size }}</td>
                                </tr>
                                <tr>
                                    <td>Concentration</td>
                                    <td>Eau de Parfum (15-20%)</td>
                                </tr>
                                <tr>
                                    <td>Origine</td>
                                    <td>France</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="care-instructions">
                                <h5>Conseils d'utilisation</h5>
                                <ul>
                                    <li>Vaporiser sur les points de pulsation</li>
                                    <li>Éviter de frotter après application</li>
                                    <li>Conserver à l'abri de la lumière</li>
                                    <li>Température idéale : 15-20°C</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-pane" id="reviews">
                    <div class="reviews-section">
                        <div class="reviews-summary">
                            <div class="rating-overview">
                                <div class="average-rating">
                                    <span class="rating-number">4.9</span>
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="total-reviews">127 avis</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="reviews-list">
                            <!-- Sample Review -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <span class="reviewer-name">Marie L.</span>
                                        <div class="review-rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                    <span class="review-date">Il y a 2 jours</span>
                                </div>
                                <p class="review-text">
                                    "Parfum absolument sublime ! La tenue est exceptionnelle et le sillage parfait. 
                                    Je le recommande vivement pour les occasions spéciales."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="related-products-section">
    <div class="container">
        <div class="section-header">
            <h2>Vous pourriez aussi aimer</h2>
            <p>Découvrez d'autres créations de la même collection</p>
        </div>
        
        <div class="products-carousel">
            <div class="products-wrapper">
                @foreach($relatedProducts as $relatedProduct)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ $relatedProduct->main_image }}" alt="{{ $relatedProduct->name }}">
                        @if($relatedProduct->hasValidPromotion())
                            <span class="promotion-badge-related">-{{ $relatedProduct->getDiscountPercentage() }}%</span>
                        @endif
                        @if($relatedProduct->badge)
                            <span class="badge">{{ $relatedProduct->badge }}</span>
                        @endif
                        <div class="product-overlay">
                            <button class="quick-add" data-product-id="{{ $relatedProduct->id }}">
                                <i class="fas fa-shopping-bag"></i>
                            </button>
                            <button class="quick-view" onclick="window.location.href='/product/{{ $relatedProduct->slug }}'">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h4>{{ $relatedProduct->name }}</h4>
                        <p class="product-category">{{ $relatedProduct->type }}</p>
                        <div class="product-rating">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        @if($relatedProduct->hasValidPromotion())
                            <div class="product-price-promo">
                                <span class="original-price-related">{{ $relatedProduct->formatted_price }}</span>
                                <span class="promo-price-related">{{ $relatedProduct->formatted_current_price }}</span>
                            </div>
                        @else
                            <div class="product-price">{{ $relatedProduct->formatted_price }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
// Product Gallery
let currentImageIndex = 0;
const allImages = @json(array_unique(array_merge([$product->main_image], $product->images ?? [])));

function changeMainImage(imageSrc, index) {
    currentImageIndex = index;
    document.querySelector('#mainImage img').src = imageSrc;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
    document.querySelector(`.thumbnail[data-index="${index}"]`).classList.add('active');
}

// Image navigation
document.getElementById('prevBtn').addEventListener('click', () => {
    currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : allImages.length - 1;
    changeMainImage(allImages[currentImageIndex], currentImageIndex);
});

document.getElementById('nextBtn').addEventListener('click', () => {
    currentImageIndex = currentImageIndex < allImages.length - 1 ? currentImageIndex + 1 : 0;
    changeMainImage(allImages[currentImageIndex], currentImageIndex);
});

// Quantity controls
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
// Tabs functionality
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tabId = btn.dataset.tab;
        
        // Remove active from all tabs and panes
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        
        // Add active to clicked tab and corresponding pane
        btn.classList.add('active');
        document.getElementById(tabId).classList.add('active');
    });
});

// Add to cart functionality
document.getElementById('addToCartBtn').addEventListener('click', function() {
    const productId = this.dataset.productId;
    const quantity = document.getElementById('quantity').value;
    
    // Button animation
    const originalText = this.innerHTML;
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Ajout en cours...</span>';
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: parseInt(productId),
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
                cartCount.style.display = 'flex';
            }
            
            // Success animation
            this.innerHTML = '<i class="fas fa-check"></i><span>Ajouté au Panier !</span>';
            this.classList.add('success');
            
            // Show notification
            showNotification(data.message, 'success');
            
            // Reset button after 3 seconds
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('success');
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

// Quick add to cart from related products
document.querySelectorAll('.quick-add').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const productId = this.dataset.productId;
        
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: parseInt(productId),
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                    cartCount.style.display = 'flex';
                }
                
                this.innerHTML = '<i class="fas fa-check"></i>';
                showNotification(data.message, 'success');
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-shopping-bag"></i>';
                }, 2000);
            } else {
                showNotification(data.message, 'error');
                this.innerHTML = '<i class="fas fa-shopping-bag"></i>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de l\'ajout', 'error');
            this.innerHTML = '<i class="fas fa-shopping-bag"></i>';
        });
    });
});

// Wishlist toggle (placeholder)
function toggleWishlist(productId) {
    const btn = document.querySelector('.btn-wishlist');
    const icon = btn.querySelector('i');
    
    if (icon.classList.contains('far')) {
        icon.classList.replace('far', 'fas');
        btn.classList.add('active');
        showNotification('Ajouté aux favoris !', 'success');
    } else {
        icon.classList.replace('fas', 'far');
        btn.classList.remove('active');
        showNotification('Retiré des favoris', 'info');
    }
}

// Apple Pay functionality
document.getElementById('applePayBtn')?.addEventListener('click', function() {
    const productId = this.dataset.productId;
    const quantity = document.getElementById('quantity').value;
    
    // Vérifier si Apple Pay est disponible
    if (window.ApplePaySession && ApplePaySession.canMakePayments()) {
        // Ajouter d'abord le produit au panier
        const originalText = this.innerHTML;
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Préparation Apple Pay...</span>';
        
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: parseInt(productId),
                quantity: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Rediriger vers checkout avec Apple Pay
                window.location.href = '/checkout?payment_method=apple_pay';
            } else {
                showNotification(data.message, 'error');
                this.innerHTML = originalText;
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de la préparation', 'error');
            this.innerHTML = originalText;
            this.disabled = false;
        });
    } else {
        showNotification('Apple Pay n\'est pas disponible sur cet appareil', 'info');
    }
});

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 350px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #28a745, #20c997)' : 
                     type === 'error' ? 'linear-gradient(135deg, #dc3545, #e74c3c)' :
                     'linear-gradient(135deg, #17a2b8, #20c997)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 
                              type === 'error' ? 'exclamation-circle' : 'info-circle'}" 
               style="font-size: 1.2rem;"></i>
            <span style="flex: 1;">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" 
                    style="background: none; border: none; color: white; cursor: pointer;">
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
</script>
@endpush