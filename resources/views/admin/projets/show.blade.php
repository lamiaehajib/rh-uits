<x-app-layout>
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $projet->titre }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.projets.index') }}">Projets</a></li>
                    <li class="breadcrumb-item active">{{ $projet->titre }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.projets.edit', $projet) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.projets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Messages de succès -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informations du Projet</h5>
                    <span class="badge 
                        @if($projet->statut_projet === 'en cours') bg-primary
                        @elseif($projet->statut_projet === 'terminé') bg-success
                        @elseif($projet->statut_projet === 'en attente') bg-warning
                        @else bg-danger
                        @endif">
                        {{ ucfirst($projet->statut_projet) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Titre:</strong></div>
                        <div class="col-sm-9">{{ $projet->titre }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Client:</strong></div>
                        <div class="col-sm-9">
                            <span class="badge bg-info">{{ $projet->client->name }}</span>
                            <small class="text-muted">({{ $projet->client->email }})</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Description:</strong></div>
                        <div class="col-sm-9">
                            @if($projet->description)
                                {{ $projet->description }}
                            @else
                                <em class="text-muted">Aucune description fournie</em>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Date de début:</strong></div>
                        <div class="col-sm-9">{{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Date de fin:</strong></div>
                        <div class="col-sm-9">
                            @if($projet->date_fin)
                                {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}
                            @else
                                <em class="text-muted">Non définie</em>
                            @endif
                        </div>
                    </div>

                    @if($projet->fichier)
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Fichier joint:</strong></div>
                            <div class="col-sm-9">
                                <a href="{{ Storage::disk('public')->url($projet->fichier) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                           <i class="fas fa-download"></i> Télécharger le fichier
                              </a>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-3"><strong>Créé le:</strong></div>
                        <div class="col-sm-9">{{ $projet->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Avancement du projet -->
            @if($projet->avancements->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Avancement du Projet</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Progression globale</span>
                                <span class="fw-bold">{{ number_format($pourcentageGlobal, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar 
                                    @if($pourcentageGlobal < 30) bg-danger
                                    @elseif($pourcentageGlobal < 70) bg-warning
                                    @else bg-success
                                    @endif" 
                                    role="progressbar" 
                                    style="width: {{ $pourcentageGlobal }}%">
                                </div>
                            </div>
                        </div>

                        <h6>Détails des avancements:</h6>
                        <div class="list-group">
                            @foreach($projet->avancements->sortBy('created_at') as $avancement)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $avancement->titre }}</h6>
                                            <p class="mb-1">{{ $avancement->description }}</p>
                                            <small class="text-muted">{{ $avancement->created_at->format('d/m/Y à H:i') }}</small>
                                        </div>
                                        <span class="badge bg-primary">{{ $avancement->pourcentage }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- La maintenance sur site  -->
            @if($projet->rendezVous->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">La maintenance sur site programmés</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date & Heure</th>
                                        <th>Lieu</th>
                                        <th>Description</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projet->rendezVous->sortBy('date_heure') as $rdv)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y à H:i') }}</td>
                                            <td>{{ $rdv->lieu ?? 'Non défini' }}</td>
                                            <td>{{ Str::limit($rdv->description, 50) }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($rdv->statut === 'programmé') bg-info
                                                    @elseif($rdv->statut === 'confirmé') bg-success
                                                    @elseif($rdv->statut === 'reporté') bg-warning
                                                    @else bg-danger
                                                    @endif">
                                                    {{ ucfirst($rdv->statut) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar avec actions et statistiques -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.projets.edit', $projet) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier le projet
                        </a>
                        
                        @if($projet->statut_projet !== 'terminé')
                            <button type="button" class="btn btn-success" onclick="marquerTermine({{ $projet->id }})">
                                <i class="fas fa-check"></i> Marquer comme terminé
                            </button>
                        @endif
                        
                        <button type="button" class="btn btn-danger" onclick="confirmerSuppression({{ $projet->id }})">
                            <i class="fas fa-trash"></i> Supprimer le projet
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistiques du projet -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $projet->rendezVous->count() }}</h4>
                                <small class="text-muted">La maintenance sur site </small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $projet->avancements->count() }}</h4>
                            <small class="text-muted">Avancements</small>
                        </div>
                    </div>
                    
                    @if($projet->date_debut && $projet->date_fin)
                        <hr>
                        <div class="text-center">
                            <h6>Durée du projet</h6>
                            <p class="mb-0">
                                {{ \Carbon\Carbon::parse($projet->date_debut)->diffInDays(\Carbon\Carbon::parse($projet->date_fin)) }} jours
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations du client -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations Client</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                            {{ strtoupper(substr($projet->client->name, 0, 2)) }}
                        </div>
                    </div>
                    <h6 class="text-center">{{ $projet->client->name }}</h6>
                    <p class="text-center text-muted mb-3">{{ $projet->client->email }}</p>
                    
                    @if($projet->client->phone)
                        <div class="d-grid">
                            <a href="tel:{{ $projet->client->phone }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-phone"></i> {{ $projet->client->phone }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.</p>
                <p><strong>Projet:</strong> {{ $projet->titre }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 60px;
    height: 60px;
    font-size: 20px;
    font-weight: bold;
}
</style>

<script>
function confirmerSuppression(projetId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/projets/${projetId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function marquerTermine(projetId) {
    if (confirm('Marquer ce projet comme terminé ?')) {
        // Créer un formulaire pour mettre à jour le statut
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/projets/${projetId}`;
        
        // Token CSRF
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Method PATCH
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);
        
        // Statut terminé
        const statutInput = document.createElement('input');
        statutInput.type = 'hidden';
        statutInput.name = 'statut_projet';
        statutInput.value = 'terminé';
        form.appendChild(statutInput);
        
        // Autres champs requis (conserver les valeurs actuelles)
        const titreInput = document.createElement('input');
        titreInput.type = 'hidden';
        titreInput.name = 'titre';
        titreInput.value = '{{ $projet->titre }}';
        form.appendChild(titreInput);
        
        const userInput = document.createElement('input');
        userInput.type = 'hidden';
        userInput.name = 'user_id';
        userInput.value = '{{ $projet->user_id }}';
        form.appendChild(userInput);
        
        const dateDebutInput = document.createElement('input');
        dateDebutInput.type = 'hidden';
        dateDebutInput.name = 'date_debut';
        dateDebutInput.value = '{{ $projet->date_debut }}';
        form.appendChild(dateDebutInput);
        
        @if($projet->date_fin)
        const dateFinInput = document.createElement('input');
        dateFinInput.type = 'hidden';
        dateFinInput.name = 'date_fin';
        dateFinInput.value = '{{ $projet->date_fin }}';
        form.appendChild(dateFinInput);
        @endif
        
        @if($projet->description)
        const descInput = document.createElement('input');
        descInput.type = 'hidden';
        descInput.name = 'description';
        descInput.value = '{{ addslashes($projet->description) }}';
        form.appendChild(descInput);
        @endif
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
</x-app-layout>