@extends('layout')

@section('title', 'Statistiques Expéditions - Admin')

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
                    <a href="{{ route('admin.shipping.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-shipping-fast me-2"></i>Expéditions
                    </a>
                    <a href="{{ route('admin.shipping.statistics') }}" class="list-group-item list-group-item-action active">
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
            <h1 class="h3 mb-4">Statistiques des Expéditions</h1>

            <!-- Cartes de statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h3>{{ $stats['pending_shipments'] }}</h3>
                            <p class="mb-0">En attente d'expédition</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-truck fa-2x mb-2"></i>
                            <h3>{{ $stats['shipped_today'] }}</h3>
                            <p class="mb-0">Expédiées aujourd'hui</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-week fa-2x mb-2"></i>
                            <h3>{{ $stats['shipped_this_week'] }}</h3>
                            <p class="mb-0">Expédiées cette semaine</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-box fa-2x mb-2"></i>
                            <h3>{{ array_sum($stats['by_carrier']) }}</h3>
                            <p class="mb-0">Total expédiées</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition par transporteur -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Répartition par Transporteur</h5>
                        </div>
                        <div class="card-body">
                            @if(count($stats['by_carrier']) > 0)
                                <canvas id="carrierChart" width="400" height="200"></canvas>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune donnée disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Détails par Transporteur</h5>
                        </div>
                        <div class="card-body">
                            @if(count($stats['by_carrier']) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Transporteur</th>
                                                <th>Expéditions</th>
                                                <th>Pourcentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php 
                                                $total = array_sum($stats['by_carrier']);
                                                $carriers = [
                                                    'colissimo' => 'Colissimo',
                                                    'chronopost' => 'Chronopost', 
                                                    'mondialrelay' => 'Mondial Relay'
                                                ];
                                            @endphp
                                            @foreach($stats['by_carrier'] as $carrier => $count)
                                            <tr>
                                                <td>
                                                    @if($carrier === 'colissimo')
                                                        <span class="badge bg-warning">{{ $carriers[$carrier] ?? $carrier }}</span>
                                                    @elseif($carrier === 'chronopost')
                                                        <span class="badge bg-danger">{{ $carriers[$carrier] ?? $carrier }}</span>
                                                    @elseif($carrier === 'mondialrelay')
                                                        <span class="badge bg-success">{{ $carriers[$carrier] ?? $carrier }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $carriers[$carrier] ?? $carrier }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $count }}</td>
                                                <td>{{ $total > 0 ? round(($count / $total) * 100, 1) : 0 }}%</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-table fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune expédition enregistrée</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Actions Rapides</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="{{ route('admin.shipping.index') }}" class="btn btn-primary w-100 mb-2">
                                        <i class="fas fa-list me-2"></i>Voir toutes les expéditions
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-success w-100 mb-2" onclick="window.print()">
                                        <i class="fas fa-print me-2"></i>Imprimer les statistiques
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-info w-100 mb-2" onclick="exportData()">
                                        <i class="fas fa-download me-2"></i>Exporter les données
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Informations Système</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Dernière mise à jour:</strong><br>
                                    <span class="text-muted">{{ now()->format('d/m/Y H:i:s') }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Transporteurs actifs:</strong><br>
                                    <span class="text-muted">3 (Colissimo, Chronopost, Mondial Relay)</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Zones de livraison:</strong><br>
                                    <span class="text-muted">France, Europe, International</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(count($stats['by_carrier']) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('carrierChart').getContext('2d');
    
    const data = @json($stats['by_carrier']);
    const labels = [];
    const values = [];
    const colors = [];
    
    Object.entries(data).forEach(([carrier, count]) => {
        switch(carrier) {
            case 'colissimo':
                labels.push('Colissimo');
                colors.push('#ffc107');
                break;
            case 'chronopost':
                labels.push('Chronopost');
                colors.push('#dc3545');
                break;
            case 'mondialrelay':
                labels.push('Mondial Relay');
                colors.push('#198754');
                break;
            default:
                labels.push(carrier);
                colors.push('#6c757d');
        }
        values.push(count);
    });
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});

function exportData() {
    const stats = @json($stats);
    const csvContent = "data:text/csv;charset=utf-8," 
        + "Métrique,Valeur\n"
        + `En attente,${stats.pending_shipments}\n`
        + `Expédiées aujourd'hui,${stats.shipped_today}\n`
        + `Expédiées cette semaine,${stats.shipped_this_week}\n`
        + Object.entries(stats.by_carrier).map(([carrier, count]) => `${carrier},${count}`).join('\n');
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `statistiques-expeditions-${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endif

<style>
@media print {
    .sidebar, .btn, .card-header {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.875rem;
}
</style>
@endsection
