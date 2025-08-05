<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'Heritage Parfums - Maison de parfums de luxe depuis 1925. Découvrez nos collections exclusives de parfums pour homme et femme.')">
    <title>@yield('title', 'Heritage Parfums - Maison de Parfums de Luxe')</title>
    
    <!-- SEO et Meta Tags Guerlain -->
    @include('components.seo-guerlain')
    
    <!-- Fonts Guerlain Style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Montserrat:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/guerlain-animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/heritage-styles.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation Élégante Style Guerlain -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <!-- Logo/Titre centré -->
            <a class="navbar-brand" href="/">
                Heritage Parfums
            </a>
            
            <!-- Navigation principale centrée -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- Accueil -->
                    <li class="nav-item">
                        <a class="nav-link" href="/">Accueil</a>
                    </li>                    
                    <!-- Les Parfums avec Mega-Menu -->
                    <li class="nav-item dropdown mega-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Les Parfums
                        </a>
                        <div class="dropdown-menu mega-menu">
                            <div class="container">
                                <div class="row">
                                    <!-- Section À la une (optionnelle) -->
                                    @if(isset($featuredProducts) && $featuredProducts->isNotEmpty())
                                    <div class="col-lg-3 featured-section">
                                        <h6 class="mega-menu-title">À la Une</h6>
                                        @php $featured = $featuredProducts->first(); @endphp
                                        <div class="featured-product">
                                            <div class="featured-image">
                                                <img src="{{ $featured->main_image }}" alt="{{ $featured->name }}" class="img-fluid">
                                            </div>
                                            <h6 class="featured-name">{{ $featured->name }}</h6>
                                            <p class="featured-price">{{ $featured->formatted_price }}</p>
                                            <a href="{{ route('product.show', $featured->slug) }}" class="btn-discover-mini">Découvrir</a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Colonnes par catégorie -->
                                    @if(isset($categories) && isset($productsByCategory))
                                        @foreach($categories as $categoryKey => $categoryLabel)
                                        <div class="col-lg-{{ isset($featuredProducts) && $featuredProducts->isNotEmpty() ? '2' : '3' }}">
                                            <h6 class="mega-menu-title">{{ $categoryLabel }}</h6>
                                            <ul class="mega-menu-list">
                                                @if(isset($productsByCategory[$categoryKey]))
                                                    @foreach($productsByCategory[$categoryKey] as $product)
                                                    <li>
                                                        <a href="{{ route('product.show', $product->slug) }}">
                                                            {{ $product->name }}
                                                            @if($product->badge)
                                                            <span class="product-badge">{{ $product->badge }}</span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                @endif
                                                <li class="view-all">
                                                    <a href="/?category={{ $categoryKey }}">Voir tous les {{ strtolower($categoryLabel) }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>                    
                    <!-- Heritage Parfums -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('heritage') }}">Heritage Parfums</a>
                    </li>
                    
                    <!-- Contact -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                    
                    <!-- Expédition -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expedition') }}">Expédition</a>
                    </li>
                </ul>
            </div>
            
            <!-- Bouton toggle pour mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Actions utilisateur -->
            <div class="navbar-actions">
                <a href="/cart" class="nav-link cart-icon me-3">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count">0</span>
                </a>
                
                @auth
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-primary" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                <i class="fas fa-box me-2"></i>Produits
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>                @else
                    <a href="{{ route('admin.login') }}" class="nav-link text-guerlain-gold">
                        <i class="fas fa-sign-in-alt me-1"></i>Admin
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <h5 class="font-serif">Heritage Parfums</h5>
                    <p>Depuis 1925, nous créons <strong>Éternelle Rose</strong>, notre parfum signature qui capture l'essence de l'élégance française et l'art de la parfumerie traditionnelle.</p>
                    <div class="social-links mt-4">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Le Parfum</h5>
                    <ul class="list-unstyled">
                        <li><a href="#product">Découvrir</a></li>
                        <li><a href="#purchase">Commander</a></li>
                        <li><a href="/about">Histoire</a></li>
                        <li><a href="#testimonials">Avis Clients</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Services</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Emballage Cadeau</a></li>
                        <li><a href="#">Livraison Express</a></li>
                        <li><a href="#">Retours Gratuits</a></li>
                        <li><a href="#">Support Client</a></li>
                    </ul>
                </div>                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Newsletter</h5>
                    <p>Restez informé de nos actualités exclusives</p>
                    <form class="d-flex flex-column">
                        <input type="email" class="form-control mb-2" placeholder="Votre email">
                        <button type="submit" class="btn btn-outline-gold btn-sm">S'inscrire</button>
                    </form>
                </div>
            </div>
            
            <hr style="border-color: #444;">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2025 Heritage Parfums. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="me-3">Politique de Confidentialité</a>
                    <a href="#">Conditions d'Utilisation</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Fade in animations - Configuration globale
        const globalObserverOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const globalObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, globalObserverOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            globalObserver.observe(el);
        });

        // Mettre à jour le compteur du panier au chargement de la page
        updateCartCount();

        function updateCartCount() {
            fetch('/cart/count', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement && data.count !== undefined) {
                    cartCountElement.textContent = data.count;
                    cartCountElement.style.display = data.count > 0 ? 'flex' : 'none';
                }
            })
            .catch(error => {
                console.warn('Erreur lors de la mise à jour du compteur:', error);
            });
        }
    </script>
    @stack('scripts')
</body>
</html>