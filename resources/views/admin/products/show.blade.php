@extends('layouts.app')

@section('title', $product->name . ' - Administration')

@push('styles')
<style>
    .admin-header {
        background: var(--guerlain-black);
        color: var(--guerlain-white);
        padding: 2rem 0;
        margin-top: 80px;
    }

    .admin-header h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 3rem;
        font-weight: 300;
        margin-bottom: 0.5rem;
        color: var(--guerlain-gold);
    }

    .admin-content {
        padding: 4rem 0;
        background: var(--guerlain-light-gray);
        min-height: calc(100vh - 200px);
    }

    .product-card {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 3rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--guerlain-gold);
    }

    .product-gallery {
        margin-bottom: 2rem;
    }

    .main-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border: 1px solid var(--guerlain-border);
        margin-bottom: 1rem;
    }

    .image-thumbnails {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding: 1rem 0;
    }

    .thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 2px solid var(--guerlain-border);
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .thumbnail:hover, .thumbnail.active {
        border-color: var(--guerlain-gold);
    }

    .product-info {
        background: var(--guerlain-light-gray);
        padding: 2rem;
        border: 1px solid var(--guerlain-border);
        margin-bottom: 2rem;
    }

    .product-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 400;
        color: var(--guerlain-black);
        margin-bottom: 1rem;
    }

    .product-price {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        font-weight: 300;
        color: var(--guerlain-gold);
        margin-bottom: 1rem;
    }

    .product-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .meta-item {
        background: var(--guerlain-white);
        padding: 0.5rem 1rem;
        border: 1px solid var(--guerlain-border);
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        font-size: 0.9rem;
    }

    .product-description {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        line-height: 1.8;
        color: var(--guerlain-black);
        margin-bottom: 2rem;
    }

    .notes-section {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .notes-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 400;
        color: var(--guerlain-black);
        margin-bottom: 1rem;
    }

    .notes-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .note-tag {
        background: var(--guerlain-gold);
        color: var(--guerlain-white);
        padding: 0.25rem 0.75rem;
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stats-section {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--guerlain-border);
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .stat-label {
        color: var(--guerlain-text-gray);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }

    .stat-value {
        font-weight: 600;
        color: var(--guerlain-black);
    }

    .action-buttons {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        position: sticky;
        top: 120px;
    }

    .btn-guerlain {
        background: var(--guerlain-gold);
        border: 1px solid var(--guerlain-gold);
        color: var(--guerlain-white);
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1rem 2rem;
        transition: all 0.3s ease;
        width: 100%;
        margin-bottom: 1rem;
    }

    .btn-guerlain:hover {
        background: var(--guerlain-black);
        border-color: var(--guerlain-black);
        color: var(--guerlain-white);
    }

    .btn-secondary {
        background: var(--guerlain-light-gray);
        border: 1px solid var(--guerlain-border);
        color: var(--guerlain-black);
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        padding: 1rem 2rem;
        width: 100%;
        margin-bottom: 1rem;
    }

    .status-indicator {
        position: absolute;
        top: 2rem;
        right: 2rem;
    }

    .badge-large {
        font-size: 1rem;
        padding: 0.5rem 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<!-- Admin Header -->
<section class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>{{ $product->name }}</h1>
                <p class="lead mb-0">Détails du produit</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-guerlain me-2">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Admin Content -->
<section class="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Informations principales -->
                <div class="product-card">
                    <div class="status-indicator">
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }} badge-large">
                            {{ $product->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                        @if($product->is_featured)
                            <span class="badge bg-warning badge-large ms-2">En vedette</span>
                        @endif
                    </div>

                    <!-- Galerie d'images -->
                    <div class="product-gallery">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="main-image" id="mainImage">
                            
                            @if(count($product->images) > 1)
                                <div class="image-thumbnails">
                                    @foreach($product->images as $index => $image)
                                        <img src="{{ $image }}" alt="{{ $product->name }} {{ $index + 1 }}" 
                                             class="thumbnail {{ $index === 0 ? 'active' : '' }}"
                                             onclick="changeMainImage('{{ $image }}', this)">
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="main-image">
                        @endif
                    </div>

                    <!-- Informations produit -->
                    <div class="product-info">
                        <h2 class="product-title">{{ $product->name }}</h2>
                        <div class="product-price">{{ $product->formatted_price }}</div>
                        
                        <div class="product-meta">
                            <div class="meta-item">
                                <strong>Catégorie:</strong> {{ $product->category_label }}
                            </div>
                            <div class="meta-item">
                                <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $product->type)) }}
                            </div>
                            <div class="meta-item">
                                <strong>Taille:</strong> {{ $product->size }}
                            </div>
                            <div class="meta-item">
                                <strong>Stock:</strong> 
                                @if($product->stock <= 0)
                                    <span class="text-danger">Rupture de stock</span>
                                @elseif($product->stock <= 5)
                                    <span class="text-warning">{{ $product->stock }} unités</span>
                                @else
                                    <span class="text-success">{{ $product->stock }} unités</span>
                                @endif
                            </div>
                            @if($product->badge)
                                <div class="meta-item">
                                    <span class="badge bg-info">{{ $product->badge }}</span>
                                </div>
                            @endif
                        </div>

                        @if($product->short_description)
                            <div class="mb-3">
                                <h5>Description courte</h5>
                                <p class="text-muted">{{ $product->short_description }}</p>
                            </div>
                        @endif

                        <div class="product-description">
                            <h5 class="mb-3">Description complète</h5>
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Notes olfactives -->
                @if($product->notes && count($product->notes) > 0)
                    <div class="notes-section">
                        <h3 class="notes-title">
                            <i class="fas fa-leaf me-2 text-success"></i>
                            Notes Olfactives
                        </h3>
                        <div class="notes-list">
                            @foreach($product->notes as $note)
                                @if(is_string($note) && trim($note))
                                    <span class="note-tag">{{ trim($note) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Statistiques -->
                <div class="stats-section">
                    <h3 class="notes-title">
                        <i class="fas fa-chart-bar me-2 text-info"></i>
                        Statistiques
                    </h3>
                    
                    <div class="stat-item">
                        <span class="stat-label">ID Produit</span>
                        <span class="stat-value">#{{ $product->id }}</span>
                    </div>
                    
                    <div class="stat-item">
                        <span class="stat-label">Slug</span>
                        <span class="stat-value">{{ $product->slug }}</span>
                    </div>
                    
                    <div class="stat-item">
                        <span class="stat-label">Date de création</span>
                        <span class="stat-value">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="stat-item">
                        <span class="stat-label">Dernière modification</span>
                        <span class="stat-value">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    @php
                        $totalOrders = $product->orderItems()->count();
                        $totalSold = $product->orderItems()->sum('quantity');
                        $totalRevenue = $product->orderItems()->sum('total_price');
                    @endphp
                    
                    <div class="stat-item">
                        <span class="stat-label">Nombre de commandes</span>
                        <span class="stat-value">{{ $totalOrders }}</span>
                    </div>
                    
                    <div class="stat-item">
                        <span class="stat-label">Quantité vendue</span>
                        <span class="stat-value">{{ $totalSold }}</span>
                    </div>
                    
                    <div class="stat-item">
                        <span class="stat-label">Chiffre d'affaires généré</span>
                        <span class="stat-value">{{ number_format($totalRevenue, 2) }} €</span>
                    </div>
                </div>
            </div>

            <!-- Actions latérales -->
            <div class="col-lg-4">
                <div class="action-buttons">
                    <h5 class="mb-4">Actions</h5>
                    
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-guerlain">
                        <i class="fas fa-edit me-2"></i>Modifier le Produit
                    </a>
                    
                    <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-{{ $product->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }} me-2"></i>
                            {{ $product->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-info">
                            <i class="fas fa-star me-2"></i>
                            {{ $product->is_featured ? 'Retirer vedette' : 'Mettre en vedette' }}
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.products.duplicate', $product) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-copy me-2"></i>Dupliquer
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                    </a>
                    
                    <hr class="my-4">
                    
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>

                <!-- Informations supplémentaires -->
                @if($totalOrders > 0)
                    <div class="action-buttons mt-4">
                        <h5 class="mb-4">Ventes Récentes</h5>
                        
                        @php
                            $recentOrders = $product->orderItems()
                                                  ->with('order')
                                                  ->whereHas('order', function($query) {
                                                      $query->where('payment_status', 'paid');
                                                  })
                                                  ->latest()
                                                  ->take(5)
                                                  ->get();
                        @endphp
                        
                        @foreach($recentOrders as $orderItem)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <small><strong>{{ $orderItem->order->order_number }}</strong></small>
                                    <small>{{ $orderItem->quantity }}x</small>
                                </div>
                                <div class="d-flex justify-content-between text-muted">
                                    <small>{{ $orderItem->order->customer_name }}</small>
                                    <small>{{ $orderItem->order->created_at->format('d/m') }}</small>
                                </div>
                            </div>
                        @endforeach
                        
                        <a href="{{ route('admin.shipping.index') }}?product={{ $product->id }}" 
                           class="btn btn-outline-info btn-sm w-100">
                            Voir toutes les commandes
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
function changeMainImage(imageSrc, thumbnail) {
    document.getElementById('mainImage').src = imageSrc;
    
    // Remove active class from all thumbnails
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    
    // Add active class to clicked thumbnail
    thumbnail.classList.add('active');
}
</script>
@endsection
