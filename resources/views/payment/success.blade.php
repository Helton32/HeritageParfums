@extends('layouts.app')

@section('title', 'Paiement Réussi - Héritaj Parfums')
@section('description', 'Votre commande Héritaj Parfums a été confirmée. Merci pour votre achat.')

@push('styles')
<style>
    .success-page {
        padding: 8rem 0 4rem;
        margin-top: 80px;
        text-align: center;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, #28a745, #20c997);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 3rem;
        color: white;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .order-summary {
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-top: 3rem;
        text-align: left;
    }

    .order-header {
        border-bottom: 2px solid var(--primary-gold);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }

    .order-item {
        border-bottom: 1px solid #eee;
        padding: 1rem 0;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .next-steps {
        background: var(--cream);
        border-radius: 10px;
        padding: 2rem;
        margin-top: 2rem;
    }

    .step {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .step-number {
        width: 30px;
        height: 30px;
        background: var(--primary-gold);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 1rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<section class="success-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                
                <h1 class="font-playfair display-4 text-gold mb-3">Paiement Réussi !</h1>
                <p class="lead mb-4">Merci pour votre commande. Nous avons bien reçu votre paiement.</p>
                
                <div class="alert alert-success d-inline-block">
                    <i class="fas fa-envelope me-2"></i>
                    Un email de confirmation a été envoyé à <strong>{{ $order->customer_email }}</strong>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="order-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="font-playfair text-gold mb-1">Commande #{{ $order->order_number }}</h4>
                                <p class="text-muted mb-0">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="badge bg-success fs-6">{{ $order->payment_status_label }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-gold">Informations Client</h6>
                            <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                            <p class="mb-1">{{ $order->customer_email }}</p>
                            @if($order->customer_phone)
                                <p class="mb-0">{{ $order->customer_phone }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-gold">Adresse de Livraison</h6>
                            <p class="mb-0">
                                {{ $order->shipping_address_line_1 }}<br>
                                @if($order->shipping_address_line_2)
                                    {{ $order->shipping_address_line_2 }}<br>
                                @endif
                                {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
                                {{ $order->shipping_country }}
                            </p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <h6 class="text-gold mb-3">Articles Commandés</h6>
                    @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1">{{ $item->product_name }}</h6>
                                <p class="text-muted mb-0">{{ $item->product_type }} - {{ $item->product_size }}</p>
                                <small class="text-muted">Quantité: {{ $item->quantity }}</small>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <strong>{{ $item->formatted_total_price }}</strong>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Order Total -->
                    <div class="row mt-3 pt-3 border-top">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Sous-total:</span>
                                <span>{{ number_format($order->subtotal, 2) }}€</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>TVA:</span>
                                <span>{{ number_format($order->tax_amount, 2) }}€</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Livraison:</span>
                                <span>
                                    @if($order->shipping_amount > 0)
                                        {{ number_format($order->shipping_amount, 2) }}€
                                    @else
                                        Gratuite
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 text-gold">
                                <span>Total:</span>
                                <span>{{ $order->formatted_total }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="next-steps">
                    <h5 class="font-playfair text-gold mb-3">Prochaines Étapes</h5>
                    
                    <div class="step">
                        <div class="step-number">1</div>
                        <div>
                            <strong>Préparation de votre commande</strong><br>
                            <span class="text-muted">Nous préparons soigneusement vos parfums dans notre atelier parisien.</span>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <div>
                            <strong>Expédition</strong><br>
                            <span class="text-muted">Votre commande sera expédiée sous 2-3 jours ouvrés.</span>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <div>
                            <strong>Livraison</strong><br>
                            <span class="text-muted">Vous recevrez un email avec le numéro de suivi dès l'expédition.</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4">
                    <a href="{{ route('home') }}" class="btn btn-gold me-3">
                        <i class="fas fa-home me-2"></i>Retour à l'Accueil
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-gold">
                        <i class="fas fa-shopping-bag me-2"></i>Continuer mes Achats
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection