<x-app-layout>
    <style>
        /* Variables de couleurs */
        :root {
            --primary-gradient: linear-gradient(135deg, #C2185B, #D32F2F);
            --primary-pink: #C2185B;
            --primary-red: #D32F2F;
            --success-green: #4CAF50;
            --warning-orange: #FF9800;
            --info-blue: #2196F3;
            --dark-text: #2C3E50;
            --light-bg: #f8f9fa;
        }

        /* Container principal */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Cards modernes avec gradient subtil */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(211, 47, 47, 0.15);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        /* Icônes avec gradient */
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: var(--primary-gradient);
            color: white;
            margin-bottom: 16px;
        }

        .stat-icon.success {
            background: linear-gradient(135deg, #4CAF50, #45a049);
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, #FF9800, #F57C00);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, #2196F3, #1976D2);
        }

        /* Titres et textes */
        .stat-title {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .stat-subtitle {
            font-size: 13px;
            color: #95a5a6;
        }

        /* Section headers */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e9ecef;
        }

        .section-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        /* Charts container */
        .chart-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        /* Progress bars */
        .progress-modern {
            height: 8px;
            border-radius: 10px;
            background: #e9ecef;
            overflow: hidden;
        }

        .progress-bar-gradient {
            background: var(--primary-gradient);
            height: 100%;
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* Badges modernes */
        .badge-gradient {
            background: var(--primary-gradient);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-success {
            background: linear-gradient(135deg, #4CAF50, #45a049);
        }

        .badge-warning {
            background: linear-gradient(135deg, #FF9800, #F57C00);
        }

        .badge-info {
            background: linear-gradient(135deg, #2196F3, #1976D2);
        }

        /* Tables modernes */
        .modern-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .modern-table thead {
            background: var(--primary-gradient);
        }

        .modern-table thead th {
            color: white;
            font-weight: 600;
            padding: 16px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .modern-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 16px;
            color: #2C3E50;
        }

        /* Buttons */
        .btn-gradient {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.3);
        }

        /* Quick actions */
        .quick-action {
            background: white;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .quick-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.15);
        }

        .quick-action-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 12px;
            border-radius: 12px;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* User list */
        .user-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: white;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }

        .user-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 16px;
            }
            
            .stat-value {
                font-size: 24px;
            }
        }
    </style>

    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="mb-5">
            <h1 class="text-4xl font-bold mb-2" style="color: var(--dark-text);">
                Bonjour, <span class="hight">{{ auth()->user()->name }}</span> 👋
            </h1>
            <p class="text-gray-600">Bienvenue sur votre tableau de bord</p>
        </div>

        <!-- Pointage Section -->
        @if(!auth()->user()->hasRole('Client'))
        <div class="mb-5">
            <div class="stat-card">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h4 class="text-lg font-semibold mb-2" style="color: var(--dark-text);">
                            <i class="fas fa-clock mr-2"></i> Pointage du jour
                        </h4>
                        <p class="text-gray-600 text-sm">
                            {{ \Carbon\Carbon::now()->isoFormat('dddd D MMMM YYYY') }}
                        </p>
                    </div>
                    
                    <div class="flex gap-3">
                        @if(!$hasClockedInToday)
                            <button onclick="clockIn()" class="btn-gradient">
                                <i class="fas fa-sign-in-alt mr-2"></i> Pointer l'arrivée
                            </button>
                        @elseif(!$hasClockedOutToday)
                            <button onclick="clockOut()" class="btn-gradient">
                                <i class="fas fa-sign-out-alt mr-2"></i> Pointer le départ
                            </button>
                        @else
                            <span class="badge-success">
                                <i class="fas fa-check-circle"></i> Pointage complet
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat Card 1 -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-title">Total Tâches</div>
                <div class="stat-value">{{ $stats['total_tasks'] ?? 0 }}</div>
                <div class="stat-subtitle">
                    <span class="badge-success">
                        <i class="fas fa-check"></i> {{ $stats['completed_tasks'] ?? 0 }} Terminées
                    </span>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-title">Projets Actifs</div>
                <div class="stat-value">{{ $stats['active_projects'] ?? 0 }}</div>
                <div class="stat-subtitle">
                    Sur {{ $stats['total_projects'] ?? 0 }} projets
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-title">Taux de Complétion</div>
                <div class="stat-value">{{ $stats['completion_rate'] ?? 0 }}%</div>
                <div class="progress-modern mt-3">
                    <div class="progress-bar-gradient" style="width: {{ $stats['completion_rate'] ?? 0 }}%"></div>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-title">Score Productivité</div>
                <div class="stat-value">{{ $stats['productivity_score'] ?? 0 }}</div>
                <div class="stat-subtitle">
                    Excellent performance! 🎉
                </div>
            </div>
        </div>

        <!-- Section Équipes (Admin Only) -->
        @if($equipeStats)
        <div class="mb-8">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Gestion des Équipes</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-title">Total Utilisateurs</div>
                    <div class="stat-value">{{ $equipeStats['total_users'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-title">Utilisateurs Actifs</div>
                    <div class="stat-value">{{ $equipeStats['active_users'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div class="stat-title">Utilisateurs Inactifs</div>
                    <div class="stat-value">{{ $equipeStats['inactive_users'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stat-title">Nouveaux ce mois</div>
                    <div class="stat-value">{{ $equipeStats['new_users_this_month'] }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section Pointage -->
        @if($pointageStats)
        <div class="mb-8">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Statistiques de Pointage</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="stat-title">Total Pointages</div>
                    <div class="stat-value">{{ $pointageStats['total_pointages'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-title">Complets</div>
                    <div class="stat-value">{{ $pointageStats['pointages_complets'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-title">Retards</div>
                    <div class="stat-value">{{ $pointageStats['retards'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stat-title">Départs Anticipés</div>
                    <div class="stat-value">{{ $pointageStats['departs_anticipes'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-title">Taux Ponctualité</div>
                    <div class="stat-value">{{ $pointageStats['taux_ponctualite'] }}%</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section Tâches -->
        @if($tachesStats)
        <div class="mb-8">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>Suivi des Tâches</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="stat-title">Total</div>
                    <div class="stat-value">{{ $tachesStats['total_taches'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="stat-title">Terminées</div>
                    <div class="stat-value">{{ $tachesStats['taches_terminees'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="stat-title">En Cours</div>
                    <div class="stat-value">{{ $tachesStats['taches_en_cours'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="stat-title">Nouvelles</div>
                    <div class="stat-value">{{ $tachesStats['taches_nouveau'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-title">En Retard</div>
                    <div class="stat-value">{{ $tachesStats['taches_en_retard'] }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section Objectifs -->
        @if($objectifsStats)
        <div class="mb-8">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Objectifs</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-target"></i>
                    </div>
                    <div class="stat-title">Total Objectifs</div>
                    <div class="stat-value">{{ $objectifsStats['total_objectifs'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-title">Complétés</div>
                    <div class="stat-value">{{ $objectifsStats['objectifs_completes'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-title">En Cours</div>
                    <div class="stat-value">{{ $objectifsStats['objectifs_en_cours'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-title">En Retard</div>
                    <div class="stat-value">{{ $objectifsStats['objectifs_en_retard'] }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Section Clients & Projets (Admin Only) -->
        @if($clientsProjectsStats)
        <div class="mb-8">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3>Clients & Projets</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Clients Stats -->
                <div class="chart-card">
                    <h4 class="font-semibold mb-4" style="color: var(--dark-text);">
                        <i class="fas fa-users mr-2"></i> Statistiques Clients
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="stat-value">{{ $clientsProjectsStats['total_clients'] }}</div>
                            <div class="stat-title">Total Clients</div>
                        </div>
                        <div class="text-center">
                            <div class="stat-value">{{ $clientsProjectsStats['clients_actifs'] }}</div>
                            <div class="stat-title">Clients Actifs</div>
                        </div>
                    </div>
                </div>

                <!-- Projets Stats -->
                <div class="chart-card">
                    <h4 class="font-semibold mb-4" style="color: var(--dark-text);">
                        <i class="fas fa-project-diagram mr-2"></i> Statistiques Projets
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="stat-value">{{ $clientsProjectsStats['projets_en_cours'] }}</div>
                            <div class="stat-title">En Cours</div>
                        </div>
                        <div class="text-center">
                            <div class="stat-value">{{ $clientsProjectsStats['projets_termines'] }}</div>
                            <div class="stat-title">Terminés</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="mb-8">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Actions Rapides</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="{{ route('taches.create') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="text-sm font-semibold">Nouvelle Tâche</div>
                </a>

                <a href="{{ route('admin.projets.create') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-folder-plus"></i>
                    </div>
                    <div class="text-sm font-semibold">Nouveau Projet</div>
                </a>

                <a href="{{ route('formations.index') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="text-sm font-semibold">Formations</div>
                </a>

                <a href="{{ route('objectifs.index') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="text-sm font-semibold">Objectifs</div>
                </a>

                <a href="{{ route('pointage.index') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="text-sm font-semibold">Pointages</div>
                </a>

                <a href="{{ route('reclamations.index') }}" class="quick-action">
                    <div class="quick-action-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="text-sm font-semibold">Réclamations</div>
                </a>
            </div>
        </div>
    </div>

    <script>
        function clockIn() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    submitPointage(position.coords.latitude, position.coords.longitude);
                }, function() {
                    submitPointage(null, null);
                });
            } else {
                submitPointage(null, null);
            }
        }

        function clockOut() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    submitPointage(position.coords.latitude, position.coords.longitude);
                }, function() {
                    submitPointage(null, null);
                });
            } else {
                submitPointage(null, null);
            }
        }

        function submitPointage(lat, lng) {
            const form = document.createElement('form');
            form.method = 'POST';
            
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            if (lat && lng) {
                const latInput = document.createElement('input');
                latInput.type = 'hidden';
                latInput.name = 'user_latitude';
                latInput.value = lat;
                form.appendChild(latInput);

                const lngInput = document.createElement('input');
                lngInput.type = 'hidden';
                lngInput.name = 'user_longitude';
                lngInput.value = lng;
                form.appendChild(lngInput);
            }

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</x-app-layout>