@extends('layouts.app')

@section('title', 'Collections - Heritage Parfums')
@section('description', 'Découvrez toutes les collections Heritage Parfums : parfums femme, homme, collections exclusives et nouveautés.')

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        margin-top: 80px;
    }

    .collection-card {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .collection-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .collection-image {
        height: 400px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .collection-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.7));
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 2rem;
        color: white;
        text-align: center;
    }

    .collection-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--primary-gold);
    }

    .collection-description {
        margin-bottom: 1rem;
    }

    .filter-buttons {
        margin-bottom: 3rem;
    }

    .filter-btn {
        border: 2px solid var(--primary-gold);
        color: var(--primary-gold);
        background: transparent;
        padding: 8px 20px;
        margin: 0 5px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--primary-gold);
        color: white;
    }
</style>
@endpush

@section('content')
<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <div class="fade-in">
            <h1 class="font-playfair display-4 mb-3">Nos Collections</h1>
            <p class="lead">Explorez l'univers olfactif d'Heritage Parfums</p>
        </div>
    </div>
</section>

<!-- Filters -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="text-center filter-buttons fade-in">
            <button class="btn filter-btn active" data-filter="all">Toutes les Collections</button>
            <button class="btn filter-btn" data-filter="femme">Femme</button>
            <button class="btn filter-btn" data-filter="homme">Homme</button>
            <button class="btn filter-btn" data-filter="exclusif">Exclusif</button>
            <button class="btn filter-btn" data-filter="nouveaute">Nouveautés</button>
        </div>
    </div>
</section>

<!-- Collections Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4" id="collections-grid">
            <!-- Collection Légendes Féminines -->
            <div class="col-lg-6 collection-item fade-in" data-category="femme">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');">
                        <div class="collection-overlay">
                            <h2 class="collection-title">Légendes Féminines</h2>
                            <p class="collection-description">Des parfums intemporels qui célèbrent la féminité dans toute sa splendeur</p>
                            <a href="/collections/femme" class="btn btn-outline-light">Découvrir la Collection</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Signatures Masculines -->
            <div class="col-lg-6 collection-item fade-in" data-category="homme">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('https://images.unsplash.com/photo-1615634260167-c8cdede054de?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');">
                        <div class="collection-overlay">
                            <h2 class="collection-title">Signatures Masculines</h2>
                            <p class="collection-description">L'élégance et la puissance masculine révélées par des compositions uniques</p>
                            <a href="/collections/homme" class="btn btn-outline-light">Découvrir la Collection</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Exclusives -->
            <div class="col-lg-4 collection-item fade-in" data-category="exclusif">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');">
                        <div class="collection-overlay">
                            <h2 class="collection-title">Pièces d'Exception</h2>
                            <p class="collection-description">Créations rares en édition limitée</p>
                            <a href="/collections/exclusifs" class="btn btn-outline-light">Explorer</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Nouveautés -->
            <div class="col-lg-4 collection-item fade-in" data-category="nouveaute">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('https://images.unsplash.com/photo-1557170334-a9632e77c6e4?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');">
                        <div class="collection-overlay">
                            <h2 class="collection-title">Nouvelles Créations</h2>
                            <p class="collection-description">Les dernières innovations olfactives</p>
                            <a href="/collections/nouveautes" class="btn btn-outline-light">Découvrir</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Les Jardins -->
            <div class="col-lg-4 collection-item fade-in" data-category="femme">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');">
                        <div class="collection-overlay">
                            <h2 class="collection-title">Les Jardins</h2>
                            <p class="collection-description">Fraîcheur florale et naturelle</p>
                            <a href="/collections/femme" class="btn btn-outline-light">Explorer</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Bois Précieux -->
            <div class="col-lg-6 collection-item fade-in" data-category="homme">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('https://images.unsplash.com/photo-1585386959984-a4155224a1ad?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');">
                        <div class="collection-overlay">
                            <h2 class="collection-title">Bois Précieux</h2>
                            <p class="collection-description">La noblesse des bois rares dans des compositions sophistiquées</p>
                            <a href="/collections/homme" class="btn btn-outline-light">Découvrir</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Orientales -->
            <div class="col-lg-6 collection-item fade-in" data-category="exclusif">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('https://images.unsplash.com/photo-1563170351-be82bc888aa4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');">
                        <div class="collection-overlay">
                            <h2 class="collection-title">Orientales Mystiques</h2>
                            <p class="collection-description">Voyage olfactif vers les terres d'Orient et leurs trésors aromatiques</p>
                            <a href="/collections/exclusifs" class="btn btn-outline-light">Voyager</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Heritage Section -->
<section class="py-5 bg-cream">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 fade-in">
                <h2 class="font-playfair text-gold mb-4">L'Art de la Composition</h2>
                <p class="lead">Chaque collection Heritage Parfums est née d'une inspiration unique, d'un voyage, d'une émotion ou d'une rencontre extraordinaire.</p>
                <p>Nos maîtres parfumeurs puisent dans un vocabulaire olfactif riche de plus de 300 matières premières précieuses pour créer des harmonies uniques qui transcendent les tendances et traversent le temps.</p>
            </div>
            <div class="col-lg-4 fade-in">
                <img src="https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                     class="img-fluid rounded shadow" alt="Matières premières parfumerie">
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Collection filtering
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const collectionItems = document.querySelectorAll('.collection-item');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter collections
                collectionItems.forEach(item => {
                    const category = item.getAttribute('data-category');
                    
                    if (filter === 'all' || category === filter) {
                        item.style.display = 'block';
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.style.opacity = '1';
                        }, 100);
                    } else {
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    });
</script>
@endpush