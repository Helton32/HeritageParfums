@extends('layouts.app')

@section('title', 'Contact - Héritaj Parfums')
@section('description', 'Contactez Héritaj Parfums. Notre équipe d\'experts vous accompagne dans le choix de vos parfums de luxe.')

@push('styles')
<style>
    /* Contact Page - Style Guerlain */
    .contact-hero {
        
        background-size: cover;
        background-position: center;
        height: 20vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--guerlain-white);
        margin-top: 0;
        padding-top: 120px;
    }

    .contact-hero h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 4rem;
        font-weight: 300;
        margin-bottom: 2rem;
        letter-spacing: 2px;
    }

    .contact-hero .lead {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.3rem;
        font-weight: 300;
        letter-spacing: 1px;
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-section {
        padding: 6rem 0;
        background: var(--guerlain-white);
    }

    .contact-card {
        background: var(--guerlain-light-gray);
        border-radius: 0;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        padding: 3rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        border: 1px solid var(--guerlain-border);
        text-align: center;
        height: 100%;
    }

    .contact-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.12);
    }

    .contact-icon {
        width: 70px;
        height: 70px;
        background: var(--guerlain-gold);
        color: var(--guerlain-white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 2rem;
        border: 3px solid var(--guerlain-white);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    }

    .contact-card h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 400;
        color: var(--guerlain-black);
        margin-bottom: 1.5rem;
    }

    .contact-card p {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: var(--guerlain-text-gray);
        line-height: 1.8;
        margin-bottom: 1rem;
    }

    .contact-card strong {
        color: var(--guerlain-gold);
        font-weight: 400;
    }

    /* Form Guerlain Style */
    .form-section {
        padding: 6rem 0;
        background: var(--guerlain-cream);
    }

    .form-control {
        border: none;
        border-bottom: 2px solid var(--guerlain-border);
        border-radius: 0;
        padding: 20px 0;
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        background: transparent;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--guerlain-gold);
        box-shadow: none;
        background: transparent;
    }

    .form-label {
        font-family: 'Montserrat', sans-serif;
        font-weight: 400;
        color: var(--guerlain-text-gray);
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .btn-submit {
        background-color: var(--guerlain-black);
        border: 2px solid var(--guerlain-black);
        color: var(--guerlain-white);
        padding: 18px 50px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 400;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-radius: 0;
        transition: all 0.4s ease;
    }

    .btn-submit:hover {
        background-color: var(--guerlain-white);
        color: var(--guerlain-black);
        border-color: var(--guerlain-black);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .map-section {
        padding: 6rem 0;
        background: var(--guerlain-white);
    }

    .map-container {
        height: 400px;
        background: var(--guerlain-light-gray);
        border: 1px solid var(--guerlain-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--guerlain-text-gray);
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
    
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.2rem rgba(184, 134, 11, 0.25);
    }

    .btn-contact {
        background: linear-gradient(45deg, var(--primary-gold), var(--secondary-gold));
        border: none;
        color: white;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-contact:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(184, 134, 11, 0.3);
        color: white;
    }

    .store-hours {
        background: var(--cream);
        border-radius: 15px;
        padding: 2rem;
    }

    .hour-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #ddd;
    }

    .hour-row:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')
<!-- Contact Hero -->
<section class="contact-hero">
    <div class="container">
        <div class="fade-in">
            <h1>Nous Contacter</h1>
            <p class="lead">Notre équipe d'experts vous accompagne dans votre choix</p>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="contact-section">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Phone -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Téléphone</h3>
                    <p><strong>01 42 86 95 30</strong></p>
                    <p>Lundi - Vendredi: 9h - 18h<br>Samedi: 10h - 17h</p>
                </div>
            </div>
            
            <!-- Email -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email</h3>
                    <p><strong>contact@heritage-parfums.fr</strong></p>
                    <p>Réponse garantie sous 24h</p>
                </div>
            </div>
            
            <!-- Address -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Boutique Parisienne</h3>
                    <p><strong>123 Avenue des Champs-Élysées<br>75008 Paris</strong></p>
                    <p>Ouvert 7j/7<br>10h - 20h</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="form-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="font-serif text-guerlain-black" style="font-size: 3.5rem; font-weight: 300;">Écrivez-nous</h2>
                    <p class="lead" style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray);">Nous serions ravis de vous accompagner</p>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Erreur:</strong> Veuillez corriger les champs suivants.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label">Prénom & Nom *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Adresse Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="+33 1 23 45 67 89">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="subject" class="form-label">Sujet *</label>
                            <select class="form-control @error('subject') is-invalid @enderror" 
                                    id="subject" name="subject" required>
                                <option value="">Choisir un sujet</option>
                                <option value="Demande d'information" {{ old('subject') === 'Demande d\'information' ? 'selected' : '' }}>
                                    Demande d'information
                                </option>
                                <option value="Conseil personnalisé" {{ old('subject') === 'Conseil personnalisé' ? 'selected' : '' }}>
                                    Conseil personnalisé
                                </option>
                                <option value="Commande & Livraison" {{ old('subject') === 'Commande & Livraison' ? 'selected' : '' }}>
                                    Commande & Livraison
                                </option>
                                <option value="Service client" {{ old('subject') === 'Service client' ? 'selected' : '' }}>
                                    Service client
                                </option>
                                <option value="Retour & Échange" {{ old('subject') === 'Retour & Échange' ? 'selected' : '' }}>
                                    Retour & Échange
                                </option>
                                <option value="Autre" {{ old('subject') === 'Autre' ? 'selected' : '' }}>
                                    Autre
                                </option>
                            </select>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="message" class="form-label">Votre Message *</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" name="message" rows="6" required 
                                  placeholder="Décrivez votre demande en détail...">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum 2000 caractères</div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer le Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="font-serif text-guerlain-black" style="font-size: 3.5rem; font-weight: 300;">Notre Boutique</h2>
            <p class="lead" style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: var(--guerlain-text-gray);">Au cœur des Champs-Élysées</p>
        </div>
        
        <div class="map-container">
            <div class="text-center">
                <i class="fas fa-map-marked-alt fa-3x mb-3" style="color: var(--guerlain-gold);"></i>
                <h5>123 Avenue des Champs-Élysées</h5>
                <p>Paris 8ème - Métro George V</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Amélioration UX pour le formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';
    });
    
    // Auto-scroll vers les messages d'erreur
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        alerts[0].scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
@endpush