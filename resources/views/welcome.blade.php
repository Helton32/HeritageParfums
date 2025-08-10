@extends('layouts.app')

@section('title', 'Héritaj Parfums - Parfums d\'Exception')
@section('description', 'Découvrez notre collection de parfums d\'exception. Des créations uniques inspirées du savoir-faire français.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home-improvements.css') }}">


<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

<!-- Meta viewport optimisée pour mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">


<style>
/* Classe spéciale pour la page d'accueil - désactive le scroll normal */
body.home-page {
    overflow-y: hidden;
}

/* Amélioration : Transition smooth entre navbar et carrousel */
.fullscreen-carousel {
    animation: slideInFromTop 0.8s ease-out;
}

/* Ajustement pour remonter les produits dans le carrousel */
.carousel-content-wrapper {
    transform: translateY(-30px); /* Remonte les produits de 30px */
}

/* Styles pour les promotions dans le carrousel - AMÉLIORÉS */
.promotion-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: bold;
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    z-index: 10;
    animation: promotional-pulse 2s infinite;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

@keyframes promotional-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); box-shadow: 0 8px 25px rgba(220, 53, 69, 0.6); }
}

.price-container {
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}

.original-price {
    font-size: 1.4rem;
    color: rgba(255, 255, 255, 0.6);
    text-decoration: line-through;
    font-weight: 300;
    position: relative;
}

.original-price::after {
    content: '';
    position: absolute;
    top: 50%;
    left: -5px;
    right: -5px;
    height: 2px;
    background: #dc3545;
    transform: translateY(-50%);
}

.promotion-price {
    font-size: 2rem;
    color: var(--guerlain-gold);
    font-weight: 700;
    text-shadow: 0 0 15px rgba(212, 175, 55, 0.6);
    position: relative;
    animation: price-glow 3s ease-in-out infinite;
}

@keyframes price-glow {
    0%, 100% { text-shadow: 0 0 15px rgba(212, 175, 55, 0.6); }
    50% { text-shadow: 0 0 25px rgba(212, 175, 55, 0.8), 0 0 35px rgba(212, 175, 55, 0.4); }
}

.current-price {
    font-size: 1.8rem;
    color: var(--guerlain-gold);
    font-weight: 600;
    text-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    font-family: 'Cormorant Garamond', serif;
}

/* Badge économies */
.savings-badge {
    position: absolute;
    bottom: 15px;
    left: 15px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    z-index: 10;
}

@keyframes slideInFromTop {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Amélioration des boutons carrousel */
.discover-button,
.order-button {
    position: relative;
    overflow: hidden;
}

.discover-button::before,
.order-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.discover-button:hover::before,
.order-button:hover::before {
    left: 100%;
}

/* Indicateurs carrousel améliorés */
.nav-dot {
    position: relative;
}

.nav-dot::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: var(--guerlain-gold);
    border-radius: 50%;
    transition: all 0.3s ease;
    transform: translate(-50%, -50%);
}

.nav-dot.active::after {
    width: 20px;
    height: 20px;
    opacity: 0.3;
}

