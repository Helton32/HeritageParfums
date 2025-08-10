@extends('layouts.app')

@section('title', 'Expédition Commande ' . $order->order_number)

@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <!-- Breadcrumb -->
        <div class="col-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.shipping.index') }}">Expéditions</a></li>
                    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                </ol>
            </nav>
        </div>

        <!-- Informations commande -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Commande {{ $order->order_number }}</h5>
                    <span class="badge bg-{{ $order->status === 'processing' ? 'warning' : 'success' }} fs-6">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations client</h6>
                            <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                            <p class="mb-1">{{ $order->customer_email }}</p>
                            @if($order->customer_phone)
                                <p class="mb-1">{{ $order->customer_phone }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Adresse de livraison</h6>
                            <p class="mb-1">{{ $order->shipping_address_line_1 }}</p>
                            @if($order->shipping_address_line_2)
                                <p class="mb-1">{{ $order->shipping_address_line_2 }}</p>
                            @endif
                            <p class="mb-1">{{ $order->shipping_postal_code }} {{ $order->shipping_city }}</p>
                            <p class="mb-1">{{ strtoupper($order->shipping_country) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu de la commande -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Contenu de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Type</th>
                                    <th>Taille</th>
                                    <th>Quantité</th>
                                    <th>Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->product_type }}</td>
                                    <td>{{ $item->product_size }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->product_price, 2) }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">Total</th>
                                    <th>{{ $order->formatted_total }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Poids estimé:</strong> {{ number_format($order->calculatePackageWeight(), 3) }} kg<br>
                            <strong>Zone de livraison:</strong> {{ ucfirst($order->getShippingZone()) }}
                        </div>
                        <div class="col-md-6">
                            @php $dimensions = $order->getPackageDimensions(); @endphp
                            <strong>Dimensions:</strong> {{ $dimensions['length'] }}x{{ $dimensions['width'] }}x{{ $dimensions['height'] }} cm
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestion transporteur -->
        <div class="col-md-4">
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

            <!-- Statut transporteur -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Statut Expédition</h6>
                </div>
                <div class="card-body">
                    @if($order->shipping_carrier)
                        <div class="mb-3">
                            <strong>Transporteur:</strong> {{ $order->carrier_name }}<br>
                            <strong>Méthode:</strong> {{ $order->shipping_method }}<br>
                            @if($order->tracking_number)
                                <strong>N° de suivi:</strong> {{ $order->tracking_number }}<br>
                            @endif
                            @if($order->carrier_reference)
                                <strong>Référence:</strong> {{ $order->carrier_reference }}
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            @if(!$order->shipping_label_path)
                                <form method="POST" action="{{ route('admin.shipping.generate-label', $order) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-file-pdf me-2"></i>Générer le bon de livraison
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('admin.shipping.download-label', $order) }}" 
                                   class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Télécharger le bon
                                </a>
                                
                                @if($order->tracking_number && $order->status === 'processing')
                                    <form method="POST" action="{{ route('admin.shipping.mark-shipped', $order) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-warning w-100"
                                                onclick="return confirm('Marquer comme expédiée ?')">
                                            <i class="fas fa-truck me-2"></i>Marquer comme expédiée
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    @else
                        <p class="text-muted">Aucun transporteur assigné</p>
                    @endif
                </div>
            </div>

            <!-- Assigner transporteur -->
            @if(!$order->shipping_carrier || !$order->shipping_label_path)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Assigner un transporteur</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.shipping.assign-carrier', $order) }}" id="carrierForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Transporteur</label>
                            <select name="shipping_carrier" class="form-select" id="carrierSelect" required>
                                <option value="">Choisir un transporteur</option>
                                @foreach($availableCarriers as $carrierData)
                                    <option value="{{ $carrierData['carrier']->code }}" 
                                            {{ $order->shipping_carrier === $carrierData['carrier']->code ? 'selected' : '' }}>
                                        {{ $carrierData['carrier']->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" id="methodsContainer" style="display: none;">
                            <label class="form-label">Méthode de livraison</label>
                            <div id="methodsList">
                                <!-- Les méthodes seront chargées ici via AJAX -->
                            </div>
                        </div>

                        <div class="mb-3" id="relayPointContainer" style="display: none;">
                            <label class="form-label">Point Relais</label>
                            <button type="button" class="btn btn-outline-secondary w-100" id="searchRelayBtn">
                                <i class="fas fa-search me-2"></i>Rechercher un point relais
                            </button>
                            <div id="relayPointsList" class="mt-2"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check me-2"></i>Assigner le transporteur
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carrierSelect = document.getElementById('carrierSelect');
    const methodsContainer = document.getElementById('methodsContainer');
    const methodsList = document.getElementById('methodsList');
    const relayPointContainer = document.getElementById('relayPointContainer');
    const searchRelayBtn = document.getElementById('searchRelayBtn');

    carrierSelect.addEventListener('change', function() {
        const carrierCode = this.value;
        
        if (carrierCode) {
            // Charger les méthodes disponibles
            fetch(`/api/shipping/carrier-methods?carrier=${carrierCode}&weight={{ $order->calculatePackageWeight() }}&zone={{ $order->getShippingZone() }}`)
                .then(response => response.json())
                .then(methods => {
                    methodsList.innerHTML = '';
                    
                    Object.entries(methods).forEach(([code, method]) => {
                        const methodDiv = document.createElement('div');
                        methodDiv.className = 'form-check mb-2';
                        methodDiv.innerHTML = `
                            <input class="form-check-input" type="radio" name="shipping_method" 
                                   id="method_${code}" value="${code}" required>
                            <label class="form-check-label" for="method_${code}">
                                <strong>${method.name}</strong> - ${method.price.toFixed(2)} €<br>
                                <small class="text-muted">${method.description} (${method.delivery_time})</small>
                            </label>
                        `;
                        methodsList.appendChild(methodDiv);
                    });
                    
                    methodsContainer.style.display = 'block';
                    
                    // Afficher la recherche de point relais pour Mondial Relay
                    if (carrierCode === 'mondialrelay') {
                        relayPointContainer.style.display = 'block';
                    } else {
                        relayPointContainer.style.display = 'none';
                    }
                });
        } else {
            methodsContainer.style.display = 'none';
            relayPointContainer.style.display = 'none';
        }
    });

    // Recherche de points relais
    searchRelayBtn.addEventListener('click', function() {
        const postalCode = '{{ $order->shipping_postal_code }}';
        const city = '{{ $order->shipping_city }}';
        
        fetch(`/api/shipping/relay-points?postal_code=${postalCode}&city=${city}`)
            .then(response => response.json())
            .then(points => {
                const relayPointsList = document.getElementById('relayPointsList');
                relayPointsList.innerHTML = '';
                
                points.forEach(point => {
                    const pointDiv = document.createElement('div');
                    pointDiv.className = 'form-check mb-2 p-2 border rounded';
                    pointDiv.innerHTML = `
                        <input class="form-check-input" type="radio" name="carrier_options[point_relais][code]" 
                               id="relay_${point.code}" value="${point.code}">
                        <label class="form-check-label w-100" for="relay_${point.code}">
                            <strong>${point.name}</strong><br>
                            <small>${point.address}<br>
                            ${point.postal_code} ${point.city}<br>
                            ${point.hours} - ${point.distance}</small>
                            <input type="hidden" name="carrier_options[point_relais][name]" value="${point.name}">
                            <input type="hidden" name="carrier_options[point_relais][address]" value="${point.address}">
                        </label>
                    `;
                    relayPointsList.appendChild(pointDiv);
                });
            });
    });

    // Charger les méthodes si un transporteur est déjà sélectionné
    if (carrierSelect.value) {
        carrierSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<style>
.form-check-label {
    cursor: pointer;
}

.border {
    border-color: #dee2e6 !important;
}

.form-check-input:checked + .form-check-label {
    background-color: #f8f9fa;
}
</style>
@endsection
