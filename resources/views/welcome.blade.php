{{--
  ═══════════════════════════════════════════════════════════════════════════════════
  🌟 HERITAGE PARFUMS - WELCOME PAGE | CAROUSEL FULLSCREEN EXPERIENCE
  ═══════════════════════════════════════════════════════════════════════════════════
  
  ⚡ Developed with Passion by: HALICK DEVOPS FULL STACK
  🎯 Laravel Blade | Responsive Design | Luxury Frontend Architecture
  💼 Full-Stack Development | Modern Web Solutions | Performance First
  
  ═══════════════════════════════════════════════════════════════════════════════════
--}}

@extends('layouts.app')

@section('title', 'Héritaj Parfums - Parfums d\'Exception')
@section('description', 'Découvrez notre collection de parfums d\'exception. Des créations uniques inspirées du savoir-faire français.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/guerlain-fullscreen.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
@endpush

@section('content')
<div class="fullscreen-carousel" id="fullscreen-carousel">

    <!-- Navigation dots -->
    <div class="carousel-nav">
        @foreach($allActiveProducts as $index => $product)
            <div class="nav-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></div>
        @endforeach
        <div class="nav-dot" onclick="goToSlide({{ $allActiveProducts->count() }})"></div>
    </div>

    <!-- Slides produits -->
    @foreach($allActiveProducts as $index => $product)
    <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}">
        <div class="carousel-content-wrapper">
            <div class="product-image-zone">
                <img src="{{ $product->main_image }}"
                     alt="{{ $product->name }}"
                     class="product-image"
                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
              
            </div>

            <div class="content-zone fade-in">
                <div class="content-top">
                    <div class="signature-text">
                        @if($product->product_type === 'parfum')
                            Parfum d'Exception
                        @else
                            Cosmétique de Luxe
                        @endif
                    </div>
                    <h1 class="product-title">{{ mb_strtoupper($product->name, 'UTF-8') }}</h1>
                    <p class="product-subtitle">{{ $product->short_description ?? 'Une création unique signée Héritaj' }}</p>
                </div>
                <div class="buttons-container">
                    <a href="{{ route('product.show', $product->slug) }}" class="discover-button">Découvrir</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Footer slide -->
    <div class="carousel-slide footer-slide">
        <div class="footer-content">
            <div class="footer-grid">
                <div>
                    <h5><i class="fas fa-crown"></i> Héritaj Parfums</h5>
                    <p>Depuis 1925, nous créons des parfums d'exception qui capturent l'élégance française.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div>
                    <h5><i class="fas fa-envelope"></i> Newsletter</h5>
                    <p>Restez informé de nos actualités exclusives</p>
                    <form class="newsletter-form" id="homeNewsletterForm">
                        <input type="email" placeholder="Votre email" required>
                        <button type="submit" class="discover-button"><i class="fas fa-paper-plane"></i> S'inscrire</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Variables globales
let currentSlide = 0;
let slides, dots;
let isDeviceMobile = false;
let isScrolling = false;
let scrollTimeout;
let lastWheelTime = 0;
let touchStartY = 0;
let touchStartTime = 0;
let navbar, body;

// Détection mobile
function detectMobileDevice() {
    isDeviceMobile = window.innerWidth <= 991;
    return isDeviceMobile;
}

// Masquer/Afficher la navbar et le body
function hideNavbarAndBody() {
    if (isDeviceMobile && navbar) {
        navbar.classList.add('hiding');
        navbar.style.transform = 'translateY(-100%) scale(0.95)';
        navbar.style.opacity = '0';
    }
    if (isDeviceMobile && body) {
        body.classList.add('scrolling');
    }
}

function showNavbarAndBody() {
    if (isDeviceMobile && navbar) {
        navbar.classList.remove('hiding');
        navbar.style.transform = 'translateY(0) scale(1)';
        navbar.style.opacity = '1';
    }
    if (isDeviceMobile && body) {
        body.classList.remove('scrolling');
    }
}