/* Animation de chargement pour les images */
.product-image {
    position: relative;
    background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%), 
                linear-gradient(-45deg, #f0f0f0 25%, transparent 25%), 
                linear-gradient(45deg, transparent 75%, #f0f0f0 75%), 
                linear-gradient(-45deg, transparent 75%, #f0f0f0 75%);
    background-size: 20px 20px;
    background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
}

.product-image.loaded {
    background: none;
}

/* Effets de transition entre slides */
.carousel-slide {
    backface-visibility: hidden;
    perspective: 1000px;
}

.carousel-slide.transitioning {
    animation: slideTransition 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

@keyframes slideTransition {
    0% { 
        opacity: 0;
        transform: translateY(30px) rotateX(-10deg);
    }
    100% { 
        opacity: 1;
        transform: translateY(0) rotateX(0deg);
    }
}
</style>
@endpush

@section('content')
<div class="fullscreen-carousel" id="fullscreen-carousel">
    <!-- Navigation points améliorée -->
    <div class="carousel-nav">
        @foreach($allActiveProducts as $index => $product)
        <div class="nav-dot {{ $index === 0 ? 'active' : '' }}" 
             onclick="goToSlide({{ $index }})" 
             data-slide="{{ $index }}"
             role="button"
             aria-label="Aller au produit {{ $index + 1 }}"
             tabindex="0"></div>
        @endforeach
        <!-- Point pour le footer -->
        <div class="nav-dot" 
             onclick="goToSlide({{ $allActiveProducts->count() }})" 
             data-slide="{{ $allActiveProducts->count() }}"
             role="button"
             aria-label="Aller au pied de page"
             tabindex="0"></div>
    </div>

    <!-- Slides des produits AMÉLIORÉS -->
    @foreach($allActiveProducts as $index => $product)
    <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" data-product-id="{{ $product->id }}">
        <div class="carousel-content-wrapper">
            <!-- Image à gauche avec améliorations -->
            <div class="product-image-zone">
                <img src="{{ $product->main_image }}"
                     alt="{{ $product->name }}"
                     class="product-image"
                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                     onload="this.classList.add('loaded')">
                
                @if($product->hasValidPromotion())
                    <div class="promotion-badge">
                        -{{ $product->getDiscountPercentage() }}%
                    </div>
                    @php
                        $savings = $product->price - $product->getCurrentPrice();
                    @endphp
                    <div class="savings-badge">
                        Économisez {{ number_format($savings, 2) }}€
                    </div>
                @endif
                
                @if($product->badge)
                    <div class="product-special-badge">
                        {{ $product->badge }}
                    </div>
                @endif
            </div>

            <!-- Texte à droite avec améliorations -->
            <div class="content-zone">
                <div class="signature-text fade-in">
                    @if($product->product_type === 'parfum')
                        Parfum d'Exception
                    @else
                        Cosmétique de Luxe
                    @endif
                </div>

                <h1 class="product-title fade-in delay-1">
                    {{ mb_strtoupper($product->name, 'UTF-8') }}
                </h1>

                <p class="product-subtitle fade-in delay-2">
                    {{ $product->short_description ?? 'Une création unique signée Héritaj' }}
                </p>

                <!-- Prix amélioré avec animations -->
                @if($product->hasValidPromotion())
                    <div class="price-container fade-in delay-2">
                        <span class="original-price">{{ $product->formatted_price }}</span>
                        <span class="promotion-price">{{ $product->formatted_current_price }}</span>
                    </div>
                    <div class="promotion-details fade-in delay-2">
                        <small class="promotion-info">
                            <i class="fas fa-clock me-1"></i>
                            Offre limitée - Jusqu'à épuisement des stocks
                        </small>
                    </div>
                @else
                    <div class="price-container fade-in delay-2">
                        <span class="current-price">{{ $product->formatted_price }}</span>
                    </div>
                @endif

                <!-- Informations supplémentaires -->
                <div class="product-info-extra fade-in delay-2">
                    <div class="product-features">
                        @if($product->product_type === 'parfum')
                            <span class="feature"><i class="fas fa-leaf me-1"></i>{{ $product->size }}</span>
                            <span class="feature"><i class="fas fa-certificate me-1"></i>Eau de Parfum</span>
                        @else
                            <span class="feature"><i class="fas fa-spa me-1"></i>{{ $product->size }}</span>
                            <span class="feature"><i class="fas fa-leaf me-1"></i>Formule naturelle</span>
                        @endif
                        <span class="feature"><i class="fas fa-shipping-fast me-1"></i>Livraison 24/48h</span>
                    </div>
                </div>

                <div class="buttons-container fade-in delay-3">
                    <a href="{{ route('product.show', $product->slug) }}" 
                       class="discover-button"
                       aria-label="Découvrir {{ $product->name }}">
                        <span>Découvrir</span>
                    </a>
                    <button class="order-button"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            onclick="addToCartFromCarousel(this)"
                            aria-label="Ajouter {{ $product->name }} au panier">
                        <span>Commander maintenant</span>
                    </button>
                </div>

                <!-- Avis clients (simulation) -->
                <div class="customer-rating fade-in delay-3">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="rating-text">4.9/5 - {{ rand(45, 127) }} avis clients</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    
    <!-- Footer comme slide final AMÉLIORÉ -->
    <div class="carousel-slide footer-slide" data-slide="{{ $allActiveProducts->count() }}">
        <div class="footer-content">
            <div class="footer-grid">
                <div>
                    <h5><i class="fas fa-crown me-2"></i>Héritaj Parfums</h5>
                    <p>Depuis 1925, nous créons des parfums d'exception qui capturent l'essence de l'élégance française et l'art de la parfumerie traditionnelle.</p>
                    
                    <!-- Statistiques de la marque -->
                    <div class="brand-stats">
                        <div class="stat">
                            <span class="stat-number">100</span>
                            <span class="stat-label">Ans d'expertise</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">50+</span>
                            <span class="stat-label">Créations uniques</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">25K+</span>
                            <span class="stat-label">Clients satisfaits</span>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
   
            
                <div>
                    <h5><i class="fas fa-envelope me-2"></i>Newsletter</h5>
                    <p>Restez informé de nos actualités exclusives et bénéficiez d'offres privilégiées</p>
                    <form class="newsletter-form" id="homeNewsletterForm">
                        <div class="d-flex flex-column gap-2">
                            <input type="email" 
                                   class="form-control" 
                                   placeholder="Votre email" 
                                   required
                                   style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 25px;">
                            <button type="submit" class="discover-button">
                                <i class="fas fa-paper-plane me-1"></i>S'inscrire
                            </button>
                        </div>
                       
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-legal">
                    <p>&copy; 2025 Héritaj Parfums. Tous droits réservés.</p>
                    <div class="legal-links">
                        <a href="#">Mentions Légales</a>
                        <a href="#">Politique de Confidentialité</a>
                        <a href="#">CGV</a>
                        <a href="#">Cookies</a>
                    </div>
                </div>
                <div class="footer-certifications">
                    <span class="certification">
                        <i class="fas fa-shield-alt me-1"></i>
                        Paiement Sécurisé SSL
                    </span>
                    <span class="certification">
                        <i class="fas fa-leaf me-1"></i>
                        Ingrédients Naturels
                    </span>
                    <span class="certification">
                        <i class="fas fa-award me-1"></i>
                        Made in France
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicateur de scroll amélioré -->
    <div class="scroll-indicator" id="scroll-indicator">
        <span>Explorez</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ===============================================
// CAROUSEL MOBILE CORRIGÉ - HERITAGE PARFUMS
// Solution pour l'erreur "Cannot access 'isMobile' before initialization"
// ===============================================

// Variables globales CORRECTEMENT déclarées pour éviter l'erreur de hoisting
let currentSlide = 0;
let isScrolling = false;
let touchStartY = 0;
let touchEndY = 0;
let autoPlayInterval = null;
let isDeviceMobile = false; // Renommé pour éviter les conflits
let isLandscape = false;
let slides, dots, totalSlides, scrollIndicator;

// Configuration
const AUTO_PLAY_DELAY = 8000;
const MOBILE_BREAKPOINT = 991;

// Fonction pour détecter l'appareil mobile (CORRECTION DU BUG PRINCIPAL)
function detectMobileDevice() {
    isDeviceMobile = window.innerWidth <= MOBILE_BREAKPOINT;
    isLandscape = window.innerHeight < window.innerWidth;
    return isDeviceMobile;
}

// Fonction optimisée pour aller à un slide spécifique (MOBILE FIRST)
function goToSlide(slideIndex, direction = 'next') {
    if (!slides || slides.length === 0) return;
    
    if (slideIndex < 0) slideIndex = 0;
    if (slideIndex >= totalSlides) slideIndex = totalSlides - 1;
    if (slideIndex === currentSlide) return;
    
    // Détecter mobile à chaque appel pour éviter les bugs
    detectMobileDevice();
    
    if (isDeviceMobile) {
        // Version mobile ultra-optimisée - BOUTONS TOUJOURS ACCESSIBLES
        slides.forEach((slide, index) => {
            slide.classList.remove('active');
            // CORRECTION : Utiliser opacity au lieu de display pour garder les boutons accessibles
            slide.style.opacity = '0';
            slide.style.visibility = 'hidden';
            slide.style.pointerEvents = 'none'; // Empêche l'interaction générale
            slide.style.zIndex = '1'; // Z-index bas pour slides inactives
        });
        
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Afficher le slide actif avec optimisations anti-blur
        const targetSlide = slides[slideIndex];
        if (targetSlide) {
            targetSlide.classList.add('active');
            targetSlide.style.display = 'flex'; // Garde le display flex pour la slide active
            targetSlide.style.visibility = 'visible';
            targetSlide.style.pointerEvents = 'auto'; // Permet l'interaction générale
            targetSlide.style.zIndex = '10'; // Z-index élevé pour slide active
            
            // Animation fade simple et performante
            requestAnimationFrame(() => {
                targetSlide.style.opacity = '1';
                // Force un reflow pour corriger le blur
                targetSlide.offsetHeight;
            });
        }
        
        if (dots[slideIndex]) {
            dots[slideIndex].classList.add('active');
        }
        
        // CORRECTION FINALE : Assure que tous les boutons restent accessibles
        const allButtons = document.querySelectorAll('.discover-button, .order-button');
        allButtons.forEach(button => {
            button.style.pointerEvents = 'auto';
            button.style.zIndex = '1000';
            button.style.opacity = '1';
            button.style.visibility = 'visible';
        });
        
    } else {
        // Version desktop avec animations
        const currentSlideElement = slides[currentSlide];
        const nextSlideElement = slides[slideIndex];
        
        if (currentSlideElement && nextSlideElement) {
            // Animation de sortie
            currentSlideElement.style.opacity = '0';
            currentSlideElement.style.visibility = 'hidden';
            
            setTimeout(() => {
                // Reset tous les slides
                slides.forEach(slide => {
                    slide.classList.remove('active');
                    slide.style.opacity = '';
                    slide.style.visibility = '';
                });
                dots.forEach(dot => dot.classList.remove('active'));
                
                // Activer le nouveau slide
                nextSlideElement.classList.add('active');
                nextSlideElement.style.opacity = '1';
                nextSlideElement.style.visibility = 'visible';
                
                if (dots[slideIndex]) {
                    dots[slideIndex].classList.add('active');
                }
            }, 200);
        }
    }
    
    currentSlide = slideIndex;
    
    // Gestion de l'indicateur de scroll
    if (scrollIndicator) {
        if (slideIndex === totalSlides - 1) {
            scrollIndicator.style.opacity = '0';
            scrollIndicator.style.pointerEvents = 'none';
        } else {
            scrollIndicator.style.opacity = '1';
            scrollIndicator.style.pointerEvents = 'auto';
        }
    }
    
    // Analytics de suivi
    trackSlideView(slideIndex);
    
    // Reset auto-play
    resetAutoPlay();
}

// Gestion du scroll optimisée - SEULEMENT VERTICAL
function handleScroll(e) {
    if (isScrolling) return;
    
    e.preventDefault();
    e.stopPropagation();
    isScrolling = true;
    
    // SEULEMENT scroll vertical (deltaY) - pas de navigation horizontale
    const delta = e.deltaY;
    const threshold = isDeviceMobile ? 25 : 50;
    
    // Ignorer le scroll horizontal (deltaX)
    if (Math.abs(e.deltaX || 0) > Math.abs(delta)) {
        isScrolling = false;
        return;
    }
    
    if (Math.abs(delta) > threshold) {
        if (delta > 0) {
            // Scroll vers le bas - slide suivant
            if (currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1, 'next');
            }
        } else {
            // Scroll vers le haut - slide précédent
            if (currentSlide > 0) {
                goToSlide(currentSlide - 1, 'prev');
            }
        }
    }
    
    // Débounce adaptatif
    const debounceTime = isDeviceMobile ? 600 : 800;
    setTimeout(() => {
        isScrolling = false;
    }, debounceTime);
}

// Navigation au clavier - SEULEMENT VERTICAL
function handleKeyboard(e) {
    if (isScrolling) return;
    
    switch(e.key) {
        case 'ArrowUp':
            e.preventDefault();
            if (currentSlide > 0) {
                goToSlide(currentSlide - 1, 'prev');
            }
            break;
        case 'ArrowDown':
        case ' ':
            e.preventDefault();
            if (currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1, 'next');
            }
            break;
        case 'Home':
            e.preventDefault();
            goToSlide(0, 'next');
            break;
        case 'End':
            e.preventDefault();
            goToSlide(totalSlides - 1, 'next');
            break;
    }
}

// Gestion tactile optimisée
function handleTouchStart(e) {
    touchStartY = e.changedTouches[0].screenY;
    stopAutoPlay();
}

function handleTouchEnd(e) {
    if (isScrolling) return;
    
    touchEndY = e.changedTouches[0].screenY;
    const difference = touchStartY - touchEndY;
    
    // Seuil adaptatif selon la taille d'écran
    let threshold = 30;
    if (window.innerWidth <= 360) threshold = 20;
    else if (window.innerWidth <= 480) threshold = 25;
    else if (isDeviceMobile) threshold = 35;
    
    if (Math.abs(difference) > threshold) {
        isScrolling = true;
        
        if (difference > 0) {
            // Swipe vers le haut - slide suivant
            if (currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1, 'next');
            }
        } else {
            // Swipe vers le bas - slide précédent
            if (currentSlide > 0) {
                goToSlide(currentSlide - 1, 'prev');
            }
        }
        
        setTimeout(() => {
            isScrolling = false;
            resetAutoPlay();
        }, 500);
    } else {
        setTimeout(() => resetAutoPlay(), 1000);
    }
}

// Gestion de l'orientation
function handleOrientationChange() {
    setTimeout(() => {
        const wasDeviceMobile = isDeviceMobile;
        detectMobileDevice();
        
        // Si changement de mode (mobile/desktop)
        if (wasDeviceMobile !== isDeviceMobile) {
            initializeCarousel();
        }
        
        // Forcer un reflow sur mobile pour corriger les problèmes d'affichage
        if (isDeviceMobile && slides && slides[currentSlide]) {
            const activeSlide = slides[currentSlide];
            activeSlide.style.display = 'none';
            activeSlide.offsetHeight; // Force reflow
            activeSlide.style.display = 'flex';
        }
    }, 300);
}

// Event listeners optimisés
function updateEventListeners() {
    // Supprimer les anciens listeners
    document.removeEventListener('wheel', handleScroll);
    document.removeEventListener('DOMMouseScroll', handleScroll);
    document.removeEventListener('touchstart', handleTouchStart);
    document.removeEventListener('touchend', handleTouchEnd);
    document.removeEventListener('keydown', handleKeyboard);
    
    // Ajouter les nouveaux listeners selon l'appareil
    if (isDeviceMobile) {
        // Mobile : privilégier le tactile
        document.addEventListener('touchstart', handleTouchStart, { passive: true });
        document.addEventListener('touchend', handleTouchEnd, { passive: true });
        document.addEventListener('wheel', handleScroll, { passive: false });
    } else {
        // Desktop : scroll et clavier
        document.addEventListener('wheel', handleScroll, { passive: false });
        document.addEventListener('DOMMouseScroll', handleScroll, { passive: false });
        document.addEventListener('keydown', handleKeyboard, { passive: false });
    }
}

// Auto-play
function startAutoPlay() {
    if (currentSlide < totalSlides - 2) { // Exclure le footer
        autoPlayInterval = setInterval(() => {
            if (currentSlide < totalSlides - 2) {
                goToSlide(currentSlide + 1, 'next');
            } else {
                stopAutoPlay();
            }
        }, AUTO_PLAY_DELAY);
    }
}

function stopAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
        autoPlayInterval = null;
    }
}

function resetAutoPlay() {
    stopAutoPlay();
    setTimeout(() => {
        if (currentSlide < totalSlides - 2) {
            startAutoPlay();
        }
    }, 3000);
}

// Initialisation du carousel CORRIGÉE
function initializeCarousel() {
    // Récupérer les éléments DOM
    slides = document.querySelectorAll('.carousel-slide');
    dots = document.querySelectorAll('.nav-dot');
    totalSlides = slides.length;
    scrollIndicator = document.getElementById('scroll-indicator');
    
    if (!slides || slides.length === 0) {
        console.warn('Aucun slide trouvé');
        return;
    }
    
    // Détecter l'appareil
    detectMobileDevice();
    
    // Configuration mobile spécifique pour éviter le blur
    if (isDeviceMobile) {
        document.body.style.webkitTouchCallout = 'none';
        document.body.style.webkitUserSelect = 'none';
        document.body.style.userSelect = 'none';
        document.body.style.touchAction = 'pan-y';
        
        // Préparer tous les slides pour mobile
        slides.forEach((slide, index) => {
            slide.style.webkitTransform = 'translateZ(0)';
            slide.style.transform = 'translateZ(0)';
            slide.style.webkitBackfaceVisibility = 'hidden';
            slide.style.backfaceVisibility = 'hidden';
            
            if (index === 0) {
                slide.classList.add('active');
                slide.style.display = 'flex';
                slide.style.opacity = '1';
                slide.style.visibility = 'visible';
            } else {
                slide.classList.remove('active');
                slide.style.display = 'none';
                slide.style.opacity = '0';
                slide.style.visibility = 'hidden';
            }
        });
        
        // Activer le premier dot
        if (dots[0]) {
            dots[0].classList.add('active');
        }
    }
    
    // Event listeners
    updateEventListeners();
    
    // Event listeners pour orientation et redimensionnement
    window.addEventListener('orientationchange', handleOrientationChange, { passive: true });
    window.addEventListener('resize', () => {
        clearTimeout(window.resizeTimeout);
        window.resizeTimeout = setTimeout(handleOrientationChange, 200);
    }, { passive: true });
    
    // Navigation dots
    dots.forEach((dot, index) => {
        // Clic/tap
        dot.addEventListener('click', (e) => {
            e.preventDefault();
            goToSlide(index);
        }, { passive: false });
        
        // Support clavier
        dot.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                goToSlide(index);
            }
        });
    });
    
    // Scroll indicator
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1, 'next');
            }
        });
        
        if (isDeviceMobile) {
            scrollIndicator.addEventListener('touchend', function(e) {
                e.preventDefault();
                if (currentSlide < totalSlides - 1) {
                    goToSlide(currentSlide + 1, 'next');
                }
            });
        }
    }
    
    // Initialiser le premier slide
    goToSlide(0);
    
    // Préchargement intelligent des images
    preloadImages();
    
    // Démarrer l'auto-play après un délai
    setTimeout(() => {
        startAutoPlay();
    }, AUTO_PLAY_DELAY);
    
    // Newsletter form du footer
    const newsletterForm = document.getElementById('homeNewsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            showAdvancedNotification('🎉 Bienvenue ! Vous recevrez bientôt votre code promo de -10% !', 'success');
            this.reset();
        });
    }
    
    // Gestion visibilité page
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoPlay();
        } else {
            setTimeout(() => resetAutoPlay(), 2000);
        }
    });
    
    // Pause auto-play sur interaction
    ['wheel', 'touchstart', 'keydown', 'click'].forEach(event => {
        document.addEventListener(event, () => {
            stopAutoPlay();
            setTimeout(() => resetAutoPlay(), 8000);
        }, { once: false, passive: true });
    });
}

// Fonction pour précharger les images
function preloadImages() {
    const images = document.querySelectorAll('.product-image[loading="lazy"]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const newImg = new Image();
                    newImg.onload = () => {
                        img.src = newImg.src;
                        img.classList.add('loaded');
                    };
                    newImg.src = img.src;
                    imageObserver.unobserve(img);
                }
            });
        }, { rootMargin: '100px' });
        
        images.forEach(img => imageObserver.observe(img));
    }
}

// Fonction pour ajouter au panier depuis le carrousel
function addToCartFromCarousel(button) {
    const productId = button.dataset.productId;
    const productName = button.dataset.productName;
    
    // Vérification de sécurité
    if (!productId || !productName) {
        showAdvancedNotification('❌ Erreur: Données produit manquantes', 'error');
        return;
    }
    
    // Animation du bouton
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.classList.add('loading');
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Ajout...</span>';
    
    // Arrêter l'auto-play
    stopAutoPlay();
    
    // Vérifier le token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showAdvancedNotification('❌ Erreur de sécurité', 'error');
        resetButton(button, originalContent);
        return;
    }
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: parseInt(productId),
            quantity: 1
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Mettre à jour le compteur du panier
            updateCartCountAnimated(data.cart_count);
            
            // Animation de succès
            button.innerHTML = '<i class="fas fa-check"></i> <span>Ajouté !</span>';
            button.classList.remove('loading');
            button.classList.add('success');
            
            // Notification de succès
            showAdvancedNotification(`✨ ${productName} ajouté au panier !`, 'success');
            
            // Analytics
            trackCarouselAddToCart(productId, productName);
            
            // Restaurer le bouton après 3 secondes
            setTimeout(() => {
                resetButton(button, originalContent);
            }, 3000);
            
        } else {
            showAdvancedNotification(data.message || 'Erreur lors de l\'ajout au panier', 'error');
            resetButton(button, originalContent);
        }
    })
    .catch(error => {
        console.error('Erreur ajout panier:', error);
        showAdvancedNotification('❌ Erreur de connexion. Veuillez réessayer.', 'error');
        resetButton(button, originalContent);
    });
}

// Fonctions utilitaires
function resetButton(button, originalContent) {
    button.innerHTML = originalContent;
    button.classList.remove('loading', 'success');
    button.disabled = false;
    setTimeout(() => resetAutoPlay(), 1000);
}

function updateCartCountAnimated(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.style.transform = 'scale(1.3) rotate(360deg)';
        cartCountElement.style.background = '#28a745';
        
        setTimeout(() => {
            cartCountElement.textContent = count;
            cartCountElement.style.display = count > 0 ? 'flex' : 'none';
            
            setTimeout(() => {
                cartCountElement.style.transform = 'scale(1) rotate(0deg)';
                cartCountElement.style.background = '';
            }, 200);
        }, 100);
    }
}

function showAdvancedNotification(message, type = 'success') {
    // Vérifier si la fonction de notification globale existe
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
    } else {
        // Fallback : créer une notification simple
        console.log(`${type.toUpperCase()}: ${message}`);
        
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            border-radius: 8px;
            font-size: 0.9rem;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Supprimer après 4 secondes
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }
}

// Analytics functions
function trackSlideView(slideIndex) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'carousel_slide_view', {
            slide_index: slideIndex,
            slide_name: slides[slideIndex]?.dataset.productId ? 'product' : 'footer'
        });
    }
}

function trackCarouselAddToCart(productId, productName) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'add_to_cart_carousel', {
            item_id: productId,
            item_name: productName,
            source: 'home_carousel'
        });
    }
}

// Initialisation automatique CORRIGÉE
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter la classe spéciale au body pour la page d'accueil
    document.body.classList.add('home-page');
    
    // CORRECTION MOBILE : Forcer l'affichage du carousel
    if (window.innerWidth <= 991) {
        // Force le style du carousel sur mobile
        const carousel = document.querySelector('.fullscreen-carousel');
        if (carousel) {
            carousel.style.display = 'block';
            carousel.style.visibility = 'visible';
            carousel.style.height = '100vh';
        }
        
        // Force l'affichage des slides - BOUTONS TOUJOURS ACCESSIBLES
        const allSlides = document.querySelectorAll('.carousel-slide');
        allSlides.forEach((slide, index) => {
            // CORRECTION : Toutes les slides gardent leur structure DOM
            slide.style.display = 'flex'; // Toutes les slides gardent display flex
            if (index === 0) {
                slide.style.opacity = '1';
                slide.style.visibility = 'visible';
                slide.style.pointerEvents = 'auto';
                slide.style.zIndex = '10';
                slide.classList.add('active');
            } else {
                slide.style.opacity = '0';
                slide.style.visibility = 'hidden';
                slide.style.pointerEvents = 'none';
                slide.style.zIndex = '1';
                slide.classList.remove('active');
            }
        });
        
        // Force l'affichage de la navigation
        const nav = document.querySelector('.carousel-nav');
        if (nav) {
            nav.style.display = 'flex';
            nav.style.visibility = 'visible';
            nav.style.opacity = '1';
            nav.style.zIndex = '100';
        }
        
        // Active le premier dot
        const dots = document.querySelectorAll('.nav-dot');
        if (dots[0]) {
            dots[0].classList.add('active');
        }
        
        // CORRECTION CRITIQUE : Force l'accessibilité de tous les boutons "Découvrir"
        const allDiscoverButtons = document.querySelectorAll('.discover-button, .order-button');
        allDiscoverButtons.forEach(button => {
            button.style.pointerEvents = 'auto';
            button.style.zIndex = '1000';
            button.style.position = 'relative';
            button.style.opacity = '1';
            button.style.visibility = 'visible';
        });
    }
    
    // Initialiser le carousel après le fix mobile
    setTimeout(() => {
        initializeCarousel();
    }, 100);
    
    // Désactiver les animations fade-in sur mobile pour de meilleures performances
    if (window.innerWidth <= 991) {
        document.querySelectorAll('.fade-in').forEach(element => {
            element.style.opacity = '1';
            element.style.transform = 'none';
        });
    } else {
        // Animations desktop
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '50px'
        };
        
        const fadeInObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    fadeInObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.fade-in').forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'all 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            fadeInObserver.observe(element);
        });
    }
});

</script>
@endpush

<!-- Styles additionnels pour les nouvelles fonctionnalités -->
<style>
.product-info-extra {
    margin-bottom: 1.5rem;
}

.product-features {
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem;
    justify-content: center;
}

.feature {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
}

.customer-rating {
    margin-top: 1.5rem;
    text-align: center;
}

.customer-rating .stars {
    color: var(--guerlain-gold);
    margin-bottom: 0.3rem;
}

.customer-rating .rating-text {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
}

.promotion-details {
    text-align: center;
    margin-bottom: 1rem;
}

.promotion-info {
    color: var(--guerlain-gold);
    font-style: italic;
    background: rgba(212, 175, 55, 0.1);
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.product-special-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, var(--guerlain-gold), var(--guerlain-dark-gold));
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 10;
}

.brand-stats {
    display: flex;
    gap: 1.5rem;
    margin: 1.5rem 0;
    flex-wrap: wrap;
}

.stat {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--guerlain-gold);
    font-family: 'Cormorant Garamond', serif;
}

.stat-label {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.8);
}

.footer-bottom {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-legal {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.legal-links {
    display: flex;
    gap: 1rem;
}

.legal-links a {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

.footer-certifications {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

.certification {
    font-size: 0.8rem;
    color: var(--guerlain-gold);
    background: rgba(212, 175, 55, 0.1);
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.loading {
    pointer-events: none;
    opacity: 0.8;
}

.success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

@media (max-width: 768px) {
    .product-features {
        flex-direction: column;
        align-items: center;
    }
    
    .brand-stats {
        justify-content: center;
        gap: 1rem;
    }
    
    .footer-legal {
        flex-direction: column;
        text-align: center;
    }
    
    .footer-certifications {
        flex-direction: column;
        align-items: center;
    }
}
</style>
