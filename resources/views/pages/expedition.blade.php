@extends('layouts.app')

@section('title', 'Expédition - Heritage Parfums')
@section('description', 'Découvrez nos méthodes d\'expédition pour vos parfums Heritage Parfums. Livraison rapide, sécurisée et élégante partout en France et dans le monde.')

@push('styles')
<style>
    .expedition-hero {
        background: linear-gradient(135deg, var(--guerlain-black) 0%, #333 100%);
        padding: 6rem 0 4rem 0;
        margin-top: 80px;
        color: white;
        text-align: center;
    }

    .expedition-content {
        padding: 4rem 0;
    }

    .expedition-section {
        margin-bottom: 4rem;
    }

    .expedition-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 300;
        color: var(--guerlain-black);
        margin-bottom: 2rem;
        text-align: center;
    }

    .expedition-subtitle {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 400;
        color: var(--guerlain-gold);
        margin-bottom: 1.5rem;
    }

    .expedition-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 1rem;
        line-height: 1.6;
        color: var(--guerlain-text-gray);
        margin-bottom: 1.5rem;
    }

    .shipping-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .shipping-card {
        background: white;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .shipping-card:hover {
        transform: translateY(-8px);
        border-color: var(--guerlain-gold);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .shipping-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: var(--guerlain-gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
    }

    .shipping-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.4rem;
        font-weight: 500;
        color: var(--guerlain-black);
        margin-bottom: 1rem;
    }

    .shipping-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--guerlain-gold);
        margin-bottom: 1rem;
    }

    .shipping-time {
        background: var(--guerlain-light-gray);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--guerlain-black);
        display: inline-block;
        margin-bottom: 1rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .feature-icon {
        width: 50px;
        height: 50px;
        background: var(--guerlain-gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .feature-content h4 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        color: var(--guerlain-black);
        margin-bottom: 0.5rem;
    }

    .feature-content p {
        font-size: 0.9rem;
        color: var(--guerlain-text-gray);
        margin: 0;
    }

    .international-section {
        background: var(--guerlain-cream);
        padding: 3rem 0;
        border-radius: 12px;
        margin: 3rem 0;
    }

    .faq-item {
        border-bottom: 1px solid var(--guerlain-border);
        padding: 1.5rem 0;
    }

    .faq-question {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        font-weight: 500;
        color: var(--guerlain-black);
        margin-bottom: 1rem;
    }

    .faq-answer {
        font-size: 0.95rem;
        color: var(--guerlain-text-gray);
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .expedition-hero {
            padding: 4rem 0 3rem 0;
            margin-top: 60px;
        }
        
        .expedition-title {
            font-size: 2rem;
        }
        
        .shipping-methods {
            grid-template-columns: 1fr;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="expedition-hero">
    <div class="container">
        <h1 class="display-3 mb-4">Expédition</h1>
        <p class="lead">Vos parfums Heritage Parfums livrés avec soin et élégance</p>
    </div>
</section>

<!-- Contenu Principal -->
<section class="expedition-content">
    <div class="container">
        <!-- Introduction -->
        <div class="expedition-section">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="expedition-title">Livraison de Luxe</h2>
                    <p class="expedition-text">
                        Chez Heritage Parfums, chaque commande est traitée avec le plus grand soin. 
                        Nos parfums d'exception méritent une expédition à la hauteur de leur qualité. 
                        Découvrez nos différentes méthodes de livraison pour recevoir vos créations 
                        olfactives dans les meilleures conditions.
                    </p>
                </div>
            </div>
        </div>

        <!-- Méthodes d'expédition -->
        <div class="expedition-section">
            <h2 class="expedition-title">Nos Méthodes d'Expédition</h2>
            <div class="shipping-methods">
                <!-- Livraison Standard -->
                <div class="shipping-card">
                    <div class="shipping-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="shipping-name">Livraison Standard</h3>
                    <div class="shipping-price">Gratuite dès 75€</div>
                    <div class="shipping-time">3-5 jours ouvrés</div>
                    <p class="expedition-text">
                        Livraison sécurisée via Colissimo ou Mondial Relay. 
                        Idéal pour vos commandes courantes avec un délai confortable.
                    </p>
                    <ul class="list-unstyled">
                        <li>✓ Suivi en temps réel</li>
                        <li>✓ Emballage premium</li>
                        <li>✓ Assurance incluse</li>
                    </ul>
                </div>

                <!-- Livraison Express -->
                <div class="shipping-card">
                    <div class="shipping-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="shipping-name">Livraison Express</h3>
                    <div class="shipping-price">9,90€</div>
                    <div class="shipping-time">24-48h</div>
                    <p class="expedition-text">
                        Pour recevoir rapidement vos parfums Heritage Parfums. 
                        Parfait pour les cadeaux de dernière minute.
                    </p>
                    <ul class="list-unstyled">
                        <li>✓ Livraison prioritaire</li>
                        <li>✓ SMS de suivi</li>
                        <li>✓ Signature requise</li>
                    </ul>
                </div>

                <!-- Livraison Premium -->
                <div class="shipping-card">
                    <div class="shipping-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="shipping-name">Livraison Premium</h3>
                    <div class="shipping-price">19,90€</div>
                    <div class="shipping-time">24h garanties</div>
                    <p class="expedition-text">
                        Service haut de gamme avec livraison en main propre. 
                        L'excellence Heritage Parfums jusqu'à votre porte.
                    </p>
                    <ul class="list-unstyled">
                        <li>✓ Coursier dédié</li>
                        <li>✓ Créneau personnalisé</li>
                        <li>✓ Emballage cadeau offert</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Nos Garanties -->
        <div class="expedition-section">
            <h2 class="expedition-title">Nos Garanties</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Emballage Sécurisé</h4>
                        <p>Vos parfums sont protégés dans un emballage spécialement conçu pour éviter tout dommage pendant le transport.</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Contrôle Température</h4>
                        <p>Transport à température contrôlée pour préserver la qualité et l'intégrité de vos parfums.</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Retours Gratuits</h4>
                        <p>Non satisfait ? Retournez votre commande gratuitement sous 30 jours (parfums non ouverts).</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Support Dédié</h4>
                        <p>Notre équipe client est disponible pour vous accompagner avant, pendant et après votre commande.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Livraison Internationale -->
        <div class="international-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto text-center">
                        <h2 class="expedition-subtitle">Livraison Internationale</h2>
                        <p class="expedition-text">
                            Heritage Parfums expédie dans le monde entier. Partagez l'art de la parfumerie française 
                            avec vos proches, où qu'ils soient.
                        </p>
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <h5>Europe</h5>
                                <p>5-7 jours • À partir de 15€</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h5>Amérique du Nord</h5>
                                <p>7-10 jours • À partir de 25€</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h5>Reste du Monde</h5>
                                <p>10-15 jours • À partir de 35€</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="expedition-section">
            <h2 class="expedition-title">Questions Fréquentes</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="faq-item">
                        <h4 class="faq-question">Comment puis-je suivre ma commande ?</h4>
                        <p class="faq-answer">
                            Dès l'expédition de votre commande, vous recevrez un email avec un numéro de suivi. 
                            Vous pourrez suivre votre colis en temps réel sur notre site ou celui du transporteur.
                        </p>
                    </div>
                    
                    <div class="faq-item">
                        <h4 class="faq-question">Que faire si mon colis est endommagé ?</h4>
                        <p class="faq-answer">
                            En cas de dommage, contactez-nous immédiatement avec des photos du colis et du produit. 
                            Nous organiserons un remplacement ou un remboursement dans les plus brefs délais.
                        </p>
                    </div>
                    
                    <div class="faq-item">
                        <h4 class="faq-question">Puis-je modifier mon adresse de livraison ?</h4>
                        <p class="faq-answer">
                            Vous pouvez modifier votre adresse tant que votre commande n'a pas été expédiée. 
                            Contactez notre service client au plus vite pour effectuer cette modification.
                        </p>
                    </div>
                    
                    <div class="faq-item">
                        <h4 class="faq-question">Livrez-vous en point relais ?</h4>
                        <p class="faq-answer">
                            Oui, nous proposons la livraison en point relais Mondial Relay pour plus de flexibilité. 
                            Cette option est disponible lors de votre commande.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="expedition-section text-center">
            <h2 class="expedition-title">Prêt à Commander ?</h2>
            <p class="expedition-text">
                Découvrez notre collection et choisissez votre méthode d'expédition préférée.
            </p>
            <a href="/" class="btn btn-guerlain mt-3">Explorer Nos Parfums</a>
        </div>
    </div>
</section>
@endsection