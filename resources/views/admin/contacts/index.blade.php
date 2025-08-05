@extends('layouts.app')

@section('title', 'Gestion des Messages - Administration')

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

    .stat-card {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 1.5rem;
        text-align: center;
        position: relative;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .stat-card.unread::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #dc3545;
    }

    .stat-card.read::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #17a2b8;
    }

    .stat-card.replied::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #28a745;
    }

    .stat-number {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 300;
        color: var(--guerlain-black);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        color: var(--guerlain-text-gray);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }

    .filter-card {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .table-responsive {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
    }

    .table th {
        background: var(--guerlain-light-gray);
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        color: var(--guerlain-text-gray);
        padding: 1rem;
    }

    .table td {
        border: none;
        border-bottom: 1px solid var(--guerlain-border);
        padding: 1rem;
        vertical-align: middle;
    }

    .message-row.unread {
        background: rgba(255, 193, 7, 0.1);
        font-weight: 500;
    }

    .message-preview {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn-guerlain {
        background: var(--guerlain-gold);
        border: 1px solid var(--guerlain-gold);
        color: var(--guerlain-white);
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0.75rem 2rem;
        transition: all 0.3s ease;
    }

    .btn-guerlain:hover {
        background: var(--guerlain-black);
        border-color: var(--guerlain-black);
        color: var(--guerlain-white);
    }

    .bulk-actions {
        background: var(--guerlain-white);
        border: 1px solid var(--guerlain-border);
        padding: 1rem;
        margin-bottom: 1rem;
        display: none;
    }

    .bulk-actions.show {
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
                <h1>Gestion des Messages</h1>
                <p class="lead mb-0">Messages via le formulaire de contact</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.contacts.export', request()->query()) }}" class="btn btn-guerlain">
                    <i class="fas fa-download me-2"></i>Exporter CSV
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Admin Content -->
<section class="admin-content">
    <div class="container-fluid">
        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card unread">
                    <div class="stat-number text-danger">{{ $stats['unread'] }}</div>
                    <div class="stat-label">Non lus</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card read">
                    <div class="stat-number text-info">{{ $stats['read'] }}</div>
                    <div class="stat-label">Lus</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card replied">
                    <div class="stat-number text-success">{{ $stats['replied'] }}</div>
                    <div class="stat-label">Répondus</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number text-warning">{{ $stats['today'] }}</div>
                    <div class="stat-label">Aujourd'hui</div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.contacts.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nom, email, sujet...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Non lus</option>
                            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Lus</option>
                            <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Répondus</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_from" class="form-label">Date début</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_to" class="form-label">Date fin</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1 mb-3">
                        <label for="order" class="form-label">Ordre</label>
                        <select class="form-select" id="order" name="order">
                            <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>↓</option>
                            <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>↑</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-guerlain w-100">
                            <i class="fas fa-search me-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions en lot -->
        <div class="bulk-actions" id="bulkActions">
            <form method="POST" action="{{ route('admin.contacts.bulk-action') }}" id="bulkForm">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <span id="selectedCount">0</span> message(s) sélectionné(s)
                    </div>
                    <div class="col-md-6 text-end">
                        <select name="action" class="form-select d-inline-block w-auto me-2">
                            <option value="">Choisir une action</option>
                            <option value="mark_read">Marquer comme lu</option>
                            <option value="mark_replied">Marquer comme répondu</option>
                            <option value="delete">Supprimer</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Appliquer</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">Annuler</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des messages -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th>Statut</th>
                        <th>Expéditeur</th>
                        <th>Sujet</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr class="message-row {{ $contact->status }}">
                            <td>
                                <input type="checkbox" name="selected_contacts[]" value="{{ $contact->id }}" 
                                       class="contact-checkbox" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <span class="badge bg-{{ $contact->status_color }}">
                                    {{ $contact->status_label }}
                                </span>
                                @if(!$contact->is_read)
                                    <i class="fas fa-circle text-danger ms-1" style="font-size: 0.5rem;" title="Non lu"></i>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $contact->name }}</strong><br>
                                <small class="text-muted">{{ $contact->email }}</small>
                                @if($contact->phone)
                                    <br><small class="text-muted">{{ $contact->phone }}</small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $contact->subject }}</strong>
                            </td>
                            <td>
                                <div class="message-preview">
                                    {{ Str::limit($contact->message, 100) }}
                                </div>
                            </td>
                            <td>
                                <small>{{ $contact->created_at->format('d/m/Y H:i') }}</small>
                                @if($contact->replied_at)
                                    <br><small class="text-success">
                                        <i class="fas fa-reply me-1"></i>{{ $contact->replied_at->format('d/m/Y H:i') }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" 
                                       class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$contact->is_read)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-warning"
                                                onclick="markAsRead({{ $contact->id }})"
                                                title="Marquer comme lu">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if(!$contact->replied_at)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success"
                                                onclick="markAsReplied({{ $contact->id }})"
                                                title="Marquer comme répondu">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                    @endif
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteContact({{ $contact->id }})"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucun message trouvé</h5>
                                <p class="text-muted">Les messages du formulaire de contact apparaîtront ici.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($contacts->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Formulaires cachés pour les actions -->
<form id="markAsReadForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="markAsRepliedForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
let selectedContacts = [];

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.contact-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedContacts = Array.from(checkboxes).map(cb => cb.value);
    selectedCount.textContent = selectedContacts.length;
    
    if (selectedContacts.length > 0) {
        bulkActions.classList.add('show');
        
        // Ajouter les contacts sélectionnés au formulaire
        const bulkForm = document.getElementById('bulkForm');
        
        // Supprimer les anciens inputs cachés
        bulkForm.querySelectorAll('input[name="contacts[]"]').forEach(input => input.remove());
        
        // Ajouter les nouveaux
        selectedContacts.forEach(contactId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'contacts[]';
            input.value = contactId;
            bulkForm.appendChild(input);
        });
    } else {
        bulkActions.classList.remove('show');
    }
}

function clearSelection() {
    document.getElementById('selectAll').checked = false;
    document.querySelectorAll('.contact-checkbox').forEach(cb => cb.checked = false);
    updateBulkActions();
}

function markAsRead(contactId) {
    if (confirm('Marquer ce message comme lu ?')) {
        const form = document.getElementById('markAsReadForm');
        form.action = `/admin/contacts/${contactId}/mark-as-read`;
        form.submit();
    }
}

function markAsReplied(contactId) {
    const notes = prompt('Notes administrateur (optionnel):');
    if (notes !== null) {
        const form = document.getElementById('markAsRepliedForm');
        form.action = `/admin/contacts/${contactId}/mark-as-replied`;
        
        // Ajouter les notes
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'admin_notes';
        notesInput.value = notes;
        form.appendChild(notesInput);
        
        form.submit();
    }
}

function deleteContact(contactId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce message ? Cette action est irréversible.')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/contacts/${contactId}`;
        form.submit();
    }
}

// Validation du formulaire en lot
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    const action = this.querySelector('select[name="action"]').value;
    if (!action) {
        e.preventDefault();
        alert('Veuillez choisir une action.');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selectedContacts.length} message(s) ? Cette action est irréversible.`)) {
            e.preventDefault();
        }
    }
});
</script>
@endsection
