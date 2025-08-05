@extends('layouts.app')

@section('title', 'Connexion Administration - Heritage Parfums')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <!-- Logo et titre -->
                        <div class="text-center mb-4">
                            <h2 class="font-serif text-guerlain-gold mb-2">Administration</h2>
                            <p class="text-guerlain-gray">Heritage Parfums</p>
                        </div>

                        <!-- Messages d'alerte -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->has('credentials'))
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-times-circle me-2"></i>{{ $errors->first('credentials') }}
                            </div>
                        @endif

                        <!-- Formulaire de connexion -->
                        <form method="POST" action="{{ route('admin.login.post') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Nom d'utilisateur</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('username') is-invalid @enderror" 
                                           id="username" 
                                           name="username" 
                                           value="{{ old('username') }}"
                                           placeholder="marouanehirch"
                                           required 
                                           autofocus>
                                </div>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password"
                                           placeholder="••••••••"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="passwordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                </button>
                            </div>
                        </form>

                        <!-- Informations de connexion pour la démo -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-info-circle me-2"></i>Informations de connexion
                            </h6>
                            <small class="text-muted">
                                <strong>Utilisateur :</strong> marouanehirch<br>
                                <strong>Mot de passe :</strong> test123
                            </small>
                        </div>

                        <!-- Lien retour -->
                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la boutique
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.getElementById('passwordIcon');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        password.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Auto-focus sur le champ nom d'utilisateur
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('username').focus();
});
</script>

<style>
.text-guerlain-gold {
    color: #d4af37;
}

.text-guerlain-gray {
    color: #666666;
}

.font-serif {
    font-family: 'Cormorant Garamond', serif;
}

.btn-primary {
    background-color: #0d0d0d;
    border-color: #0d0d0d;
}

.btn-primary:hover {
    background-color: #d4af37;
    border-color: #d4af37;
}

.form-control:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
}
</style>
@endsection
