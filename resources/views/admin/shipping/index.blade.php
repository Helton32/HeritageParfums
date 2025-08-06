@extends('layouts.app')

@section('title', 'Gestion des Expéditions - Heritage Parfums Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
<style>
.order-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 1rem;
    transition: box-shadow 0.2s ease;
}

.order-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.order-header {
    background: linear-gradient(135deg, var(--guerlain-gold), #d4af37);
    color: white;
    padding: 15px 20px;
    border-radius: 8px 8px 0 0;
}

.order-body {
    padding: 20px;
}

.status-badge {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-weight: 500;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-assigned {
    background-color: #cce5ff;
    color: #004085;
}

.status-ready {
    background-color: #d4edda;
    color: #155724;
}

.carrier-select {
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 6px;
    padding: 15px;
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-gold {
    background: linear-gradient(135deg, var(--guerlain-gold), #d4af37);
    border: none;
    color: white;
}

.btn-gold:hover {
    background: linear-gradient(135deg, #d4af37, var(--guerlain-gold));
    color: white;
}
</style>
@endpush

@section('content')
<!-- Admin Header -->
<section class="admin-header">
    <div class="container">
        <div class="text-center">
            <h1>Gestion des Expéditions</h1>
            <p class="lead">Interface moderne Heritage Parfums</p>
        </div>
    </div>
</section>

<!-- Admin Content -->
<section class="admin-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Admin -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card admin-sidebar">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Navigation</h5>
                        <i class="fas fa-user-shield"></i>
                    </div>
                    
                    <div class="admin-user-profile">
                        <div class="admin-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h6 class="admin-username">{{ Auth::user()->name }}</h6>
                        <small class="admin-role">Administrateur</small>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-bottle-droplet me-2"></i>Produits
                        </a>
                        <a href="{{ route('admin.contacts.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-envelope me-2"></i>Messages
                        </a>
                        <a href="{{ route('admin.shipping.index') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-shipping-fast me-2"></i>Expéditions
                            @php $pendingShipments = $orders->count(); @endphp
                            @if($pendingShipments > 0)
                                <span class="badge bg-warning float-end">{{ $pendingShipments }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.shipping.statistics') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </a>
                        <hr class="my-2">
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-store me-2"></i>Boutique
                        </a>
                        <hr class="my-2">
                        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="list-group-item list-group-item-action text-danger border-0 w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contenu principal -->
            <div class="col-lg-9 col-md-8">
                <!-- En-tête avec statistiques -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h2 class="mb-3">
                            <i class="fas fa-shipping-fast me-2" style="color: var(--guerlain-gold);"></i>
                            Commandes à Expédier
                        </h2>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="stat-card border-left-warning">
                            <div class="stat-value">{{ $orders->count() }}</div>
                            <div class="stat-label">En attente d'expédition</div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($orders->count() > 0)
                    @foreach($orders as $order)
                    <div class="order-card">
                        <div class="order-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">
                                        <i class="fas fa-box me-2"></i>{{ $order->order_number }}
                                    </h5>
                                    <small>{{ $order->created_at->format('d/m/Y à H:i') }}</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-1">{{ $order->formatted_total }}</h5>
                                    @if($order->hasTrackingNumber())
                                        <span class="status-badge status-ready">
                                            <i class="fas fa-check me-1"></i>Prêt à expédier
                                        </span>
                                    @elseif($order->shipping_carrier)
                                        <span class="status-badge status-assigned">
                                            <i class="fas fa-truck me-1"></i>Transporteur assigné
                                        </span>
                                    @else
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="order-body">
                            <div class="row">
                                <!-- Informations client -->
                                <div class="col-md-4 mb-3">
                                    <h6><i class="fas fa-user me-2" style="color: var(--guerlain-gold);"></i>Client</h6>
                                    <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                                    <p class="mb-1 text-muted">{{ $order->customer_email }}</p>
                                    <p class="mb-0 text-muted">{{ $order->customer_phone }}</p>
                                </div>

                                <!-- Adresse de livraison -->
                                <div class="col-md-4 mb-3">
                                    <h6><i class="fas fa-map-marker-alt me-2" style="color: var(--guerlain-gold);"></i>Livraison</h6>
                                    <p class="mb-1">{{ $order->shipping_address_line_1 }}</p>
                                    @if($order->shipping_address_line_2)
                                        <p class="mb-1">{{ $order->shipping_address_line_2 }}</p>
                                    @endif
                                    <p class="mb-0">{{ $order->shipping_postal_code }} {{ $order->shipping_city }}</p>
                                </div>

                                <!-- Transporteur et suivi -->
                                <div class="col-md-4 mb-3">
                                    <h6><i class="fas fa-shipping-fast me-2" style="color: var(--guerlain-gold);"></i>Transport</h6>
                                    @if($order->shipping_carrier)
                                        <p class="mb-1"><strong>{{ $order->carrier_name }}</strong></p>
                                        @if($order->shipping_method)
                                            <p class="mb-1 text-muted">{{ $order->shipping_method }}</p>
                                        @endif
                                        @if($order->tracking_number)
                                            <p class="mb-0">
                                                <small class="text-muted">Suivi:</small> 
                                                <code>{{ $order->tracking_number }}</code>
                                            </p>
                                        @endif
                                    @else
                                        <div class="carrier-select">
                                            <i class="fas fa-truck fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Aucun transporteur assigné</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Articles de la commande -->
                            <div class="mb-3">
                                <h6><i class="fas fa-list me-2" style="color: var(--guerlain-gold);"></i>Articles ({{ $order->items->count() }})</h6>
                                <div class="row">
                                    @foreach($order->items as $item)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bottle-droplet me-2 text-muted"></i>
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product_size)
                                                    <small class="text-muted">({{ $item->product_size }})</small>
                                                @endif
                                                <br>
                                                <small class="text-muted">Qté: {{ $item->quantity }} × {{ number_format($item->product_price, 2) }}€</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="action-buttons">
                                <a href="{{ route('admin.shipping.show', $order) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Détails
                                </a>

                                @if(!$order->shipping_carrier)
                                    <!-- Bouton pour assigner transporteur -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignCarrier{{ $order->id }}">
                                        <i class="fas fa-truck me-1"></i>Assigner Transporteur
                                    </button>
                                @endif

                                @if($order->shipping_carrier && !$order->hasTrackingNumber())
                                    <!-- Bouton pour générer le bon de livraison -->
                                    <form method="POST" action="{{ route('admin.shipping.generate-label', $order) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-file-alt me-1"></i>Générer Bon
                                        </button>
                                    </form>
                                @endif

                                @if($order->shipping_label_path)
                                    <!-- Bouton pour télécharger le bon -->
                                    <a href="{{ route('admin.shipping.download-label', $order) }}" class="btn btn-success">
                                        <i class="fas fa-download me-1"></i>Télécharger
                                    </a>
                                @endif

                                @if($order->hasTrackingNumber())
                                    <!-- Bouton pour marquer comme expédié -->
                                    <form method="POST" action="{{ route('admin.shipping.mark-shipped', $order) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-gold"
                                                onclick="return confirm('Marquer comme expédiée et envoyer l\'email de suivi au client ?')">
                                            <i class="fas fa-paper-plane me-1"></i>Marquer Expédiée
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Modal d'assignation de transporteur -->
                    @if(!$order->shipping_carrier)
                    <div class="modal fade" id="assignCarrier{{ $order->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Assigner un Transporteur</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.shipping.assign-carrier', $order) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Transporteur</label>
                                            <select name="shipping_carrier" class="form-select" required>
                                                <option value="">Choisir un transporteur...</option>
                                                <option value="colissimo">Colissimo</option>
                                                <option value="chronopost">Chronopost</option>
                                                <option value="mondialrelay">Mondial Relay</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Méthode</label>
                                            <select name="shipping_method" class="form-select" required>
                                                <option value="">Choisir une méthode...</option>
                                                <option value="standard">Standard (2-3 jours)</option>
                                                <option value="express">Express (24h)</option>
                                                <option value="relay">Point relais</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Assigner</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @else
                    <!-- Message quand aucune commande -->
                    <div class="text-center py-5">
                        <i class="fas fa-shipping-fast fa-4x mb-3" style="color: var(--guerlain-gold);"></i>
                        <h4>Toutes les commandes sont traitées ! 🎉</h4>
                        <p class="text-muted mb-4">Aucune commande en attente d'expédition.</p>
                        
                        <div class="action-buttons justify-content-center">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-home me-1"></i>Retour au Dashboard
                            </a>
                            <a href="{{ route('admin.shipping.statistics') }}" class="btn btn-outline-info">
                                <i class="fas fa-chart-bar me-1"></i>Voir les Statistiques
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
// Auto-refresh de la page toutes les 5 minutes pour voir les nouvelles commandes
setInterval(function() {
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 300000); // 5 minutes
</script>
@endsection
