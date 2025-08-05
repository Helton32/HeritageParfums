@extends('layouts.app')

@section('title', 'Gestion des Produits - Administration')

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
        border-radius: 0;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: var(--guerlain-light-gray);
    }

    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--guerlain-gold);
        color: var(--guerlain-white);
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-card {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 1.5rem;
        text-align: center;
        position: relative;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--guerlain-gold);
    }

    .stat-number {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 300;
        color: var(--guerlain-black);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: var(--guerlain-text-gray);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }

    .filter-card {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .btn-guerlain {
        background: var(--guerlain-gold);
        border: 1px solid var(--guerlain-gold);
        color: var(--guerlain-white);
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0.75rem 2rem;
        transition: all 0.3s ease;
    }

    .btn-guerlain:hover {
        background: var(--guerlain-black);
        border-color: var(--guerlain-black);
        color: var(--guerlain-white);
    }

    .table-responsive {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
    }

    .table th {
        background: var(--guerlain-light-gray);
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        color: var(--guerlain-text-gray);
        padding: 1rem;
    }

    .table td {
        border: none;
        border-bottom: 1px solid var(--guerlain-border);
        padding: 1rem;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<!-- Admin Header -->
<section class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Gestion des Produits</h1>
                <p class="lead mb-0">Administration Heritage Parfums</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.products.create') }}" class="btn btn-guerlain">
                    <i class="fas fa-plus me-2"></i>Nouveau Produit
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Admin Content -->
<section class="admin-content">
    <div class="container-fluid">
        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number text-success">{{ $stats['active'] }}</div>
                    <div class="stat-label">Actifs</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number text-danger">{{ $stats['inactive'] }}</div>
                    <div class="stat-label">Inactifs</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number text-warning">{{ $stats['featured'] }}</div>
                    <div class="stat-label">En vedette</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number text-info">{{ $stats['out_of_stock'] }}</div>
                    <div class="stat-label">Rupture</div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nom, description...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="category" class="form-label">Catégorie</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Toutes</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactifs</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="sort" class="form-label">Trier par</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date création</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                            <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Prix</option>
                            <option value="stock" {{ request('sort') === 'stock' ? 'selected' : '' }}>Stock</option>
                        </select>
                    </div>
                    <div class="col-md-1 mb-3">
                        <label for="order" class="form-label">Ordre</label>
                        <select class="form-select" id="order" name="order">
                            <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>↓</option>
                            <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>↑</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-guerlain w-100">
                            <i class="fas fa-search me-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des produits -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="position-relative">
                                    <img src="{{ $product->main_image }}" 
                                         alt="{{ $product->name }}" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    @if($product->is_featured)
                                        <span class="badge bg-warning position-absolute top-0 end-0"
                                              style="font-size: 0.6rem; transform: translate(50%, -50%);">⭐</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <h6 class="mb-1">{{ $product->name }}</h6>
                                <small class="text-muted">{{ $product->type }} - {{ $product->size }}</small>
                                @if($product->badge)
                                    <br><span class="badge bg-info mt-1">{{ $product->badge }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $product->category_label }}</span>
                            </td>
                            <td>
                                <strong>{{ $product->formatted_price }}</strong>
                            </td>
                            <td>
                                @if($product->stock <= 0)
                                    <span class="badge bg-danger">Rupture</span>
                                @elseif($product->stock <= 5)
                                    <span class="badge bg-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-{{ $product->is_active ? 'warning' : 'success' }}"
                                            onclick="toggleStatus({{ $product->id }})"
                                            title="{{ $product->is_active ? 'Désactiver' : 'Activer' }}">
                                        <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteProduct({{ $product->id }})"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucun produit trouvé</h5>
                                <p class="text-muted">Commencez par créer votre premier produit.</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-guerlain">
                                    <i class="fas fa-plus me-2"></i>Créer un produit
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Formulaires cachés pour les actions -->
<form id="toggleStatusForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function toggleStatus(productId) {
    if (confirm('Voulez-vous vraiment changer le statut de ce produit ?')) {
        const form = document.getElementById('toggleStatusForm');
        form.action = `/admin/products/${productId}/toggle-status`;
        form.submit();
    }
}

function deleteProduct(productId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/products/${productId}`;
        form.submit();
    }
}
</script>
@endsection
