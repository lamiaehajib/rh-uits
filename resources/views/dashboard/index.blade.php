<x-app-layout>
    <style>
        /* الكود CSS اللي عندك بالضبط (ما غيرتش فيه شي حاجة) */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .gradient-red { background: linear-gradient(135deg, #C2185B, #D32F2F); }
        .gradient-blue { background: linear-gradient(135deg, #1976D2, #2196F3); }
        .gradient-green { background: linear-gradient(135deg, #388E3C, #4CAF50); }
        .gradient-orange { background: linear-gradient(135deg, #F57C00, #FF9800); }
        .gradient-purple { background: linear-gradient(135deg, #7B1FA2, #9C27B0); }
        .gradient-teal { background: linear-gradient(135deg, #00796B, #009688); }

        .stat-card {
            background: white; border-radius: 12px; padding: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07); transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease; position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; right: 0; width: 100px; height: 100px;
            background: rgba(255, 255, 255, 0.1); border-radius: 50%;
            transform: translate(30%, -30%);
        }
        .stat-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15); }
        .stat-card-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white; margin-bottom: 16px; }
        .stat-card-value { font-size: 32px; font-weight: bold; margin: 12px 0 8px 0; color: #2c3e50; }
        .stat-card-label { color: #7f8c8d; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-top: 8px; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }

        .chart-container { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07); animation: fadeInUp 0.8s ease; margin-bottom: 24px; }
        .chart-title { font-size: 18px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .chart-title i { background: linear-gradient(135deg, #C2185B, #D32F2F); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .activity-list { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07); animation: slideInRight 0.8s ease; }
        .activity-item { padding: 16px; border-bottom: 1px solid #ecf0f1; transition: all 0.3s ease; }
        .activity-item:last-child { border-bottom: none; }
        .activity-item:hover { background: #f8f9fa; padding-left: 20px; }
        .activity-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; }
        .activity-time { color: #95a5a6; font-size: 12px; }

        .alert-box { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07); margin-bottom: 24px; border-left: 4px solid; animation: fadeInUp 1s ease; }
        .alert-box.alert-danger { border-color: #D32F2F; }
        .alert-box.alert-warning { border-color: #FF9800; }
        .alert-box.alert-info { border-color: #2196F3; }
        .alert-item { padding: 12px; margin-bottom: 8px; background: #f8f9fa; border-radius: 6px; display: flex; align-items: center; gap: 12px; transition: all 0.3s ease; }
        .alert-item:hover { background: #e9ecef; transform: translateX(5px); }

        .performer-card { background: white; border-radius: 8px; padding: 16px; margin-bottom: 12px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); display: flex; align-items: center; gap: 16px; transition: all 0.3s ease; }
        .performer-card:hover { transform: translateX(10px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        .performer-rank { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 18px; }
        .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); }
        .rank-2 { background: linear-gradient(135deg, #C0C0C0, #808080); }
        .rank-3 { background: linear-gradient(135deg, #CD7F32, #8B4513); }
        .rank-other { background: linear-gradient(135deg, #7B1FA2, #9C27B0); }

        .progress-bar-custom { height: 8px; background: #ecf0f1; border-radius: 4px; overflow: hidden; margin-top: 8px; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #C2185B, #D32F2F); border-radius: 4px; transition: width 1s ease; }

        .loading-spinner { display: inline-block; width: 20px; height: 20px; border: 3px solid rgba(211, 47, 47, 0.1); border-radius: 50%; border-top-color: #D32F2F; animation: spin 1s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .section-header { margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid #ecf0f1; }
        .section-title { font-size: 24px; font-weight: bold; background: linear-gradient(135deg, #C2185B, #D32F2F); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block; }

        .quick-action-btn { background: white; border: 2px solid #ecf0f1; border-radius: 8px; padding: 16px; text-align: center; transition: all 0.3s ease; cursor: pointer; }
        .quick-action-btn:hover { border-color: #D32F2F; transform: translateY(-4px); box-shadow: 0 8px 16px rgba(211, 47, 47, 0.2); }
        .quick-action-icon { font-size: 32px; background: linear-gradient(135deg, #C2185B, #D32F2F); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 8px; }

        @media (max-width: 768px) {
            .stat-card-value { font-size: 24px; }
            .chart-container { padding: 16px; }
            .performer-card { flex-direction: column; text-align: center; }
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="section-title mb-2">
                    <i class="fas fa-chart-line"></i> Dashboard
                </h1>
                <p style="color: #7f8c8d; font-size: 14px;">
                    <i class="far fa-calendar"></i> {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                </p>
            </div>
        </div>

        <!-- Quick Actions (اختياري لكن يعطي لمسة حلوة) -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="quick-action-btn" onclick="location.href='{{ route('taches.create') }}'">
                    <div class="quick-action-icon"><i class="fas fa-plus-circle"></i></div>
                    <div>Nouvelle Tâche</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="quick-action-btn" onclick="location.href='{{ route('admin.projets.create') }}'">
                    <div class="quick-action-icon"><i class="fas fa-project-diagram"></i></div>
                    <div>Nouveau Projet</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="quick-action-btn" onclick="location.href='{{ route('admin.rendez-vous.create') }}'">
                    <div class="quick-action-icon"><i class="fas fa-calendar-plus"></i></div>
                    <div>Rendez-vous</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('dashboard.export') }}" class="quick-action-btn d-block text-decoration-none text-dark">
                    <div class="quick-action-icon"><i class="fas fa-file-export"></i></div>
                    <div>Exporter Stats</div>
                </a>
            </div>
        </div>

        <!-- Admin Stats -->
        @if($isAdmin)
        <div class="row mb-4">
            @if($stats['users'])
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-icon gradient-red"><i class="fas fa-users"></i></div>
                    <div class="stat-card-value">{{ $stats['users']['total'] }}</div>
                    <div class="stat-card-label">Total Utilisateurs</div>
                    <div class="mt-3">
                        <span class="badge-success stat-card-badge"><i class="fas fa-check-circle"></i> {{ $stats['users']['active'] }} Actifs</span>
                        <span class="badge-danger stat-card-badge ms-2"><i class="fas fa-times-circle"></i> {{ $stats['users']['inactive'] }} Inactifs</span>
                    </div>
                </div>
            </div>
            @endif

            @if($stats['clients'])
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-icon gradient-blue"><i class="fas fa-user-tie"></i></div>
                    <div class="stat-card-value">{{ $stats['clients']['total'] }}</div>
                    <div class="stat-card-label">Total Clients</div>
                    <div class="mt-3">
                        <span class="badge-info stat-card-badge"><i class="fas fa-user"></i> {{ $stats['clients']['particuliers'] }} Particuliers</span>
                        <span class="badge-warning stat-card-badge ms-2"><i class="fas fa-building"></i> {{ $stats['clients']['entreprises'] }} Entreprises</span>
                    </div>
                </div>
            </div>
            @endif

            @if($stats['projets'])
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-icon gradient-purple"><i class="fas fa-project-diagram"></i></div>
                    <div class="stat-card-value">{{ $stats['projets']['total'] }}</div>
                    <div class="stat-card-label">Total Projets</div>
                    <div class="mt-3">
                        <span class="badge-warning stat-card-badge"><i class="fas fa-spinner"></i> {{ $stats['projets']['en_cours'] }} En cours</span>
                        <span class="badge-success stat-card-badge ms-2"><i class="fas fa-check"></i> {{ $stats['projets']['termine'] }} Terminés</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Pointages, Tâches, Objectifs -->
        <div class="row mb-4">
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-icon gradient-green"><i class="fas fa-clock"></i></div>
                    <div class="stat-card-value">{{ $stats['pointages']['total_this_month'] }}</div>
                    <div class="stat-card-label">Pointages ce mois</div>
                    <div class="mt-3">
                        <span class="badge-danger stat-card-badge"><i class="fas fa-exclamation-triangle"></i> {{ $stats['pointages']['retards'] }} Retards</span>
                        <span class="badge-success stat-card-badge ms-2">{{ $stats['pointages']['taux_ponctualite'] }}% Ponctuel</span>
                    </div>
                    <div class="mt-2"><small style="color: #7f8c8d;"><i class="far fa-clock"></i> Temps moyen: {{ $stats['pointages']['temps_moyen_heures'] }}h</small></div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-icon gradient-orange"><i class="fas fa-tasks"></i></div>
                    <div class="stat-card-value">{{ $stats['taches']['total'] }}</div>
                    <div class="stat-card-label">Total Tâches</div>
                    <div class="mt-3">
                        <span class="badge-info stat-card-badge"><i class="fas fa-hourglass-start"></i> {{ $stats['taches']['nouveau'] }} Nouveau</span>
                        <span class="badge-warning stat-card-badge ms-2"><i class="fas fa-spinner"></i> {{ $stats['taches']['en_cours'] }} En cours</span>
                        <span class="badge-success stat-card-badge ms-2"><i class="fas fa-check"></i> {{ $stats['taches']['termine'] }} Terminé</span>
                    </div>
                    <div class="progress-bar-custom mt-3">
                        <div class="progress-fill" style="width: {{ $stats['taches']['taux_completion'] }}%"></div>
                    </div>
                    <small style="color: #7f8c8d;">{{ $stats['taches']['taux_completion'] }}% Complété</small>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-icon gradient-teal"><i class="fas fa-bullseye"></i></div>
                    <div class="stat-card-value">{{ $stats['objectifs']['total'] }}</div>
                    <div class="stat-card-label">Total Objectifs</div>
                    <div class="mt-3">
                        <span class="badge-warning stat-card-badge"><i class="fas fa-hourglass-half"></i> {{ $stats['objectifs']['in_progress'] }} En cours</span>
                        <span class="badge-success stat-card-badge ms-2"><i class="fas fa-trophy"></i> {{ $stats['objectifs']['completed'] }} Complétés</span>
                    </div>
                    <div class="progress-bar-custom mt-3">
                        <div class="progress-fill" style="width: {{ $stats['objectifs']['avg_progress'] }}%"></div>
                    </div>
                    <small style="color: #7f8c8d;">Progression moyenne: {{ $stats['objectifs']['avg_progress'] }}%</small>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if($alerts['retards']->count() > 0 || $alerts['taches_overdue']->count() > 0 || $alerts['objectifs_incomplets']->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="section-title mb-3"><i class="fas fa-exclamation-triangle"></i> Alertes</h4>
            </div>

            @if($alerts['retards']->count() > 0)
            <div class="col-lg-4 mb-4">
                <div class="alert-box alert-danger">
                    <h6 style="color: #D32F2F; font-weight: 600; margin-bottom: 16px;">
                        <i class="fas fa-user-clock"></i> Retards fréquents (>3)
                    </h6>
                    @foreach($alerts['retards']->take(5) as $retard)
                    <div class="alert-item">
                        <div class="activity-icon gradient-red"><i class="fas fa-user"></i></div>
                        <div class="flex-grow-1">
                            <strong>{{ $retard->user->name ?? 'N/A' }}</strong><br>
                            <small style="color: #7f8c8d;">{{ $retard->retards_count }} retards</small>
                        </div>
                        <span class="badge-danger stat-card-badge">{{ $retard->retards_count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($alerts['taches_overdue']->count() > 0)
            <div class="col-lg-4 mb-4">
                <div class="alert-box alert-warning">
                    <h6 style="color: #FF9800; font-weight: 600; margin-bottom: 16px;">
                        <i class="fas fa-exclamation-circle"></i> Tâches en retard
                    </h6>
                    @foreach($alerts['taches_overdue']->take(5) as $tache)
                    <div class="alert-item">
                        <div class="activity-icon gradient-orange"><i class="fas fa-tasks"></i></div>
                        <div class="flex-grow-1">
                            <strong>{{ Str::limit($tache->titre, 40) }}</strong><br>
                            <small style="color: #7f8c8d;">Deadline: {{ $tache->date_fin_prevue ? \Carbon\Carbon::parse($tache->date_fin_prevue)->format('d/m/Y') : 'N/A' }}</small>
                        </div>
                        <a href="{{ route('taches.show', $tache->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-eye"></i></a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($alerts['objectifs_incomplets']->count() > 0)
            <div class="col-lg-4 mb-4">
                <div class="alert-box alert-info">
                    <h6 style="color: #2196F3; font-weight: 600; margin-bottom: 16px;">
                        <i class="fas fa-bullseye"></i> Objectifs en retard
                    </h6>
                    @foreach($alerts['objectifs_incomplets']->take(5) as $objectif)
                    <div class="alert-item">
                        <div class="activity-icon gradient-teal"><i class="fas fa-bullseye"></i></div>
                        <div class="flex-grow-1">
                            <strong>{{ Str::limit($objectif->titre, 40) }}</strong><br>
                            <small style="color: #7f8c8d;">Échéance: {{ \Carbon\Carbon::parse($objectif->date)->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="section-title mb-3"><i class="fas fa-chart-bar"></i> Statistiques Visuelles</h4>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-calendar-alt"></i> <span>Évolution Pointages (6 mois)</span></div>
                    <canvas id="pointagesMonthlyChart" height="250"></canvas>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-tasks"></i> <span>Répartition des Tâches</span></div>
                    <canvas id="tachesStatusChart" height="250"></canvas>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-bullseye"></i> <span>Progression Objectifs</span></div>
                    <canvas id="objectifsProgressChart" height="250"></canvas>
                </div>
            </div>

            @if($isAdmin && $chartData['projets_status'])
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-project-diagram"></i> <span>Statut des Projets</span></div>
                    <canvas id="projetsStatusChart" height="250"></canvas>
                </div>
            </div>
            @endif

            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-chart-line"></i> <span>Tendance Retards (4 semaines)</span></div>
                    <canvas id="retardsTrendChart" height="250"></canvas>
                </div>
            </div>

            @if($isAdmin && $chartData['users_performance'])
            <div class="col-lg-12 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-trophy"></i> <span>Top 10 Performers (Tâches terminées ce mois)</span></div>
                    <canvas id="usersPerformanceChart" height="120"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- Activities & Top Performers -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="activity-list">
                    <h5 class="chart-title"><i class="fas fa-history"></i> <span>Activités Récentes</span></h5>

                    @if($recentActivities['taches']->count() > 0)
                    <h6 style="color: #7f8c8d; font-size: 14px; margin: 20px 0 12px 0;"><i class="fas fa-tasks"></i> Dernières Tâches</h6>
                    @foreach($recentActivities['taches'] as $tache)
                    <div class="activity-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="activity-icon gradient-orange"><i class="fas fa-tasks"></i></div>
                            <div class="flex-grow-1">
                                <strong>{{ Str::limit($tache->titre, 50) }}</strong><br>
<small style="color: #7f8c8d;">
    Assigné à: {{ collect($tache->users)->pluck('name')->implode(', ') }}
</small>                            </div>
                            <div class="text-end">
                                <span class="badge-{{ $tache->status == 'termine' ? 'success' : ($tache->status == 'en cours' ? 'warning' : 'info') }} stat-card-badge">
                                    {{ ucfirst(str_replace('_', ' ', $tache->status)) }}
                                </span>
                                <div class="activity-time mt-1">{{ $tache->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                    @if($recentActivities['pointages']->count() > 0)
                    <h6 style="color: #7f8c8d; font-size: 14px; margin: 20px 0 12px 0;"><i class="fas fa-clock"></i> Derniers Pointages</h6>
                    @foreach($recentActivities['pointages'] as $pointage)
                    <div class="activity-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="activity-icon gradient-green"><i class="fas fa-sign-in-alt"></i></div>
                            <div class="flex-grow-1">
                                <strong>{{ $pointage->user->name }}</strong><br>
                                <small style="color: #7f8c8d;">
                                    Arrivée: {{ $pointage->heure_arrivee ? \Carbon\Carbon::parse($pointage->heure_arrivee)->format('H:i') : '-' }}
                                    @if($pointage->heure_depart) / Départ: {{ \Carbon\Carbon::parse($pointage->heure_depart)->format('H:i') }} @endif
                                </small>
                            </div>
                            <div class="activity-time">{{ \Carbon\Carbon::parse($pointage->date_pointage)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                    @if($isAdmin && $recentActivities['projets'] && $recentActivities['projets']->count() > 0)
                    <h6 style="color: #7f8c8d; font-size: 14px; margin: 20px 0 12px 0;"><i class="fas fa-project-diagram"></i> Projets Récents</h6>
                    @foreach($recentActivities['projets'] as $projet)
                    <div class="activity-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="activity-icon gradient-purple"><i class="fas fa-project-diagram"></i></div>
                            <div class="flex-grow-1">
                                <strong>{{ Str::limit($projet->titre ?? $projet->nom, 50) }}</strong><br>
                                <small style="color: #7f8c8d;">Clients: {{ $projet->users->pluck('name')->join(', ') }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge-{{ $projet->statut_projet == 'terminé' ? 'success' : ($projet->statut_projet == 'en cours' ? 'warning' : 'info') }} stat-card-badge">
                                    {{ ucfirst($projet->statut_projet) }}
                                </span>
                                <div class="activity-time mt-1">{{ $projet->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                    @if($isAdmin && $recentActivities['rendezvous'] && $recentActivities['rendezvous']->count() > 0)
                    <h6 style="color: #7f8c8d; font-size: 14px; margin: 20px 0 12px 0;"><i class="fas fa-calendar-check"></i> Prochains Rendez-vous</h6>
                    @foreach($recentActivities['rendezvous'] as $rdv)
                    <div class="activity-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="activity-icon gradient-blue"><i class="fas fa-handshake"></i></div>
                            <div class="flex-grow-1">
                                <strong>{{ $rdv->titre ?? 'Rendez-vous' }}</strong><br>
                                <small style="color: #7f8c8d;">
                                    Avec: {{ $rdv->projet->users->pluck('name')->join(', ') }}
                                    - {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y à H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>

            <!-- Top Performers -->
            @if($isAdmin && $topPerformers && $topPerformers->count() > 0)
            <div class="col-lg-4 mb-4">
                <div class="activity-list">
                    <h5 class="chart-title mb-4"><i class="fas fa-trophy"></i> <span>Top Performers</span></h5>
                    @foreach($topPerformers as $index => $performer)
                    <div class="performer-card">
                        <div class="performer-rank rank-{{ $index < 3 ? $index + 1 : 'other' }}">{{ $index + 1 }}</div>
                        <div class="performer-info">
                            <div class="performer-name">{{ $performer->name }}</div>
                            <div class="performer-stats">
                                <i class="fas fa-check-circle"></i> {{ $performer->taches_terminees }} tâches terminées
                                | <i class="fas fa-clock"></i> {{ $performer->pointages_ponctuel }} pointages ponctuels
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx1 = document.getElementById('pointagesMonthlyChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                // Wrap the array in collect()
                labels: @json(collect($chartData['pointages_monthly'])->pluck('month')),
                datasets: [
                    { 
                        label: 'Ponctuels', 
                        data: @json(collect($chartData['pointages_monthly'])->pluck('ponctuel')), 
                        backgroundColor: '#4CAF50' 
                    },
                    { 
                        label: 'Retards', 
                        data: @json(collect($chartData['pointages_monthly'])->pluck('retards')), 
                        backgroundColor: '#F44336' 
                    }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });
    });
</script>
</x-app-layout>