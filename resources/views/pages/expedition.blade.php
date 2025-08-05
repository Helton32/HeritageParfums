@extends('layouts.app')

@section('title', 'Expédition - Heritage Parfums')
@section('description', 'Choisissez votre mode de livraison préféré lors de votre commande : Mondial Relay, Colissimo ou Chronopost.')

@push('styles')
<style>
    .expedition-hero {
        background: linear-gradient(135deg, var(--guerlain-black) 0%, #333 100%);
        padding: 6rem 0 4rem 0;
        margin-top: 80px;
        color: white;
        text-align: center;
    }

    .expedition-content {
        padding: 4rem 0;
    }

    .expedition-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 300;
        color: var(--guerlain-black);
        margin-bottom: 2rem;
        text-align: center;
    }

    .expedition-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--guerlain-text-gray);
        max-width: 800px;
        margin: 0 auto 3rem auto;
        text-align: center;
    }

    .delivery-logos {
        display: flex;
        justify-content: center;
        gap: 3rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .delivery-logos img {
        height: 60px;
        object-fit: contain;
        filter: grayscale(0%);
        transition: transform 0.3s ease;
    }

    .delivery-logos img:hover {
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .expedition-hero {
            padding: 4rem 0 3rem 0;
            margin-top: 60px;
        }

        .expedition-title {
            font-size: 2rem;
        }

        .delivery-logos {
            gap: 2rem;
        }

        .delivery-logos img {
            height: 50px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="expedition-hero">
    <div class="container">
        <h1 class="display-3 mb-4">Expédition</h1>
        <p class="lead">Choisissez votre mode de livraison préféré lors de la commande</p>
    </div>
</section>

<!-- Contenu Principal -->
<section class="expedition-content">
    <div class="container">
        <h2 class="expedition-title">Livraison au choix</h2>
        <p class="expedition-text">
            Lors de la finalisation de votre commande, vous aurez la possibilité de choisir parmi plusieurs options de livraison :<br><br>
            • <strong>Mondial Relay</strong> : en point relais, pratique et économique.<br>
            • <strong>Colissimo</strong> : livraison à domicile ou en bureau de poste.<br>
            • <strong>Chronopost</strong> : livraison express à domicile ou en point relais.<br><br>
            Heritage Parfums vous laisse la liberté de sélectionner le service qui convient le mieux à votre emploi du temps et à vos préférences.
        </p>

        <div class="delivery-logos">
            <img src="{{ asset('images/livraison/mondial-relay.png') }}" alt="Mondial Relay">
            <img src="{{ asset('images/livraison/colissimo.png') }}" alt="Colissimo">
            <img src="{{ asset('images/livraison/chronopost.png') }}" alt="Chronopost">
        </div>
    </div>
</section>
@endsection
