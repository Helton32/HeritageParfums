@extends('layouts.app')

@section('title', 'Heritage Parfums - Parfums d\'Exception')
@section('description', 'Découvrez notre collection de parfums d\'exception. Des créations uniques inspirées du savoir-faire français.')

@push('styles')
<style>
    /* Variables CSS pour les couleurs Guerlain */
    :root {
        --guerlain-black: #000000;
        --guerlain-white: #ffffff;
        --guerlain-gold: #d4af37;
        --guerlain-gold-dark: #b8941f;
    }

    /* Style général pour la page */
    body {
        overflow-x: hidden;
    }

    /* Section hero plein écran - ajustée pour la navbar */
    .hero-section {
        height: calc(100vh - 120px); /* Hauteur ajustée pour la navbar */
        width: 100vw;
        background: var(--guerlain-black);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        margin-top: 120px; /* Espace pour la navbar centrée */
    }

    @media (max-width: 991px) {
        .hero-section {
            height: calc(100vh - 80px);
            margin-top: 80px;
        }
    }

    /* Container principal du carousel */
    .carousel-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
    }

    /* Slide individual */
    .carousel-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 8rem;
    }

    .carousel-slide.active {
        opacity: 1;
    }

    /* Zone de l'image produit */
    .product-image-zone {
        flex: 1;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    /* Styling de l'image produit */
    .product-image {
        max-height: 70vh;
        max-width: 100%;
        object-fit: contain;
        filter: drop-shadow(0 20px 40px rgba(212, 175, 55, 0.3));
        transition: transform 0.8s ease-in-out;
    }

    .product-image:hover {
        transform: scale(1.05);
    }

    /* Zone de contenu texte */
    .content-zone {
        flex: 1;
        padding: 0 0 0 4rem;
        color: var(--guerlain-white);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        height: 100%;
    }

    /* Texte signature en haut */
    .signature-text {
        font-size: 0.875rem;
        letter-spacing: 0.2em;
        margin-bottom: 1.5rem;
        color: var(--guerlain-gold);
        text-transform: uppercase;
        font-weight: 300;
    }

    /* Titre principal */
    .product-title {
        font-size: 3.5rem;
        font-weight: 300;
        line-height: 1.1;
        margin-bottom: 1rem;
        letter-spacing: 0.05em;
        font-family: 'Playfair Display', serif;
    }

    /* Sous-titre */
    .product-subtitle {
        font-size: 1.125rem;
        font-weight: 300;
        letter-spacing: 0.1em;
        margin-bottom: 3rem;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
    }

    /* Bouton Découvrir */
    .discover-button {
        background: transparent;
        border: 2px solid var(--guerlain-white);
        color: var(--guerlain-white);
        padding: 1rem 2.5rem;
        font-size: 0.875rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        transition: all 0.3s ease;
        cursor: pointer;
        font-weight: 400;
    }

    .discover-button:hover {
        background: var(--guerlain-white);
        color: var(--guerlain-black);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 255, 255, 0.2);
    }

    /* Navigation points (carousel indicators) */
    .carousel-nav {
        position: absolute;
        left: 3rem;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        z-index: 10;
    }

    .nav-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.5);
        background: transparent;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .nav-dot.active {
        background: var(--guerlain-white);
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
    }

    .nav-dot:hover {
        border-color: var(--guerlain-white);
        transform: scale(1.2);
    }

    /* Effets de fond pour le luxe */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(212, 175, 55, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    /* Animation de fade-in pour le contenu */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 1s ease-out forwards;
    }

    .fade-in.delay-1 { animation-delay: 0.2s; }
    .fade-in.delay-2 { animation-delay: 0.4s; }
    .fade-in.delay-3 { animation-delay: 0.6s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .carousel-slide {
            flex-direction: column;
            text-align: center;
            padding: 2rem;
        }
        
        .product-image-zone {
            flex: none;
            height: 50%;
        }
        
        .content-zone {
            flex: none;
            padding: 2rem 0 0 0;
            align-items: center;
        }
        
        .product-title {
            font-size: 2.5rem;
        }
        
        .carousel-nav {
            left: 1rem;
            bottom: 2rem;
            top: auto;
            transform: none;
            flex-direction: row;
        }
    }

    @media (max-width: 640px) {
        .product-title {
            font-size: 2rem;
        }
        
        .product-subtitle {
            font-size: 1rem;
        }
        
        .discover-button {
            padding: 0.875rem 2rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<section class="hero-section">
    <!-- Navigation points -->
    <div class="carousel-nav">
        @foreach($featuredProducts as $index => $product)
        <div class="nav-dot {{ $index === 0 ? 'active' : '' }}" 
             onclick="goToSlide({{ $index }})" 
             data-slide="{{ $index }}"></div>
        @endforeach
    </div>

    <!-- Container du carousel -->
    <div class="carousel-container">
        @foreach($featuredProducts as $index => $product)
        <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
            <!-- Zone image produit -->
            <div class="product-image-zone">
                <img src="{{ $product->main_image }}" 
                     alt="{{ $product->name }}" 
                     class="product-image">
            </div>

            <!-- Zone contenu texte -->
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
                
                <a href="{{ route('product.show', $product->slug) }}" 
                   class="discover-button fade-in delay-3">
                    Découvrir
                </a>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Section suivante (optionnelle) -->
<section class="bg-white py-20">
    <div class="container mx-auto px-6">
        <div class="text-center">
            <h2 class="text-4xl font-light text-gray-900 mb-6">Notre Savoir-Faire</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Depuis des générations, nous perpétuons l'art de la parfumerie française. 
                Chaque création Heritage Parfums est une œuvre d'art olfactive, 
                née de la passion et du respect des traditions ancestrales.
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const dots = document.querySelectorAll('.nav-dot');
const totalSlides = slides.length;

// Fonction pour aller à un slide spécifique
function goToSlide(slideIndex) {
    // Retirer la classe active de tous les slides et dots
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    // Ajouter la classe active au slide et dot correspondants
    slides[slideIndex].classList.add('active');
    dots[slideIndex].classList.add('active');
    
    currentSlide = slideIndex;
}

// Auto-rotation du carousel (optionnel)
function autoRotate() {
    const nextSlide = (currentSlide + 1) % totalSlides;
    goToSlide(nextSlide);
}

// Démarrer l'auto-rotation (toutes les 8 secondes)
setInterval(autoRotate, 8000);

// Navigation au clavier
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') {
        const prevSlide = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1;
        goToSlide(prevSlide);
    } else if (e.key === 'ArrowRight') {
        const nextSlide = (currentSlide + 1) % totalSlides;
        goToSlide(nextSlide);
    }
});

// Animation des éléments au chargement
document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
    });
    
    // Déclencher les animations
    setTimeout(() => {
        fadeElements.forEach(element => {
            element.style.transition = 'all 1s ease-out';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        });
    }, 100);
});
</script>
@endpush