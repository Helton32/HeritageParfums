@extends('layouts.app')

@section('title', 'Heritage Parfums - Parfums d\'Exception')
@section('description', 'Découvrez notre collection de parfums d\'exception. Des créations uniques inspirées du savoir-faire français.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home-improvements.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

<style>
/* Classe spéciale pour la page d'accueil - désactive le scroll normal */
body.home-page {
    overflow-y: hidden;
}

/* Amélioration : Transition smooth entre navbar et carrousel */
.fullscreen-carousel {
    animation: slideInFromTop 0.8s ease-out;
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
</style>
@endpush

@section('content')
<div class="fullscreen-carousel" id="fullscreen-carousel">
    <!-- Navigation points -->
    <div class="carousel-nav">
        @foreach($allActiveProducts as $index => $product)
        <div class="nav-dot {{ $index === 0 ? 'active' : '' }}" 
             onclick="goToSlide({{ $index }})" 
             data-slide="{{ $index }}"></div>
        @endforeach
        <!-- Point pour le footer -->
        <div class="nav-dot" 
             onclick="goToSlide({{ $allActiveProducts->count() }})" 
             data-slide="{{ $allActiveProducts->count() }}"></div>
    </div>

    <!-- Slides des produits -->
    @foreach($allActiveProducts as $index => $product)
    <div class="carousel-slide active">
       <div class="carousel-content-wrapper">
    <!-- Image à gauche -->
    <div class="product-image-zone">
        <img src="{{ $product->main_image }}"
             alt="{{ $product->name }}"
             class="product-image">
    </div>

    <!-- Texte à droite -->
    <div class="content-zone">
        <div class="signature-text fade-in">
            Parfum d'Exception
        </div>

        <h1 class="product-title fade-in delay-1">
            {{ strtoupper($product->name) }}
        </h1>

        <p class="product-subtitle fade-in delay-2">
            {{ $product->short_description ?? 'Une création unique' }}
        </p>

        <div class="buttons-container fade-in delay-3">
            <a href="{{ route('product.show', $product->slug) }}" class="discover-button">
                Découvrir
            </a>
            <button class="order-button"
                    data-product-id="{{ $product->id }}"
                    onclick="addToCartFromCarousel(this)">
                Commander maintenant
            </button>
        </div>
    </div>
</div>
</div>


    
    @endforeach
    <!-- Footer comme slide final -->
    <div class="carousel-slide footer-slide" data-slide="{{ $allActiveProducts->count() }}">
        <div class="footer-content">
            <div class="footer-grid">
                <div>
                    <h5>Heritage Parfums</h5>
                    <p>Depuis 1925, nous créons des parfums d'exception qui capturent l'essence de l'élégance française et l'art de la parfumerie traditionnelle.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div>
                    <h5>Navigation</h5>
                    <ul class="footer-links">
                        <li><a href="/">Accueil</a></li>
                        <li><a href="{{ route('heritage') }}">Heritage Parfums</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('expedition') }}">Expédition</a></li>
                    </ul>
                </div>
                
                <div>
                    <h5>Services</h5>
                    <ul class="footer-links">
                        <li><a href="#">Livraison Express</a></li>
                        <li><a href="#">Retours Gratuits</a></li>
                        <li><a href="#">Support Client</a></li>
                        <li><a href="#">Personnalisation</a></li>
                    </ul>
                </div>
                
                <div>
                    <h5>Newsletter</h5>
                    <p>Restez informé de nos actualités exclusives</p>
                    <form class="d-flex flex-column">
                        <input type="email" class="form-control mb-2" placeholder="Votre email" style="background: transparent; border: 1px solid rgba(255,255,255,0.3); color: white;">
                        <button type="submit" class="discover-button">S'inscrire</button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Heritage Parfums. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <!-- Indicateur de scroll -->
    <div class="scroll-indicator" id="scroll-indicator">
        <span>Scroll</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</div>
@endsection
@push('scripts')
<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const dots = document.querySelectorAll('.nav-dot');
const totalSlides = slides.length;
const scrollIndicator = document.getElementById('scroll-indicator');
let isScrolling = false;

// Fonction pour aller à un slide spécifique
function goToSlide(slideIndex) {
    if (slideIndex < 0) slideIndex = 0;
    if (slideIndex >= totalSlides) slideIndex = totalSlides - 1;
    
    // Retirer la classe active de tous les slides et dots
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    // Ajouter la classe active au slide et dot correspondants
    slides[slideIndex].classList.add('active');
    dots[slideIndex].classList.add('active');
    
    currentSlide = slideIndex;
    
    // Masquer l'indicateur de scroll au dernier slide (footer)
    if (slideIndex === totalSlides - 1) {
        scrollIndicator.style.opacity = '0';
    } else {
        scrollIndicator.style.opacity = '1';
    }
}

