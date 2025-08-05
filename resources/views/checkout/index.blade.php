@extends('layouts.app')

@section('title', 'Commande - Heritage Parfums')
@section('description', 'Finalisez votre commande Heritage Parfums en toute sécurité avec Stripe.')

@push('styles')
<style>
    .page-header {
        background: var(--light-gray);
        padding: 6rem 0 3rem;
        margin-top: 80px;
    }

    .checkout-section {
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        color: var(--primary-gold);
        border-bottom: 2px solid var(--primary-gold);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px 15px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.2rem rgba(184, 134, 11, 0.25);
    }

    .order-item {
        border-bottom: 1px solid #eee;
        padding: 1rem 0;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .price-row.total {
        border-top: 2px solid var(--primary-gold);
        padding-top: 1rem;
        margin-top: 1rem;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .security-badges {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #eee;
    }

    .security-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        font-size: 0.9rem;
    }

    .btn-pay {
        background: linear-gradient(45deg, var(--primary-gold), var(--secondary-gold));
        border: none;
        color: white;
        padding: 15px 30px;
        font-size: 1.1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(184, 134, 11, 0.3);
        color: white;
    }

    .btn-pay:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .loading-spinner {
        display: none;
        margin-left: 10px;
    }
</style>
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
                        <h3 class="section-title">Informations Client</h3>
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
                        <h3 class="section-title">Adresse de Facturation</h3>
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
                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="checkout-section">
                    <h3 class="section-title">Votre Commande</h3>
                    
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
                    <div class="price-row">
                        <span>Sous-total:</span>
                        <span>{{ number_format($cartData['subtotal'], 2) }}€</span>
                    </div>
                    
                    <div class="price-row">
                        <span>TVA (20%):</span>
                        <span>{{ number_format($cartData['tax_amount'], 2) }}€</span>
                    </div>
                    
                    <div class="price-row">
                        <span>Livraison:</span>
                        <span>
                            @if($cartData['shipping_amount'] > 0)
                                {{ number_format($cartData['shipping_amount'], 2) }}€
                            @else
                                Gratuite
                            @endif
                        </span>
                    </div>
                    
                    <div class="price-row total">
                        <span>Total:</span>
                        <span>{{ number_format($cartData['total'], 2) }}€</span>
                    </div>

                    <!-- Payment Button -->
                    <div class="mt-4">
                        <button type="button" class="btn btn-pay w-100" id="pay-button">
                            <i class="fas fa-lock me-2"></i>Payer Maintenant
                            <div class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </button>
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
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('pay-button');
    const checkoutForm = document.getElementById('checkout-form');
    const loadingSpinner = document.querySelector('.loading-spinner');

    payButton.addEventListener('click', function() {
        // Validate form
        if (!checkoutForm.checkValidity()) {
            checkoutForm.reportValidity();
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
                // Redirect to Stripe Checkout
                window.location.href = data.url;
            } else {
                throw new Error(data.error || 'Erreur lors de la création de la session de paiement');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur: ' + error.message);
            
            // Re-enable button and hide loading
            payButton.disabled = false;
            loadingSpinner.style.display = 'none';
        });
    });
});
</script>
@endpush