// Aller à un slide
function goToSlide(index) {
    if (!slides) return;
    if (index < 0) index = 0;
    if (index >= slides.length) index = slides.length - 1;

    // Sur desktop : système d'opacité
    if (!isDeviceMobile) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        slides[index].classList.add('active');
        dots[index]?.classList.add('active');
    } else {
        // Sur mobile : scroll vers la slide
        if (slides[index]) {
            slides[index].scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    currentSlide = index;
}

// Scroll desktop avec throttling
function handleDesktopScroll(e) {
    if (isDeviceMobile) return;
    
    // Vérifier si le scroll se fait dans un menu ou élément scrollable
    const target = e.target;
    const isInMenu = target.closest('.navbar, .mega-menu, .dropdown-menu, nav, header, .menu, .modal, .popup');
    if (isInMenu) return; // Ne pas gérer le slide si dans un menu
    
    e.preventDefault();
    
    const now = Date.now();
    if (now - lastWheelTime < 800) return; // Throttling pour éviter le scroll trop rapide
    
    lastWheelTime = now;
    
    if (e.deltaY > 0) {
        goToSlide(currentSlide + 1);
    } else {
        goToSlide(currentSlide - 1);
    }
}

// Gestion du touch sur mobile
function handleTouchStart(e) {
    if (!isDeviceMobile) return;
    
    // Vérifier si le touch commence dans un menu ou élément scrollable
    const target = e.target;
    const isInMenu = target.closest('.navbar, .mega-menu, .dropdown-menu, nav, header, .menu, .dropdown-toggle, .navbar-toggler, .btn, button, a[data-toggle], [data-bs-toggle]');
    if (isInMenu) return; // Ne pas gérer le slide si dans un menu ou bouton
    
    touchStartY = e.touches[0].clientY;
    touchStartTime = Date.now();
}

function handleTouchMove(e) {
    if (!isDeviceMobile) return;
    
    // Vérifier si le touch est dans un menu
    const target = e.target;
    const isInMenu = target.closest('.navbar, .mega-menu, .dropdown-menu, nav, header, .menu, .dropdown-toggle, .navbar-toggler, .btn, button, a[data-toggle], [data-bs-toggle]');
    if (isInMenu) return; // Ne pas masquer la navbar si dans un menu
    
    if (!isScrolling) {
        isScrolling = true;
        hideNavbarAndBody();
    }
}

function handleTouchEnd(e) {
    if (!isDeviceMobile) return;
    
    // Vérifier si le touch était dans un menu
    const target = e.target;
    const isInMenu = target.closest('.navbar, .mega-menu, .dropdown-menu, nav, header, .menu, .dropdown-toggle, .navbar-toggler, .btn, button, a[data-toggle], [data-bs-toggle]');
    if (isInMenu) return; // Ne pas changer de slide si dans un menu
    
    const touchEndY = e.changedTouches[0].clientY;
    const touchEndTime = Date.now();
    const deltaY = touchStartY - touchEndY;
    const deltaTime = touchEndTime - touchStartTime;
    
    // Vérifier si c'est un swipe valide (distance minimale et temps raisonnable)
    if (Math.abs(deltaY) > 50 && deltaTime < 300) {
        if (deltaY > 0) {
            // Swipe vers le haut - slide suivante
            goToSlide(currentSlide + 1);
        } else {
            // Swipe vers le bas - slide précédente
            goToSlide(currentSlide - 1);
        }
    }
    
    // Réafficher la navbar après un délai
    isScrolling = false;
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
        showNavbarAndBody();
    }, 500);
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    slides = document.querySelectorAll('.carousel-slide');
    dots = document.querySelectorAll('.nav-dot');
    navbar = document.querySelector('.navbar, nav, header');
    body = document.body;
    
    detectMobileDevice();

    // Correction du bug des dots
    dots.forEach((dot, i) => dot.addEventListener('click', () => goToSlide(i)));
    
    // Event listeners pour desktop
    if (!isDeviceMobile) {
        document.addEventListener('wheel', handleDesktopScroll, { passive: false });
    }
    
    // Event listeners pour mobile
    if (isDeviceMobile) {
        document.addEventListener('touchstart', handleTouchStart, { passive: true });
        document.addEventListener('touchmove', handleTouchMove, { passive: true });
        document.addEventListener('touchend', handleTouchEnd, { passive: true });
    }
    
    // Réinitialiser lors du redimensionnement
    window.addEventListener('resize', () => {
        detectMobileDevice();
        showNavbarAndBody();
    });
    
    goToSlide(currentSlide);
});
</script>
@endpush
