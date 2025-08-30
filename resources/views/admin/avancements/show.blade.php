<x-app-layout>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1">{{ $avancement->etape }}</h2>
                    <p class="text-muted mb-0">
                        Projet: <a href="{{ route('admin.projets.show', $projet) }}" class="text-decoration-none">{{ $projet->titre }}</a>
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.avancements.edit', [$projet, $avancement]) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <form action="{{ route('admin.avancements.destroy', [$projet, $avancement]) }}" 
                          method="POST" 
                          style="display: inline-block;"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette étape ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Détails principaux -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Détails de l'étape</h5>
                </div>
                <div class="card-body">
                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Description</h6>
                        <p class="text-muted">{{ $avancement->description }}</p>
                    </div>

                    <!-- Progression -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Progression</h6>
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar 
                                @if($avancement->pourcentage == 100) bg-success
                                @elseif($avancement->pourcentage >= 50) bg-info
                                @else bg-warning
                                @endif" 
                                 role="progressbar" 
                                 style="width: {{ $avancement->pourcentage }}%"
                                 aria-valuenow="{{ $avancement->pourcentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ $avancement->pourcentage }}%
                            </div>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Statut</h6>
                        @switch($avancement->statut)
                            @case('en cours')
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-clock"></i> En cours
                                </span>
                                @break
                            @case('terminé')
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check"></i> Terminé
                                </span>
                                @break
                            @case('bloqué')
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-exclamation-triangle"></i> Bloqué
                                </span>
                                @break
                        @endswitch
                    </div>

                    <!-- Dates -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Date prévue</h6>
                            <p class="text-muted">
                                {{ $avancement->date_prevue ? $avancement->date_prevue->format('d/m/Y') : 'Non définie' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Date réalisée</h6>
                            <p class="text-muted">
                                {{ $avancement->date_realisee ? $avancement->date_realisee->format('d/m/Y') : 'Non réalisée' }}
                            </p>
                        </div>
                    </div>

                    <!-- Commentaires -->
                    <h5><i class="fas fa-comment-dots me-2 text-primary"></i>Commentaires du client</h5>
                   @if($avancement->commentaires)
        <div class="alert alert-info" role="alert">
            {{-- Display comments with line breaks --}}
            <p class="mb-0">{!! nl2br(e($avancement->commentaires)) !!}</p>
        </div>
    @else
        <p class="text-muted">Aucun commentaire n'a été laissé pour le moment.</p>
    @endif

                    <!-- Fichier joint -->
                    @if($avancement->fichiers)
                        <div class="mb-4">
                            <h6 class="fw-bold">Fichier joint</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file fa-2x text-primary me-3"></i>
                                <div>
                                    <p class="mb-0">
                                        @if($avancement->fichiers)
                                <hr>
                                <p class="card-text mb-2">
                                    <strong><i class="fas fa-paperclip me-2"></i>Fichier associé:</strong>
                                </p>
                               <a href="{{ route('client.avancements.download', $avancement) }}" target="_blank" class="btn custom-btn">
    <i class="fas fa-download me-2"></i> Télécharger le fichier
</a>
                            @endif

                                    </p>
                                    <small class="text-muted">Cliquez pour télécharger</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informations rapides -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informations rapides</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Créé le</small>
                        <p class="mb-0">{{ $avancement->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Dernière modification</small>
                        <p class="mb-0">{{ $avancement->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @if($avancement->date_prevue)
                        <div class="mb-3">
                            <small class="text-muted">Délai</small>
                            <p class="mb-0">
                                @if($avancement->date_prevue->isPast() && $avancement->statut !== 'terminé')
                                    <span class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        En retard de {{ $avancement->date_prevue->diffInDays(now()) }} jour(s)
                                    </span>
                                @elseif($avancement->date_prevue->isFuture())
                                    <span class="text-info">
                                        <i class="fas fa-calendar"></i> 
                                        Dans {{ now()->diffInDays($avancement->date_prevue) }} jour(s)
                                    </span>
                                @else
                                    <span class="text-success">
                                        <i class="fas fa-check"></i> À temps
                                    </span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Actions rapides</h6>
                </div>
                <div class="card-body">
                    @if($avancement->statut !== 'terminé')
                        <button class="btn btn-success btn-sm w-100 mb-2" onclick="markAsCompleted()">
                            <i class="fas fa-check"></i> Marquer comme terminé
                        </button>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label small">Mise à jour rapide du pourcentage:</label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   id="quickPourcentage" 
                                   value="{{ $avancement->pourcentage }}" 
                                   min="0" 
                                   max="100">
                            <button class="btn btn-outline-secondary btn-sm" 
                                    type="button" 
                                    onclick="updateQuickPourcentage()">
                                Mettre à jour
                            </button>
                        </div>
                    </div>

                    <a href="{{ route('admin.avancements.index', $projet) }}" 
                       class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-list"></i> Voir toutes les étapes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsCompleted() {
    if (confirm('Marquer cette étape comme terminée (100%) ?')) {
        // Redirection vers la page d'édition avec le statut terminé
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.avancements.update", [$projet, $avancement]) }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add method override
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        form.appendChild(methodField);
        
        // Add current values
        const fields = ['etape', 'description', 'commentaires'];
        const values = {
            'etape': '{{ $avancement->etape }}',
            'description': '{{ $avancement->description }}',
            'pourcentage': '100',
            'statut': 'terminé',
            'date_prevue': '{{ $avancement->date_prevue ? $avancement->date_prevue->format("Y-m-d") : "" }}',
            'date_realisee': '{{ now()->format("Y-m-d") }}',
            'commentaires': '{{ $avancement->commentaires }}'
        };
        
        Object.entries(values).forEach(([name, value]) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

function updateQuickPourcentage() {
    const newValue = document.getElementById('quickPourcentage').value;
    
    if (newValue < 0 || newValue > 100) {
        alert('Le pourcentage doit être entre 0 et 100.');
        return;
    }
    
    fetch('{{ route("admin.avancements.update-pourcentage", [$projet, $avancement]) }}', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            pourcentage: newValue
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour');
    });
}
</script>
@endpush
</x-app-layout>
