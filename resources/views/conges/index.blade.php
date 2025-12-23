<x-app-layout>
    <style>
        :root {
            --primary-color: #C2185B;
            --danger-color: #D32F2F;
            --accent-color: #ef4444;
        }
        
        .gradient-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(194, 24, 91, 0.3);
        }
        
        .stats-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            position: relative;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--danger-color));
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(194, 24, 91, 0.2);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 1rem;
        }
        
        .icon-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .icon-danger { background: linear-gradient(135deg, var(--danger-color) 0%, #b71c1c 100%); color: white; }
        .icon-primary { background: linear-gradient(135deg, var(--primary-color) 0%, #880e4f 100%); color: white; }
        .icon-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
        
        .btn-custom-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(194, 24, 91, 0.3);
        }
        
        .btn-custom-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(194, 24, 91, 0.4);
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: white;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-custom:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .table-modern {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .table-modern thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
        }
        
        .table-modern thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table-modern tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .table-modern tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.05) 0%, rgba(211, 47, 47, 0.05) 100%);
            transform: scale(1.01);
        }
        
        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .badge-waiting {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
        }
        
        .badge-approved {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .badge-rejected {
            background: linear-gradient(135deg, var(--danger-color) 0%, #b71c1c 100%);
            color: white;
        }
        
        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-action:hover {
            transform: scale(1.1);
        }
        
        .btn-view {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }
        
        .empty-state i {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 1rem;
        }
        
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .alert-success-custom {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
            border-left: 4px solid #10b981;
            color: #047857;
        }
        
        .alert-danger-custom {
            background: linear-gradient(135deg, rgba(211, 47, 47, 0.1) 0%, rgba(183, 28, 28, 0.1) 100%);
            border-left: 4px solid var(--danger-color);
            color: var(--danger-color);
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header avec gradient -->
        <div class="gradient-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2 fw-bold">
                        <i class="fas fa-umbrella-beach me-2"></i>
                        Gestion des Congés
                    </h2>
                    <p class="mb-0 opacity-75">Gérez vos demandes de congés en toute simplicité</p>
                </div>
                <div>
                    @if(!Auth::user()->hasRole('Client'))
                        <a href="{{ route('conges.solde') }}" class="btn btn-outline-custom bg-white me-2">
                            <i class="fas fa-chart-pie me-2"></i>Mon Solde
                        </a>
                        <a href="{{ route('conges.create') }}" class="btn btn-custom-primary">
                            <i class="fas fa-plus-circle me-2"></i>Nouvelle Demande
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alertes -->
        @if(session('success'))
            <div class="alert alert-success-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Succès!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Erreur!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistiques -->
        @if(!Auth::user()->hasRole('Client'))
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="card-body text-center p-4">
                            <div class="stats-icon icon-success mx-auto">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3 class="mb-1 fw-bold">{{ $solde->total_jours }}</h3>
                            <p class="text-muted mb-0">Total de jours</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="card-body text-center p-4">
                            <div class="stats-icon icon-danger mx-auto">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h3 class="mb-1 fw-bold">{{ $solde->jours_utilises }}</h3>
                            <p class="text-muted mb-0">Jours utilisés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="card-body text-center p-4">
                            <div class="stats-icon icon-primary mx-auto">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <h3 class="mb-1 fw-bold">{{ $solde->jours_restants }}</h3>
                            <p class="text-muted mb-0">Jours restants</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="card-body text-center p-4">
                            <div class="stats-icon icon-warning mx-auto">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <h3 class="mb-1 fw-bold">{{ $conges->where('statut', 'en_attente')->count() }}</h3>
                            <p class="text-muted mb-0">En attente</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Table des congés -->
        <div class="table-modern">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            @if(Auth::user()->hasRole(['Custom_Admin', 'Sup_Admin']))
                                <th><i class="fas fa-user me-2"></i>Employé</th>
                            @endif
                            <th><i class="fas fa-calendar-day me-2"></i>Date Début</th>
                            <th><i class="fas fa-calendar-day me-2"></i>Date Fin</th>
                            <th><i class="fas fa-hashtag me-2"></i>Jours Demandés</th>
                            <th><i class="fas fa-briefcase me-2"></i>Jours Ouvrables</th>
                            <th><i class="fas fa-info-circle me-2"></i>Statut</th>
                            <th><i class="fas fa-clock me-2"></i>Date Demande</th>
                            <th><i class="fas fa-cog me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conges as $conge)
                            <tr>
                                @if(Auth::user()->hasRole(['Custom_Admin', 'Sup_Admin']))
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $conge->user->name }}</strong>
                                                <small class="text-muted">{{ $conge->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                <td><i class="fas fa-calendar text-muted me-2"></i>{{ $conge->date_debut->format('d/m/Y') }}</td>
                                <td><i class="fas fa-calendar text-muted me-2"></i>{{ $conge->date_fin->format('d/m/Y') }}</td>
                                <td><span class="badge bg-secondary">{{ $conge->nombre_jours_demandes }} jours</span></td>
                                <td><span class="badge bg-info">{{ $conge->nombre_jours_ouvrables }} jours</span></td>
                                <td>
                                    @if($conge->statut == 'en_attente')
                                        <span class="badge-custom badge-waiting">
                                            <i class="fas fa-clock"></i> En attente
                                        </span>
                                    @elseif($conge->statut == 'approuve')
                                        <span class="badge-custom badge-approved">
                                            <i class="fas fa-check-circle"></i> Approuvé
                                        </span>
                                    @else
                                        <span class="badge-custom badge-rejected">
                                            <i class="fas fa-times-circle"></i> Refusé
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $conge->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('conges.show', $conge) }}" class="btn-action btn-view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-folder-open"></i>
                                        <h5 class="mt-3">Aucune demande de congé</h5>
                                        <p>Commencez par créer votre première demande de congé</p>
                                        @if(!Auth::user()->hasRole('Client'))
                                            <a href="{{ route('conges.create') }}" class="btn btn-custom-primary mt-3">
                                                <i class="fas fa-plus-circle me-2"></i>Créer une demande
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($conges->hasPages())
                <div class="p-3 border-top">
                    {{ $conges->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
    </style>
</x-app-layout>