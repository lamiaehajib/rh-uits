{{-- resources/views/admin/rendez-vous/planning.blade.php --}}
<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title mb-1">Planning de la semaine</h3>
                        <small class="text-muted">
                            Du {{ now()->startOfWeek()->format('d/m/Y') }} au {{ now()->endOfWeek()->format('d/m/Y') }}
                        </small>
                    </div>
                    <div>
                        <a href="{{ route('admin.rendez-vous.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau
                        </a>
                        <a href="{{ route('admin.rendez-vous.aujourdhui') }}" class="btn btn-warning">
                            <i class="fas fa-calendar-day"></i> Aujourd'hui
                        </a>
                        <a href="{{ route('admin.rendez-vous.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Liste
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($rendezVous->count() > 0)
                        <!-- Navigation semaine -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <div class="btn-group" role="group">
                                    <a href="?week={{ now()->subWeek()->startOfWeek()->format('Y-m-d') }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-chevron-left"></i> Semaine précédente
                                    </a>
                                    <button class="btn btn-secondary" disabled>
                                        Semaine {{ now()->weekOfYear }} - {{ now()->year }}
                                    </button>
                                    <a href="?week={{ now()->addWeek()->startOfWeek()->format('Y-m-d') }}" 
                                       class="btn btn-outline-secondary">
                                        Semaine suivante <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Vue en grille par jour -->
                        <div class="row">
                            @php
                                $days = [
                                    'lundi' => now()->startOfWeek(),
                                    'mardi' => now()->startOfWeek()->addDay(),
                                    'mercredi' => now()->startOfWeek()->addDays(2),
                                    'jeudi' => now()->startOfWeek()->addDays(3),
                                    'vendredi' => now()->startOfWeek()->addDays(4),
                                    'samedi' => now()->startOfWeek()->addDays(5),
                                    'dimanche' => now()->startOfWeek()->addDays(6),
                                ];
                            @endphp

                            @foreach($days as $dayName => $date)
                                @php
                                    $dayRdv = $rendezVous->filter(function($rdv) use ($date) {
                                        return $rdv->date_heure->isSameDay($date);
                                    })->sortBy('date_heure');
                                    
                                    $isToday = $date->isToday();
                                    $isWeekend = $date->isWeekend();
                                @endphp

                                <div class="col-12 col-lg-6 col-xl-4 mb-4">
                                    <div class="card h-100 @if($isToday) border-primary @elseif($isWeekend) border-light @endif">
                                        <div class="card-header @if($isToday) bg-primary text-white @elseif($isWeekend) bg-light @endif">
                                            <h6 class="card-title mb-0">
                                                {{ ucfirst($dayName) }}
                                                @if($isToday)
                                                    <span class="badge bg-light text-primary ms-2">Aujourd'hui</span>
                                                @endif
                                            </h6>
                                            <small class="@if($isToday) text-white-50 @else text-muted @endif">
                                                {{ $date->format('d/m/Y') }}
                                            </small>
                                        </div>

                                        <div class="card-body p-2">
                                            @if($dayRdv->count() > 0)
                                                @foreach($dayRdv as $rdv)
                                                    <div class="card mb-2">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                                <h6 class="card-title mb-0 small">
                                                                    {{ $rdv->date_heure->format('H:i') }}
                                                                    - {{ $rdv->titre }}
                                                                </h6>
                                                                <span class="badge badge-sm
                                                                    @switch($rdv->statut)
                                                                        @case('programmé') bg-secondary @break
                                                                        @case('confirmé') bg-primary @break
                                                                        @case('terminé') bg-success @break
                                                                        @case('annulé') bg-danger @break
                                                                    @endswitch
                                                                ">{{ $rdv->statut }}</span>
                                                            </div>

                                                            <p class="card-text small text-muted mb-2">
                                                                <i class="fas fa-project-diagram me-1"></i>
                                                                {{ $rdv->projet->titre}}
                                                                <br>
                                                                <i class="fas fa-user me-1"></i>
                                                                 {{ $rdv->client->name }}
                                                                @if($rdv->lieu)
                                                                    <br>
                                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                                    {{ $rdv->lieu }}
                                                                @endif
                                                            </p>

                                                            <!-- Actions rapides -->
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <a href="{{ route('admin.rendez-vous.show', $rdv) }}" 
                                                                   class="btn btn-outline-info btn-sm flex-fill">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('admin.rendez-vous.edit', $rdv) }}" 
                                                                   class="btn btn-outline-warning btn-sm flex-fill">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                @if($rdv->statut === 'confirmé' && !$rdv->date_heure->isPast())
                                                                    <form method="POST" 
                                                                          action="{{ route('admin.rendez-vous.update', $rdv) }}" 
                                                                          class="flex-fill">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="hidden" name="projet_id" value="{{ $rdv->projet_id }}">
                                                                        <input type="hidden" name="titre" value="{{ $rdv->titre }}">
                                                                        <input type="hidden" name="description" value="{{ $rdv->description }}">
                                                                        <input type="hidden" name="date_heure" value="{{ $rdv->date_heure->format('Y-m-d H:i:s') }}">
                                                                        <input type="hidden" name="lieu" value="{{ $rdv->lieu }}">
                                                                        <input type="hidden" name="statut" value="terminé">
                                                                        <input type="hidden" name="notes" value="{{ $rdv->notes }}">
                                                                        <button type="submit" 
                                                                                class="btn btn-outline-success btn-sm w-100"
                                                                                onclick="return confirm('Marquer terminé ?')">
                                                                            <i class="fas fa-check"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center text-muted py-4">
                                                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                                    <p class="small mb-0">Aucun interventione</p>
                                                    @if(!$isWeekend)
                                                        <a href="{{ route('admin.rendez-vous.create') }}?date={{ $date->format('Y-m-d') }}" 
                                                           class="btn btn-sm btn-outline-primary mt-2">
                                                            <i class="fas fa-plus"></i> Ajouter
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        @if($dayRdv->count() > 0)
                                            <div class="card-footer p-2">
                                                <small class="text-muted">
                                                    {{ $dayRdv->count() }} rendez-vous
                                                    @if($dayRdv->where('statut', 'confirmé')->count() > 0)
                                                        | {{ $dayRdv->where('statut', 'confirmé')->count() }} confirmé(s)
                                                    @endif
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Résumé de la semaine -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-chart-bar me-2"></i>Résumé de la semaine
                                        </h6>
                                        <div class="row text-center">
                                            <div class="col-6 col-md-3">
                                                <div class="border-end">
                                                    <h4 class="text-primary mb-0">{{ $rendezVous->count() }}</h4>
                                                    <small class="text-muted">Total</small>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="border-end">
                                                    <h4 class="text-info mb-0">{{ $rendezVous->where('statut', 'confirmé')->count() }}</h4>
                                                    <small class="text-muted">Confirmés</small>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="border-end">
                                                    <h4 class="text-success mb-0">{{ $rendezVous->where('statut', 'terminé')->count() }}</h4>
                                                    <small class="text-muted">Terminés</small>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <h4 class="text-warning mb-0">{{ $rendezVous->where('statut', 'programmé')->count() }}</h4>
                                                <small class="text-muted">En attente</small>
                                            </div>
                                        </div>

                                        <!-- Prochains rendez-vous -->
                                        @php
                                            $prochains = $rendezVous->where('date_heure', '>', now())
                                                                   ->where('statut', '!=', 'annulé')
                                                                   ->sortBy('date_heure')
                                                                   ->take(3);
                                        @endphp

                                        @if($prochains->count() > 0)
                                            <hr>
                                            <h6 class="mt-3 mb-2">
                                                <i class="fas fa-clock me-2"></i>Prochains rendez-vous
                                            </h6>
                                            <div class="row">
                                                @foreach($prochains as $rdv)
                                                    <div class="col-12 col-md-4 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-3">
                                                                <div class="badge bg-primary">
                                                                    {{ $rdv->date_heure->format('d/m') }}
                                                                </div>
                                                                <div class="small text-center">
                                                                    {{ $rdv->date_heure->format('H:i') }}
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-bold small">{{ $rdv->titre }}</div>
                                                                <div class="text-muted small">
                                                                    {{ $rdv->client->prenom }} {{ $rdv->client->nom }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-week fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun rendez-vous cette semaine</h5>
                            <p class="text-muted">Planifiez vos rendez-vous pour organiser votre semaine.</p>
                            <a href="{{ route('admin.rendez-vous.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Planifier un rendez-vous
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création rapide (optionnel) -->
<div class="modal fade" id="quickCreateModal" tabindex="-1" aria-labelledby="quickCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickCreateModalLabel">Création rapide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quickCreateForm" method="POST" action="{{ route('admin.rendez-vous.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="quick_titre" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="quick_titre" name="titre" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="quick_date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="quick_date" name="date" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="quick_heure" class="form-label">Heure</label>
                                <input type="time" class="form-control" id="quick_heure" name="heure" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="quick_date_heure" name="date_heure">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="submitQuickForm()">Créer</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.badge-sm {
    font-size: 0.65em;
}

.card-body .btn-group {
    box-shadow: none;
}

.card-body .btn-group .btn {
    border-radius: 0;
    font-size: 0.75rem;
}

.card-body .btn-group .btn:first-child {
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}

.card-body .btn-group .btn:last-child {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}

@media (max-width: 768px) {
    .col-xl-4, .col-lg-6 {
        margin-bottom: 1rem;
    }
    
    .btn-group .btn {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}

@media (max-width: 768px) {
    .border-end {
        border-right: none !important;
        border-bottom: 1px solid #dee2e6 !important;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    
    .col-6:nth-child(2) .border-end,
    .col-6:nth-child(4) .border-end {
        border-bottom: none !important;
        margin-bottom: 0;
        padding-bottom: 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Fonction pour la création rapide
function openQuickCreate(date) {
    document.getElementById('quick_date').value = date;
    document.getElementById('quick_heure').value = '09:00';
    new bootstrap.Modal(document.getElementById('quickCreateModal')).show();
}

function submitQuickForm() {
    const date = document.getElementById('quick_date').value;
    const heure = document.getElementById('quick_heure').value;
    document.getElementById('quick_date_heure').value = date + ' ' + heure + ':00';
    document.getElementById('quickCreateForm').submit();
}

// Auto-refresh toutes les 5 minutes pour le planning en temps réel
let refreshInterval;

function startAutoRefresh() {
    refreshInterval = setInterval(() => {
        // Refresh silencieux de la page
        window.location.reload();
    }, 300000); // 5 minutes
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
}

// Démarrer l'auto-refresh au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
    
    // Arrêter l'auto-refresh si l'utilisateur quitte l'onglet
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });
});
</script>
@endpush
</x-app-layout>