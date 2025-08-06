@extends('layouts.app')

@section('title', 'Catalogue Complet - Heritage Parfums')
@section('description', 'Découvrez notre catalogue complet de parfums de niche et cosmétiques de luxe. Heritage Parfums, la quintessence de l\'élégance française.')

@section('content')
<div class="container mt-5 pt-5">
    <!-- Hero Section -->
    <div class="hero-section text-center mb-5">
        <h1 class="display-4 font-serif mb-3">Notre Catalogue</h1>
        <p class="lead text-muted">Découvrez l'intégralité de nos créations d'exception</p>
    </div>

    <!-- Filters Section -->
    <div class="filters-section mb-4">
        <div class="row">
            <div class="col-md-8">
                <form method="GET" action="{{ route('catalogue') }}" class="row g-3">
                    <!-- Search -->
                    <div class="col-md-4">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Rechercher un produit..." 
                               value="{{ request('search') }}">
                    </div>

                    <!-- Product Type -->
                    <div class="col-md-3">
                        <select name="product_type" class="form-select">
                            <option value="">Tous les produits</option>
                            <option value="parfum" {{ request('product_type') === 'parfum' ? 'selected' : '' }}>
                                Parfums
                            </option>
                            <option value="cosmetique" {{ request('product_type') === 'cosmetique' ? 'selected' : '' }}>
                                Cosmétiques
                            </option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div class="col-md-3">
                        <select name="category" class="form-select" id="categorySelect">
                            <option value="">Toutes les catégories</option>
                            @if(request('product_type') === 'cosmetique')
                                @foreach($cosmetiqueCategories as $key => $label)
                                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            @else
                                @foreach($parfumCategories as $key => $label)
                                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sort -->
            <div class="col-md-4">
                <form method="GET" action="{{ route('catalogue') }}" class="d-flex">
                    @foreach(request()->except('sort') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>
                            Nom (A-Z)
                        </option>
                        <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>
                            Prix croissant
                        </option>
                        <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>
                            Prix décroissant
                        </option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                            Plus récents
                        </option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Info -->
    <div class="results-info mb-4">
        <p class="text-muted">
            {{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }} trouvé{{ $products->total() > 1 ? 's' : '' }}
            @if(request('search'))
                pour "{{ request('search') }}"
            @endif
        </p>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="product-card h-100">
                        <div class="product-image-container">
                            <img src="{{ $product->main_image }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image">
                            
                            @if($product->badge)
                                <span class="product-badge">{{ $product->badge }}</span>
                            @endif
                            
                            @if($product->product_type === 'cosmetique')
                                <span class="product-type-badge">Cosmétique</span>
                            @endif
                        </div>
                        
                        <div class="product-info">
                            <h5 class="product-name">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h5>
                            
                            <p class="product-category text-muted">
                                {{ $product->category_label }}
                            </p>
                            
                            @if($product->short_description)
                                <p class="product-description">
                                    {{ Str::limit($product->short_description, 80) }}
                                </p>
                            @endif
                            
                            <div class="product-details">
                                <span class="product-type">{{ $product->type }}</span>
                                <span class="product-size">{{ $product->size }}</span>
                            </div>
                            
                            <!-- Mini aperçu des notes olfactives -->
                            @include('components.mini-olfactory-notes', ['product' => $product])
                            
                            <div class="product-footer">
                                <div class="product-price">{{ $product->formatted_price }}</div>
                                
                                @if($product->stock > 0)
                                    <button class="btn btn-add-to-cart" 
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ $product->price }}">
                                        <i class="fas fa-shopping-bag"></i>
                                        Ajouter
                                    </button>
                                @else
                                    <span class="text-muted">Stock épuisé</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h3>Aucun produit trouvé</h3>
                <p class="text-muted">Essayez de modifier vos critères de recherche</p>
                <a href="{{ route('catalogue') }}" class="btn btn-outline-primary">
                    Voir tous les produits
                </a>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 60px 0;
    margin: 0 -15px 40px -15px;
    border-radius: 15px;
}

.filters-section {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.product-image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #d4af37;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.product-type-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #6c757d;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.product-info {
    padding: 25px;
}

.product-name a {
    color: #333;
    text-decoration: none;
    font-weight: 600;
}

.product-name a:hover {
    color: #d4af37;
}

.product-category {
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.product-description {
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 15px;
}

.product-details {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    font-size: 0.85rem;
    color: #666;
}

.product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.product-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #d4af37;
}

.btn-add-to-cart {
    background: #d4af37;
    border: none;
    color: white;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-add-to-cart:hover {
    background: #b8941f;
    transform: translateY(-1px);
}

.empty-state {
    padding: 60px 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        padding: 40px 0;
    }
    
    .filters-section {
        padding: 20px;
    }
    
    .product-info {
        padding: 20px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Dynamic category update based on product type
document.querySelector('select[name="product_type"]').addEventListener('change', function() {
    const productType = this.value;
    const categorySelect = document.getElementById('categorySelect');
    
    // Clear current options
    categorySelect.innerHTML = '<option value="">Toutes les catégories</option>';
    
    if (productType === 'parfum') {
        @foreach($parfumCategories as $key => $label)
            categorySelect.innerHTML += '<option value="{{ $key }}">{{ $label }}</option>';
        @endforeach
    } else if (productType === 'cosmetique') {
        @foreach($cosmetiqueCategories as $key => $label)
            categorySelect.innerHTML += '<option value="{{ $key }}">{{ $label }}</option>';
        @endforeach
    }
});

// Add to cart functionality
document.querySelectorAll('.btn-add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const productName = this.dataset.productName;
        const productPrice = this.dataset.productPrice;
        
        // Add to cart logic (adapt based on your cart implementation)
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                updateCartCount();
                
                // Show success feedback
                this.innerHTML = '<i class="fas fa-check"></i> Ajouté';
                this.classList.add('btn-success');
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-shopping-bag"></i> Ajouter';
                    this.classList.remove('btn-success');
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });
});
</script>
@endpush
@endsection
