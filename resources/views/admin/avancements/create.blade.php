<x-app-layout>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h4 mb-1">Nouvelle étape d'avancement</h2>
            <p class="text-muted mb-0">Projet: {{ $projet->nom }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations de l'étape</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.avancements.store', $projet) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Nom de l'étape -->
                        <div class="mb-3">
                            <label for="etape" class="form-label">Nom de l'étape <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('etape') is-invalid @enderror" 
                                   id="etape" 
                                   name="etape" 
                                   value="{{ old('etape') }}" 
                                   placeholder="Ex: Conception des maquettes"
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
                                      placeholder="Décrivez en détail cette étape d'avancement..."
                                      required>{{ old('description') }}</textarea>
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
                                               value="{{ old('pourcentage', 0) }}"
                                               oninput="document.getElementById('pourcentage').value = this.value; document.getElementById('pourcentageDisplay').textContent = this.value + '%'">
                                        <input type="number" 
                                               class="form-control @error('pourcentage') is-invalid @enderror" 
                                               id="pourcentage" 
                                               name="pourcentage" 
                                               value="{{ old('pourcentage', 0) }}" 
                                               min="0" 
                                               max="100"
                                               oninput="document.getElementById('pourcentageRange').value = this.value; document.getElementById('pourcentageDisplay').textContent = this.value + '%'"
                                               required>
                                        <span class="input-group-text" id="pourcentageDisplay">{{ old('pourcentage', 0) }}%</span>
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
                                        <option value="en cours" {{ old('statut') == 'en cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="terminé" {{ old('statut') == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                        <option value="bloqué" {{ old('statut') == 'bloqué' ? 'selected' : '' }}>Bloqué</option>
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
                                           value="{{ old('date_prevue') }}">
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
                                           value="{{ old('date_realisee') }}">
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
                                      placeholder="Commentaires additionnels...">{{ old('commentaires') }}</textarea>
                            @error('commentaires')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload de fichier -->
                        <div class="mb-3">
                            <label for="fichiers" class="form-label">Fichier joint</label>
                            <input type="file" 
                                   class="form-control @error('fichiers') is-invalid @enderror" 
                                   id="fichiers" 
                                   name="fichiers" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                            <div class="form-text">
                                Formats acceptés: PDF, DOC, DOCX, JPG, PNG, ZIP (Max: 10MB)
                            </div>
                            @error('fichiers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.avancements.index', $projet) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer l'étape
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar with project info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Informations du projet</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> {{ $projet->nom }}</p>
                    @if($projet->description)
                        <p><strong>Description:</strong></p>
                        <p class="text-muted">{{ Str::limit($projet->description, 100) }}</p>
                    @endif
                    @if($projet->date_debut)
                        <p><strong>Date de début:</strong> {{ $projet->date_debut->format('d/m/Y') }}</p>
                    @endif
                    @if($projet->date_fin)
                        <p><strong>Date de fin:</strong> {{ $projet->date_fin->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>