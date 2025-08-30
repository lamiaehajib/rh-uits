<x-app-layout>
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Modifier le Projet</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                   
                    <li class="breadcrumb-item"><a href="{{ route('admin.projets.index') }}">Projets</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.projets.show', $projet) }}">{{ Str::limit($projet->titre, 20) }}</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.projets.show', $projet) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6><i class="fas fa-exclamation-triangle"></i> Erreurs de validation :</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulaire de modification -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Informations du Projet</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.projets.update', $projet) }}" method="POST" enctype="multipart/form-data" id="projetForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Titre -->
                            <div class="col-md-12 mb-3">
                                <label for="titre" class="form-label">
                                    <i class="fas fa-heading text-primary"></i> Titre du projet <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('titre') is-invalid @enderror" 
                                       id="titre" 
                                       name="titre" 
                                       value="{{ old('titre', $projet->titre) }}" 
                                       placeholder="Ex: Création site web e-commerce"
                                       required>
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Client -->
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">
                                    <i class="fas fa-user text-primary"></i> Client <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" 
                                        name="user_id" 
                                        required>
                                    <option value="">Sélectionner un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" 
                                                {{ old('user_id', $projet->user_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }} ({{ $client->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div class="col-md-6 mb-3">
                                <label for="statut_projet" class="form-label">
                                    <i class="fas fa-flag text-primary"></i> Statut <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('statut_projet') is-invalid @enderror" 
                                        id="statut_projet" 
                                        name="statut_projet" 
                                        required>
                                    <option value="en attente" {{ old('statut_projet', $projet->statut_projet) == 'en attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="en cours" {{ old('statut_projet', $projet->statut_projet) == 'en cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="terminé" {{ old('statut_projet', $projet->statut_projet) == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                    <option value="annulé" {{ old('statut_projet', $projet->statut_projet) == 'annulé' ? 'selected' : '' }}>Annulé</option>
                                </select>
                                @error('statut_projet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de début -->
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">
                                    <i class="fas fa-calendar-plus text-primary"></i> Date de début <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('date_debut') is-invalid @enderror" 
                                       id="date_debut" 
                                       name="date_debut" 
                                       value="{{ old('date_debut', $projet->date_debut) }}" 
                                       required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de fin -->
                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">
                                    <i class="fas fa-calendar-check text-primary"></i> Date de fin (optionnelle)
                                </label>
                                <input type="date" 
                                       class="form-control @error('date_fin') is-invalid @enderror" 
                                       id="date_fin" 
                                       name="date_fin" 
                                       value="{{ old('date_fin', $projet->date_fin) }}">
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">La date de fin doit être postérieure à la date de début</div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left text-primary"></i> Description
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Description détaillée du projet, objectifs, contraintes particulières...">{{ old('description', $projet->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="charCount">{{ strlen($projet->description ?? '') }}</span> caractères
                                </div>
                            </div>

                            <!-- Fichier -->
                            <div class="col-md-12 mb-3">
                                <label for="fichier" class="form-label">
                                    <i class="fas fa-paperclip text-primary"></i> Fichier joint
                                </label>
                                
                                @if($projet->fichier)
                                    <div class="mb-2">
                                        <div class="alert alert-info d-flex align-items-center">
                                            <i class="fas fa-file me-2"></i>
                                            <div class="flex-grow-1">
                                                <strong>Fichier actuel :</strong> 
                                                <a href="{{ Storage::url($projet->fichier) }}" target="_blank" class="text-decoration-none">
                                                    {{ basename($projet->fichier) }}
                                                </a>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="supprimerFichier()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                
                                <input type="file" 
                                       class="form-control @error('fichier') is-invalid @enderror" 
                                       id="fichier" 
                                       name="fichier"
                                       accept=".pdf,.doc,.docx,.jpg,.png">
                                @error('fichier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Formats acceptés : PDF, DOC, DOCX, JPG, PNG. Taille maximale : 5 MB
                                    @if($projet->fichier)
                                        <br><em>Sélectionner un nouveau fichier remplacera l'actuel</em>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Boutons de validation -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.projets.show', $projet) }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Annuler
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary" onclick="previewChanges()">
                                            <i class="fas fa-eye"></i> Aperçu
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Enregistrer les modifications
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar avec informations -->
        <div class="col-lg-4">
            <!-- Aide -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-question-circle text-info"></i> Aide</h6>
                </div>
                <div class="card-body">
                    <h6>Conseils pour la modification :</h6>
                    <ul class="small mb-0">
                        <li>Vérifiez que les dates sont cohérentes</li>
                        <li>Le statut influence l'affichage du projet</li>
                        <li>La description aide à comprendre le projet</li>
                        <li>Un fichier joint peut contenir le cahier des charges</li>
                    </ul>
                </div>
            </div>

            <!-- Informations actuelles -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle text-info"></i> Informations actuelles</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="row mb-2">
                            <div class="col-5"><strong>Créé le :</strong></div>
                            <div class="col-7">{{ $projet->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5"><strong>Modifié le :</strong></div>
                            <div class="col-7">{{ $projet->updated_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5"><strong>ID Projet :</strong></div>
                            <div class="col-7">#{{ $projet->id }}</div>
                        </div>
                        @if($projet->avancements->count() > 0)
                            <div class="row">
                                <div class="col-5"><strong>Avancement :</strong></div>
                                <div class="col-7">{{ number_format($projet->avancements->avg('pourcentage') ?? 0, 1) }}%</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Historique des modifications -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-history text-warning"></i> Attention</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <small>
                            <i class="fas fa-exclamation-triangle"></i>
                            Les modifications seront appliquées immédiatement et le client sera potentiellement notifié selon la configuration du système.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aperçu -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aperçu des modifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Le contenu sera généré dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('projetForm').submit()">
                    <i class="fas fa-save"></i> Confirmer et enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Compteur de caractères pour la description
document.getElementById('description').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

// Validation des dates
document.getElementById('date_debut').addEventListener('change', validateDates);
document.getElementById('date_fin').addEventListener('change', validateDates);

function validateDates() {
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;
    
    if (dateDebut && dateFin && dateDebut >= dateFin) {
        document.getElementById('date_fin').setCustomValidity('La date de fin doit être postérieure à la date de début');
    } else {
        document.getElementById('date_fin').setCustomValidity('');
    }
}

// Aperçu des modifications
function previewChanges() {
    const form = document.getElementById('projetForm');
    const formData = new FormData(form);
    
    let previewHTML = '<div class="row">';
    
    // Titre
    previewHTML += `
        <div class="col-12 mb-3">
            <div class="border-start border-primary border-3 ps-3">
                <h5 class="text-primary mb-1">${formData.get('titre')}</h5>
                <small class="text-muted">Titre du projet</small>
            </div>
        </div>
    `;
    
    // Client
    const clientSelect = document.getElementById('user_id');
    const clientText = clientSelect.options[clientSelect.selectedIndex].text;
    previewHTML += `
        <div class="col-md-6 mb-3">
            <div class="border-start border-info border-3 ps-3">
                <strong>${clientText}</strong>
                <br><small class="text-muted">Client assigné</small>
            </div>
        </div>
    `;
    
    // Statut
    const statutSelect = document.getElementById('statut_projet');
    const statutText = statutSelect.options[statutSelect.selectedIndex].text;
    const statutClass = getStatutClass(formData.get('statut_projet'));
    previewHTML += `
        <div class="col-md-6 mb-3">
            <div class="border-start border-${statutClass} border-3 ps-3">
                <span class="badge bg-${statutClass}">${statutText}</span>
                <br><small class="text-muted">Statut du projet</small>
            </div>
        </div>
    `;
    
    // Dates
    previewHTML += `
        <div class="col-md-6 mb-3">
            <div class="border-start border-success border-3 ps-3">
                <strong>${formatDate(formData.get('date_debut'))}</strong>
                <br><small class="text-muted">Date de début</small>
            </div>
        </div>
    `;
    
    if (formData.get('date_fin')) {
        previewHTML += `
            <div class="col-md-6 mb-3">
                <div class="border-start border-warning border-3 ps-3">
                    <strong>${formatDate(formData.get('date_fin'))}</strong>
                    <br><small class="text-muted">Date de fin</small>
                </div>
            </div>
        `;
    }
    
    // Description
    if (formData.get('description')) {
        previewHTML += `
            <div class="col-12 mb-3">
                <div class="border-start border-secondary border-3 ps-3">
                    <p class="mb-1">${formData.get('description')}</p>
                    <small class="text-muted">Description</small>
                </div>
            </div>
        `;
    }
    
    // Fichier
    const fichierInput = document.getElementById('fichier');
    if (fichierInput.files.length > 0) {
        previewHTML += `
            <div class="col-12 mb-3">
                <div class="alert alert-info">
                    <i class="fas fa-file me-2"></i>
                    <strong>Nouveau fichier :</strong> ${fichierInput.files[0].name}
                    <br><small>Ce fichier remplacera l'ancien fichier joint</small>
                </div>
            </div>
        `;
    }
    
    previewHTML += '</div>';
    
    document.getElementById('previewContent').innerHTML = previewHTML;
    
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    previewModal.show();
}

function getStatutClass(statut) {
    switch(statut) {
        case 'en cours': return 'primary';
        case 'terminé': return 'success';
        case 'en attente': return 'warning';
        case 'annulé': return 'danger';
        default: return 'secondary';
    }
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR');
}

function supprimerFichier() {
    if (confirm('Êtes-vous sûr de vouloir supprimer le fichier actuel ?')) {
        // Cette fonction nécessiterait une route AJAX pour supprimer le fichier
        // Pour l'instant, on peut juste cacher l'alerte
        document.querySelector('.alert-info').style.display = 'none';
        alert('Fonctionnalité à implémenter : suppression du fichier via AJAX');
    }
}

// Validation du formulaire avant soumission
document.getElementById('projetForm').addEventListener('submit', function(e) {
    // Validation supplémentaire côté client si nécessaire
    const titre = document.getElementById('titre').value.trim();
    const client = document.getElementById('user_id').value;
    const dateDebut = document.getElementById('date_debut').value;
    
    if (!titre || !client || !dateDebut) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }
    
    // Validation des dates
    validateDates();
    if (!document.getElementById('date_fin').validity.valid) {
        e.preventDefault();
        alert('Erreur de validation des dates. Vérifiez que la date de fin est postérieure à la date de début.');
        return false;
    }
});

// Auto-save en draft (optionnel)
let autoSaveTimeout;
document.querySelectorAll('input, textarea, select').forEach(element => {
    element.addEventListener('change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Ici on pourrait implémenter un auto-save
            console.log('Auto-save déclenché...');
        }, 2000);
    });
});
</script>

<style>
.border-3 {
    border-width: 3px !important;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.btn {
    border-radius: 0.375rem;
}

.alert {
    border-radius: 0.5rem;
}

.modal-content {
    border-radius: 0.5rem;
}

/* Animation pour les champs en erreur */
.is-invalid {
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>
</x-app-layout>