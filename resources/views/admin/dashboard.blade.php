@extends('layouts.app')

@section('title', 'Dashboard Administration - Heritage Parfums')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')
<!-- Admin Header -->
<section class="admin-header">
    <div class="container">
        <div class="text-center">
            <h1>Administration</h1>
            <p class="lead">Tableau de bord Heritage Parfums</p>
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
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-bottle-droplet me-2"></i>Produits
                            @php $totalProducts = \App\Models\Product::count(); @endphp
                            @if($totalProducts > 0)
                                <span class="badge bg-info float-end">{{ $totalProducts }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.contacts.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-envelope me-2"></i>Messages
                            @php $unreadContacts = \App\Models\Contact::where('is_read', false)->count(); @endphp
                            @if($unreadContacts > 0)
                                <span class="badge bg-danger float-end">{{ $unreadContacts }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.shipping.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shipping-fast me-2"></i>Expéditions
                            @if($stats['pending_orders'] > 0)
                                <span class="badge bg-warning float-end">{{ $stats['pending_orders'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.shipping.statistics') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </a>
                        <hr class="my-2">
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-store me-2"></i>Boutique
                        </a>
                        <a href="{{ route('demo.shipping') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-play me-2"></i>Démo Système
                        </a>
                        <a href="{{ route('demo.guerlain') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-palette me-2"></i>Design Guerlain
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
                <!-- Cartes de statistiques -->
                <div class="row mb-4">
                    <!-- Commandes en attente -->
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="stat-card border-left-warning">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-value">{{ $stats['pending_orders'] }}</div>
                            <div class="stat-label">Commandes en attente</div>
                        </div>
                    </div>

                    <!-- Produits actifs -->
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="stat-card border-left-primary">
                            <div class="stat-icon">
                                <i class="fas fa-bottle-droplet"></i>
                            </div>
                            @php $activeProducts = \App\Models\Product::where('is_active', true)->count(); @endphp
                            <div class="stat-value">{{ $activeProducts }}</div>
                            <div class="stat-label">Produits actifs</div>
                        </div>
                    </div>

                    <!-- Messages non lus -->
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="stat-card border-left-danger">
                            <div class="stat-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            @php $unreadMessages = \App\Models\Contact::where('is_read', false)->count(); @endphp
                            <div class="stat-value">{{ $unreadMessages }}</div>
                            <div class="stat-label">Messages non lus</div>
                        </div>
                    </div>

                    <!-- Total expédié -->
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="stat-card border-left-success">
                            <div class="stat-icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="stat-value">{{ $stats['total_shipped'] }}</div>
                            <div class="stat-label">Total expédié</div>
                        </div>
                    </div>

                    <!-- Revenus du mois -->
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="stat-card border-left-info">
                            <div class="stat-icon">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                            <div class="stat-value">{{ number_format($stats['monthly_revenue'], 0, ',', ' ') }}€</div>
                            <div class="stat-label">Revenus du mois</div>
                        </div>
                    </div>

                    <!-- Flacons vendus -->
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="stat-card border-left-primary">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-value">{{ $stats['bottles_sold'] }}</div>
                            <div class="stat-label">Flacons vendus</div>
                        </div>
                    </div>
                </div>

                <!-- Activité récente -->
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="recent-activity">
                            <h3 class="activity-header">Activité Récente</h3>
                            <div class="activity-item">
                                <i class="fas fa-shopping-cart me-2" style="color: var(--guerlain-gold);"></i>
                                Nouvelle commande #HP{{ str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) }}
                                <span class="activity-time">Il y a 2h</span>
                            </div>
                            <div class="activity-item">
                                <i class="fas fa-truck me-2" style="color: var(--guerlain-gold);"></i>
                                Expédition vers Paris confirmée
                                <span class="activity-time">Il y a 4h</span>
                            </div>
                            <div class="activity-item">
                                <i class="fas fa-star me-2" style="color: var(--guerlain-gold);"></i>
                                Nouvel avis 5 étoiles reçu
                                <span class="activity-time">Il y a 6h</span>
                            </div>
                            <div class="activity-item">
                                <i class="fas fa-box me-2" style="color: var(--guerlain-gold);"></i>
                                Stock réapprovisionné (+50 flacons)
                                <span class="activity-time">Hier</span>
                            </div>
                            <div class="activity-item">
                                <i class="fas fa-globe me-2" style="color: var(--guerlain-gold);"></i>
                                Commande internationale (London)
                                <span class="activity-time">Il y a 2 jours</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="quick-actions">
                            <h3 class="activity-header">Actions Rapides</h3>
                            <a href="{{ route('admin.products.create') }}" class="action-button">
                                <i class="fas fa-plus me-2"></i>
                                Créer un produit
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="action-button">
                                <i class="fas fa-bottle-droplet me-2"></i>
                                Gérer les produits
                            </a>
                            <a href="{{ route('admin.contacts.index') }}" class="action-button">
                                <i class="fas fa-envelope me-2"></i>
                                Messages de contact
                                @php $unreadContacts = \App\Models\Contact::where('is_read', false)->count(); @endphp
                                @if($unreadContacts > 0)
                                    <span class="badge bg-danger ms-2">{{ $unreadContacts }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.shipping.index') }}" class="action-button">
                                <i class="fas fa-shipping-fast me-2"></i>
                                Gérer les expéditions
                            </a>
                            <a href="{{ route('admin.shipping.statistics') }}" class="action-button">
                                <i class="fas fa-chart-bar me-2"></i>
                                Voir les statistiques
                            </a>
                            <a href="{{ route('demo.shipping') }}" class="action-button">
                                <i class="fas fa-play me-2"></i>
                                Démonstration système
                            </a>
                            <a href="{{ route('demo.guerlain') }}" class="action-button">
                                <i class="fas fa-palette me-2"></i>
                                Voir design Guerlain
                            </a>
                            <a href="{{ route('home') }}" class="action-button">
                                <i class="fas fa-store me-2"></i>
                                Retour à la boutique
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function generateTestOrders() {
    if (confirm('Voulez-vous générer des commandes de test pour la démonstration ?')) {
        // Simuler la génération (en réalité, il faudrait faire un appel AJAX)
        alert('3 commandes de test ont été générées avec succès !');
        location.reload();
    }
}
</script>
@endsection
