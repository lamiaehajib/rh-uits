<x-app-layout>
    <style>
        :root {
            --primary-color: #C2185B;
            --danger-color: #D32F2F;
            --accent-color: #ef4444;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(194, 24, 91, 0.3);
        }
        
        .card-modern {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            padding: 1.5rem;
            border: none;
        }
        
        .solde-stat-big {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05) 0%, rgba(211, 47, 47, 0.05) 100%);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .solde-stat-big::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--danger-color));
        }
        
        .stat-icon-big {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        
        .icon-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
        }
        
        .icon-danger-gradient {
            background: linear-gradient(135deg, var(--danger-color) 0%, #b71c1c 100%);
            color: white;
        }
        
        .icon-success-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .stat-number-big {
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        
        .progress-modern {
            height: 35px;
            border-radius: 20px;
            background: #f3f4f6;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .progress-bar-gradient {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--danger-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            transition: width 0.6s ease;
        }
        
        .btn-custom-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
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
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-custom:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .table-elegant {
            margin: 0;
        }
        
        .table-elegant thead {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1) 0%, rgba(211, 47, 47, 0.1) 100%);
        }
        
        .table-elegant thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table-elegant tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .table-elegant tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.03) 0%, rgba(211, 47, 47, 0.03) 100%);
            transform: scale(1.01);
        }
        
        .table-elegant tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        .badge-elegant {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .badge-info-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        
        .badge-success-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .info-box {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .info-box h6 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .rule-item {
            display: flex;
            align-items: start;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .rule-item:hover {
            background: rgba(194, 24, 91, 0.05);
        }
        
        .rule-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
        }
        
        .day-badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1) 0%, rgba(211, 47, 47, 0.1) 100%);
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.85rem;
            margin: 0.25rem;
        }
        
        .stats-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 20px rgba(194, 24, 91, 0.3);
        }
        
        .stats-circle-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
        }
        
        .stats-circle-label {
            font-size: 0.75rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }
        
        .alert-warning-custom {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
            border: none;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 1rem 1.5rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #9ca3af;
        }
        
        .empty-state i {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 1rem;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="page-header fade-in-up">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2 fw-bold">
                        <i class="fas fa-chart-pie me-2"></i>
                        Mon Solde de Congés
                    </h2>
                    <p class="mb-0 opacity-75">Gérez et suivez votre solde de congés annuel</p>
                </div>
                <div>
                    <a class="btn btn-outline-custom bg-white me-2" href="{{ route('conges.index') }}">
                        <i class="fas fa-list me-2"></i>Mes demandes
                    </a>
                    <a class="btn btn-custom-primary" href="{{ route('conges.create') }}">
                        <i class="fas fa-plus-circle me-2"></i>Nouvelle demande
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Solde de l'année -->
                <div class="card-modern mb-4 fade-in-up" style="animation-delay: 0.1s;">
                    <div class="card-header-custom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-check me-2"></i>
                            Solde pour l'année {{ $solde->annee }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row text-center mb-4">
                            <div class="col-md-4">
                                <div class="solde-stat-big">
                                    <div class="stat-icon-big icon-primary-gradient">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <h3 class="stat-number-big">{{ $solde->total_jours }}</h3>
                                    <p class="text-muted mb-0 fw-semibold">Total de jours</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="solde-stat-big">
                                    <div class="stat-icon-big icon-danger-gradient">
                                        <i class="fas fa-calendar-times"></i>
                                    </div>
                                    <h3 class="stat-number-big">{{ $solde->jours_utilises }}</h3>
                                    <p class="text-muted mb-0 fw-semibold">Jours utilisés</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="solde-stat-big">
                                    <div class="stat-icon-big icon-success-gradient">
                                        <i class="fas fa-calendar-plus"></i>
                                    </div>
                                    <h3 class="stat-number-big">{{ $solde->jours_restants }}</h3>
                                    <p class="text-muted mb-0 fw-semibold">Jours restants</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                <i class="fas fa-chart-bar" style="color: var(--primary-color);"></i>
                                Utilisation du solde
                            </label>
                            <div class="progress-modern">
                                <div class="progress-bar-gradient" style="width: {{ ($solde->jours_restants / $solde->total_jours) * 100 }}%">
                                    {{ round(($solde->jours_restants / $solde->total_jours) * 100, 1) }}% disponible
                                </div>
                            </div>
                        </div>

                        @if($solde->jours_restants < 5)
                            <div class="alert-warning-custom">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Attention:</strong> Il vous reste moins de 5 jours de congés pour cette année.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historique -->
                <div class="card-modern fade-in-up" style="animation-delay: 0.2s;">
                    <div class="card-header-custom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2"></i>
                            Historique des congés approuvés
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-elegant">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-calendar-alt me-2"></i>Période</th>
                                        <th><i class="fas fa-briefcase me-2"></i>Jours ouvrables</th>
                                        <th><i class="fas fa-check-circle me-2"></i>Statut</th>
                                        <th><i class="fas fa-clock me-2"></i>Traité le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($congesApprouves as $conge)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-calendar text-muted"></i>
                                                    <span>
                                                        {{ $conge->date_debut->format('d/m/Y') }} 
                                                        <i class="fas fa-arrow-right mx-1" style="color: var(--primary-color); font-size: 0.75rem;"></i> 
                                                        {{ $conge->date_fin->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge-elegant badge-info-gradient">
                                                    <i class="fas fa-briefcase me-1"></i>
                                                    {{ $conge->nombre_jours_ouvrables }} jour(s)
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-elegant badge-success-gradient">
                                                    <i class="fas fa-check me-1"></i>
                                                    Approuvé
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fas fa-clock text-muted me-1"></i>
                                                {{ $conge->traite_le ? $conge->traite_le->format('d/m/Y') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="empty-state">
                                                    <i class="fas fa-inbox"></i>
                                                    <h5 class="mt-3">Aucun congé approuvé</h5>
                                                    <p>Vous n'avez pas encore de congés approuvés pour cette année</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Informations -->
                <div class="card-modern mb-4 fade-in-up" style="animation-delay: 0.3s;">
                    <div class="card-header-custom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>
                            Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="info-box">
                            <h6><i class="fas fa-book me-2"></i>Règles de congés</h6>
                            <div class="rule-item">
                                <div class="rule-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>18 jours de congés par an</span>
                            </div>
                            <div class="rule-item">
                                <div class="rule-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>Approbation requise par un administrateur</span>
                            </div>
                            <div class="rule-item">
                                <div class="rule-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>Tous les jours sont comptabilisés (y compris week-ends)</span>
                            </div>
                        </div>

                        <div class="info-box">
                            <h6><i class="fas fa-bed me-2"></i>Vos jours de repos</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                    $repos = is_array(Auth::user()->repos) ? Auth::user()->repos : json_decode(Auth::user()->repos, true);
                                    if (empty($repos)) $repos = [];
                                @endphp
                                @forelse($repos as $jour)
                                    <span class="day-badge">{{ $jour }}</span>
                                @empty
                                    <span class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Aucun jour de repos défini
                                    </span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card-modern fade-in-up" style="animation-delay: 0.4s;">
                    <div class="card-header-custom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-bar me-2"></i>
                            Statistiques
                        </h5>
                    </div>
                    <div class="card-body text-center p-4">
                        <div class="stats-circle">
                            <div class="stats-circle-number">{{ round(($solde->jours_utilises / $solde->total_jours) * 100) }}%</div>
                            <div class="stats-circle-label">Utilisé</div>
                        </div>
                        <div class="progress-modern mb-3" style="height: 12px;">
                            <div class="progress-bar-gradient" style="width: {{ ($solde->jours_utilises / $solde->total_jours) * 100 }}%"></div>
                        </div>
                        <p class="text-muted mb-0">
                            <i class="fas fa-chart-line me-1" style="color: var(--primary-color);"></i>
                            <strong>{{ $solde->jours_utilises }}</strong> jours utilisés sur 
                            <strong>{{ $solde->total_jours }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>