// Gestion du scroll pour navigation entre slides
function handleScroll(e) {
    if (isScrolling) return;
    
    isScrolling = true;
    
    // Détecter la direction du scroll
    const delta = e.deltaY || e.detail || e.wheelDelta;
    
    if (delta > 0) {
        // Scroll vers le bas - slide suivant
        if (currentSlide < totalSlides - 1) {
            goToSlide(currentSlide + 1);
        }
    } else {
        // Scroll vers le haut - slide précédent
        if (currentSlide > 0) {
            goToSlide(currentSlide - 1);
        }
    }
    
    // Débounce pour éviter les scrolls trop rapides
    setTimeout(() => {
        isScrolling = false;
    }, 800);
}
// Navigation au clavier
function handleKeyboard(e) {
    if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
        if (currentSlide > 0) {
            goToSlide(currentSlide - 1);
        }
    } else if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
        if (currentSlide < totalSlides - 1) {
            goToSlide(currentSlide + 1);
        }
    }
}

// Gestion du scroll tactile (mobile)
let touchStartY = 0;
let touchEndY = 0;

function handleTouchStart(e) {
    touchStartY = e.changedTouches[0].screenY;
}

function handleTouchEnd(e) {
    if (isScrolling) return;
    
    touchEndY = e.changedTouches[0].screenY;
    const difference = touchStartY - touchEndY;
    
    if (Math.abs(difference) > 50) { // Seuil minimum pour déclencher le changement
        isScrolling = true;
        
        if (difference > 0) {
            // Swipe vers le haut - slide suivant
            if (currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1);
            }
        } else {
            // Swipe vers le bas - slide précédent
            if (currentSlide > 0) {
                goToSlide(currentSlide - 1);
            }
        }
        
        setTimeout(() => {
            isScrolling = false;
        }, 800);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter la classe spéciale au body pour la page d'accueil
    document.body.classList.add('home-page');
    
    // Event listeners pour le scroll
    document.addEventListener('wheel', handleScroll, { passive: false });
    document.addEventListener('DOMMouseScroll', handleScroll, { passive: false }); // Firefox
    
    // Event listeners pour le clavier
    document.addEventListener('keydown', handleKeyboard);
    
    // Event listeners pour le tactile
    document.addEventListener('touchstart', handleTouchStart, { passive: true });
    document.addEventListener('touchend', handleTouchEnd, { passive: true });
    
    // Amélioration : Rendre l'indicateur de scroll cliquable
    const scrollIndicator = document.getElementById('scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function() {
            if (currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1);
            }
        });
    }
    
    // Animation des éléments au chargement
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
    });
    
    // Déclencher les animations avec un délai plus court
    setTimeout(() => {
        fadeElements.forEach(element => {
            element.style.transition = 'all 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        });
    }, 200); // Réduit de 100ms à 200ms pour un effet plus fluide
    
    // Initialiser le premier slide
    goToSlide(0);
    
    // Amélioration : Préchargement des images pour une navigation plus fluide
    const images = document.querySelectorAll('.product-image');
    images.forEach(img => {
        const newImg = new Image();
        newImg.src = img.src;
    });
});

// Fonction pour ajouter au panier depuis le carrousel
function addToCartFromCarousel(button) {
    const productId = button.dataset.productId;
    
    // Animation du bouton
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout...';
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
                cartCount.style.display = 'flex';
            }
            
            // Animation de succès
            button.innerHTML = '<i class="fas fa-check"></i> Ajouté !';
            button.style.background = '#28a745';
            button.style.borderColor = '#28a745';
            
            // Notification toast
            showNotification(data.message || 'Produit ajouté au panier !', 'success');
            
            // Restaurer le bouton après 3 secondes
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
                button.style.borderColor = '';
                button.disabled = false;
            }, 3000);
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur lors de l\'ajout au panier', 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Fonction pour afficher les notifications
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `position-fixed`;
    notification.style.cssText = `
        top: 100px; 
        right: 20px; 
        z-index: 9999; 
        min-width: 350px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #28a745, #20c997)' : 'linear-gradient(135deg, #dc3545, #e74c3c)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="font-size: 1.2rem;"></i>
            <span style="flex: 1;">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; padding: 0;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto hide après 4 secondes
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}
</script>
@endpush