@extends('layouts.app')

@section('title', 'Notre Histoire - Heritage Parfums')
@section('description', 'Découvrez l\'histoire de Heritage Parfums, maison de parfumerie française fondée en 1925. Un siècle de savoir-faire et d\'excellence.')

@push('styles')
<style>
    /* Page About - Style Guerlain */
    .hero-about {
        padding: 150px 0 100px;
        background: linear-gradient(rgba(13, 13, 13, 0.7), rgba(13, 13, 13, 0.7)), 
                    url('https://images.unsplash.com/photo-1515377905703-c4788e51af15?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
        color: var(--guerlain-white);
        text-align: center;
    }

    .hero-about h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 4rem;
        font-weight: 300;
        margin-bottom: 2rem;
        letter-spacing: 2px;
    }

    .hero-about .lead {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.3rem;
        font-weight: 300;
        letter-spacing: 1px;
        max-width: 600px;
        margin: 0 auto;
    }

    .timeline {
        position: relative;
        padding: 6rem 0;
        background: var(--guerlain-cream);
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 1px;
        background: var(--guerlain-gold);
        transform: translateX(-50%);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 4rem;
        width: 50%;
    }

    .timeline-item:nth-child(odd) {
        left: 0;
        padding-right: 4rem;
        text-align: right;
    }

    .timeline-item:nth-child(even) {
        left: 50%;
        padding-left: 4rem;
        text-align: left;
    }

    .timeline-marker {
        position: absolute;
        top: 20px;
        width: 15px;
        height: 15px;
        background: var(--guerlain-gold);
        border: 3px solid var(--guerlain-white);
        border-radius: 50%;
        box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
    }

    .timeline-item:nth-child(odd) .timeline-marker {
        right: -9px;
    }

    .timeline-item:nth-child(even) .timeline-marker {
        left: -9px;
    }

    .timeline-content {
        background: var(--guerlain-white);
        padding: 3rem;
        border-radius: 0;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--guerlain-border);
        transition: all 0.3s ease;
    }

    .timeline-content:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.12);
    }

    .timeline-year {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        color: var(--guerlain-gold);
        font-weight: 300;
        margin-bottom: 1.5rem;
        letter-spacing: 1px;
    }

    .timeline-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        color: var(--guerlain-black);
        font-weight: 400;
        margin-bottom: 1rem;
    }

    .timeline-description {
        font-family: 'Montserrat', sans-serif;
        font-size: 1rem;
        font-weight: 300;
        color: var(--guerlain-text-gray);
        line-height: 1.8;
    }

    /* Sections Guerlain */
    .values-section {
        padding: 6rem 0;
        background: var(--guerlain-white);
    }

    .values-card {
        text-align: center;
        padding: 3rem 2rem;
        background: var(--guerlain-light-gray);
        border: none;
        border-radius: 0;
        height: 100%;
        transition: all 0.3s ease;
    }

    .values-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
    }

    .values-icon {
        font-size: 3rem;
        color: var(--guerlain-gold);
        margin-bottom: 2rem;
    }

    .values-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 400;
        color: var(--guerlain-black);
        margin-bottom: 1.5rem;
    }

    .values-text {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: var(--guerlain-text-gray);
        line-height: 1.8;
    }
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .value-card {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .value-card:hover {
        transform: translateY(-5px);
    }

    .value-icon {
        font-size: 3rem;
        color: var(--primary-gold);
        margin-bottom: 1rem;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .team-member {
        text-align: center;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .team-member:hover {
        transform: translateY(-5px);
    }

    .team-photo {
        height: 250px;
        background-size: cover;
        background-position: center;
    }

    .team-info {
        padding: 1.5rem;
    }

    .team-name {
        font-family: 'Playfair Display', serif;
        color: var(--primary-gold);
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }

    .team-role {
        color: #666;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .timeline::before {
            left: 20px;
        }

        .timeline-item {
            width: 100%;
            left: 0 !important;
            padding-left: 3rem !important;
            padding-right: 0 !important;
            text-align: left !important;
        }

        .timeline-marker {
            left: 10px !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section Guerlain -->
<section class="hero-about">
    <div class="container">
        <div class="fade-in">
            <h1>Notre Histoire</h1>
            <p class="lead">Un siècle de passion pour l'art de la parfumerie française</p>
        </div>
    </div>
</section>

<!-- Histoire -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 fade-in">
                <h2 class="font-serif text-guerlain-gold mb-4" style="font-size: 3rem;">L'Héritage d'une Passion</h2>
                <p class="lead" style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray);">Depuis 1925, Heritage Parfums incarne l'excellence de la parfumerie française.</p>
                <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; line-height: 1.8;">Tout a commencé dans un petit atelier parisien où Henri Dubois, notre fondateur, a créé sa première fragrance. Passionné par l'art olfactif et inspiré par les jardins de Grasse, il a développé une approche unique qui allie tradition artisanale et innovation constante.</p>
                <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; line-height: 1.8;">Aujourd'hui, Heritage Parfums est devenu une référence mondiale, reconnu pour ses créations d'exception et son savoir-faire incomparable. Chaque parfum raconte une histoire, capture une émotion et révèle la personnalité unique de celui qui le porte.</p>
            </div>
            <div class="col-lg-6 fade-in">
                <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     class="img-fluid shadow" alt="Atelier Heritage Parfums 1925" style="filter: sepia(10%) saturate(90%);">
            </div>
        </div>
    </div>
</section>

<!-- Timeline -->
<section class="timeline">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="font-serif text-guerlain-black fade-in" style="font-size: 3.5rem; font-weight: 300;">Chronologie de l'Excellence</h2>
            <p class="lead fade-in" style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray);">Les moments clés qui ont façonné notre maison</p>
        </div>
        
        <div class="timeline">
            <div class="timeline-item fade-in">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-year">1925</div>
                    <h4 class="timeline-title">Fondation de la Maison</h4>
                    <p class="timeline-description">Henri Dubois ouvre son premier atelier parisien rue Saint-Honoré et crée sa première fragrance signature.</p>
                    <p>Henri Dubois fonde Heritage Parfums dans son atelier parisien du 16ème arrondissement. Sa première création, "Rose Éternelle", connaît un succès immédiat.</p>
                </div>
            </div>
            
            <div class="timeline-item fade-in">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-year">1935</div>
                    <h4>Expansion Internationale</h4>
                    <p>Ouverture de la première boutique à Londres, marquant le début de l'expansion internationale de Heritage Parfums.</p>
                </div>
            </div>
            
            <div class="timeline-item fade-in">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-year">1950</div>
                    <h4 class="timeline-title">L'Innovation Olfactive</h4>
                    <p class="timeline-description">Lancement de "Bois Mystique", premier parfum utilisant des accords synthétiques innovants, révolutionnant la parfumerie moderne.</p>
                </div>
            </div>
            
            <div class="timeline-item fade-in">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-year">1970</div>
                    <h4 class="timeline-title">Nouvelle Génération</h4>
                    <p class="timeline-description">Marie Dubois, fille du fondateur, reprend la direction artistique et lance la collection "Légendes Féminines".</p>
                </div>
            </div>
            
            <div class="timeline-item fade-in">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-year">1995</div>
                    <h4 class="timeline-title">Les Collections Exclusives</h4>
                    <p class="timeline-description">Création de la première collection exclusive avec des éditions limitées, marquant l'entrée dans la haute parfumerie.</p>
                </div>
            </div>
            
            <div class="timeline-item fade-in">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-year">2025</div>
                    <h4 class="timeline-title">Éternelle Rose</h4>
                    <p class="timeline-description">Lancement de notre création signature, incarnant un siècle de savoir-faire dans une fragrance d'exception unique.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Valeurs -->
<section class="values-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="font-serif text-guerlain-black" style="font-size: 3.5rem; font-weight: 300;">Nos Valeurs</h2>
            <p class="lead" style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray);">Les piliers de notre excellence</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="values-card">
                    <div class="values-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3 class="values-title">Excellence</h3>
                    <p class="values-text">Chaque création répond aux standards les plus exigeants de la haute parfumerie française.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="values-card">
                    <div class="values-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 class="values-title">Naturel</h3>
                    <p class="values-text">Nous privilégions les ingrédients naturels et respectons l'environnement dans toutes nos créations.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="values-card">
                    <div class="values-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="values-title">Tradition</h3>
                    <p class="values-text">Un siècle de savoir-faire artisanal transmis de génération en génération.</p>
                </div>
            </div>
        </div>
    </div>
</section>
            
            <div class="timeline-item fade-in">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-year">2025</div>
                    <h4>Un Siècle d'Excellence</h4>
                    <p>Heritage Parfums célèbre son centenaire avec de nouvelles créations qui perpétuent la tradition tout en embrassant l'avenir.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nos Valeurs -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="font-playfair text-gold fade-in">Nos Valeurs</h2>
            <p class="lead fade-in">Ce qui guide chacune de nos créations</p>
        </div>
        
        <div class="values-grid">
            <div class="value-card fade-in">
                <div class="value-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h4>Excellence</h4>
                <p>Nous sélectionnons uniquement les matières premières les plus nobles et travaillons avec les meilleurs maîtres parfumeurs pour créer des fragrances d'exception.</p>
            </div>
            
            <div class="value-card fade-in">
                <div class="value-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h4>Passion</h4>
                <p>Notre amour pour l'art olfactif transparaît dans chaque création. Chaque parfum est conçu avec dedication et attention aux moindres détails.</p>
            </div>
            
            <div class="value-card fade-in">
                <div class="value-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h4>Responsabilité</h4>
                <p>Nous nous engageons pour un développement durable, en privilégiant des sources éthiques et en respectant l'environnement.</p>
            </div>
            
            <div class="value-card fade-in">
                <div class="value-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <h4>Innovation</h4>
                <p>Tout en respectant nos traditions, nous explorons constamment de nouvelles voies olfactives pour créer les parfums de demain.</p>
            </div>
        </div>
    </div>
</section>

<!-- Notre Équipe -->
<section class="py-5 bg-cream">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="font-playfair text-gold fade-in">Nos Maîtres Parfumeurs</h2>
            <p class="lead fade-in">Les artistes qui donnent vie à nos créations</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="artisan-story fade-in">
                    <img src="https://images.unsplash.com/photo-1612198188060-c7c2a3b66eae?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Atelier Heritage Parfums" 
                         class="img-fluid rounded shadow mb-4">
                    <h4 class="font-playfair text-gold mb-3">L'Art de la Parfumerie Française</h4>
                    <p class="lead">Dans notre atelier parisien, chaque parfum naît de la passion et du savoir-faire transmis de génération en génération.</p>
                    <p>Nos maîtres parfumeurs puisent dans un héritage centenaire pour créer des fragrances d'exception qui capturent l'essence même de l'élégance française.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Atelier -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 fade-in">
                <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     class="img-fluid rounded shadow" alt="Notre atelier">
            </div>
            <div class="col-lg-6 fade-in">
                <h2 class="font-playfair text-gold mb-4">Notre Atelier</h2>
                <p class="lead">Au cœur de Paris, notre atelier perpétue l'artisanat d'exception.</p>
                <p>Dans nos laboratoires, tradition et modernité se côtoient harmonieusement. Nos maîtres parfumeurs disposent d'une palette de plus de 3000 matières premières, sélectionnées aux quatre coins du monde.</p>
                <p>Chaque création nécessite des mois de recherche et d'affinage. C'est dans cette quête de perfection que naissent nos parfums d'exception, véritables œuvres d'art olfactives.</p>
                <a href="/contact" class="btn btn-gold">Visiter notre Atelier</a>
            </div>
        </div>
    </div>
</section>
@endsection