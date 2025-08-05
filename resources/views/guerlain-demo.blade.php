@extends('layouts.app')

@section('title', 'Démonstration Design Guerlain - Heritage Parfums')

@section('content')
<!-- Hero Section Démonstration -->
<section style="padding: 150px 0 100px; background: linear-gradient(rgba(13, 13, 13, 0.8), rgba(13, 13, 13, 0.8)), 
                url('https://images.unsplash.com/photo-1541643600914-78b084683601?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover; 
                color: var(--guerlain-white); text-align: center;">
    <div class="container">
        <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 5rem; font-weight: 300; margin-bottom: 2rem; letter-spacing: 3px;">
            Design Guerlain
        </h1>
        <p style="font-family: 'Montserrat', sans-serif; font-size: 1.3rem; font-weight: 300; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 3rem;">
            Transformation complète vers l'élégance française
        </p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="#transformation" class="btn btn-guerlain-gold btn-lg">Découvrir</a>
            <a href="#comparison" class="btn btn-guerlain-outline btn-lg">Comparaison</a>
        </div>
    </div>
</section>

<!-- Section Transformation -->
<section id="transformation" style="padding: 6rem 0; background: var(--guerlain-cream);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; font-weight: 300; color: var(--guerlain-black); letter-spacing: 1px;">
                L'Art du Raffinement
            </h2>
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); font-size: 1.1rem; max-width: 600px; margin: 0 auto; line-height: 1.8;">
                Découvrez comment Heritage Parfums a embrassé l'esthétique légendaire de Guerlain, créant une harmonie parfaite entre tradition et modernité.
            </p>
        </div>

        <div class="row">
            <!-- Palette de couleurs -->
            <div class="col-lg-4 mb-4">
                <div style="background: var(--guerlain-white); padding: 3rem 2rem; text-align: center; border: 1px solid var(--guerlain-border); transition: all 0.3s ease;">
                    <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 2rem;">
                        <div style="width: 40px; height: 40px; background: var(--guerlain-black); border-radius: 50%;"></div>
                        <div style="width: 40px; height: 40px; background: var(--guerlain-gold); border-radius: 50%;"></div>
                        <div style="width: 40px; height: 40px; background: var(--guerlain-white); border: 2px solid var(--guerlain-border); border-radius: 50%;"></div>
                    </div>
                    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; font-weight: 400; color: var(--guerlain-black); margin-bottom: 1rem;">
                        Palette Signature
                    </h3>
                    <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); line-height: 1.8;">
                        Noir profond, or raffiné et blanc pur - l'essence de l'élégance parisienne.
                    </p>
                </div>
            </div>

            <!-- Typographie -->
            <div class="col-lg-4 mb-4">
                <div style="background: var(--guerlain-white); padding: 3rem 2rem; text-align: center; border: 1px solid var(--guerlain-border); transition: all 0.3s ease;">
                    <div style="margin-bottom: 2rem;">
                        <div style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: var(--guerlain-black); margin-bottom: 0.5rem;">Aa</div>
                        <div style="font-family: 'Montserrat', sans-serif; font-size: 1.2rem; color: var(--guerlain-text-gray);">Aa</div>
                    </div>
                    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; font-weight: 400; color: var(--guerlain-black); margin-bottom: 1rem;">
                        Typographie Premium
                    </h3>
                    <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); line-height: 1.8;">
                        Cormorant Garamond & Montserrat - alliance entre classique et modernité.
                    </p>
                </div>
            </div>

            <!-- Design Elements -->
            <div class="col-lg-4 mb-4">
                <div style="background: var(--guerlain-white); padding: 3rem 2rem; text-align: center; border: 1px solid var(--guerlain-border); transition: all 0.3s ease;">
                    <div style="margin-bottom: 2rem;">
                        <div style="width: 60px; height: 2px; background: var(--guerlain-gold); margin: 0 auto 10px;"></div>
                        <div style="width: 40px; height: 40px; border: 2px solid var(--guerlain-gold); margin: 0 auto;"></div>
                    </div>
                    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; font-weight: 400; color: var(--guerlain-black); margin-bottom: 1rem;">
                        Éléments Épurés
                    </h3>
                    <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); line-height: 1.8;">
                        Formes géométriques simples, espacement généreux et détails raffinés.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Comparaison -->
