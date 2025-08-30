<x-app-layout>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Créer un Nouveau Projet</h1>
        <a href="{{ route('admin.projets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm mr-2"></i>
            Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du Projet</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.projets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titre">Titre du Projet <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('titre') is-invalid @enderror" 
                                           id="titre" 
                                           name="titre" 
                                           value="{{ old('titre') }}" 
                                           required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Client <span class="text-danger">*</span></label>
                                    <select class="form-control @error('user_id') is-invalid @enderror" 
                                            id="user_id" 
                                            name="user_id" 
                                            required>
                                        <option value="">Sélectionner un client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('user_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }} ({{ $client->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Décrivez les détails du projet...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_debut">Date de Début <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('date_debut') is-invalid @enderror" 
                                           id="date_debut" 
                                           name="date_debut" 
                                           value="{{ old('date_debut') }}" 
                                           required>
                                    @error('date_debut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_fin">Date de Fin Prévue</label>
                                    <input type="date" 
                                           class="form-control @error('date_fin') is-invalid @enderror" 
                                           id="date_fin" 
                                           name="date_fin" 
                                           value="{{ old('date_fin') }}">
                                    @error('date_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="statut_projet">Statut <span class="text-danger">*</span></label>
                                    <select class="form-control @error('statut_projet') is-invalid @enderror" 
                                            id="statut_projet" 
                                            name="statut_projet" 
                                            required>
                                        <option value="en cours" {{ old('statut_projet') == 'en cours' ? 'selected' : '' }}>En Cours</option>
                                        <option value="en attente" {{ old('statut_projet') == 'en attente' ? 'selected' : '' }}>En Attente</option>
                                        <option value="terminé" {{ old('statut_projet') == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                        <option value="annulé" {{ old('statut_projet') == 'annulé' ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                    @error('statut_projet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fichier">Fichier Joint</label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('fichier') is-invalid @enderror" 
                                       id="fichier" 
                                       name="fichier"
                                       accept=".pdf,.doc,.docx,.jpg,.png">
                                <label class="custom-file-label" for="fichier">Choisir un fichier...</label>
                            </div>
                            <small class="form-text text-muted">
                                Formats acceptés: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)
                            </small>
                            @error('fichier')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span class="text">Créer le Projet</span>
                            </button>
                            <a href="{{ route('admin.projets.index') }}" class="btn btn-secondary ml-2">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aide</h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">Conseils pour créer un projet:</h6>
                    <ul class="small text-gray-600">
                        <li>Choisissez un titre descriptif et clair</li>
                        <li>Sélectionnez le bon client dans la liste</li>
                        <li>La description aide à comprendre les objectifs</li>
                        <li>Définissez des dates réalistes</li>
                        <li>Vous pourrez ajouter des étapes d'avancement après création</li>
                    </ul>
                    
                    <hr>
                    
                    <h6 class="font-weight-bold">Statuts disponibles:</h6>
                    <div class="small">
                        <span class="badge badge-warning mr-1">En Cours</span> Projet actif<br>
                        <span class="badge badge-info mr-1">En Attente</span> Projet en pause<br>
                        <span class="badge badge-success mr-1">Terminé</span> Projet fini<br>
                        <span class="badge badge-danger mr-1">Annulé</span> Projet annulé
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Gestion du nom de fichier dans l'input
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : 'Choisir un fichier...';
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});
</script>
</x-app-layout>