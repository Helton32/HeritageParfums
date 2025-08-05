@extends('layouts.app')

@section('title')
@switch($category)
    @case('femme')
        Parfums Femme - Heritage Parfums
        @break
    @case('homme')
        Parfums Homme - Heritage Parfums
        @break
    @case('exclusifs')
        Collections Exclusives - Heritage Parfums
        @break
    @case('nouveautes')
        Nouveautés - Heritage Parfums
        @break
    @default
        Collection - Heritage Parfums
@endswitch
@endsection

@section('description')
@switch($category)
    @case('femme')
        Découvrez les parfums femme Heritage Parfums : fragrances élégantes et sensuelles pour révéler votre féminité.
        @break
    @case('homme')
        Collection de parfums homme Heritage Parfums : compositions masculines raffinées et puissantes.
        @break
    @case('exclusifs')
        Collections exclusives Heritage Parfums : créations rares et précieuses en édition limitée.
        @break
    @case('nouveautes')
        Nouveautés parfums Heritage Parfums : découvrez nos dernières créations olfactives.
        @break
    @default
        Découvrez la collection Heritage Parfums.
@endswitch
@endsection

@push('styles')
<style>
    .collection-hero {
        height: 70vh;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        margin-top: 80px;
        position: relative;
    }

    .collection-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .product-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        height: 320px;
        background-size: cover;
        background-position: center;
        position: relative;
        overflow: hidden;
    }

    .product-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary-gold);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(184, 134, 11, 0.9);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .product-info {
        padding: 1.5rem;
        text-align: center;
    }

    .product-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        color: var(--primary-gold);
        margin-bottom: 0.5rem;
    }

    .product-category {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .product-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--deep-black);
        margin-bottom: 1rem;
    }

    .sort-filters {
        background: var(--light-gray);
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }

    .sort-select {
        border: 1px solid var(--primary-gold);
        border-radius: 5px;
        padding: 8px 15px;
    }

    .empty-collection {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-collection-icon {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<!-- Collection Hero -->
<section class="collection-hero" style="background-image: url('@switch($category)
    @case('femme')
        https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80
        @break
    @case('homme')
        https://images.unsplash.com/photo-1615634260167-c8cdede054de?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80
        @break
    @case('exclusifs')
        https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80
        @break
    @case('nouveautes')
        https://images.unsplash.com/photo-1557170334-a9632e77c6e4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80
        @break
    @default
        https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80
@endswitch');">
    <div class="container">
        <div class="hero-content fade-in">
            <h1 class="font-playfair display-3 mb-3">
                @switch($category)
                    @case('femme')
                        Parfums Femme
                        @break
                    @case('homme')
                        Parfums Homme
                        @break
                    @case('exclusifs')
                        Collections Exclusives
                        @break
                    @case('nouveautes')
                        Nouveautés
                        @break
                    @default
                        Collection
                @endswitch
            </h1>
            <p class="lead">
                @switch($category)
                    @case('femme')
                        L'élégance féminine révélée par des fragrances d'exception
                        @break
                    @case('homme')
                        La sophistication masculine dans chaque note
                        @break
                    @case('exclusifs')
                        Des créations rares pour les connaisseurs
                        @break
                    @case('nouveautes')
                        Les dernières innovations olfactives de nos maîtres parfumeurs
                        @break
                    @default
                        Découvrez notre collection
                @endswitch
            </p>
        </div>
    </div>
</section>

<!-- Sort & Filter -->
<section class="sort-filters">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="text-muted" id="product-count">{{ $products->total() }} parfum{{ $products->total() > 1 ? 's' : '' }}</span>
            </div>
            <div class="col-md-6 text-md-end">
                <select class="sort-select" id="sort-products" onchange="sortProducts()">
                    <option value="name">Trier par nom</option>
                    <option value="price-asc">Prix croissant</option>
                    <option value="price-desc">Prix décroissant</option>
                    <option value="newest">Plus récents</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-5">
    <div class="container">
        @if($products->count() > 0)
            <div class="product-grid" id="products-grid">
                @foreach($products as $product)
                <div class="product-card fade-in" data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                    <div class="product-image" style="background-image: url('{{ $product->main_image }}');">
                        @if($product->badge)
                            <div class="product-badge">{{ $product->badge }}</div>
                        @endif
                        <div class="product-overlay">
                            <a href="/product/{{ $product->slug }}" class="btn btn-outline-light me-2">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                            <button class="btn btn-light add-to-cart" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                <i class="fas fa-shopping-bag"></i> Ajouter
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-category">{{ $product->type }} - {{ $product->size }}</p>
                        <p class="product-price">{{ $product->formatted_price }}</p>
                        <div class="d-flex flex-column gap-2">
                            @if($product->isInStock())
                                <button class="btn btn-outline-gold add-to-cart" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                    <i class="fas fa-shopping-bag me-1"></i>Ajouter au Panier
                                </button>
                                <a href="/product/{{ $product->slug }}" class="btn btn-outline-secondary btn-sm">
                                    Voir Détails
                                </a>
                            @else
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="fas fa-times me-1"></i>Rupture de Stock
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        @else
            <div class="empty-collection">
                <div class="empty-collection-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="font-playfair text-gold mb-3">Aucun produit trouvé</h3>
                <p class="text-muted mb-4">Il n'y a actuellement aucun parfum dans cette catégorie.</p>
                <a href="/collections" class="btn btn-gold">
                    <i class="fas fa-arrow-left me-2"></i>Voir toutes les Collections
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Collection Story -->
<section class="py-5 bg-cream">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 fade-in">
                <h2 class="font-playfair text-gold mb-4">
                    @switch($category)
                        @case('femme')
                            L'Essence de la Féminité
                            @break
                        @case('homme')
                            L'Art de la Masculinité
                            @break
                        @case('exclusifs')
                            Créations d'Exception
                            @break
                        @case('nouveautes')
                            Innovation Olfactive
                            @break
                        @default
                            Notre Savoir-Faire
                    @endswitch
                </h2>
                <p class="lead">
                    @switch($category)
                        @case('femme')
                            Nos parfums pour femme célèbrent la diversité et la complexité de la féminité moderne.
                            @break
                        @case('homme')
                            Chaque fragrance masculine Heritage Parfums exprime force, élégance et sophistication.
                            @break
                        @case('exclusifs')
                            Nos collections exclusives représentent l'apogée de notre art parfumeur.
                            @break
                        @case('nouveautes')
                            Nos dernières créations repoussent les limites de l'innovation olfactive.
                            @break
                        @default
                            Découvrez l'histoire et la philosophie derrière nos créations.
                    @endswitch
                </p>
                <p>
                    @switch($category)
                        @case('femme')
                            De la fraîcheur florale aux compositions orientales les plus sophistiquées, chaque parfum raconte une histoire unique et révèle une facette de votre personnalité.
                            @break
                        @case('homme')
                            Des bois nobles aux épices rares, nos compositions masculines allient tradition et modernité pour créer des signatures olfactives inoubliables.
                            @break
                        @case('exclusifs')
                            Utilisant les matières premières les plus rares et précieuses, ces créations sont réservées aux véritables connaisseurs.
                            @break
                        @case('nouveautes')
                            En quête perpétuelle d'innovation, nos maîtres parfumeurs explorent de nouvelles voies olfactives pour créer les parfums de demain.
                            @break
                        @default
                            Chaque création Heritage Parfums est le fruit d'un savoir-faire exceptionnel et d'une passion pour l'excellence.
                    @endswitch
                </p>
            </div>
            <div class="col-lg-6 fade-in">
                <img src="@switch($category)
                    @case('femme')
                        https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80
                        @break
                    @case('homme')
                        https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80
                        @break
                    @case('exclusifs')
                        https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80
                        @break
                    @case('nouveautes')
                        https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80
                        @break
                    @default
                        https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80
                @endswitch" 
                     class="img-fluid rounded shadow" alt="Heritage Parfums">
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            
            // Disable button temporarily
            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Ajout...';
            
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
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                    }
                    
                    // Show success message
                    this.innerHTML = '<i class="fas fa-check me-1"></i>Ajouté !';
                    this.classList.add('btn-success');
                    this.classList.remove('btn-outline-gold', 'btn-light');
                    
                    // Show notification
                    showNotification(data.message, 'success');
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-outline-gold');
                        this.disabled = false;
                    }, 2000);
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
    });
});

function sortProducts() {
    const sortBy = document.getElementById('sort-products').value;
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('sort', sortBy);
    window.location.href = currentUrl.toString();
}

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
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
    
    // Close button functionality
    notification.querySelector('.btn-close').addEventListener('click', () => {
        notification.remove();
    });
}
</script>
@endpush