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
    <style>
        :root {
            /* Palette Guerlain */
            --guerlain-black: #0d0d0d;
            --guerlain-gold: #d4af37;
            --guerlain-dark-gold: #b8941f;
            --guerlain-burgundy: #8b0000;
            --guerlain-white: #ffffff;
            --guerlain-cream: #faf9f7;
            --guerlain-light-gray: #f5f5f5;
            --guerlain-medium-gray: #e8e8e8;
            --guerlain-text-gray: #666666;
            --guerlain-border: #d8d8d8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.7;
            color: var(--guerlain-black);
            background-color: var(--guerlain-white);
            font-weight: 300;
            letter-spacing: 0.3px;
        }

        .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }

        .font-sans {
            font-family: 'Montserrat', sans-serif;
        }

        /* Typographie Guerlain */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 400;
            letter-spacing: 0.5px;
            line-height: 1.3;
            color: var(--guerlain-black);
        }

        .display-1, .display-2, .display-3 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 300;
            letter-spacing: -0.5px;
        }

        /* Couleurs de marque */
        .text-guerlain-gold {
            color: var(--guerlain-gold);
        }

        .text-guerlain-burgundy {
            color: var(--guerlain-burgundy);
        }

        .text-guerlain-gray {
            color: var(--guerlain-text-gray);
        }

        .bg-guerlain-black {
            background-color: var(--guerlain-black);
        }

        .bg-guerlain-cream {
            background-color: var(--guerlain-cream);
        }

        .bg-guerlain-light {
            background-color: var(--guerlain-light-gray);
        }

        /* Boutons Guerlain Style */
        .btn-guerlain {
            background-color: var(--guerlain-black);
            border: 2px solid var(--guerlain-black);
            color: var(--guerlain-white);
            padding: 16px 40px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.4s ease;
            border-radius: 0;
            position: relative;
            overflow: hidden;
        }

        .btn-guerlain:hover {
            background-color: var(--guerlain-white);
            color: var(--guerlain-black);
            border-color: var(--guerlain-black);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .btn-guerlain-outline {
            background-color: transparent;
            border: 2px solid var(--guerlain-gold);
            color: var(--guerlain-gold);
            padding: 16px 40px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.4s ease;
            border-radius: 0;
        }

        .btn-guerlain-outline:hover {
            background-color: var(--guerlain-gold);
            color: var(--guerlain-white);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
        }

        .btn-guerlain-gold {
            background-color: var(--guerlain-gold);
            border: 2px solid var(--guerlain-gold);
            color: var(--guerlain-white);
            padding: 16px 40px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.4s ease;
            border-radius: 0;
        }

        .btn-guerlain-gold:hover {
            background-color: var(--guerlain-dark-gold);
            border-color: var(--guerlain-dark-gold);
            color: var(--guerlain-white);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        }
            border: 2px solid var(--primary-gold);
            color: var(--primary-gold);
            background: transparent;
            padding: 12px 30px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-outline-gold:hover {
            background-color: var(--primary-gold);
            color: white;
            transform: translateY(-2px);
        }

        /* Navigation Guerlain - Version Centrée sur deux niveaux */
        .navbar {
            background: var(--guerlain-white);
            border-bottom: 1px solid var(--guerlain-border);
            transition: all 0.5s ease;
            padding: 15px 0 10px 0;
            box-shadow: none;
            min-height: 120px; /* Hauteur pour contenir le titre + navigation */
        }

        .navbar.scrolled {
            background: var(--guerlain-white);
            box-shadow: 0 2px 30px rgba(0, 0, 0, 0.08);
            padding: 10px 0 5px 0;
            min-height: 100px;
        }

        /* Container de navbar avec positionnement relatif */
        .navbar .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            position: relative;
            width: 100%;
        }

        /* Logo/Titre parfaitement centré en haut */
        .navbar-brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            font-weight: 300;
            color: var(--guerlain-black) !important;
            text-decoration: none;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            margin: 0 0 15px 0;
            text-align: center;
        }

        .navbar-brand:hover {
            color: var(--guerlain-gold) !important;
        }

        /* Navigation centrée en dessous */
        .navbar-collapse {
            justify-content: center;
            width: 100%;
        }

        .navbar-nav {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .nav-link {
            color: var(--guerlain-black) !important;
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 10px 20px !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--guerlain-gold) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 50%;
            width: 0;
            height: 1px;
            background: var(--guerlain-gold);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 60%;
        }

        /* Zone des actions utilisateur (panier, etc.) */
        .navbar-actions {
            position: absolute;
            right: 0;
            top: 0;
            display: flex;
            align-items: center;
            z-index: 10;
        }

        .cart-icon {
            position: relative;
            color: var(--guerlain-black) !important;
            transition: all 0.3s ease;
        }

        .cart-icon:hover {
            color: var(--guerlain-gold) !important;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--guerlain-gold);
            color: var(--guerlain-white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Montserrat', sans-serif;
        }

        /* Responsive navbar */
        @media (max-width: 991px) {
            .navbar {
                min-height: auto;
                padding: 10px 0;
            }
            
            .navbar .container {
                flex-direction: column;
            }
            
            .navbar-actions {
                position: static;
                transform: none;
                margin-top: 1rem;
                justify-content: center;
            }
            
            .navbar-brand {
                font-size: 2rem;
                margin-bottom: 10px;
            }
        }

        /* Ajustement pour le contenu principal */
        .main-content {
            margin-top: 120px; /* Espace pour la navbar plus haute */
        }

        @media (max-width: 991px) {
            .main-content {
                margin-top: 80px;
            }
        }

        /* Dropdown Menu Guerlain */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-radius: 0;
            padding: 15px 0;
            margin-top: 10px;
        }

        .dropdown-item {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 300;
            letter-spacing: 1px;
            padding: 10px 25px;
            color: var(--guerlain-black);
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--guerlain-light-gray);
            color: var(--guerlain-gold);
        }

        /* Hero Section Guerlain */
        .hero {
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.25));
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--guerlain-white);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="0.3" fill="rgba(255,255,255,0.05)"/><circle cx="60" cy="40" r="0.4" fill="rgba(255,255,255,0.08)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            opacity: 0.3;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 80px 20px;
        }

        .hero-content h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 4.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
            letter-spacing: 2px;
            line-height: 1.1;
        }

        .hero-content .lead {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.1rem;
            font-weight: 300;
            letter-spacing: 1px;
            margin-bottom: 3rem;
            opacity: 0.9;
        }

        /* Sections Guerlain */
        .section {
            padding: 100px 0;
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3rem;
            font-weight: 300;
            text-align: center;
            margin-bottom: 60px;
            color: var(--guerlain-black);
            letter-spacing: 1px;
        }

        .section-subtitle {
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 300;
            text-align: center;
            color: var(--guerlain-text-gray);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        /* Cards Guerlain */
        .card {
            border: none;
            border-radius: 0;
            box-shadow: none;
            transition: all 0.5s ease;
            background: var(--guerlain-white);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: var(--guerlain-cream);
            border: none;
            padding: 30px;
            border-radius: 0;
        }

        .card-body {
            padding: 40px;
        }

        .card-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 400;
            color: var(--guerlain-black);
            margin-bottom: 20px;
        }

        .card-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            color: var(--guerlain-text-gray);
            line-height: 1.8;
        }

        /* Product Cards */
        .product-card {
            border: none;
            transition: all 0.5s ease;
            background: var(--guerlain-white);
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            height: 400px;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .product-info {
            padding: 40px 30px;
            text-align: center;
        }

        .product-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 400;
            color: var(--guerlain-black);
            margin-bottom: 15px;
        }

        .product-price {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.3rem;
            font-weight: 300;
            color: var(--guerlain-gold);
            margin-bottom: 20px;
        }

        .product-description {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            color: var(--guerlain-text-gray);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        /* Form Guerlain */
        .form-control {
            border: none;
            border-bottom: 2px solid var(--guerlain-border);
            border-radius: 0;
            padding: 15px 0;
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            background: transparent;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--guerlain-gold);
            background: transparent;
        }

        .form-label {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            color: var(--guerlain-text-gray);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 12px;
        }

        /* Footer Guerlain */
        .footer {
            background: var(--guerlain-black);
            color: var(--guerlain-white);
            padding: 80px 0 40px;
            margin-top: 100px;
        }

        .footer h5 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 300;
            color: var(--guerlain-white);
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .footer p {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
            font-size: 0.95rem;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: var(--guerlain-gold);
        }

        .footer .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .footer .social-links a:hover {
            border-color: var(--guerlain-gold);
            color: var(--guerlain-gold);
        }

        /* Animations et Effets */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Guerlain */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 3rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .product-info {
                padding: 30px 20px;
            }
            
            .btn-guerlain,
            .btn-guerlain-outline,
            .btn-guerlain-gold {
                padding: 12px 25px;
                font-size: 12px;
                letter-spacing: 1.5px;
            }
        }

        /* Navbar burger */
        .navbar-toggler {
            border: none;
            padding: 0;
            outline: none;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Admin styles Guerlain */
        .border-left-warning {
            border-left: 4px solid var(--guerlain-gold) !important;
        }

        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }

        .border-left-primary {
            border-left: 4px solid var(--guerlain-black) !important;
        }

        .text-xs {
            font-size: 0.7rem;
        }

        .avatar {
            border: 3px solid var(--guerlain-white);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Admin navigation */
        .list-group-item {
            border: none;
            border-radius: 0;
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            font-size: 14px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .list-group-item.active {
            background-color: var(--guerlain-black);
            border-color: var(--guerlain-black);
            color: var(--guerlain-white);
        }

        .list-group-item:hover {
            background-color: var(--guerlain-light-gray);
            color: var(--guerlain-gold);
        }

        /* Alert Guerlain */
        .alert {
            border-radius: 0;
            border: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
        }

        .alert-success {
            background-color: rgba(28, 200, 138, 0.1);
            color: #1cc88a;
            border-left: 4px solid #1cc88a;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        .alert-warning {
            background-color: rgba(212, 175, 55, 0.1);
            color: var(--guerlain-gold);
            border-left: 4px solid var(--guerlain-gold);
        }

        .alert-info {
            background-color: rgba(54, 185, 204, 0.1);
            color: #36b9cc;
            border-left: 4px solid #36b9cc;
        }

        /* Badge Guerlain */
        .badge {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            font-size: 0.75rem;
            letter-spacing: 1px;
            border-radius: 0;
            padding: 6px 12px;
        }

        .badge.bg-warning {
            background-color: var(--guerlain-gold) !important;
            color: var(--guerlain-white);
        }

        .badge.bg-primary {
            background-color: var(--guerlain-black) !important;
        }
    </style>
</head>
<body>

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation Centrée Style Guerlain -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <!-- Logo/Titre parfaitement centré -->
            <a class="navbar-brand" href="/">
                Heritage Parfums
            </a>
            
            <!-- Bouton toggle pour mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Actions utilisateur (panier, profil) positionnées à droite -->
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
                            <li><a class="dropdown-item" href="{{ route('admin.contacts.index') }}">
                                <i class="fas fa-envelope me-2"></i>Messages
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
                    </div>
                @else
                    <a href="{{ route('admin.login') }}" class="nav-link text-guerlain-gold">
                        <i class="fas fa-sign-in-alt me-1"></i>Admin
                    </a>
                @endauth
            </div>
            
            <!-- Navigation principale centrée en dessous du titre -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#product">Le Parfum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#purchase">Commander</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Notre Histoire</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-guerlain-gold" href="{{ route('demo.shipping') }}">
                            <i class="fas fa-shipping-fast me-1"></i>Expédition
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-guerlain-gold" href="{{ route('demo.guerlain') }}">
                            <i class="fas fa-palette me-1"></i>Design Guerlain
                        </a>
                    </li>
                </ul>
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