<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Nouveau La maintenance sur site </h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.rendez-vous.store') }}">
                        @csrf

                        <div class="row">
                            <!-- Projet -->
                            <div class="col-12 mb-3">
                                <label for="projet_id" class="form-label">Projet <span class="text-danger">*</span></label>
                                <select name="projet_id" id="projet_id" class="form-select @error('projet_id') is-invalid @enderror" required>
                                    <option value="">Sélectionnez un projet</option>
                                    @foreach($projets as $projet)
                                        <option value="{{ $projet->id }}" {{ old('projet_id') == $projet->id ? 'selected' : '' }}>
                                            {{ $projet->titre }} - {{ $projet->client->name }} 
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
                                       value="{{ old('titre') }}" 
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
                                    <option value="programmé" {{ old('statut') == 'programmé' ? 'selected' : '' }}>Programmé</option>
                                    <option value="confirmé" {{ old('statut') == 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                                    <option value="terminé" {{ old('statut') == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                    <option value="annulé" {{ old('statut') == 'annulé' ? 'selected' : '' }}>Annulé</option>
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
                                       value="{{ old('date_heure') }}" 
                                       required
                                       min="{{ now()->format('Y-m-d\TH:i') }}">
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
                                       value="{{ old('lieu') }}"
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
                                          placeholder="Détails du La maintenance sur site , points à aborder...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.rendez-vous.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer le La maintenance sur site 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar avec conseils -->
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb text-warning"></i> Conseils
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Vérifiez la disponibilité avant de programmer
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Ajoutez une description détaillée pour une meilleure préparation
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Confirmez le lieu et l'heure avec le client
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success"></i>
                            Préparez l'agenda des points à aborder
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
    // Auto-remplir le titre basé sur le projet sélectionné
    const projetSelect = document.getElementById('projet_id');
    const titreInput = document.getElementById('titre');
    
    projetSelect.addEventListener('change', function() {
        if (this.value && !titreInput.value) {
            const selectedOption = this.options[this.selectedIndex];
            const projetNom = selectedOption.text.split(' - ')[0];
            titreInput.value = `Réunion - ${projetNom}`;
        }
    });
});
</script>
@endpush
</x-app-layout>