@extends('layouts.app')

@section('title', 'Créer un Produit - Administration')

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

    .notes-container, .images-container {
        border: 1px solid var(--guerlain-border);
        padding: 1.5rem;
        background: var(--guerlain-light-gray);
        margin-top: 1rem;
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
</style>
@endpush

@section('content')
<!-- Admin Header -->
<section class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Créer un Produit</h1>
                <p class="lead mb-0">Ajouter un nouveau parfum à la collection</p>
            </div>
            <div class="col-md-6 text-md-end">
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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-card">
                <!-- Informations de base -->
                <div class="form-section">
                    <h3 class="section-title">Informations de Base</h3>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Nom du Produit *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Prix (€) *</label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label">Catégorie *</label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
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
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="size" class="form-label">Taille *</label>
                            <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                   id="size" name="size" value="{{ old('size') }}" 
                                   placeholder="ex: 50ml, 100ml" required>
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stock *</label>
                            <input type="number" min="0" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="badge" class="form-label">Badge (optionnel)</label>
                            <input type="text" class="form-control @error('badge') is-invalid @enderror" 
                                   id="badge" name="badge" value="{{ old('badge') }}" 
                                   placeholder="ex: Nouveau, Édition limitée">
                            @error('badge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                  placeholder="Description courte pour les listes de produits">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description Complète *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="6" 
                                  placeholder="Description détaillée du parfum" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Notes olfactives -->
                <div class="form-section">
                    <h3 class="section-title">Notes Olfactives</h3>
                    
                    <div class="notes-container">
                        <div id="notes-list">
                            @if(old('notes'))
                                @foreach(old('notes') as $index => $note)
                                    <div class="note-input">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="notes[]" 
                                                   value="{{ htmlspecialchars($note, ENT_QUOTES, 'UTF-8') }}" placeholder="ex: Rose, Jasmin, Bois de santal">
                                            <button type="button" class="btn remove-btn" onclick="removeNote(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="note-input">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="notes[]" 
                                               placeholder="ex: Rose, Jasmin, Bois de santal">
                                        <button type="button" class="btn remove-btn" onclick="removeNote(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn add-btn" onclick="addNote()">
                            <i class="fas fa-plus me-2"></i>Ajouter une note
                        </button>
                    </div>
                </div>

                <!-- Images -->
                <div class="form-section">
                    <h3 class="section-title">Images</h3>
                    
                    <div class="images-container">
                        <div id="images-list">
                            @if(old('images'))
                                @foreach(old('images') as $index => $image)
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
                            @else
                                <div class="image-input">
                                    <div class="input-group">
                                        <input type="url" class="form-control" name="images[]" 
                                               placeholder="https://example.com/image.jpg">
                                        <button type="button" class="btn remove-btn" onclick="removeImage(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
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
                                       name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Produit actif
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" 
                                       name="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
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
                        <i class="fas fa-save me-2"></i>Créer le Produit
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function addNote() {
    const notesList = document.getElementById('notes-list');
    const newNote = document.createElement('div');
    newNote.className = 'note-input';
    newNote.innerHTML = `
        <div class="input-group">
            <input type="text" class="form-control" name="notes[]" 
                   placeholder="ex: Rose, Jasmin, Bois de santal">
            <button type="button" class="btn remove-btn" onclick="removeNote(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    notesList.appendChild(newNote);
}

function removeNote(button) {
    const notesList = document.getElementById('notes-list');
    if (notesList.children.length > 1) {
        button.closest('.note-input').remove();
    }
}

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
</script>
@endsection
