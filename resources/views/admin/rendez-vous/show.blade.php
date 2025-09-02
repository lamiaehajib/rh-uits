<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title mb-1">{{ $rendezVous->titre }}</h3>
                        @if($rendezVous->date_heure)
                        <small class="text-muted">
                            l'intervention  du {{ $rendezVous->date_heure->format('d/m/Y à H:i') }}
                        </small>
                        @endif
                    </div>
                    <div>
                        @switch($rendezVous->statut)
                            @case('programmé')
                                <span class="badge bg-secondary fs-6">Programmé</span>
                                @break
                            @case('confirmé')
                                <span class="badge bg-primary fs-6">Confirmé</span>
                                @break
                            @case('terminé')
                                <span class="badge bg-success fs-6">Terminé</span>
                                @break
                            @case('annulé')
                                <span class="badge bg-danger fs-6">Annulé</span>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Informations principales -->
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle"></i> Informations de l'intervention
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Date et Heure</label>
                                    @if($rendezVous->date_heure)
                                    <p class="mb-0">
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ $rendezVous->date_heure->format('l d F Y') }}
                                    </p>
                                    <p class="mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        {{ $rendezVous->date_heure->format('H:i') }}
                                    </p>
                                    @else
                                    <p class="mb-0 text-muted fst-italic">Non spécifié</p>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Lieu</label>
                                    <p class="mb-0">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        {{ $rendezVous->lieu ?? 'Non spécifié' }}
                                    </p>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="fw-bold text-muted">Description</label>
                                    @if($rendezVous->description)
                                        <div class="border rounded p-3 bg-light">
                                            {{ $rendezVous->description }}
                                        </div>
                                    @else
                                        <p class="text-muted fst-italic">Aucune description</p>
                                    @endif
                                </div>

                                @if($rendezVous->notes)
                                    <div class="col-12">
                                        <label class="fw-bold text-muted">Notes</label>
                                        <div class="border rounded p-3 bg-warning bg-opacity-10 border-warning">
                                            <i class="fas fa-sticky-note me-2 text-warning"></i>
                                            {{ $rendezVous->notes }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations du projet et client -->
                <div class="col-12 col-lg-4 mt-4 mt-lg-0">
                    <!-- Projet -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-project-diagram"></i> Projet associé
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($rendezVous->projet)
                            <h6 class="mb-2">
                                <a href="{{ route('admin.projets.show', $rendezVous->projet) }}" 
                                   class="text-decoration-none">
                                    {{ $rendezVous->projet->titre }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-tag me-1"></i>
                                {{ $rendezVous->projet->type }}
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-calendar me-1"></i>
                                Début: {{ $rendezVous->projet->date_debut->format('d/m/Y') }}
                            </p>
                            @else
                            <p class="text-muted fst-italic">Aucun projet associé.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Client -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-user"></i> Client
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($rendezVous->client)
                                <h6 class="mb-2">{{ $rendezVous->client->name }}</h6>
                                @if($rendezVous->client->email)
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-envelope me-1"></i>
                                        <a href="mailto:{{ $rendezVous->client->email }}">{{ $rendezVous->client->email }}</a>
                                    </p>
                                @endif
                                @if($rendezVous->client->tele)
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-phone me-1"></i>
                                        <a href="tel:{{ $rendezVous->client->tele }}">{{ $rendezVous->client->tele }}</a>
                                    </p>
                                @endif
                            @else
                                <p class="text-muted fst-italic">Aucun client associé.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.rendez-vous.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour à la liste
                                </a>
                                {{-- <a href="{{ route('admin.rendez-vous.edit', $rendezVous) }}" class="btn btn-warning"> --}}
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                @if($rendezVous->statut !== 'terminé')
                                    {{-- <form method="POST" action="{{ route('admin.rendez-vous.update', $rendezVous) }}" class="d-inline"> --}}
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="projet_id" value="{{ $rendezVous->projet_id }}">
                                        <input type="hidden" name="titre" value="{{ $rendezVous->titre }}">
                                        <input type="hidden" name="description" value="{{ $rendezVous->description }}">
                                        @if($rendezVous->date_heure)
                                        <input type="hidden" name="date_heure" value="{{ $rendezVous->date_heure->format('Y-m-d H:i:s') }}">
                                        @endif
                                        <input type="hidden" name="lieu" value="{{ $rendezVous->lieu }}">
                                        <input type="hidden" name="statut" value="terminé">
                                        <input type="hidden" name="notes" value="{{ $rendezVous->notes }}">
                                        <button type="submit" class="btn btn-success" 
                                                onclick="return confirm('Marquer cette intervention comme terminée. ?')">
                                            <i class="fas fa-check"></i> Marquer terminé
                                        </button>
                                    </form>
                                @endif
                                {{-- <form method="POST" action="{{ route('admin.rendez-vous.destroy', $rendezVous) }}" class="d-inline">
                                    @csrf --}}
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette intervention  ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
