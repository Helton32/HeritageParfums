@extends('layout')

@section('title', 'Gestion des Expéditions - Admin')

@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <!-- Sidebar Admin -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Administration</h5>
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="card-body p-3">
                    <div class="text-center mb-3">
                        <div class="avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <h6 class="mt-2 mb-1">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">Administrateur</small>
                    </div>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.shipping.index') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-shipping-fast me-2"></i>Expéditions
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
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Commandes à Expédier</h1>
                <div class="text-muted">
                    {{ $orders->total() }} commande(s) en attente
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($orders->count() > 0)
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Transporteur</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $order->customer_email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <strong>{{ $order->formatted_total }}</strong>
                                    </td>
                                    <td>
                                        @if($order->shipping_carrier)
                                            <span class="badge bg-primary">
                                                {{ $order->carrier_name }}
                                            </span>
                                            @if($order->shipping_method)
                                                <br><small class="text-muted">{{ $order->shipping_method }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-warning">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->hasTrackingNumber())
                                            <span class="badge bg-success">Prêt à expédier</span>
                                        @elseif($order->shipping_carrier)
                                            <span class="badge bg-info">Transporteur assigné</span>
                                        @else
                                            <span class="badge bg-secondary">En attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.shipping.show', $order) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($order->shipping_label_path)
                                                <a href="{{ route('admin.shipping.download-label', $order) }}" 
                                                   class="btn btn-sm btn-success">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            
                                            @if($order->hasTrackingNumber())
                                                <form method="POST" action="{{ route('admin.shipping.mark-shipped', $order) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                            onclick="return confirm('Marquer cette commande comme expédiée ?')">
                                                        <i class="fas fa-truck"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucune commande à expédier</h4>
                    <p class="text-muted">Toutes les commandes ont été traitées.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        Retour à la boutique
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    border-radius: 0.25rem !important;
    margin-right: 2px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}
</style>
@endsection
