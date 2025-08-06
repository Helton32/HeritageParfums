@extends('layouts.app')

@section('title', 'Modifier ' . $product->name . ' - Administration')

@push('styles')
<style>
    .admin-header {
        background: var(--guerlain-black);
        color: var(--guerlain-white);
        padding: 2rem 0;
        margin-top: 80px;
    }

    .admin-header h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 3rem;
        font-weight: 300;
        margin-bottom: 0.5rem;
        color: var(--guerlain-gold);
    }

    .admin-content {
        padding: 4rem 0;
        background: var(--guerlain-light-gray);
        min-height: calc(100vh - 200px);
    }

    .form-card {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 3rem;
        margin-bottom: 2rem;
    }

    .form-section {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--guerlain-border);
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .section-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 400;
        color: var(--guerlain-black);
        margin-bottom: 1.5rem;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -0.5rem;
        left: 0;
        width: 50px;
        height: 2px;
        background: var(--guerlain-gold);
    }

    .form-label {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        color: var(--guerlain-black);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid var(--guerlain-border);
        padding: 0.75rem 1rem;
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--guerlain-gold);
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
    }

    .btn-guerlain {
        background: var(--guerlain-gold);
        border: 1px solid var(--guerlain-gold);
        color: var(--guerlain-white);
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1rem 3rem;
        transition: all 0.3s ease;
    }

    .btn-guerlain:hover {
        background: var(--guerlain-black);
        border-color: var(--guerlain-black);
        color: var(--guerlain-white);
    }

    .btn-secondary {
        background: var(--guerlain-light-gray);
        border: 1px solid var(--guerlain-border);
        color: var(--guerlain-black);
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        padding: 1rem 3rem;
    }

    .product-preview {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        position: sticky;
        top: 120px;
    }

    .preview-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: var(--guerlain-light-gray);
        border: 1px solid var(--guerlain-border);
        margin-bottom: 1rem;
    }

    .notes-container, .images-container {
        border: 1px solid var(--guerlain-border);
        padding: 1.5rem;
        background: var(--guerlain-light-gray);
        margin-top: 1rem;
    }

    .notes-category {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid var(--guerlain-border);
    }

    .notes-category-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--guerlain-black);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notes-category-title small {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        font-weight: 300;
        font-style: italic;
        margin-left: 0.5rem;
    }

    .pyramid-preview {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-top: 2rem;
        border: 2px solid var(--guerlain-border);
    }

    .pyramid-preview h6 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 1.5rem;
        color: var(--guerlain-black);
    }

    .preview-pyramid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .preview-layer {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: relative;
    }

    .preview-layer.head-preview {
        border-left: 4px solid #2196F3;
    }

    .preview-layer.heart-preview {
        border-left: 4px solid #E91E63;
    }

    .preview-layer.base-preview {
        border-left: 4px solid #FF9800;
    }

    .layer-title {
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--guerlain-black);
        margin-bottom: 0.5rem;
        display: block;
    }

    .layer-notes {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .preview-note {
        background: var(--guerlain-gold);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .no-notes {
        color: var(--guerlain-text-gray);
        font-style: italic;
        font-size: 0.85rem;
    }

    .note-input, .image-input {
        margin-bottom: 1rem;
    }

    .remove-btn {
        background: #dc3545;
        border: none;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-size: 0.8rem;
    }

    .add-btn {
        background: var(--guerlain-gold);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 3px;
        font-size: 0.9rem;
    }

    .form-check-input:checked {
        background-color: var(--guerlain-gold);
        border-color: var(--guerlain-gold);
    }

    .form-check-label {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: var(--guerlain-text-gray);
    }

    .action-buttons {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        margin-top: 2rem;
    }

    /* Styles pour les promotions */
    .promotion-preview {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 2px solid #ffc107;
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1rem;
        text-align: center;
    }

    .promotion-preview h6 {
        color: #856404;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .price-display {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .original-price {
        font-size: 1.2rem;
        color: #dc3545;
        text-decoration: line-through;
        font-weight: 300;
    }

    .promotion-price {
        font-size: 1.8rem;
        color: #28a745;
        font-weight: 700;
    }

    .discount-badge {
        background: #dc3545;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .promotion-text {
        font-style: italic;
        color: #856404;
        margin-top: 0.5rem;
    }

    #promotion-fields {
        border-left: 4px solid #ffc107;
        padding-left: 1rem;
        margin-top: 1rem;
    }
</style>
@endpush

@section('content')
<!-- Admin Header -->
<section class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Modifier le Produit</h1>
                <p class="lead mb-0">{{ $product->name }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary me-2">
                    <i class="fas fa-eye me-2"></i>Voir
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Admin Content -->
<section class="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-card">
                        <!-- Informations de base -->
                        <div class="form-section">
                            <h3 class="section-title">Informations de Base</h3>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom du Produit *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-2 mb-3">
                                    <label for="brand" class="form-label">Marque</label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                           id="brand" name="brand" value="{{ old('brand', $product->brand) }}" 
                                           placeholder="ex: Guerlain, Dior">
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="price" class="form-label">Prix (€) *</label>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="product_type" class="form-label">Type de Produit *</label>
                                    <select class="form-select @error('product_type') is-invalid @enderror" 
                                            id="product_type" name="product_type" required onchange="updateCategoriesAndTypes()">
                                        <option value="">Sélectionner le type de produit</option>
                                        <option value="parfum" {{ old('product_type', $product->product_type ?? 'parfum') === 'parfum' ? 'selected' : '' }}>
                                            Parfum
                                        </option>
                                        <option value="cosmetique" {{ old('product_type', $product->product_type ?? 'parfum') === 'cosmetique' ? 'selected' : '' }}>
                                            Cosmétique
                                        </option>
                                    </select>
                                    @error('product_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label">Catégorie *</label>
                                    <select class="form-select @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <!-- Options dynamiques -->
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label">Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Sélectionner un type</option>
                                        <!-- Options dynamiques -->
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="size" class="form-label">Taille *</label>
                                    <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                           id="size" name="size" value="{{ old('size', $product->size) }}" 
                                           placeholder="ex: 50ml, 100ml, 30g" required>
                                    @error('size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stock *</label>
                                    <input type="number" min="0" 
                                           class="form-control @error('stock') is-invalid @enderror" 
                                           id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="badge" class="form-label">Badge (optionnel)</label>
                                    <input type="text" class="form-control @error('badge') is-invalid @enderror" 
                                           id="badge" name="badge" value="{{ old('badge', $product->badge) }}" 
                                           placeholder="ex: Nouveau, Édition limitée">
                                    @error('badge')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Promotions -->
                        <div class="form-section">
                            <h3 class="section-title">🎯 Promotions et Prix Spécial</h3>
                            <p class="text-muted mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Configurez une promotion avec prix barré pour attirer les clients
                            </p>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_on_promotion" 
                                               name="is_on_promotion" {{ old('is_on_promotion', $product->is_on_promotion) ? 'checked' : '' }}
                                               onchange="togglePromotionFields()">
                                        <label class="form-check-label" for="is_on_promotion">
                                            <strong>🏷️ Activer la promotion</strong>
                                            <br><small>Si coché, affichera le prix original barré et le prix promotion</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="promotion-fields" style="display: {{ old('is_on_promotion', $product->is_on_promotion) ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="promotion_price" class="form-label">💰 Prix en Promotion (€)</label>
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('promotion_price') is-invalid @enderror" 
                                               id="promotion_price" name="promotion_price" 
                                               value="{{ old('promotion_price', $product->promotion_price) }}"
                                               placeholder="Prix réduit">
                                        @error('promotion_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Doit être inférieur au prix normal</small>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="promotion_start_date" class="form-label">📅 Date de Début</label>
                                        <input type="date" 
                                               class="form-control @error('promotion_start_date') is-invalid @enderror" 
                                               id="promotion_start_date" name="promotion_start_date" 
                                               value="{{ old('promotion_start_date', $product->promotion_start_date?->format('Y-m-d')) }}">
                                        @error('promotion_start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Optionnel - laissez vide pour démarrer immédiatement</small>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="promotion_end_date" class="form-label">📅 Date de Fin</label>
                                        <input type="date" 
                                               class="form-control @error('promotion_end_date') is-invalid @enderror" 
                                               id="promotion_end_date" name="promotion_end_date" 
                                               value="{{ old('promotion_end_date', $product->promotion_end_date?->format('Y-m-d')) }}">
                                        @error('promotion_end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Optionnel - laissez vide pour une promotion permanente</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="promotion_description" class="form-label">📝 Description de la Promotion</label>
                                        <textarea class="form-control @error('promotion_description') is-invalid @enderror" 
                                                  id="promotion_description" name="promotion_description" rows="2" 
                                                  placeholder="ex: Offre spéciale Saint-Valentin, Liquidation de stock, etc.">{{ old('promotion_description', $product->promotion_description) }}</textarea>
                                        @error('promotion_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Aperçu de la promotion -->
                                <div class="promotion-preview" id="promotion-preview" style="display: none;">
                                    <h6><i class="fas fa-eye me-2"></i>Aperçu de l'affichage</h6>
                                    <div class="price-display">
                                        <span class="original-price" id="preview-original">0,00 €</span>
                                        <span class="promotion-price" id="preview-promotion">0,00 €</span>
                                        <span class="discount-badge" id="preview-discount">-0%</span>
                                    </div>
                                    <div class="promotion-text" id="preview-description"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Descriptions -->
                        <div class="form-section">
                            <h3 class="section-title">Descriptions</h3>
                            
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Description Courte</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                          id="short_description" name="short_description" rows="3" 
                                          placeholder="Description courte pour les listes de produits">{{ old('short_description', $product->short_description) }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description Complète *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="6" 
                                          placeholder="Description détaillée du parfum" required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes olfactives -->
                        <div class="form-section">
                            <h3 class="section-title">Notes Olfactives</h3>
                            <p class="text-muted mb-4">Organisez les notes par catégories pour créer une pyramide olfactive complète</p>
                            
                            @php
                                $productNotes = $product->notes ?: [];
                                $headNotes = old('head_notes', $productNotes['head'] ?? ['']);
                                $heartNotes = old('heart_notes', $productNotes['heart'] ?? ['']);
                                $baseNotes = old('base_notes', $productNotes['base'] ?? ['']);
                                
                                // S'assurer qu'il y a au moins un champ vide
                                if (empty(array_filter($headNotes))) $headNotes = [''];
                                if (empty(array_filter($heartNotes))) $heartNotes = [''];
                                if (empty(array_filter($baseNotes))) $baseNotes = [''];
                            @endphp
                            
                            <!-- Notes de Tête -->
                            <div class="notes-category mb-4">
                                <h5 class="notes-category-title">
                                    <i class="fas fa-cloud text-primary"></i>
                                    Notes de Tête
                                    <small class="text-muted">(Première impression, légères et volatiles)</small>
                                </h5>
                                <div class="notes-container">
                                    <div id="head-notes-list">
                                        @foreach($headNotes as $note)
                                            <div class="note-input">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="head_notes[]" 
                                                           value="{{ htmlspecialchars($note, ENT_QUOTES, 'UTF-8') }}" 
                                                           placeholder="ex: Bergamote, Citron, Menthe"
                                                           onchange="updatePyramidPreview()">
                                                    <button type="button" class="btn remove-btn" onclick="removeNote(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn add-btn" onclick="addNote('head')">
                                        <i class="fas fa-plus me-2"></i>Ajouter une note de tête
                                    </button>
                                </div>
                            </div>

                            <!-- Notes de Cœur -->
                            <div class="notes-category mb-4">
                                <h5 class="notes-category-title">
                                    <i class="fas fa-heart text-danger"></i>
                                    Notes de Cœur
                                    <small class="text-muted">(Personnalité du parfum, florales ou fruitées)</small>
                                </h5>
                                <div class="notes-container">
                                    <div id="heart-notes-list">
                                        @foreach($heartNotes as $note)
                                            <div class="note-input">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="heart_notes[]" 
                                                           value="{{ htmlspecialchars($note, ENT_QUOTES, 'UTF-8') }}" 
                                                           placeholder="ex: Rose, Jasmin, Pivoine"
                                                           onchange="updatePyramidPreview()">
                                                    <button type="button" class="btn remove-btn" onclick="removeNote(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn add-btn" onclick="addNote('heart')">
                                        <i class="fas fa-plus me-2"></i>Ajouter une note de cœur
                                    </button>
                                </div>
                            </div>

                            <!-- Notes de Fond -->
                            <div class="notes-category mb-4">
                                <h5 class="notes-category-title">
                                    <i class="fas fa-tree text-warning"></i>
                                    Notes de Fond
                                    <small class="text-muted">(Sillage persistant, boisées ou orientales)</small>
                                </h5>
                                <div class="notes-container">
                                    <div id="base-notes-list">
                                        @foreach($baseNotes as $note)
                                            <div class="note-input">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="base_notes[]" 
                                                           value="{{ htmlspecialchars($note, ENT_QUOTES, 'UTF-8') }}" 
                                                           placeholder="ex: Bois de Santal, Musc, Vanille"
                                                           onchange="updatePyramidPreview()">
                                                    <button type="button" class="btn remove-btn" onclick="removeNote(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn add-btn" onclick="addNote('base')">
                                        <i class="fas fa-plus me-2"></i>Ajouter une note de fond
                                    </button>
                                </div>
                            </div>

                            <!-- Aperçu de la pyramide -->
                            <div class="pyramid-preview">
                                <h6>Aperçu de la Pyramide Olfactive</h6>
                                <div class="preview-pyramid">
                                    <div class="preview-layer head-preview">
                                        <span class="layer-title">Tête</span>
                                        <div class="layer-notes" id="head-preview"></div>
                                    </div>
                                    <div class="preview-layer heart-preview">
                                        <span class="layer-title">Cœur</span>
                                        <div class="layer-notes" id="heart-preview"></div>
                                    </div>
                                    <div class="preview-layer base-preview">
                                        <span class="layer-title">Fond</span>
                                        <div class="layer-notes" id="base-preview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="form-section">
                            <h3 class="section-title">Images</h3>
                            
                            <div class="images-container">
                                <div id="images-list">
                                    @php
                                        $images = old('images', $product->images ?: []);
                                        if (empty($images)) $images = [''];
                                        // S'assurer que chaque image est une string
                                        $images = array_map(function($image) {
                                            return is_string($image) ? $image : '';
                                        }, $images);
                                    @endphp
                                    @foreach($images as $index => $image)
                                        <div class="image-input">
                                            <div class="input-group">
                                                <input type="url" class="form-control" name="images[]" 
                                                       value="{{ htmlspecialchars($image, ENT_QUOTES, 'UTF-8') }}" placeholder="https://example.com/image.jpg">
                                                <button type="button" class="btn remove-btn" onclick="removeImage(this)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn add-btn" onclick="addImage()">
                                    <i class="fas fa-plus me-2"></i>Ajouter une image
                                </button>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="form-section">
                            <h3 class="section-title">Options</h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" 
                                               name="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Produit actif
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_featured" 
                                               name="is_featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Produit en vedette
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-guerlain me-3">
                                <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                            </button>
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary me-2">
                                <i class="fas fa-eye me-2"></i>Voir le Produit
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Aperçu du produit -->
            <div class="col-lg-4">
                <div class="product-preview">
                    <h5 class="mb-3">Aperçu du Produit</h5>
                    
                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="preview-image">
                    
                    <div class="mb-3">
                        <h6>{{ $product->name }}</h6>
                        <span class="badge bg-secondary">{{ $product->category_label }}</span>
                        @if($product->badge)
                            <span class="badge bg-info ms-1">{{ $product->badge }}</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>{{ $product->formatted_price }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">{{ $product->type }} - {{ $product->size }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <small>Stock: 
                            @if($product->stock <= 0)
                                <span class="text-danger">Rupture</span>
                            @elseif($product->stock <= 5)
                                <span class="text-warning">{{ $product->stock }}</span>
                            @else
                                <span class="text-success">{{ $product->stock }}</span>
                            @endif
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                            {{ $product->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                        @if($product->is_featured)
                            <span class="badge bg-warning ms-1">En vedette</span>
                        @endif
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="action-buttons">
                    <h6 class="mb-3">Actions Rapides</h6>
                    
                    <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-{{ $product->is_active ? 'warning' : 'success' }} w-100">
                            <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }} me-2"></i>
                            {{ $product->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-info w-100">
                            <i class="fas fa-star me-2"></i>
                            {{ $product->is_featured ? 'Retirer vedette' : 'Mettre en vedette' }}
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.products.duplicate', $product) }}" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-copy me-2"></i>Dupliquer
                        </button>
                    </form>
                    
                    <hr>
                    
                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary w-100 mb-2" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Données des catégories et types
const parfumCategories = {!! json_encode($parfumCategories) !!};
const cosmetiqueCategories = {!! json_encode($cosmetiqueCategories) !!};
const parfumTypes = {!! json_encode($parfumTypes) !!};
const cosmetiqueTypes = {!! json_encode($cosmetiqueTypes) !!};

// Fonction pour mettre à jour les catégories et types
function updateCategoriesAndTypes() {
    const productType = document.getElementById('product_type').value;
    const categorySelect = document.getElementById('category');
    const typeSelect = document.getElementById('type');
    
    // Réinitialiser les selects
    categorySelect.innerHTML = '<option value="">Sélectionner une catégorie</option>';
    typeSelect.innerHTML = '<option value="">Sélectionner un type</option>';
    
    if (productType === 'parfum') {
        // Ajouter les catégories de parfum
        Object.entries(parfumCategories).forEach(([key, label]) => {
            const option = new Option(label, key);
            categorySelect.add(option);
        });
        
        // Ajouter les types de parfum
        Object.entries(parfumTypes).forEach(([key, label]) => {
            const option = new Option(label, key);
            typeSelect.add(option);
        });
        
        // Mettre à jour le placeholder de la taille
        document.getElementById('size').placeholder = 'ex: 50ml, 100ml, 125ml';
        
    } else if (productType === 'cosmetique') {
        // Ajouter les catégories de cosmétique
        Object.entries(cosmetiqueCategories).forEach(([key, label]) => {
            const option = new Option(label, key);
            categorySelect.add(option);
        });
        
        // Ajouter les types de cosmétique
        Object.entries(cosmetiqueTypes).forEach(([key, label]) => {
            const option = new Option(label, key);
            typeSelect.add(option);
        });
        
        // Mettre à jour le placeholder de la taille
        document.getElementById('size').placeholder = 'ex: 30ml, 50ml, 100g, 250ml';
    }
    
    // Restaurer les valeurs actuelles du produit
    const currentCategory = '{{ old("category", $product->category) }}';
    const currentType = '{{ old("type", $product->type) }}';
    
    setTimeout(() => {
        if (currentCategory) {
            categorySelect.value = currentCategory;
        }
        if (currentType) {
            typeSelect.value = currentType;
        }
    }, 100);
}

function addNote(category = null) {
    let notesList, placeholder;
    
    if (category) {
        notesList = document.getElementById(`${category}-notes-list`);
        const placeholders = {
            'head': 'ex: Bergamote, Citron, Menthe',
            'heart': 'ex: Rose, Jasmin, Pivoine',
            'base': 'ex: Bois de Santal, Musc, Vanille'
        };
        placeholder = placeholders[category];
    } else {
        notesList = document.getElementById('notes-list');
        placeholder = 'ex: Rose, Jasmin, Bois de santal';
    }
    
    const newNote = document.createElement('div');
    newNote.className = 'note-input';
    newNote.innerHTML = `
        <div class="input-group">
            <input type="text" class="form-control" name="${category ? category + '_notes[]' : 'notes[]'}" 
                   placeholder="${placeholder}" onchange="updatePyramidPreview()">
            <button type="button" class="btn remove-btn" onclick="removeNote(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    notesList.appendChild(newNote);
}

function removeNote(button) {
    const notesList = button.closest('[id$="-notes-list"], #notes-list');
    if (notesList.children.length > 1) {
        button.closest('.note-input').remove();
        updatePyramidPreview();
    }
}

function updatePyramidPreview() {
    const categories = ['head', 'heart', 'base'];
    
    categories.forEach(category => {
        const inputs = document.querySelectorAll(`input[name="${category}_notes[]"]`);
        const preview = document.getElementById(`${category}-preview`);
        
        if (preview) {
            const notes = Array.from(inputs)
                .map(input => input.value.trim())
                .filter(note => note !== '');
            
            preview.innerHTML = notes.length > 0 
                ? notes.map(note => `<span class="preview-note">${note}</span>`).join('')
                : '<span class="no-notes">Aucune note</span>';
        }
    });
}

// Initialiser l'aperçu au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les catégories/types si un type de produit est déjà sélectionné
    const productTypeValue = document.getElementById('product_type').value;
    if (productTypeValue) {
        updateCategoriesAndTypes();
    }
    
    // Mettre à jour l'aperçu de la pyramide
    updatePyramidPreview();
    
    // Ajouter des event listeners pour les notes existantes
    document.querySelectorAll('input[name$="_notes[]"]').forEach(input => {
        input.addEventListener('input', updatePyramidPreview);
    });
});

function addImage() {
    const imagesList = document.getElementById('images-list');
    const newImage = document.createElement('div');
    newImage.className = 'image-input';
    newImage.innerHTML = `
        <div class="input-group">
            <input type="url" class="form-control" name="images[]" 
                   placeholder="https://example.com/image.jpg">
            <button type="button" class="btn remove-btn" onclick="removeImage(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    imagesList.appendChild(newImage);
}

function removeImage(button) {
    const imagesList = document.getElementById('images-list');
    if (imagesList.children.length > 1) {
        button.closest('.image-input').remove();
    }
}

// Fonctions pour les promotions
function togglePromotionFields() {
    const isOnPromotion = document.getElementById('is_on_promotion').checked;
    const promotionFields = document.getElementById('promotion-fields');
    
    if (isOnPromotion) {
        promotionFields.style.display = 'block';
        updatePromotionPreview();
    } else {
        promotionFields.style.display = 'none';
        document.getElementById('promotion-preview').style.display = 'none';
    }
}

function updatePromotionPreview() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const promotionPrice = parseFloat(document.getElementById('promotion_price').value) || 0;
    const promotionDescription = document.getElementById('promotion_description').value;
    const isOnPromotion = document.getElementById('is_on_promotion').checked;
    
    const preview = document.getElementById('promotion-preview');
    const originalPreview = document.getElementById('preview-original');
    const promotionPreview = document.getElementById('preview-promotion');
    const discountPreview = document.getElementById('preview-discount');
    const descriptionPreview = document.getElementById('preview-description');
    
    if (isOnPromotion && price > 0 && promotionPrice > 0 && promotionPrice < price) {
        preview.style.display = 'block';
        
        // Mettre à jour les prix
        originalPreview.textContent = price.toFixed(2) + ' €';
        promotionPreview.textContent = promotionPrice.toFixed(2) + ' €';
        
        // Calculer le pourcentage de réduction
        const discountPercent = Math.round(((price - promotionPrice) / price) * 100);
        discountPreview.textContent = '-' + discountPercent + '%';
        
        // Afficher la description
        descriptionPreview.textContent = promotionDescription || '';
    } else {
        preview.style.display = 'none';
    }
}

// Ajouter des event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners existants...
    updateCategoriesAndTypes();
    updatePyramidPreview();
    
    // Event listeners pour les promotions
    document.getElementById('price').addEventListener('input', updatePromotionPreview);
    document.getElementById('promotion_price').addEventListener('input', updatePromotionPreview);
    document.getElementById('promotion_description').addEventListener('input', updatePromotionPreview);
    
    // Initialiser l'état des champs de promotion
    togglePromotionFields();
});
</script>
@endsection
