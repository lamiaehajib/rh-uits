<x-app-layout>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h4 mb-1">Modifier l'étape d'avancement</h2>
            <p class="text-muted mb-0">
                Projet: <a href="{{ route('admin.projets.show', $projet) }}" class="text-decoration-none">{{ $projet->nom }}</a>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations de l'étape</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.avancements.update', [$projet, $avancement]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nom de l'étape -->
                        <div class="mb-3">
                            <label for="etape" class="form-label">Nom de l'étape <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('etape') is-invalid @enderror" 
                                   id="etape" 
                                   name="etape" 
                                   value="{{ old('etape', $avancement->etape) }}" 
                                   required>
                            @error('etape')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required>{{ old('description', $avancement->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Row for Pourcentage and Statut -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pourcentage" class="form-label">Pourcentage d'achèvement <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="range" 
                                               class="form-range" 
                                               id="pourcentageRange" 
                                               min="0" 
                                               max="100" 
                                               value="{{ old('pourcentage', $avancement->pourcentage) }}"
                                               oninput="document.getElementById('pourcentage').value = this.value; document.getElementById('pourcentageDisplay').textContent = this.value + '%'">
                                        <input type="number" 
                                               class="form-control @error('pourcentage') is-invalid @enderror" 
                                               id="pourcentage" 
                                               name="pourcentage" 
                                               value="{{ old('pourcentage', $avancement->pourcentage) }}" 
                                               min="0" 
                                               max="100"
                                               oninput="document.getElementById('pourcentageRange').value = this.value; document.getElementById('pourcentageDisplay').textContent = this.value + '%'"
                                               required>
                                        <span class="input-group-text" id="pourcentageDisplay">{{ old('pourcentage', $avancement->pourcentage) }}%</span>
                                    </div>
                                    @error('pourcentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select @error('statut') is-invalid @enderror" 
                                            id="statut" 
                                            name="statut" 
                                            required>
                                        <option value="">Choisir un statut</option>
                                        <option value="en cours" {{ old('statut', $avancement->statut) == 'en cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="terminé" {{ old('statut', $avancement->statut) == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                        <option value="bloqué" {{ old('statut', $avancement->statut) == 'bloqué' ? 'selected' : '' }}>Bloqué</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Row for Dates -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_prevue" class="form-label">Date prévue</label>
                                    <input type="date" 
                                           class="form-control @error('date_prevue') is-invalid @enderror" 
                                           id="date_prevue" 
                                           name="date_prevue" 
                                           value="{{ old('date_prevue', $avancement->date_prevue?->format('Y-m-d')) }}">
                                    @error('date_prevue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_realisee" class="form-label">Date réalisée</label>
                                    <input type="date" 
                                           class="form-control @error('date_realisee') is-invalid @enderror" 
                                           id="date_realisee" 
                                           name="date_realisee" 
                                           value="{{ old('date_realisee', $avancement->date_realisee?->format('Y-m-d')) }}">
                                    @error('date_realisee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Commentaires -->
                        <div class="mb-3">
                            <label for="commentaires" class="form-label">Commentaires</label>
                            <textarea class="form-control @error('commentaires') is-invalid @enderror" 
                                      id="commentaires" 
                                      name="commentaires" 
                                      rows="3" 
                                      placeholder="Commentaires additionnels...">{{ old('commentaires', $avancement->commentaires) }}</textarea>
                            @error('commentaires')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload de fichier -->
                        <div class="mb-3">
                            <label for="fichiers" class="form-label">Nouveau fichier joint</label>
                            @if($avancement->fichiers)
                                <div class="mb-2">
                                    <div class="alert alert-info py-2">
                                        <i class="fas fa-info-circle"></i> 
                                        Fichier actuel: 
                                        <a href="{{ Storage::url($avancement->fichiers) }}" target="_blank">
                                            {{ basename($avancement->fichiers) }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('fichiers') is-invalid @enderror" 
                                   id="fichiers" 
                                   name="fichiers" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                            <div class="form-text">
                                Formats acceptés: PDF, DOC, DOCX, JPG, PNG, ZIP (Max: 10MB)
                                @if($avancement->fichiers)
                                    <br><strong>Note:</strong> Uploader un nouveau fichier remplacera le fichier actuel.
                                @endif
                            </div>
                            @error('fichiers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.avancements.show', [$projet, $avancement]) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Progression actuelle -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Progression actuelle</h6>
                </div>
                <div class="card-body">
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar 
                            @if($avancement->pourcentage == 100) bg-success
                            @elseif($avancement->pourcentage >= 50) bg-info
                            @else bg-warning
                            @endif" 
                             role="progressbar" 
                             style="width: {{ $avancement->pourcentage }}%">
                            {{ $avancement->pourcentage }}%
                        </div>
                    </div>
                    <small class="text-muted">Progression de cette étape</small>
                </div>
            </div>

            <!-- Historique -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Historique</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Créé le</small>
                        <p class="mb-0">{{ $avancement->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Dernière modification</small>
                        <p class="mb-0">{{ $avancement->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-update date_realisee when status is set to 'terminé'
document.getElementById('statut').addEventListener('change', function() {
    const dateRealiseInput = document.getElementById('date_realisee');
    const pourcentageInput = document.getElementById('pourcentage');
    const pourcentageRange = document.getElementById('pourcentageRange');
    const pourcentageDisplay = document.getElementById('pourcentageDisplay');
    
    if (this.value === 'terminé') {
        if (!dateRealiseInput.value) {
            dateRealiseInput.value = new Date().toISOString().split('T')[0];
        }
        pourcentageInput.value = 100;
        pourcentageRange.value = 100;
        pourcentageDisplay.textContent = '100%';
    }
});

// Auto-update status when percentage reaches 100%
document.getElementById('pourcentage').addEventListener('input', function() {
    const statutSelect = document.getElementById('statut');
    const dateRealiseInput = document.getElementById('date_realisee');
    
    if (this.value == 100 && statutSelect.value !== 'terminé') {
        if (confirm('Marquer automatiquement le statut comme "terminé" ?')) {
            statutSelect.value = 'terminé';
            if (!dateRealiseInput.value) {
                dateRealiseInput.value = new Date().toISOString().split('T')[0];
            }
        }
    }
});
</script>
@endpush
</x-app-layout>