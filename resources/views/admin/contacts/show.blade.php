@extends('layouts.app')

@section('title', 'Message de ' . $contact->name . ' - Administration')

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

    .message-card {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 3rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .message-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--guerlain-gold);
    }

    .message-header {
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--guerlain-border);
        margin-bottom: 2rem;
    }

    .sender-info {
        background: var(--guerlain-light-gray);
        padding: 1.5rem;
        border-radius: 0;
        border: 1px solid var(--guerlain-border);
        margin-bottom: 2rem;
    }

    .sender-avatar {
        width: 60px;
        height: 60px;
        background: var(--guerlain-gold);
        color: var(--guerlain-white);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 1rem;
        border: 3px solid var(--guerlain-white);
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
    }

    .sender-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 400;
        color: var(--guerlain-black);
        margin-bottom: 0.25rem;
    }

    .sender-email {
        color: var(--guerlain-text-gray);
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
    }

    .message-subject {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        font-weight: 400;
        color: var(--guerlain-black);
        margin-bottom: 1rem;
    }

    .message-meta {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: var(--guerlain-text-gray);
        font-size: 0.9rem;
    }

    .message-content {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: var(--guerlain-black);
        line-height: 1.8;
        font-size: 1.1rem;
        padding: 2rem;
        background: var(--guerlain-light-gray);
        border: 1px solid var(--guerlain-border);
        white-space: pre-wrap;
    }

    .status-badge {
        position: absolute;
        top: 2rem;
        right: 2rem;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .admin-notes {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        margin-top: 2rem;
    }

    .notes-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 400;
        color: var(--guerlain-black);
        margin-bottom: 1rem;
    }

    .btn-guerlain {
        background: var(--guerlain-gold);
        border: 1px solid var(--guerlain-gold);
        color: var(--guerlain-white);
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1rem 2rem;
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
        padding: 1rem 2rem;
    }

    .action-buttons {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        text-align: center;
    }

    .btn-outline-success:hover {
        background: #28a745;
        border-color: #28a745;
    }

    .btn-outline-warning:hover {
        background: #ffc107;
        border-color: #ffc107;
        color: var(--guerlain-black);
    }

    .reply-form {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        margin-top: 2rem;
        display: none;
    }

    .reply-form.show {
        display: block;
    }
</style>
@endpush

@section('content')
<!-- Admin Header -->
<section class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Message de Contact</h1>
                <p class="lead mb-0">Détails du message</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
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
                <!-- Message principal -->
                <div class="message-card">
                    <span class="badge bg-{{ $contact->status_color }} status-badge">
                        {{ $contact->status_label }}
                    </span>

                    <div class="message-header">
                        <div class="sender-info">
                            <div class="d-flex align-items-center">
                                <div class="sender-avatar">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="sender-name">{{ $contact->name }}</h4>
                                    <div class="sender-email">
                                        <i class="fas fa-envelope me-2"></i>{{ $contact->email }}
                                    </div>
                                    @if($contact->phone)
                                        <div class="sender-email">
                                            <i class="fas fa-phone me-2"></i>{{ $contact->phone }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <h2 class="message-subject">{{ $contact->subject }}</h2>
                        
                        <div class="message-meta">
                            <i class="fas fa-clock me-2"></i>
                            Reçu le {{ $contact->created_at->format('d/m/Y à H:i') }}
                            ({{ $contact->created_at->diffForHumans() }})
                            
                            @if($contact->replied_at)
                                <br><i class="fas fa-reply me-2 text-success"></i>
                                Répondu le {{ $contact->replied_at->format('d/m/Y à H:i') }}
                            @endif
                        </div>
                    </div>

                    <div class="message-content">{{ $contact->message }}</div>
                </div>

                <!-- Notes administrateur -->
                @if($contact->admin_notes)
                    <div class="admin-notes">
                        <h3 class="notes-title">
                            <i class="fas fa-sticky-note me-2 text-warning"></i>
                            Notes Administrateur
                        </h3>
                        <div class="notes-content">{{ $contact->admin_notes }}</div>
                    </div>
                @endif

                <!-- Formulaire de réponse -->
                <div class="reply-form" id="replyForm">
                    <h3 class="notes-title">
                        <i class="fas fa-reply me-2"></i>
                        Marquer comme répondu
                    </h3>
                    <form method="POST" action="{{ route('admin.contacts.mark-as-replied', $contact) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Notes administrateur (optionnel)</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" 
                                    placeholder="Ajoutez des notes sur votre réponse ou des informations importantes...">{{ old('admin_notes', $contact->admin_notes) }}</textarea>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="hideReplyForm()">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Marquer comme répondu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Actions latérales -->
            <div class="col-lg-4">
                <div class="action-buttons">
                    <h5 class="mb-4">Actions Rapides</h5>
                    
                    @if(!$contact->is_read)
                        <form method="POST" action="{{ route('admin.contacts.mark-as-read', $contact) }}" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-warning w-100">
                                <i class="fas fa-eye me-2"></i>Marquer comme lu
                            </button>
                        </form>
                    @endif

                    @if(!$contact->replied_at)
                        <button type="button" class="btn btn-outline-success w-100 mb-3" onclick="showReplyForm()">
                            <i class="fas fa-reply me-2"></i>Marquer comme répondu
                        </button>
                    @endif

                    <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" 
                       class="btn btn-outline-info w-100 mb-3">
                        <i class="fas fa-envelope me-2"></i>Répondre par email
                    </a>

                    @if($contact->phone)
                        <a href="tel:{{ $contact->phone }}" class="btn btn-outline-secondary w-100 mb-3">
                            <i class="fas fa-phone me-2"></i>Appeler
                        </a>
                    @endif

                    <hr class="my-4">

                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer le message
                        </button>
                    </form>
                </div>

                <!-- Informations supplémentaires -->
                <div class="action-buttons mt-4">
                    <h5 class="mb-4">Informations</h5>
                    
                    <div class="text-start">
                        <div class="mb-3">
                            <strong>ID:</strong> #{{ $contact->id }}
                        </div>
                        
                        <div class="mb-3">
                            <strong>Statut:</strong>
                            <span class="badge bg-{{ $contact->status_color }} ms-2">
                                {{ $contact->status_label }}
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Date de création:</strong><br>
                            <small>{{ $contact->created_at->format('d/m/Y H:i:s') }}</small>
                        </div>
                        
                        @if($contact->replied_at)
                            <div class="mb-3">
                                <strong>Date de réponse:</strong><br>
                                <small>{{ $contact->replied_at->format('d/m/Y H:i:s') }}</small>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <strong>Longueur du message:</strong><br>
                            <small>{{ strlen($contact->message) }} caractères</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function showReplyForm() {
    document.getElementById('replyForm').classList.add('show');
    document.getElementById('admin_notes').focus();
}

function hideReplyForm() {
    document.getElementById('replyForm').classList.remove('show');
}

// Auto-scroll vers le formulaire si il y a des erreurs
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        showReplyForm();
        document.getElementById('replyForm').scrollIntoView({ behavior: 'smooth' });
    });
@endif
</script>
@endsection
