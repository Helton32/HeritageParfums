@extends('layouts.app')

@section('title', 'Paiement Annulé - Heritage Parfums')
@section('description', 'Votre paiement a été annulé. Vous pouvez reprendre votre commande à tout moment.')

@push('styles')
<style>
    .cancel-page {
        padding: 8rem 0 4rem;
        margin-top: 80px;
        text-align: center;
    }

    .cancel-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, #dc3545, #fd7e14);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 3rem;
        color: white;
    }

    .help-section {
        background: var(--light-gray);
        border-radius: 10px;
        padding: 2rem;
        margin-top: 3rem;
        text-align: left;
    }

    .help-item {
        display: flex;
        align-items: start;
        margin-bottom: 1.5rem;
    }

    .help-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-gold);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .contact-options {
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-top: 2rem;
    }

    .contact-item {
        text-align: center;
        padding: 1rem;
    }

    .contact-item i {
        font-size: 2rem;
        color: var(--primary-gold);
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<section class="cancel-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Cancel Message -->
                <div class="cancel-icon">
                    <i class="fas fa-times"></i>
                </div>
                
                <h1 class="font-playfair display-4 text-danger mb-3">Paiement Annulé</h1>
                <p class="lead mb-4">Votre paiement a été annulé. Aucune somme n'a été débitée de votre compte.</p>
                
                <div class="alert alert-info d-inline-block">
                    <i class="fas fa-info-circle me-2"></i>
                    Vos articles sont toujours dans votre panier et vous attendent !
                </div>

                <!-- Help Section -->
                <div class="help-section">
                    <h4 class="font-playfair text-gold mb-4">Pourquoi reprendre votre commande ?</h4>
                    
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div>
                            <h6>Emballage Cadeau Offert</h6>
                            <p class="text-muted mb-0">Tous nos parfums sont présentés dans un écrin luxueux, parfait pour offrir ou se faire plaisir.</p>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div>
                            <h6>Livraison Gratuite dès 150€</h6>
                            <p class="text-muted mb-0">Profitez de la livraison gratuite en France métropolitaine pour toute commande de 150€ et plus.</p>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h6>Paiement 100% Sécurisé</h6>
                            <p class="text-muted mb-0">Vos données sont protégées par le cryptage SSL et Stripe, leader mondial du paiement en ligne.</p>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fas fa-undo"></i>
                        </div>
                        <div>
                            <h6>Retours Gratuits</h6>
                            <p class="text-muted mb-0">Vous disposez de 30 jours pour changer d'avis et retourner vos parfums gratuitement.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4">
                    <a href="{{ route('cart') }}" class="btn btn-gold btn-lg me-3">
                        <i class="fas fa-shopping-cart me-2"></i>Reprendre ma Commande
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-gold btn-lg">
                        <i class="fas fa-search me-2"></i>Continuer mes Achats
                    </a>
                </div>

                <!-- Contact Options -->
                <div class="contact-options">
                    <h5 class="font-playfair text-gold text-center mb-4">Besoin d'Aide ?</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <h6>Téléphone</h6>
                                <p class="text-muted">01 42 86 95 30</p>
                                <small class="text-muted">Lun-Ven 9h-18h</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <h6>Email</h6>
                                <p class="text-muted">contact@heritage-parfums.fr</p>
                                <small class="text-muted">Réponse sous 24h</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-item">
                                <i class="fas fa-comments"></i>
                                <h6>Chat en Ligne</h6>
                                <p class="text-muted">Assistance instantanée</p>
                                <small class="text-muted">Lun-Ven 9h-18h</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Quick Links -->
                <div class="mt-4">
                    <h6 class="text-center text-muted mb-3">Questions Fréquentes</h6>
                    <div class="d-flex justify-content-center flex-wrap gap-3">
                        <a href="#" class="btn btn-outline-secondary btn-sm">Modes de Paiement</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Sécurité</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Livraison</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Retours</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection