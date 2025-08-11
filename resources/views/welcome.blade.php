@extends('layouts.app')

@section('title', 'Héritaj Parfums - Parfums d\'Exception')
@section('description', 'Découvrez notre collection de parfums d\'exception. Des créations uniques inspirées du savoir-faire français.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home-improvements.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;500;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Meta viewport optimisée pour mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<style>
/* STYLES ADDITIONNELS GUERLAIN FULLSCREEN */
/* Assure que l'effet plein écran fonctionne parfaitement */

/* Force le plein écran même avec navbar */
.navbar {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 1000 !important;
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(10px) !important;
    -webkit-backdrop-filter: blur(10px) !important;
    transition: transform 0.3s ease !important;
    transform: translateY(0) !important;
}

/* Navbar cachée au scroll */
.navbar.hidden-scroll {
    transform: translateY(-100%) !important;
}

.navbar.visible-scroll {
    transform: translateY(0) !important;
}

/* Badges et promotions sur l'image plein écran */
.promotion-badge {
    position: absolute !important;
    top: 2rem !important;
    right: 2rem !important;
    background: linear-gradient(135deg, #dc3545, #e74c3c) !important;
    color: white !important;
    padding: 0.8rem 1.5rem !important;
    border-radius: 30px !important;
    font-size: 1rem !important;
    font-weight: bold !important;
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4) !important;
    z-index: 15 !important;
    animation: promotional-pulse 2s infinite !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
}

@keyframes promotional-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); box-shadow: 0 10px 30px rgba(220, 53, 69, 0.6); }
}

.savings-badge {
    position: absolute !important;
    bottom: 2rem !important;
    left: 2rem !important;
    background: linear-gradient(135deg, #28a745, #20c997) !important;
    color: white !important;
    padding: 0.6rem 1.2rem !important;
    border-radius: 25px !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3) !important;
    z-index: 15 !important;
}

/* Améliorations des éléments de contenu superposés */
.product-features {
    margin-bottom: 1.5rem !important;
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 0.8rem !important;
    justify-content: flex-start !important;
}

.feature {
    background: rgba(255, 255, 255, 0.1) !important;
    padding: 0.5rem 1rem !important;
    border-radius: 20px !important;
    font-size: 0.85rem !important;
    color: rgba(255, 255, 255, 0.95) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    backdrop-filter: blur(10px) !important;
    -webkit-backdrop-filter: blur(10px) !important;
}

.customer-rating {
    margin-top: 1.5rem !important;
    text-align: left !important;
}

.customer-rating .stars {
    color: var(--guerlain-gold) !important;
    margin-bottom: 0.3rem !important;
    font-size: 1rem !important;
}

.customer-rating .rating-text {
    font-size: 0.9rem !important;
    color: rgba(255, 255, 255, 0.8) !important;
}

/* Responsive mobile pour plein écran */
@media (max-width: 991px) {
    .promotion-badge {
        top: 1rem !important;
        right: 1rem !important;
        padding: 0.6rem 1.2rem !important;
        font-size: 0.9rem !important;
    }
    
    .savings-badge {
        bottom: 1rem !important;
        left: 1rem !important;
        padding: 0.5rem 1rem !important;
        font-size: 0.8rem !important;
    }
    
    .product-features {
        justify-content: center !important;
    }
    
    .feature {
        font-size: 0.75rem !important;
        padding: 0.4rem 0.8rem !important;
    }
    
    .customer-rating {
        text-align: center !important;
        margin-top: 1rem !important;
    }
}

/* Animation d'entrée pour les éléments */
.fade-in {
    opacity: 0 !important;
    transform: translateY(20px) !important;
    transition: all 1s ease-out !important;
}

.fade-in.delay-1 { transition-delay: 0.2s !important; }
.fade-in.delay-2 { transition-delay: 0.4s !important; }
.fade-in.delay-3 { transition-delay: 0.6s !important; }
.fade-in.delay-4 { transition-delay: 0.8s !important; }

/* Force l'affichage des animations sur desktop */
@media (min-width: 992px) {
    .fade-in.show {
        opacity: 1 !important;
        transform: translateY(0) !important;
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

    <!-- Slides des produits STYLE GUERLAIN PLEIN ÉCRAN -->
    @foreach($allActiveProducts as $index => $product)
    <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" data-product-id="{{ $product->id }}">
        <div class="carousel-content-wrapper">
            <!-- Image plein écran arrière-plan -->
            <div class="product-image-zone">
                <img src="{{ $product->main_image }}"
                     alt="{{ $product->name }}"
                     class="product-image"
                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                     onload="this.classList.add('loaded')">
                
                <!-- Badges superposés sur l'image -->
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

            <!-- Contenu texte superposé style Guerlain -->
            <div class="content-zone">
                <div class="signature-text fade-in">
                    {{ mb_strtoupper($product->brand ?? "L'éveil d'une jungle parfumée", 'UTF-8') }}
                </div>

                <h1 class="product-title fade-in delay-1">
                    {{ mb_strtoupper($product->name, 'UTF-8') }}
                </h1>

                <p class="product-subtitle fade-in delay-2">
                    {{ mb_strtoupper($product->short_description ?? "L'éveil d'une jungle parfumée", 'UTF-8') }}
                </p>

               

                <!-- Bouton Découvrir unique -->
                <div class="buttons-container fade-in delay-3">
                    <a href="{{ route('product.show', $product->slug) }}" 
                       class="discover-button"
                       aria-label="Découvrir {{ $product->name }}">
                        <span>Découvrir</span>
                    </a>
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

    <!-- Indicateur de scroll minimaliste (masqué sur mobile) -->
    <div class="scroll-indicator" id="scroll-indicator" style="display: none;">
        <span>Explorez</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ===============================================
// CAROUSEL GUERLAIN STYLE - HERITAGE PARFUMS
// Système de navigation par scroll vertical avec navbar qui disparaît
// ===============================================

// Variables globales
let currentSlide = 0;
let isScrolling = false;
let touchStartX = 0;
let touchStartY = 0;
let touchEndX = 0;
let touchEndY = 0;
let slides, dots, totalSlides, scrollIndicator;
let lastScrollTime = 0;
let scrollTimer = null;
let navbar;
let carouselContainer;

// Configuration
const SCROLL_COOLDOWN = 600;
const MOBILE_BREAKPOINT = 991;

// Détection mobile
function isMobile() {
    return window.innerWidth <= MOBILE_BREAKPOINT;
}

// === GESTION NAVBAR SCROLL ===
function initNavbarScrollBehavior() {
    navbar = document.querySelector('.navbar');
    if (!navbar) return;
    
    let isNavbarVisible = true;
    let scrollTimeout;
    
    function hideNavbar() {
        if (!navbar) return;
        navbar.classList.add('hidden-scroll');
        navbar.classList.remove('visible-scroll');
        isNavbarVisible = false;
    }
    
    function showNavbar() {
        if (!navbar) return;
        navbar.classList.remove('hidden-scroll');
        navbar.classList.add('visible-scroll');
        isNavbarVisible = true;
    }
    
    // Cacher la navbar pendant le scroll
    function onScrollStart() {
        if (isMobile()) {
            hideNavbar();
        }
        
        // Réafficher après 1.5 secondes d'inactivité
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            showNavbar();
        }, 1500);
    }
    
    // Attacher aux événements de scroll
    if (!isMobile()) {
        window.addEventListener('wheel', onScrollStart, { passive: true });
    }
    window.addEventListener('touchstart', onScrollStart, { passive: true });
    window.addEventListener('touchmove', onScrollStart, { passive: true });
}

// === NAVIGATION GUERLAIN ===
function goToSlide(slideIndex, direction = 'next') {
    if (!slides || slides.length === 0) return;
    
    if (slideIndex < 0) slideIndex = 0;
    if (slideIndex >= totalSlides) slideIndex = totalSlides - 1;
    if (slideIndex === currentSlide) return;
    
    if (isMobile()) {
        // Mobile : défilement horizontal
        const slideWidth = window.innerWidth;
        const scrollPosition = slideIndex * slideWidth;
        
        if (carouselContainer) {
            carouselContainer.scrollTo({
                left: scrollPosition,
                behavior: 'smooth'
            });
        }
        
        currentSlide = slideIndex;
        
        // Mettre à jour l'indicateur mobile
        updateMobileScrollIndicator(slideIndex);
        
    } else {
        // Desktop : système vertical existant
        slides.forEach((slide, index) => {
            slide.classList.remove('active');
            slide.style.opacity = '0';
            slide.style.visibility = 'hidden';
        });
        
        // Navigation dots (seulement desktop)
        if (dots) {
            dots.forEach(dot => dot.classList.remove('active'));
            if (dots[slideIndex]) {
                dots[slideIndex].classList.add('active');
            }
        }
        
        // Afficher le slide actif
        const targetSlide = slides[slideIndex];
        if (targetSlide) {
            targetSlide.classList.add('active');
            targetSlide.style.opacity = '1';
            targetSlide.style.visibility = 'visible';
        }
        
        currentSlide = slideIndex;
        
        // Gestion scroll indicator
        if (scrollIndicator) {
            if (slideIndex === totalSlides - 1) {
                scrollIndicator.style.opacity = '0';
            } else {
                scrollIndicator.style.opacity = '1';
            }
        }
    }
}

// === GESTION SCROLL DESKTOP ===
function handleScroll(e) {
    if (isMobile() || isScrolling) return;
    
    e.preventDefault();
    e.stopPropagation();
    
    const currentTime = Date.now();
    if (currentTime - lastScrollTime < SCROLL_COOLDOWN) return;
    
    isScrolling = true;
    lastScrollTime = currentTime;
    
    const delta = e.deltaY;
    
    if (Math.abs(delta) > 30) {
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
    
    setTimeout(() => {
        isScrolling = false;
    }, SCROLL_COOLDOWN);
}

// === GESTION TACTILE MOBILE HORIZONTALE ===
function handleTouchStart(e) {
    touchStartX = e.changedTouches[0].screenX;
    touchStartY = e.changedTouches[0].screenY;
}

function handleTouchEnd(e) {
    if (isScrolling) return;
    
    touchEndX = e.changedTouches[0].screenX;
    touchEndY = e.changedTouches[0].screenY;
    
    if (isMobile()) {
        // Mobile : geste horizontal
        const deltaX = touchStartX - touchEndX;
        const deltaY = Math.abs(touchStartY - touchEndY);
        
        // Vérifier que c'est bien un swipe horizontal (et pas vertical)
        if (Math.abs(deltaX) > 50 && deltaY < 100) {
            if (deltaX > 0) {
                // Swipe gauche - slide suivant
                if (currentSlide < totalSlides - 1) {
                    goToSlide(currentSlide + 1, 'next');
                }
            } else {
                // Swipe droite - slide précédent
                if (currentSlide > 0) {
                    goToSlide(currentSlide - 1, 'prev');
                }
            }
        }
    } else {
        // Desktop : geste vertical
        const difference = touchStartY - touchEndY;
        if (Math.abs(difference) > 50) {
            handleScroll({ deltaY: difference });
        }
    }
}

// === INDICATEUR MOBILE ===
function createMobileScrollIndicator() {
    if (!isMobile()) return;
    
    const indicator = document.createElement('div');
    indicator.className = 'scroll-indicator-mobile';
    indicator.id = 'mobile-scroll-indicator';
    document.body.appendChild(indicator);
    
    // Masquer après 3 secondes
    setTimeout(() => {
        indicator.classList.add('hidden');
    }, 3000);
    
    return indicator;
}

function updateMobileScrollIndicator(slideIndex) {
    if (!isMobile()) return;
    
    const indicator = document.getElementById('mobile-scroll-indicator');
    if (indicator) {
        indicator.textContent = ``;
        
        // Afficher temporairement
        indicator.classList.remove('hidden');
        
        // Masquer après 2 secondes
        setTimeout(() => {
            indicator.classList.add('hidden');
        }, 2000);
    }
}

// === CONFIGURATION MOBILE HORIZONTALE ===
function setupMobileHorizontalCarousel() {
    if (!isMobile() || !carouselContainer) return;
    
    // Configurer le conteneur pour le défilement horizontal
    carouselContainer.style.display = 'flex';
    carouselContainer.style.flexDirection = 'row';
    carouselContainer.style.overflowX = 'auto';
    carouselContainer.style.overflowY = 'hidden';
    carouselContainer.style.scrollBehavior = 'smooth';
    carouselContainer.style.webkitOverflowScrolling = 'touch';
    
    // Configurer chaque slide
    slides.forEach((slide, index) => {
        slide.style.flex = '0 0 100vw';
        slide.style.width = '100vw';
        slide.style.minWidth = '100vw';
        slide.style.position = 'relative';
        slide.style.opacity = '1';
        slide.style.visibility = 'visible';
        slide.classList.remove('active');
    });
    
    // Ajouter l'indicateur mobile
    createMobileScrollIndicator();
    
    // Écouter le scroll manuel
    carouselContainer.addEventListener('scroll', function() {
        const scrollLeft = this.scrollLeft;
        const slideWidth = window.innerWidth;
        const newSlideIndex = Math.round(scrollLeft / slideWidth);
        
        if (newSlideIndex !== currentSlide) {
            currentSlide = newSlideIndex;
            updateMobileScrollIndicator(currentSlide);
        }
    }, { passive: true });
}

// === NAVIGATION CLAVIER ===
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
    }
}

// === INITIALISATION GUERLAIN ===
function initializeCarousel() {
    // Récupérer les éléments DOM
    slides = document.querySelectorAll('.carousel-slide');
    dots = document.querySelectorAll('.nav-dot');
    totalSlides = slides.length;
    scrollIndicator = document.getElementById('scroll-indicator');
    carouselContainer = document.getElementById('fullscreen-carousel');
    
    if (!slides || slides.length === 0) {
        console.warn('Aucun slide trouvé');
        return;
    }
    
    // Initialiser la navbar
    initNavbarScrollBehavior();
    
    if (isMobile()) {
        // CONFIGURATION MOBILE : défilement horizontal
        setupMobileHorizontalCarousel();
        
        // Event listeners tactiles pour mobile
        document.addEventListener('touchstart', handleTouchStart, { passive: true });
        document.addEventListener('touchend', handleTouchEnd, { passive: true });
        
        // Masquer les dots de navigation sur mobile
        if (dots) {
            dots.forEach(dot => {
                dot.style.display = 'none';
            });
        }
        
    } else {
        // CONFIGURATION DESKTOP : navigation verticale
        
        // Event listeners pour navigation desktop
        document.addEventListener('wheel', handleScroll, { passive: false });
        document.addEventListener('DOMMouseScroll', handleScroll, { passive: false }); // Firefox
        document.addEventListener('keydown', handleKeyboard, { passive: false });
        
        // Tactile desktop
        document.addEventListener('touchstart', handleTouchStart, { passive: true });
        document.addEventListener('touchend', handleTouchEnd, { passive: true });
        
        // Navigation dots (seulement desktop)
        if (dots) {
            dots.forEach((dot, index) => {
                dot.addEventListener('click', (e) => {
                    e.preventDefault();
                    goToSlide(index);
                });
                
                dot.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        goToSlide(index);
                    }
                });
            });
        }
        
        // Scroll indicator
        if (scrollIndicator) {
            scrollIndicator.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentSlide < totalSlides - 1) {
                    goToSlide(currentSlide + 1, 'next');
                }
            });
        }
        
        // Initialiser le premier slide pour desktop
        goToSlide(0);
    }
    
    // Gestion orientation
    window.addEventListener('orientationchange', function() {
        setTimeout(() => {
            if (isMobile()) {
                setupMobileHorizontalCarousel();
            } else {
                goToSlide(currentSlide);
            }
        }, 300);
    });
    
    // Gestion redimensionnement
    window.addEventListener('resize', function() {
        const wasMobile = isMobile();
        setTimeout(() => {
            if (wasMobile !== isMobile()) {
                // Changement mobile/desktop - réinitialiser
                location.reload();
            }
        }, 100);
    });
}

// === FONCTIONS UTILITAIRES ===
function addToCartFromCarousel(button) {
    const productId = button.dataset.productId;
    const productName = button.dataset.productName;
    
    if (!productId || !productName) {
        showAdvancedNotification('❌ Erreur: Données produit manquantes', 'error');
        return;
    }
    
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Ajout...</span>';
    
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
            updateCartCountAnimated(data.cart_count);
            
            button.innerHTML = '<i class="fas fa-check"></i> <span>Ajouté !</span>';
            button.classList.add('success');
            
            showAdvancedNotification(`✨ ${productName} ajouté au panier !`, 'success');
            
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

function resetButton(button, originalContent) {
    button.innerHTML = originalContent;
    button.classList.remove('loading', 'success');
    button.disabled = false;
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
    // Supprimer notifications existantes
    document.querySelectorAll('.notification').forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = 'notification';
    
    const colors = {
        success: 'linear-gradient(135deg, #28a745, #20c997)',
        error: 'linear-gradient(135deg, #dc3545, #e74c3c)',
        warning: 'linear-gradient(135deg, #ffc107, #ff9800)',
        info: 'linear-gradient(135deg, #17a2b8, #20c997)'
    };
    
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 350px;
        max-width: 500px;
        background: ${colors[type] || colors.info};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        transform: translateX(100%);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
    `;
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-${icons[type] || icons.info}" style="font-size: 1.25rem; flex-shrink: 0;"></i>
            <span style="flex: 1; line-height: 1.4;">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" 
                    style="background: none; border: none; color: white; cursor: pointer; padding: 0.25rem; border-radius: 50%; transition: background 0.2s;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, type === 'error' ? 6000 : 4000);
}

// === INITIALISATION AUTOMATIQUE GUERLAIN ===
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter la classe spéciale au body pour la page d'accueil
    document.body.classList.add('home-page');
    
    // Configuration navbar fixe
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        navbar.style.position = 'fixed';
        navbar.style.top = '0';
        navbar.style.width = '100%';
        navbar.style.zIndex = '1000';
        navbar.classList.add('visible-scroll');
    }
    
    // Initialiser le carousel Guerlain
    setTimeout(() => {
        initializeCarousel();
    }, 100);
    
    // Activer les animations fade-in sur desktop
    if (!isMobile()) {
        setTimeout(() => {
            document.querySelectorAll('.fade-in').forEach((element, index) => {
                setTimeout(() => {
                    element.classList.add('show');
                }, index * 100);
            });
        }, 500);
    } else {
        // Sur mobile, afficher directement sans animation pour performance
        document.querySelectorAll('.fade-in').forEach(element => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        });
    }
    
    // Newsletter form du footer
    const newsletterForm = document.getElementById('homeNewsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            if (email) {
                showAdvancedNotification('🎉 Merci ! Vous recevrez bientôt nos actualités exclusives !', 'success');
                this.reset();
            }
        });
    }
    
    // Préchargement optimisé des images
    const images = document.querySelectorAll('.product-image');
    images.forEach((img, index) => {
        if (index === 0) return; // Première image déjà chargée
        
        const newImg = new Image();
        newImg.onload = () => {
            img.src = newImg.src;
            img.classList.add('loaded');
        };
        newImg.src = img.src;
    });
    
    // Support iOS Safari pour viewport
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        function adjustViewport() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
            
            // Ajustement pour barre d'URL mobile
            document.body.style.height = window.innerHeight + 'px';
            const carousel = document.getElementById('fullscreen-carousel');
            if (carousel) {
                carousel.style.height = window.innerHeight + 'px';
            }
        }
        
        adjustViewport();
        window.addEventListener('resize', adjustViewport);
        window.addEventListener('orientationchange', () => {
            setTimeout(adjustViewport, 300);
        });
    }
    
    // Gestion visibilité page pour optimisation
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Page cachée - pause des animations si nécessaire
        } else {
            // Page visible - reprendre les animations
            if (!isMobile()) {
                document.querySelectorAll('.fade-in:not(.show)').forEach(element => {
                    element.classList.add('show');
                });
            }
        }
    });
    
    // Debug info pour développement
    console.log('🌟 Héritaj Parfums - Style Guerlain initialisé');
    console.log('📱 Mobile:', isMobile());
    console.log('🖼️ Slides trouvés:', document.querySelectorAll('.carousel-slide').length);
});

// Analytics functions (optionnels)
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




</script>
@endpush

