<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0 color-primary heading-bounce">Détails de la demande de congé</h2>
                    <a class="btn btn-outline-primary-custom" href="{{ route('conges.index') }}">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                {{-- Informations principales --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 color-primary">
                            <i class="fas fa-info-circle me-2"></i>Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->hasRole(['Custom_Admin', 'Sup_Admin']))
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Employé :</strong></div>
                                <div class="col-md-8">
                                    {{ $conge->user->name }}<br>
                                    <small class="text-muted">{{ $conge->user->email }}</small><br>
                                    <small class="text-muted">{{ $conge->user->poste }}</small>
                                </div>
                            </div>
                            <hr>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Date de début :</strong></div>
                            <div class="col-md-8">{{ $conge->date_debut->format('d/m/Y') }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Date de fin :</strong></div>
                            <div class="col-md-8">{{ $conge->date_fin->format('d/m/Y') }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Nombre de jours demandés :</strong></div>
                            <div class="col-md-8">
                                <span class="badge bg-secondary">{{ $conge->nombre_jours_demandes }} jour(s)</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Jours ouvrables :</strong></div>
                            <div class="col-md-8">
                                <span class="badge bg-success">{{ $conge->nombre_jours_ouvrables }} jour(s)</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Motif :</strong></div>
                            <div class="col-md-8">{{ $conge->motif }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Statut :</strong></div>
                            <div class="col-md-8">
                                @if($conge->statut == 'en_attente')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @elseif($conge->statut == 'approuve')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Approuvé
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>Refusé
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($conge->traite_par)
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Traité par :</strong></div>
                                <div class="col-md-8">{{ $conge->traitePar->name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Date de traitement :</strong></div>
                                <div class="col-md-8">{{ $conge->traite_le->format('d/m/Y H:i') }}</div>
                            </div>
                            @if($conge->commentaire_admin)
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Commentaire :</strong></div>
                                    <div class="col-md-8">
                                        <div class="alert alert-info mb-0">
                                            {{ $conge->commentaire_admin }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <hr>
                        <div class="row">
                            <div class="col-md-4"><strong>Demande créée le :</strong></div>
                            <div class="col-md-8">{{ $conge->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Détail des jours --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 color-primary">
                            <i class="fas fa-calendar me-2"></i>Détail des jours
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Jour</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details['jours'] as $jour)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($jour['date'])->format('d/m/Y') }}</td>
                                            <td>{{ ucfirst($jour['jour']) }}</td>
                                            <td>
                                                @if($jour['type'] == 'ouvrable')
                                                    <span class="badge bg-success">Jour ouvrable</span>
                                                @elseif($jour['type'] == 'repos')
                                                    <span class="badge bg-warning text-dark">Jour de repos</span>
                                                @else
                                                    <span class="badge bg-primary">Jour férié</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Résumé --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 color-primary">
                            <i class="fas fa-chart-pie me-2"></i>Résumé
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Total de jours</span>
                                <strong>{{ $details['total'] }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Jours ouvrables</span>
                                <strong class="text-success">{{ $details['ouvrables'] }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Jours de repos</span>
                                <strong class="text-warning">{{ $details['repos'] }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Jours fériés</span>
                                <strong class="text-primary">{{ $details['feries'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions admin --}}
                @if(Auth::user()->hasRole(['Custom_Admin', 'Sup_Admin']) && $conge->statut == 'en_attente')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <h5 class="mb-0 color-primary">
                                <i class="fas fa-tasks me-2"></i>Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Approuver --}}
                            <form method="POST" action="{{ route('conges.approve', $conge) }}" class="mb-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="commentaire_approve" class="form-label">Commentaire (optionnel)</label>
                                    <textarea name="commentaire" id="commentaire_approve" class="form-control" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Confirmer l\'approbation de ce congé ?')">
                                    <i class="fas fa-check me-2"></i>Approuver
                                </button>
                            </form>

                            {{-- Refuser --}}
                            <form method="POST" action="{{ route('conges.reject', $conge) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="commentaire_reject" class="form-label">Raison du refus <span class="text-danger">*</span></label>
                                    <textarea name="commentaire" id="commentaire_reject" class="form-control" rows="2" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Confirmer le refus de ce congé ?')">
                                    <i class="fas fa-times me-2"></i>Refuser
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>