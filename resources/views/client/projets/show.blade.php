<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">{{ $projet->titre }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.projets.index') }}">Mes Projets</a></li>
                        <li class="breadcrumb-item active">{{ $projet->titre }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('client.projets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informations du Projet</h5>
                        <span class="badge 
                            @if($projet->statut_projet === 'en cours') bg-primary
                            @elseif($projet->statut_projet === 'terminé') bg-success
                            @elseif($projet->statut_projet === 'en attente') bg-warning
                            @else bg-danger
                            @endif
                            text-white">
                            {{ ucfirst($projet->statut_projet) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <p><strong>Description:</strong> {{ $projet->description ?? 'Aucune description' }}</p>
                        <p><strong>Date de début:</strong> {{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}</p>
                        @if($projet->date_fin)
                            <p><strong>Date de fin prévue:</strong> {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}</p>
                        @endif
                        @if($projet->fichier)
                            <hr>
                            <a href="{{ Storage::disk('public')->url($projet->fichier) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download"></i> Télécharger le fichier du projet
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Avancement du Projet ({{ number_format($pourcentageGlobal, 1) }}%)</h5>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-4" style="height: 25px;">
                            <div class="progress-bar 
                                @if($pourcentageGlobal < 30) bg-danger
                                @elseif($pourcentageGlobal < 70) bg-warning
                                @else bg-success
                                @endif" 
                                role="progressbar" 
                                style="width: {{ $pourcentageGlobal }}%"
                                aria-valuenow="{{ $pourcentageGlobal }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ number_format($pourcentageGlobal, 1) }}%
                            </div>
                        </div>

                        <h6>Détails des avancements:</h6>
                        <div class="list-group">
                            @forelse($projet->avancements as $avancement)
                                <div class="list-group-item">
                                    <h6 class="mb-1">{{ $avancement->etape }} <span class="badge bg-primary float-end">{{ $avancement->pourcentage }}%</span></h6>
                                    <p class="mb-1 text-muted small">{{ $avancement->description }}</p>
                                    <small class="text-muted">Mise à jour le {{ $avancement->updated_at->format('d/m/Y à H:i') }}</small>
                                </div>
                            @empty
                                <p class="text-muted fst-italic">Aucune étape d'avancement enregistrée.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Rendez-vous programmés</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($projet->rendezVous as $rdv)
                                <div class="list-group-item">
                                    <h6 class="mb-1">{{ $rdv->titre }} <span class="badge bg-primary float-end">{{ ucfirst($rdv->statut) }}</span></h6>
                                    <p class="mb-1 text-muted small">{{ $rdv->description }}</p>
                                    <small class="text-muted">Date: {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y à H:i') }}</small>
                                </div>
                            @empty
                                <p class="text-muted fst-italic">Aucun rendez-vous programmé pour ce projet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Actions et Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <h6>Jours restants</h6>
                        <p class="h4 mb-0 text-info">
                            @if($projet->date_fin)
                                {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($projet->date_fin)) }} jours
                            @else
                                <span class="text-muted">Non calculable</span>
                            @endif
                        </p>
                        <hr>
                        <h6>Informations client</h6>
                        <p class="mb-1"><strong>Nom:</strong> {{ $projet->client->name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $projet->client->email }}</p>
                        <p class="mb-0"><strong>Téléphone:</strong> {{ $projet->client->tele ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
