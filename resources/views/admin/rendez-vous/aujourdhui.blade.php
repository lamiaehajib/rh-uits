<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title mb-1">La maintenance sur site  d'aujourd'hui</h3>
                        <small class="text-muted">{{ now()->format('l d F Y') }}</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.rendez-vous.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau
                        </a>
                        <a href="{{ route('admin.rendez-vous.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> Tous
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($rendezVous->count() > 0)
                        <!-- Statistiques rapides -->
                        <div class="row mb-4">
                            <div class="col-sm-6 col-lg-3 mb-2">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h4 class="text-primary mb-1">{{ $rendezVous->count() }}</h4>
                                        <small class="text-muted">Total aujourd'hui</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3 mb-2">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h4 class="text-warning mb-1">{{ $rendezVous->where('statut', 'programmé')->count() }}</h4>
                                        <small class="text-muted">Programmés</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3 mb-2">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <h4 class="text-info mb-1">{{ $rendezVous->where('statut', 'confirmé')->count() }}</h4>
                                        <small class="text-muted">Confirmés</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3 mb-2">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h4 class="text-success mb-1">{{ $rendezVous->where('statut', 'terminé')->count() }}</h4>
                                        <small class="text-muted">Terminés</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline des La maintenance sur site  -->
                        <div class="timeline">
                            @php
                                $currentHour = null;
                                $now = now();
                            @endphp

                            @foreach($rendezVous as $rdv)
                                @php
                                    $rdvHour = $rdv->date_heure->format('H:i');
                                    $isPast = $rdv->date_heure->isPast();
                                    $isUpcoming = $rdv->date_heure->isFuture() && $rdv->date_heure->diffInHours($now) <= 1;
                                @endphp

                                <div class="timeline-item @if($isPast) timeline-past @elseif($isUpcoming) timeline-upcoming @endif">
                                    <div class="timeline-marker">
                                        <div class="timeline-time">{{ $rdvHour }}</div>
                                        <div class="timeline-icon">
                                            @switch($rdv->statut)
                                                @case('programmé')
                                                    <i class="fas fa-clock text-secondary"></i>
                                                    @break
                                                @case('confirmé')
                                                    <i class="fas fa-check-circle text-primary"></i>
                                                    @break
                                                @case('terminé')
                                                    <i class="fas fa-check-double text-success"></i>
                                                    @break
                                                @case('annulé')
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>

                                    <div class="timeline-content">
                                        <div class="card @if($isUpcoming) border-warning @endif">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">{{ $rdv->titre }}</h6>
                                                    <span class="badge 
                                                        @switch($rdv->statut)
                                                            @case('programmé') bg-secondary @break
                                                            @case('confirmé') bg-primary @break
                                                            @case('terminé') bg-success @break
                                                            @case('annulé') bg-danger @break
                                                        @endswitch
                                                    ">{{ ucfirst($rdv->statut) }}</span>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-4">
                                                        <small class="text-muted">
                                                            <i class="fas fa-project-diagram me-1"></i>
                                                            <strong>Projet:</strong> {{ $rdv->projet->titre }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">
                                                            <i class="fas fa-user me-1"></i>
                                                            <strong>Client:</strong>   @forelse($rdv->projet->users as $client)
                                                    <span class="badge bg-info text-dark mb-1 d-block">  @forelse($rdv->projet->users as $client)
                                                    <span class="badge bg-info text-dark mb-1 d-block">{{ $client->name }}</span>
                                                @empty
                                                    <span class="text-muted fst-italic">N/A</span>
                                                @endforelse</span>
                                                @empty
                                                    <span class="text-muted fst-italic">N/A</span>
                                                @endforelse
                                                        </small>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">
                                                            <i class="fas fa-map-marker-alt me-1"></i>
                                                            <strong>Lieu:</strong> {{ $rdv->lieu ?? 'Non défini' }}
                                                        </small>
                                                    </div>
                                                </div>

                                                @if($rdv->description)
                                                    <p class="card-text small text-muted mb-2">
                                                        {{ Str::limit($rdv->description, 100) }}
                                                    </p>
                                                @endif

                                                @if($isUpcoming)
                                                    <div class="alert alert-warning py-2 mb-2">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        <strong>Attention:</strong> La maintenance sur site  dans {{ $rdv->date_heure->diffForHumans() }}
                                                    </div>
                                                @endif

                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.rendez-vous.show', $rdv) }}" 
                                                       class="btn btn-outline-info btn-sm">
                                                        <i class="fas fa-eye"></i> Voir
                                                    </a>
                                                    @if($rdv->statut !== 'terminé' && $rdv->statut !== 'annulé')
                                                        <a href="{{ route('admin.rendez-vous.edit', $rdv) }}" 
                                                           class="btn btn-outline-warning btn-sm">
                                                            <i class="fas fa-edit"></i> Modifier
                                                        </a>
                                                        @if($rdv->statut === 'confirmé')
                                                            <form method="POST" 
                                                                  action="{{ route('admin.rendez-vous.update', $rdv) }}" 
                                                                  class="d-inline">
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
                                                                        class="btn btn-outline-success btn-sm"
                                                                        onclick="return confirm('Marquer comme terminé ?')">
                                                                    <i class="fas fa-check"></i> Terminer
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check fa-4x text-success mb-3"></i>
                            <h5 class="text-success">Aucun La maintenance sur site  aujourd'hui !</h5>
                            <p class="text-muted">Profitez de cette journée libre ou planifiez de nouveaux La maintenance sur site .</p>
                            <a href="{{ route('admin.rendez-vous.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Planifier un La maintenance sur site 
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 0;
}

.timeline-item {
    position: relative;
    display: flex;
    margin-bottom: 2rem;
}

.timeline-marker {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 1rem;
    position: relative;
}

.timeline-time {
    background: #fff;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    min-width: 60px;
    text-align: center;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.timeline-content {
    flex: 1;
}

.timeline-item:not(:last-child) .timeline-marker::after {
    content: '';
    position: absolute;
    top: 70px;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: calc(100% + 2rem);
    background: #dee2e6;
    z-index: -1;
}

.timeline-upcoming .timeline-time {
    background: #fff3cd;
    border-color: #ffc107;
    color: #856404;
}

.timeline-upcoming .timeline-icon {
    border-color: #ffc107;
    background: #fff3cd;
}

.timeline-past {
    opacity: 0.7;
}

.timeline-past .timeline-time {
    background: #f8f9fa;
    color: #6c757d;
}

@media (max-width: 768px) {
    .timeline-item {
        flex-direction: column;
    }
    
    .timeline-marker {
        flex-direction: row;
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .timeline-time {
        margin-right: 1rem;
        margin-bottom: 0;
    }
    
    .timeline-item:not(:last-child) .timeline-marker::after {
        display: none;
    }
}
</style>
@endpush
</x-app-layout>