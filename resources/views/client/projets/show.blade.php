<x-app-layout>
    <style>
        /* Custom styles avec tes couleurs */
        .gradient-bg {
            background: linear-gradient(135deg, #C2185B 0%, #D32F2F 50%, #ef4444 100%);
        }
        
        .custom-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            backdrop-filter: blur(10px);
        }
        
        .custom-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #C2185B, #D32F2F, #ef4444);
        }
        
        .custom-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(194, 24, 91, 0.15) !important;
        }
        
        .page-title {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2.2rem;
        }
        
        .custom-btn {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            color: white;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .custom-btn:hover {
            background: linear-gradient(135deg, #D32F2F, #ef4444);
            transform: scale(1.05);
            color: white;
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
        }
        
        .custom-btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            color: white;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .custom-btn-secondary:hover {
            background: linear-gradient(135deg, #495057, #343a40);
            transform: scale(1.05);
            color: white;
        }
        
        .status-badge-custom {
            border-radius: 20px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .status-en-cours {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }
        
        .status-termine {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        
        .status-attente {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }
        
        .status-other {
            background: linear-gradient(135deg, #6c757d, #495057);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            border-bottom: 2px solid rgba(194, 24, 91, 0.2);
            border-radius: 15px 15px 0 0 !important;
        }
        
        .breadcrumb-custom {
            background: rgba(194, 24, 91, 0.05);
            border-radius: 10px;
            padding: 10px 15px;
        }
        
        .breadcrumb-custom a {
            color: #C2185B;
            text-decoration: none;
            font-weight: 500;
        }
        
        .breadcrumb-custom a:hover {
            color: #D32F2F;
            text-decoration: underline;
        }
        
        .progress-custom {
            height: 30px;
            border-radius: 15px;
            background: rgba(194, 24, 91, 0.1);
            overflow: hidden;
        }
        
        .progress-bar-custom {
            background: linear-gradient(135deg, #C2185B, #D32F2F, #ef4444);
            border-radius: 15px;
            transition: all 0.8s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .list-group-item-custom {
            border: none;
            border-radius: 10px;
            margin-bottom: 10px;
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248,249,250,0.9));
            border-left: 4px solid #C2185B;
            transition: all 0.3s ease;
        }
        
        .list-group-item-custom:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(194, 24, 91, 0.1);
        }
        
        .info-card {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            border-radius: 15px;
            border: 1px solid rgba(194, 24, 91, 0.1);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .icon-accent {
            color: #C2185B;
        }
        
        .container-custom {
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(248,249,250,0.95));
            min-height: 100vh;
            padding: 2rem 0;
        }
    </style>

    <div class="container-custom">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="page-title"><i class="fas fa-project-diagram me-3"></i>{{ $projet->titre }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item">
                                <a href="{{ route('client.projets.index') }}">
                                    <i class="fas fa-home me-1"></i>Mes Projets
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-chevron-right mx-2"></i>{{ $projet->titre }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('client.projets.index') }}" class="custom-btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card custom-card shadow-lg mb-4">
                        <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2 icon-accent"></i>Informations du Projet</h5>
                            <span class="status-badge-custom text-white
                                @if($projet->statut_projet === 'en cours') status-en-cours
                                @elseif($projet->statut_projet === 'terminé') status-termine
                                @elseif($projet->statut_projet === 'en attente') status-attente
                                @else status-other
                                @endif">
                                <i class="fas fa-flag me-1"></i>{{ ucfirst($projet->statut_projet) }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <p><i class="fas fa-align-left me-2 icon-accent"></i><strong>Description:</strong> {{ $projet->description ?? 'Aucune description' }}</p>
                            <p><i class="fas fa-calendar-plus me-2 icon-accent"></i><strong>Date de début:</strong> {{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}</p>
                            @if($projet->date_fin)
                                <p><i class="fas fa-calendar-check me-2 icon-accent"></i><strong>Date de fin prévue:</strong> {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}</p>
                            @endif
                            @if($projet->fichier)
                                <hr>
                               <a href="{{ route('admin.projets.download', $projet) }}" class="btn custom-btn">
    <i class="fas fa-download me-2"></i> Télécharger le fichier du projet
</a>
                            @endif
                        </div>
                    </div>

                    <div class="card custom-card shadow-lg mb-4">
                        <div class="card-header card-header-custom">
                            <h5 class="mb-0"><i class="fas fa-chart-line me-2 icon-accent"></i>Avancement du Projet ({{ number_format($pourcentageGlobal, 1) }}%)</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="progress progress-custom mb-4">
                                <div class="progress-bar progress-bar-custom
                                    @if($pourcentageGlobal < 30) 
                                    @elseif($pourcentageGlobal < 70) 
                                    @else 
                                    @endif" 
                                    role="progressbar" 
                                    style="width: {{ $pourcentageGlobal }}%"
                                    aria-valuenow="{{ $pourcentageGlobal }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                    <i class="fas fa-bolt me-2"></i>{{ number_format($pourcentageGlobal, 1) }}%
                                </div>
                            </div>

                             <h6><i class="fas fa-tasks me-2 icon-accent"></i>Détails des avancements:</h6>
                            <div class="list-group">
                                @forelse($projet->avancements as $avancement)
                                    <div class="list-group-item list-group-item-custom d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fas fa-step-forward me-2 icon-accent"></i>{{ $avancement->etape }} 
                                                <span class="badge status-badge-custom status-en-cours float-end">
                                                    <i class="fas fa-percentage me-1"></i>{{ $avancement->pourcentage }}%
                                                </span>
                                            </h6>
                                            <p class="mb-1 text-muted small"><i class="fas fa-comment-dots me-1"></i>{{ Str::limit($avancement->description, 50) }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>Mise à jour le {{ $avancement->updated_at->format('d/m/Y à H:i') }}
                                            </small>
                                        </div>
                                        <div>
                                            {{-- Bouton "Voir" --}}
                                            <a href="{{ route('client.avancements.show', $avancement) }}" class="btn custom-btn-secondary btn-sm">
                                                <i class="fas fa-eye me-1"></i> Voir
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                                        <p class="text-muted fst-italic">Aucune étape d'avancement enregistrée.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card shadow-lg mb-4">
                        <div class="card-header card-header-custom">
                            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2 icon-accent"></i>La maintenance sur site  programmés</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="list-group">
                                @forelse($projet->rendezVous as $rdv)
                                    <div class="list-group-item list-group-item-custom">
                                        <h6 class="mb-1">
                                            <i class="fas fa-handshake me-2 icon-accent"></i>{{ $rdv->titre }} 
                                            <span class="badge status-badge-custom status-en-cours float-end">
                                                <i class="fas fa-dot-circle me-1"></i>{{ ucfirst($rdv->statut) }}
                                            </span>
                                        </h6>
                                        <p class="mb-1 text-muted small"><i class="fas fa-comment me-1"></i>{{ $rdv->description }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>Date: {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted fst-italic">Aucun La maintenance sur site  programmé pour ce projet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card custom-card info-card shadow-lg mb-4">
                        <div class="card-header card-header-custom">
                            <h5 class="mb-0"><i class="fas fa-chart-pie me-2 icon-accent"></i>Actions et Statistiques</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h6><i class="fas fa-hourglass-half me-2 icon-accent"></i>Jours restants</h6>
                                <p class="stats-number mb-0">
                                    @if($projet->date_fin)
                                        <i class="fas fa-calendar-day me-2"></i>{{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($projet->date_fin)) }}
                                    @else
                                        <span class="text-muted"><i class="fas fa-question-circle me-2"></i>N/A</span>
                                    @endif
                                </p>
                                <small class="text-muted">jours</small>
                            </div>
                            <hr>
                            <h6><i class="fas fa-user-circle me-2 icon-accent"></i>Informations client</h6>
                            <div class="mt-3">
                                <p class="mb-2">
                                    <i class="fas fa-user me-2 icon-accent"></i><strong>Nom:</strong> {{ $projet->client->name }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-envelope me-2 icon-accent"></i><strong>Email:</strong> {{ $projet->client->email }}
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-phone me-2 icon-accent"></i><strong>Téléphone:</strong> {{ $projet->client->tele ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>