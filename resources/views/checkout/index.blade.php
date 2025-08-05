@extends('layouts.app')
@extends('layouts.app')

@section('title', 'Commande - Heritage Parfums')
@section('description', 'Finalisez votre commande Heritage Parfums en toute sécurité avec Stripe.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout-shipping.css') }}">
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="text-center">
            <h1 class="font-playfair display-4 text-gold">Finaliser ma Commande</h1>
            <p class="lead">Paiement sécurisé par Stripe</p>
        </div>
    </div>
</section>

<!-- Checkout Form -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Customer Information -->
            <div class="col-lg-8">
                <form id="checkout-form">
                    @csrf
                    
                    <!-- Customer Details -->
                    <div class="checkout-section">
                        <h3 class="section-title">
                            <i class="fas fa-user me-2"></i>Informations Client
                        </h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Nom complet *</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone">
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="checkout-section">
                        <h3 class="section-title">
                            <i class="fas fa-credit-card me-2"></i>Adresse de Facturation
                        </h3>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="billing_address_line_1" class="form-label">Adresse *</label>
                                <input type="text" class="form-control" id="billing_address_line_1" name="billing_address_line_1" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="billing_address_line_2" class="form-label">Complément d'adresse</label>
                                <input type="text" class="form-control" id="billing_address_line_2" name="billing_address_line_2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_city" class="form-label">Ville *</label>
                                <input type="text" class="form-control" id="billing_city" name="billing_city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_postal_code" class="form-label">Code postal *</label>
                                <input type="text" class="form-control" id="billing_postal_code" name="billing_postal_code" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_country" class="form-label">Pays *</label>
                                <select class="form-control" id="billing_country" name="billing_country" required>
                                    <option value="FR" selected>France</option>
                                    <option value="BE">Belgique</option>
                                    <option value="CH">Suisse</option>
                                    <option value="DE">Allemagne</option>
                                    <option value="IT">Italie</option>
                                    <option value="ES">Espagne</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Shipping Selection -->
                    <div class="checkout-section shipping-section" id="shipping-section">
                        <h3 class="section-title">
                            <i class="fas fa-truck me-2"></i>Mode de Livraison
                        </h3>
                        
                        <!-- Loading State -->
                        <div class="shipping-loading" id="shipping-loading">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                                <p class="mt-2">Calcul des options de livraison...</p>
                            </div>
                        </div>
                        
                        <!-- Shipping Options -->
                        <div class="shipping-options" id="shipping-options" style="display: none;">
                            <!-- Options will be loaded dynamically -->
                        </div>
                        
                        <!-- Point Relais Selection -->
                        <div class="relay-points-section" id="relay-points-section" style="display: none;">
                            <h4 class="relay-title">
                                <i class="fas fa-map-marker-alt me-2"></i>Choisir un Point Relais
                            </h4>
                            <div class="relay-search">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="relay-search-input" 
                                           placeholder="Entrez votre code postal ou ville">
                                    <button class="btn btn-outline-secondary" type="button" id="search-relays">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="relay-results mt-3" id="relay-results">
                                <!-- Results will be loaded here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden inputs for shipping -->
                    <input type="hidden" name="shipping_carrier" id="shipping_carrier">
                    <input type="hidden" name="shipping_method" id="shipping_method">
                    <input type="hidden" name="shipping_amount" id="shipping_amount">
                    <input type="hidden" name="relay_point_id" id="relay_point_id">
                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="checkout-section order-summary" id="order-summary">
                    <h3 class="section-title">
                        <i class="fas fa-shopping-cart me-2"></i>Votre Commande
                    </h3>
                    
                    <!-- Order Items -->
                    <div class="mb-4">
                        @foreach($cartData['items'] as $item)
                        <div class="order-item">
                            <div class="d-flex align-items-center">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                    <small class="text-muted">{{ $item['type'] }} - {{ $item['size'] }}</small>
                                    <div class="d-flex justify-content-between mt-1">
                                        <span>Qté: {{ $item['quantity'] }}</span>
                                        <strong>{{ number_format($item['total'], 2) }}€</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Price Summary -->
                    <div class="price-summary">
                        <div class="price-row">
                            <span>Sous-total:</span>
                            <span id="subtotal-amount">{{ number_format($cartData['subtotal'], 2) }}€</span>
                        </div>
                        
                        <div class="price-row">
                            <span>TVA (20%):</span>
                            <span id="tax-amount">{{ number_format($cartData['tax_amount'], 2) }}€</span>
                        </div>
                        
                        <div class="price-row shipping-row">
                            <span>Livraison:</span>
                            <span id="shipping-display">
                                <span class="text-muted">Sélectionnez un mode de livraison</span>
                            </span>
                        </div>
                        
                        <div class="price-row total">
                            <span>Total:</span>
                            <span id="total-amount">{{ number_format($cartData['subtotal'] + $cartData['tax_amount'], 2) }}€</span>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <div class="mt-4">
                        <button type="button" class="btn btn-pay w-100" id="pay-button" disabled>
                            <i class="fas fa-lock me-2"></i>Payer Maintenant
                            <div class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </button>
                        <p class="text-muted text-center small mt-2">
                            Veuillez sélectionner un mode de livraison
                        </p>
                    </div>

                    <!-- Security Badges -->
                    <div class="security-badges">
                        <div class="security-badge">
                            <i class="fas fa-shield-alt text-success"></i>
                            <span>Paiement Sécurisé</span>
                        </div>
                        <div class="security-badge">
                            <i class="fab fa-cc-stripe text-primary"></i>
                            <span>Stripe</span>
                        </div>
                        <div class="security-badge">
                            <i class="fas fa-lock text-warning"></i>
                            <span>SSL 256-bit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
let cartData = @json($cartData);
let availableCarriers = [];
let selectedCarrier = null;
let selectedMethod = null;
let selectedRelayPoint = null;

document.addEventListener('DOMContentLoaded', function() {
    const billingPostalCode = document.getElementById('billing_postal_code');
    const billingCountry = document.getElementById('billing_country');
    const payButton = document.getElementById('pay-button');
    const checkoutForm = document.getElementById('checkout-form');
    const loadingSpinner = document.querySelector('.loading-spinner');

    // Load shipping options when address changes
    billingPostalCode.addEventListener('blur', loadShippingOptions);
    billingCountry.addEventListener('change', loadShippingOptions);

    // Load initial shipping options
    setTimeout(loadShippingOptions, 1000);

    function loadShippingOptions() {
        const postalCode = billingPostalCode.value;
        const country = billingCountry.value;
        
        if (!postalCode || postalCode.length < 3) return;

        showShippingLoading();
        
        fetch('/api/shipping/options', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                postal_code: postalCode,
                country: country,
                weight: calculatePackageWeight(),
                subtotal: cartData.subtotal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                availableCarriers = data.carriers;
                displayShippingOptions(data.carriers);
            } else {
                showShippingError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showShippingError('Erreur lors du chargement des options de livraison');
        });
    }

    function showShippingLoading() {
        document.getElementById('shipping-loading').style.display = 'block';
        document.getElementById('shipping-options').style.display = 'none';
        document.getElementById('relay-points-section').style.display = 'none';
    }

    function displayShippingOptions(carriers) {
        const optionsContainer = document.getElementById('shipping-options');
        let html = '';

        carriers.forEach(carrier => {
            Object.entries(carrier.methods).forEach(([methodCode, methodData]) => {
                const price = carrier.pricing[methodCode] || 0;
                const isRelay = methodData.features.includes('point_relais');
                
                html += `
                    <div class="shipping-option ${isRelay ? 'relay-option' : ''}" 
                         data-carrier="${carrier.code}" 
                         data-method="${methodCode}" 
                         data-price="${price}"
                         data-is-relay="${isRelay}">
                        <div class="option-content">
                            <div class="option-header">
                                <div class="carrier-info">
                                    <img src="${carrier.logo_path}" alt="${carrier.name}" class="carrier-logo">
                                    <div class="carrier-details">
                                        <h5 class="carrier-name">${carrier.name}</h5>
                                        <h6 class="method-name">${methodData.name}</h6>
                                    </div>
                                </div>
                                <div class="option-price">
                                    <span class="price">${price.toFixed(2)}€</span>
                                    <div class="delivery-time">${methodData.delivery_time}</div>
                                </div>
                            </div>
                            <p class="method-description">${methodData.description}</p>
                            <div class="method-features">
                                ${methodData.features.map(feature => `
                                    <span class="feature-tag ${feature}">
                                        <i class="fas fa-${getFeatureIcon(feature)}"></i>
                                        ${getFeatureLabel(feature)}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                        <div class="option-selector">
                            <input type="radio" name="shipping_option" 
                                   value="${carrier.code}-${methodCode}" 
                                   id="shipping_${carrier.code}_${methodCode}">
                            <label for="shipping_${carrier.code}_${methodCode}"></label>
                        </div>
                    </div>
                `;
            });
        });

        optionsContainer.innerHTML = html;
        
        // Hide loading and show options
        document.getElementById('shipping-loading').style.display = 'none';
        optionsContainer.style.display = 'block';

        // Add event listeners
        document.querySelectorAll('input[name="shipping_option"]').forEach(radio => {
            radio.addEventListener('change', handleShippingSelection);
        });
    }

    function handleShippingSelection(e) {
        const option = e.target.closest('.shipping-option');
        const carrier = option.dataset.carrier;
        const method = option.dataset.method;
        const price = parseFloat(option.dataset.price);
        const isRelay = option.dataset.isRelay === 'true';

        // Update selection
        selectedCarrier = carrier;
        selectedMethod = method;
        
        // Update hidden fields
        document.getElementById('shipping_carrier').value = carrier;
        document.getElementById('shipping_method').value = method;
        document.getElementById('shipping_amount').value = price;

        // Update visual selection
        document.querySelectorAll('.shipping-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        option.classList.add('selected');

        // Show/hide relay selection
        const relaySection = document.getElementById('relay-points-section');
        if (isRelay) {
            relaySection.style.display = 'block';
            // Auto-search for relay points
            searchRelayPoints();
        } else {
            relaySection.style.display = 'none';
            selectedRelayPoint = null;
            document.getElementById('relay_point_id').value = '';
            updateOrderSummary(price);
            enablePayButton();
        }
    }
    function searchRelayPoints() {
        const postalCode = document.getElementById('billing_postal_code').value;
        const city = document.getElementById('billing_city').value;
        
        fetch('/api/shipping/relay-points', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                carrier: selectedCarrier,
                postal_code: postalCode,
                city: city
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayRelayPoints(data.points);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function displayRelayPoints(points) {
        const resultsContainer = document.getElementById('relay-results');
        let html = '';

        if (points.length === 0) {
            html = '<p class="text-muted">Aucun point relais trouvé dans votre zone.</p>';
        } else {
            points.forEach(point => {
                html += `
                    <div class="relay-point" data-point-id="${point.id}">
                        <div class="relay-content">
                            <div class="relay-info">
                                <h6 class="relay-name">${point.name}</h6>
                                <p class="relay-address">
                                    ${point.address}<br>
                                    ${point.postal_code} ${point.city}
                                </p>
                                <div class="relay-distance">
                                    <i class="fas fa-route"></i>
                                    ${point.distance} km
                                </div>
                            </div>
                            <div class="relay-hours">
                                <small class="text-muted">
                                    ${point.opening_hours || 'Horaires non disponibles'}
                                </small>
                            </div>
                        </div>
                        <div class="relay-selector">
                            <input type="radio" name="relay_point" value="${point.id}" id="relay_${point.id}">
                            <label for="relay_${point.id}"></label>
                        </div>
                    </div>
                `;
            });
        }

        resultsContainer.innerHTML = html;

        // Add event listeners for relay selection
        document.querySelectorAll('input[name="relay_point"]').forEach(radio => {
            radio.addEventListener('change', handleRelaySelection);
        });
    }

    function handleRelaySelection(e) {
        const relayPoint = e.target.closest('.relay-point');
        const pointId = relayPoint.dataset.pointId;
        
        selectedRelayPoint = pointId;
        document.getElementById('relay_point_id').value = pointId;

        // Update visual selection
        document.querySelectorAll('.relay-point').forEach(point => {
            point.classList.remove('selected');
        });
        relayPoint.classList.add('selected');

        // Update order summary and enable payment
        const shippingPrice = parseFloat(document.getElementById('shipping_amount').value);
        updateOrderSummary(shippingPrice);
        enablePayButton();
    }

    function updateOrderSummary(shippingPrice) {
        const subtotal = cartData.subtotal;
        const taxAmount = cartData.tax_amount;
        const total = subtotal + taxAmount + shippingPrice;

        document.getElementById('shipping-display').innerHTML = 
            shippingPrice > 0 ? `${shippingPrice.toFixed(2)}€` : '<span class="text-success">Gratuite</span>';
        document.getElementById('total-amount').textContent = `${total.toFixed(2)}€`;
        
        // Update cart data for payment
        cartData.shipping_amount = shippingPrice;
        cartData.total = total;
    }

    function enablePayButton() {
        const payBtn = document.getElementById('pay-button');
        const hasShipping = selectedCarrier && selectedMethod;
        const needsRelay = document.querySelector('.shipping-option.selected')?.dataset.isRelay === 'true';
        const hasRelay = !needsRelay || selectedRelayPoint;

        if (hasShipping && hasRelay) {
            payBtn.disabled = false;
            payBtn.nextElementSibling.style.display = 'none';
        }
    }

    function showShippingError(message) {
        const optionsContainer = document.getElementById('shipping-options');
        optionsContainer.innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `;
        document.getElementById('shipping-loading').style.display = 'none';
        optionsContainer.style.display = 'block';
    }

    function calculatePackageWeight() {
        let weight = 0;
        cartData.items.forEach(item => {
            const itemWeight = item.size === '30ml' ? 0.15 : item.size === '100ml' ? 0.3 : 0.2;
            weight += itemWeight * item.quantity;
        });
        return weight + 0.1; // Add packaging weight
    }

    function getFeatureIcon(feature) {
        const icons = {
            'domicile': 'home',
            'point_relais': 'map-marker-alt',
            'express': 'bolt',
            'signature': 'signature',
            'tracking': 'search'
        };
        return icons[feature] || 'check';
    }

    function getFeatureLabel(feature) {
        const labels = {
            'domicile': 'Domicile',
            'point_relais': 'Point Relais',
            'express': 'Express',
            'signature': 'Signature',
            'tracking': 'Suivi'
        };
        return labels[feature] || feature;
    }

    // Payment processing
    payButton.addEventListener('click', function() {
        // Validate form
        if (!checkoutForm.checkValidity()) {
            checkoutForm.reportValidity();
            return;
        }

        if (!selectedCarrier || !selectedMethod) {
            alert('Veuillez sélectionner un mode de livraison');
            return;
        }

        // Disable button and show loading
        payButton.disabled = true;
        loadingSpinner.style.display = 'inline-block';

        // Collect form data
        const formData = new FormData(checkoutForm);
        const data = Object.fromEntries(formData);

        // Create Stripe session
        fetch('/payment/create-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.url) {
                window.location.href = data.url;
            } else {
                throw new Error(data.error || 'Erreur lors de la création de la session de paiement');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur: ' + error.message);
            payButton.disabled = false;
            loadingSpinner.style.display = 'none';
        });
    });
});
</script>
@endpush