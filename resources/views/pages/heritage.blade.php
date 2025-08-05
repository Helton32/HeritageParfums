@extends('layouts.app')

@section('title', 'Heritage Parfums - Notre Histoire')
@section('description', 'Découvrez l\'histoire de Heritage Parfums, maison de parfumerie française fondée en 1925. Un siècle de savoir-faire et de passion pour l\'art olfactif.')

@push('styles')
<style>
    .heritage-hero {
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), 
                    url('https://images.unsplash.com/photo-1615729947596-a598e5de0ab3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
        height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-top: 80px;
    }

    .heritage-content {
        padding: 5rem 0;
    }

    .heritage-section {
        margin-bottom: 4rem;
    }

    .heritage-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 300;
        color: var(--guerlain-black);
        margin-bottom: 2rem;
        text-align: center;
    }

    .heritage-subtitle {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 400;
        color: var(--guerlain-gold);
        margin-bottom: 1.5rem;
    }

    .heritage-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.1rem;
        line-height: 1.7;
        color: var(--guerlain-text-gray);
        margin-bottom: 2rem;
    }

    .timeline {
        position: relative;
        padding: 2rem 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--guerlain-gold);
        transform: translateX(-50%);
    }

    .timeline-item {
        position: relative;
        margin: 3rem 0;
        padding: 0 2rem;
    }

    .timeline-item:nth-child(odd) {
        text-align: right;
        padding-right: calc(50% + 3rem);
    }

    .timeline-item:nth-child(even) {
        text-align: left;
        padding-left: calc(50% + 3rem);
    }

    .timeline-date {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        background: var(--guerlain-gold);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .timeline-content {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-top: 2rem;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .value-card {
        background: white;
        padding: 2.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .value-card:hover {
        transform: translateY(-5px);
    }

    .value-icon {
        font-size: 3rem;
        color: var(--guerlain-gold);
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .heritage-hero {
            height: 50vh;
            margin-top: 60px;
        }
        
        .heritage-title {
            font-size: 2rem;
        }
        
        .timeline::before {
            left: 2rem;
        }
        
        .timeline-item:nth-child(odd) {
            text-align: left;
            padding-right: 2rem;
            padding-left: 4rem;
        }
        
        .timeline-date {
            left: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="heritage-hero">
    <div class="container">
        <h1 class="display-2 mb-4">Heritage Parfums</h1>
        <p class="lead">Un siècle de passion pour l'art olfactif français</p>
    </div>
</section>

<!-- Contenu Principal -->
<section class="heritage-content">
    <div class="container">
        <!-- Introduction -->
        <div class="heritage-section">
            <h2 class="heritage-title">Notre Histoire</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <p class="heritage-text">
                        Depuis 1925, Heritage Parfums perpétue l'excellence de la parfumerie française. 
                        Née de la passion d'un maître parfumeur visionnaire, notre maison incarne 
                        l'élégance et le raffinement à travers des créations olfactives uniques.
                    </p>
                    <p class="heritage-text">
                        Chaque parfum Heritage Parfums raconte une histoire, évoque une émotion, 
                        capture un instant d'éternité. Nous puisons notre inspiration dans les 
                        traditions ancestrales tout en embrassant l'innovation contemporaine.
                    </p>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="heritage-section">
            <h2 class="heritage-title">Un Siècle de Créations</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">1925</div>
                    <div class="timeline-content">
                        <h4 class="heritage-subtitle">La Fondation</h4>
                        <p class="heritage-text">
                            Henri Dubois fonde Heritage Parfums dans son atelier parisien. 
                            Sa première création, "Rose Éternelle", devient rapidement 
                            l'emblème de la maison.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-date">1950</div>
                    <div class="timeline-content">
                        <h4 class="heritage-subtitle">L'Expansion</h4>
                        <p class="heritage-text">
                            Ouverture de la première boutique sur les Champs-Élysées. 
                            Heritage Parfums devient une référence internationale 
                            du luxe français.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-date">1975</div>
                    <div class="timeline-content">
                        <h4 class="heritage-subtitle">L'Innovation</h4>
                        <p class="heritage-text">
                            Lancement de la collection "Signatures", des parfums 
                            sur-mesure créés pour une clientèle exclusive. 
                            Innovation dans les techniques d'extraction.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-date">2000</div>
                    <div class="timeline-content">
                        <h4 class="heritage-subtitle">Le Renouveau</h4>
                        <p class="heritage-text">
                            Nouvelle génération, nouvelle vision. Tout en préservant 
                            l'héritage familial, Heritage Parfums se réinvente 
                            pour les amateurs contemporains.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-date">2025</div>
                    <div class="timeline-content">
                        <h4 class="heritage-subtitle">L'Avenir</h4>
                        <p class="heritage-text">
                            Un siècle plus tard, Heritage Parfums continue d'écrire 
                            son histoire avec la même passion et le même engagement 
                            vers l'excellence.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nos Valeurs -->
        <div class="heritage-section">
            <h2 class="heritage-title">Nos Valeurs</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h4 class="heritage-subtitle">Excellence</h4>
                    <p class="heritage-text">
                        Chaque parfum est le fruit d'un savoir-faire artisanal 
                        transmis de génération en génération.
                    </p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 class="heritage-subtitle">Passion</h4>
                    <p class="heritage-text">
                        Notre amour pour l'art olfactif guide chacune de nos 
                        créations avec authenticité et émotion.
                    </p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="heritage-subtitle">Innovation</h4>
                    <p class="heritage-text">
                        Nous allions tradition et modernité pour créer les 
                        parfums de demain tout en respectant notre héritage.
                    </p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="heritage-section text-center">
            <h2 class="heritage-title">Découvrez Nos Créations</h2>
            <p class="heritage-text">
                Laissez-vous séduire par l'univers Heritage Parfums et trouvez 
                le parfum qui vous ressemble.
            </p>
            <a href="/" class="btn btn-guerlain mt-3">Explorer Nos Parfums</a>
        </div>
    </div>
</section>
@endsection