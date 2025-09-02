<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Modifier le La maintenance sur site </h3>
                    <small class="text-muted">{{ $rendezVous->titre }}</small>
                </div>

                <div class="card-body">
                   <form method="POST" action="{{ route('admin.rendez-vous.update', $rendezVous) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Projet -->
                            <div class="col-12 mb-3">
                                <label for="projet_id" class="form-label">Projet <span class="text-danger">*</span></label>
                                <select name="projet_id" id="projet_id" class="form-select @error('projet_id') is-invalid @enderror" required>
                                    <option value="">Sélectionnez un projet</option>
                                    @foreach($projets as $projet)
                                        <option value="{{ $projet->id }}" 
                                                {{ (old('projet_id', $rendezVous->projet_id) == $projet->id) ? 'selected' : '' }}>
                                            {{ $projet->titre }} - {{ $projet->client->prenom }} {{ $projet->client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('projet_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Titre -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="titre" 
                                       id="titre" 
                                       class="form-control @error('titre') is-invalid @enderror" 
                                       value="{{ old('titre', $rendezVous->titre) }}" 
                                       required
                                       placeholder="Ex: Présentation du design">
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select name="statut" id="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                    <option value="programmé" {{ old('statut', $rendezVous->statut) == 'programmé' ? 'selected' : '' }}>Programmé</option>
                                    <option value="confirmé" {{ old('statut', $rendezVous->statut) == 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                                    <option value="terminé" {{ old('statut', $rendezVous->statut) == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                    <option value="annulé" {{ old('statut', $rendezVous->statut) == 'annulé' ? 'selected' : '' }}>Annulé</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date et Heure -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="date_heure" class="form-label">Date & Heure <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       name="date_heure" 
                                       id="date_heure" 
                                       class="form-control @error('date_heure') is-invalid @enderror" 
                                       value="{{ old('date_heure', $rendezVous->date_heure->format('Y-m-d\TH:i')) }}" 
                                       required>
                                @error('date_heure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lieu -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="lieu" class="form-label">Lieu</label>
                                <input type="text" 
                                       name="lieu" 
                                       id="lieu" 
                                       class="form-control @error('lieu') is-invalid @enderror" 
                                       value="{{ old('lieu', $rendezVous->lieu) }}"
                                       placeholder="Ex: Bureau, Visioconférence, Chez le client">
                                @error('lieu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" 
                                          id="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="4"
                                          placeholder="Détails du La maintenance sur site , points à aborder...">{{ old('description', $rendezVous->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" 
                                          id="notes" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          rows="3"
                                          placeholder="Notes personnelles, remarques...">{{ old('notes', $rendezVous->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Ces notes ne sont visibles que par vous.</small>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.rendez-vous.show', $rendezVous) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <div>
                                <a href="{{ route('admin.rendez-vous.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-list"></i> Liste
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar avec historique -->
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <!-- Informations actuelles -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Informations actuelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <small class="text-muted">Statut actuel:</small>
                            <div>
                                @switch($rendezVous->statut)
                                    @case('programmé')
                                        <span class="badge bg-secondary">Programmé</span>
                                        @break
                                    @case('confirmé')
                                        <span class="badge bg-primary">Confirmé</span>
                                        @break
                                    @case('terminé')
                                        <span class="badge bg-success">Terminé</span>
                                        @break
                                    @case('annulé')
                                        <span class="badge bg-danger">Annulé</span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Date actuelle:</small>
                            <div>{{ $rendezVous->date_heure->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Créé le:</small>
                            <div>{{ $rendezVous->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        @if($rendezVous->updated_at != $rendezVous->created_at)
                            <div class="col-12">
                                <small class="text-muted">Dernière modification:</small>
                                <div>{{ $rendezVous->updated_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Conseils -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-lightbulb text-warning"></i> Conseils
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Prévenez le client en cas de changement d'horaire
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Utilisez les notes pour vos remarques personnelles
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Marquez "confirmé" une fois la confirmation reçue
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success"></i>
                            N'oubliez pas de marquer "terminé" après le RDV
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avertissement si on change la date pour une date passée
    const dateInput = document.getElementById('date_heure');
    const now = new Date().toISOString().slice(0, 16);
    
    dateInput.addEventListener('change', function() {
        if (this.value < now) {
            if (!confirm('Attention: vous définissez une date dans le passé. Continuer ?')) {
                this.value = now;
            }
        }
    });

    // Auto-save dans localStorage pour éviter les pertes
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            localStorage.setItem('rdv_edit_' + this.name, this.value);
        });
        
        // Restaurer les valeurs sauvegardées si disponibles
        const savedValue = localStorage.getItem('rdv_edit_' + input.name);
        if (savedValue && !input.value) {
            input.value = savedValue;
        }
    });
    
    // Nettoyer le localStorage à la soumission
    form.addEventListener('submit', function() {
        inputs.forEach(input => {
            localStorage.removeItem('rdv_edit_' + input.name);
        });
    });
});
</script>
@endpush
</x-app-layout>