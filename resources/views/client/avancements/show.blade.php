{{-- Fichier: resources/views/client/avancements/show.blade.php --}}

<x-app-layout>
    {{-- Réutilise les mêmes styles que la vue projet.show --}}
    <style>
        .gradient-bg { background: linear-gradient(135deg, #C2185B 0%, #D32F2F 50%, #ef4444 100%); }
        .custom-card { border: none; border-radius: 15px; transition: all 0.3s ease; overflow: hidden; position: relative; backdrop-filter: blur(10px); }
        .custom-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #C2185B, #D32F2F, #ef4444); }
        .custom-card:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(194, 24, 91, 0.15) !important; }
        .page-title { background: linear-gradient(135deg, #C2185B, #D32F2F); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 700; font-size: 2.2rem; }
        .custom-btn-secondary { background: linear-gradient(135deg, #6c757d, #495057); border: none; border-radius: 25px; padding: 10px 25px; color: white; transition: all 0.3s ease; font-weight: 500; }
        .custom-btn-secondary:hover { background: linear-gradient(135deg, #495057, #343a40); transform: scale(1.05); color: white; }
        .container-custom { background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(248,249,250,0.95)); min-height: 100vh; padding: 2rem 0; }
        .breadcrumb-custom { background: rgba(194, 24, 91, 0.05); border-radius: 10px; padding: 10px 15px; }
        .breadcrumb-custom a { color: #C2185B; text-decoration: none; font-weight: 500; }
        .breadcrumb-custom a:hover { color: #D32F2F; text-decoration: underline; }
        .status-badge-custom { border-radius: 20px; padding: 8px 16px; font-weight: 600; font-size: 0.9rem; }
        .status-en-cours { background: linear-gradient(135deg, #C2185B, #D32F2F); }
        .status-termine { background: linear-gradient(135deg, #28a745, #20c997); }
        .status-bloque { background: linear-gradient(135deg, #ffc107, #fd7e14); }
        .status-other { background: linear-gradient(135deg, #6c757d, #495057); }
    </style>

    <div class="container-custom">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="page-title"><i class="fas fa-tasks me-3"></i>Détails de l'avancement</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item">
                                <a href="{{ route('client.projets.index') }}">
                                    <i class="fas fa-home me-1"></i>Mes Projets
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('client.projets.show', $avancement->projet) }}">
                                    <i class="fas fa-chevron-right mx-2"></i>{{ $avancement->projet->titre }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-chevron-right mx-2"></i>Détails
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('client.projets.show', $avancement->projet) }}" class="custom-btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au projet
                    </a>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card custom-card shadow-lg">
                        <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-step-forward me-2 icon-accent"></i>Étape: {{ $avancement->etape }}</h5>
                            <span class="status-badge-custom text-white
                                @if($avancement->statut === 'en cours') status-en-cours
                                @elseif($avancement->statut === 'terminé') status-termine
                                @elseif($avancement->statut === 'bloqué') status-bloque
                                @else status-other
                                @endif">
                                {{ ucfirst($avancement->statut) }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text mb-3">
    <strong><i class="fas fa-align-left me-2"></i>Description:</strong> {!! nl2br(e($avancement->description)) !!}
</p>
                            <p class="card-text mb-3">
                                <strong><i class="fas fa-percentage me-2"></i>Pourcentage:</strong> {{ $avancement->pourcentage }}%
                            </p>
                            
                            @if($avancement->date_prevue)
                                <p class="card-text mb-3">
                                    <strong><i class="fas fa-calendar-alt me-2"></i>Date prévue:</strong> {{ \Carbon\Carbon::parse($avancement->date_prevue)->format('d/m/Y') }}
                                </p>
                            @endif
                            
                            @if($avancement->date_realisee)
                                <p class="card-text mb-3">
                                    <strong><i class="fas fa-calendar-check me-2"></i>Date de réalisation:</strong> {{ \Carbon\Carbon::parse($avancement->date_realisee)->format('d/m/Y') }}
                                </p>
                            @endif

                            @if($avancement->commentaires)
                                <p class="card-text mb-3">
                                    <strong><i class="fas fa-comment-alt me-2"></i>Commentaires:</strong> {{ $avancement->commentaires }}
                                </p>
                            @endif
                            
                            
                               
                                
       
 @if($avancement->fichiers)
                                <hr>
                                <p class="card-text mb-2">
                                    <strong><i class="fas fa-paperclip me-2"></i>Fichier associé:</strong>
                                </p>
                               <a href="{{ route('client.avancements.download', $avancement) }}" target="_blank" class="btn custom-btn">
    <i class="fas fa-download me-2"></i> Télécharger le fichier
</a>
                            @endif

                       
                        </div>

                        <div class="mt-4 pt-4 border-top">
    <h5><i class="fas fa-comment-dots me-2 text-primary"></i>Commentaires du client</h5>
    @if($avancement->commentaires)
        <div class="alert alert-info" role="alert">
            {{-- Display comments with line breaks --}}
            <p class="mb-0">{!! nl2br(e($avancement->commentaires)) !!}</p>
        </div>
    @else
        <p class="text-muted">Aucun commentaire n'a été laissé pour le moment.</p>
    @endif

    <form action="{{ route('client.client.avancements.addComment', $avancement) }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-3">
            <label for="commentaires" class="form-label">Ajouter un nouveau commentaire</label>
            <textarea class="form-control" id="commentaires" name="commentaires" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary custom-btn">
            <i class="fas fa-paper-plane me-2"></i>Envoyer le commentaire
        </button>
    </form>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>