<section id="comparison" style="padding: 6rem 0; background: var(--guerlain-white);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; font-weight: 300; color: var(--guerlain-black); letter-spacing: 1px;">
                Avant & Après
            </h2>
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); font-size: 1.1rem;">
                La métamorphose vers l'excellence
            </p>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div style="background: var(--guerlain-light-gray); padding: 3rem; border: 1px solid var(--guerlain-border);">
                    <h3 style="font-family: 'Cormorant Garamond', serif; color: var(--guerlain-black); margin-bottom: 2rem; text-align: center;">
                        Style Classique
                    </h3>
                    <ul style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); line-height: 2;">
                        <li>Couleurs : Or traditionnel (#B8860B)</li>
                        <li>Police : Playfair Display</li>
                        <li>Style : Français classique</li>
                        <li>Boutons : Arrondis avec dégradés</li>
                        <li>Espacement : Standard</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div style="background: var(--guerlain-black); color: var(--guerlain-white); padding: 3rem; border: 2px solid var(--guerlain-gold);">
                    <h3 style="font-family: 'Cormorant Garamond', serif; color: var(--guerlain-gold); margin-bottom: 2rem; text-align: center;">
                        Style Guerlain
                    </h3>
                    <ul style="font-family: 'Montserrat', sans-serif; font-weight: 300; line-height: 2;">
                        <li>Couleurs : Noir & Or raffiné (#d4af37)</li>
                        <li>Polices : Cormorant + Montserrat</li>
                        <li>Style : Luxe moderne minimaliste</li>
                        <li>Boutons : Rectangulaires épurés</li>
                        <li>Espacement : Généreux et aéré</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Pages Transformées -->
<section style="padding: 6rem 0; background: var(--guerlain-cream);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; font-weight: 300; color: var(--guerlain-black);">
                Pages Transformées
            </h2>
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray);">
                Chaque page a été repensée pour incarner l'excellence Guerlain
            </p>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="{{ route('home') }}" style="text-decoration: none;">
                    <div style="background: var(--guerlain-white); padding: 2rem; text-align: center; border: 1px solid var(--guerlain-border); transition: all 0.3s ease;" 
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 50px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <i class="fas fa-home fa-2x" style="color: var(--guerlain-gold); margin-bottom: 1rem;"></i>
                        <h4 style="font-family: 'Cormorant Garamond', serif; color: var(--guerlain-black); margin-bottom: 1rem;">Accueil</h4>
                        <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); font-size: 0.9rem;">
                            Hero section, produit showcase
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="{{ route('about') }}" style="text-decoration: none;">
                    <div style="background: var(--guerlain-white); padding: 2rem; text-align: center; border: 1px solid var(--guerlain-border); transition: all 0.3s ease;"
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 50px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <i class="fas fa-history fa-2x" style="color: var(--guerlain-gold); margin-bottom: 1rem;"></i>
                        <h4 style="font-family: 'Cormorant Garamond', serif; color: var(--guerlain-black); margin-bottom: 1rem;">Histoire</h4>
                        <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); font-size: 0.9rem;">
                            Timeline, valeurs
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="{{ route('contact') }}" style="text-decoration: none;">
                    <div style="background: var(--guerlain-white); padding: 2rem; text-align: center; border: 1px solid var(--guerlain-border); transition: all 0.3s ease;"
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 50px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <i class="fas fa-envelope fa-2x" style="color: var(--guerlain-gold); margin-bottom: 1rem;"></i>
                        <h4 style="font-family: 'Cormorant Garamond', serif; color: var(--guerlain-black); margin-bottom: 1rem;">Contact</h4>
                        <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); font-size: 0.9rem;">
                            Formulaire, informations
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                @auth
                    <a href="{{ route('admin.dashboard') }}" style="text-decoration: none;">
                @else
                    <a href="{{ route('admin.login') }}" style="text-decoration: none;">
                @endauth
                    <div style="background: var(--guerlain-white); padding: 2rem; text-align: center; border: 1px solid var(--guerlain-border); transition: all 0.3s ease;"
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 50px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <i class="fas fa-user-shield fa-2x" style="color: var(--guerlain-gold); margin-bottom: 1rem;"></i>
                        <h4 style="font-family: 'Cormorant Garamond', serif; color: var(--guerlain-black); margin-bottom: 1rem;">Administration</h4>
                        <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray); font-size: 0.9rem;">
                            Login, dashboard, shipping
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Section Détails Techniques -->
<section style="padding: 6rem 0; background: var(--guerlain-white);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; font-weight: 300; color: var(--guerlain-black);">
                Détails Techniques
            </h2>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div style="background: var(--guerlain-light-gray); padding: 3rem; border: 1px solid var(--guerlain-border);">
                    <h3 style="font-family: 'Cormorant Garamond', serif; color: var(--guerlain-black); margin-bottom: 2rem;">
                        Variables CSS
                    </h3>
                    <pre style="background: var(--guerlain-black); color: var(--guerlain-white); padding: 1.5rem; font-size: 0.85rem; overflow-x: auto;">
:root {
    --guerlain-black: #0d0d0d;
    --guerlain-gold: #d4af37;
    --guerlain-dark-gold: #b8941f;
    --guerlain-white: #ffffff;
    --guerlain-cream: #faf9f7;
    --guerlain-text-gray: #666666;
}</pre>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div style="background: var(--guerlain-light-gray); padding: 3rem; border: 1px solid var(--guerlain-border);">
                    <h3 style="font-family: 'Cormorant Garamond', serif; color: var(--guerlain-black); margin-bottom: 2rem;">
                        Classes Principales
                    </h3>
                    <div style="font-family: 'Montserrat', sans-serif; font-weight: 300; line-height: 1.8;">
                        <code>.btn-guerlain</code> - Bouton noir principal<br>
                        <code>.btn-guerlain-outline</code> - Bouton or outline<br>
                        <code>.btn-guerlain-gold</code> - Bouton or plein<br>
                        <code>.text-guerlain-gold</code> - Texte or<br>
                        <code>.font-serif</code> - Cormorant Garamond<br>
                        <code>.font-sans</code> - Montserrat
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section CTA Final -->
<section style="padding: 6rem 0; background: var(--guerlain-black); color: var(--guerlain-white); text-align: center;">
    <div class="container">
        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; font-weight: 300; margin-bottom: 2rem; color: var(--guerlain-gold);">
            L'Excellence Incarnée
        </h2>
        <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 1.2rem; margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.8;">
            Heritage Parfums reflète maintenant l'élégance intemporelle et le raffinement absolu de la tradition Guerlain.
        </p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('home') }}" class="btn btn-guerlain-gold btn-lg">Découvrir la Boutique</a>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="btn btn-guerlain-outline btn-lg">Administration</a>
            @else
                <a href="{{ route('admin.login') }}" class="btn btn-guerlain-outline btn-lg">Connexion Admin</a>
            @endauth
        </div>
    </div>
</section>
@endsection
