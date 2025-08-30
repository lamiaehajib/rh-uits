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
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(194, 24, 91, 0.15) !important;
        }
        
        .page-title {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 2rem;
        }
        
        .custom-btn {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            color: white;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .custom-btn:hover {
            background: linear-gradient(135deg, #D32F2F, #ef4444);
            transform: scale(1.05);
            color: white;
        }
        
        .status-badge-custom {
            border-radius: 20px;
            padding: 6px 15px;
            font-weight: 500;
            font-size: 0.85rem;
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
        
        .empty-state {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            border-radius: 20px;
            padding: 3rem;
            border: 2px dashed rgba(194, 24, 91, 0.2);
        }
        
        .empty-icon {
            color: #C2185B;
            margin-bottom: 1.5rem;
        }
        
        .card-title-custom {
            color: #C2185B;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .date-info {
            background: rgba(194, 24, 91, 0.1);
            border-radius: 10px;
            padding: 4px 10px;
            font-size: 0.8rem;
            color: #C2185B;
            font-weight: 500;
        }
        
        .container-custom {
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248,249,250,0.9));
            min-height: 100vh;
            padding: 2rem 0;
        }
    </style>

    <div class="container-custom">
        <div class="container-fluid">
                                <h1 class="page-title"><i class="fas fa-rocket me-3"></i>Mes Projets</h1>
            
            @if($projets->count() > 0)
                <div class="row">
                    @foreach($projets as $projet)
                        <div class="col-lg-6 mb-4">
                            <div class="card custom-card shadow-lg">
                                <div class="card-body p-4">
                                    <h5 class="card-title-custom"><i class="fas fa-project-diagram me-2"></i>{{ $projet->titre }}</h5>
                                    <p class="card-text text-muted mb-3">{{ Str::limit($projet->description, 100) }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <div class="d-flex flex-column gap-2">
                                            <span class="status-badge-custom text-white
                                                @if($projet->statut_projet === 'en cours') status-en-cours
                                                @elseif($projet->statut_projet === 'terminé') status-termine
                                                @elseif($projet->statut_projet === 'en attente') status-attente
                                                @else status-other
                                                @endif">
                                                {{ ucfirst($projet->statut_projet) }}
                                            </span>
                                            @if($projet->date_fin)
                                                <div class="date-info">
                                                    <i class="fas fa-calendar-alt me-1"></i>Fin prévue: {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('client.projets.show', $projet) }}" class="custom-btn">
                                            <i class="fas fa-eye me-2"></i>Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state text-center">
                    <i class="fas fa-folder-open empty-icon fa-5x mb-4"></i>
                    <h4 class="text-gray-700 mb-3"><i class="fas fa-info-circle me-2"></i>Aucun projet pour le moment</h4>
                    <p class="text-muted"><i class="fas fa-comments me-2"></i>Tes futurs projets apparaîtront ici. Contacte l'administrateur pour plus d'infos !</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>