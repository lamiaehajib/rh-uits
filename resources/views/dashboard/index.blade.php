<x-app-layout>
    <style>
        /* Tout le CSS dial avant */
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

        .chart-container { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07); animation: fadeInUp 0.8s ease; margin-bottom: 24px; }
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

        .section-title { font-size: 24px; font-weight: bold; background: linear-gradient(135deg, #C2185B, #D32F2F); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block; }

        .quick-action-btn { background: white; border: 2px solid #ecf0f1; border-radius: 8px; padding: 16px; text-align: center; transition: all 0.3s ease; cursor: pointer; }
        .quick-action-btn:hover { border-color: #D32F2F; transform: translateY(-4px); box-shadow: 0 8px 16px rgba(211, 47, 47, 0.2); }
        .quick-action-icon { font-size: 32px; background: linear-gradient(135deg, #C2185B, #D32F2F); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 8px; }

        .chart-wrapper { position: relative; margin: auto; height: 300px; width: 100%; }
        .performance-wrapper { height: 400px; }

        /* Header Dashboard - GRAND ET CENTRÉ */
        .dashboard-header {
            text-align: center;
            padding: 48px 24px;
            background: linear-gradient(135deg, #ffffff 0%, #fef5f7 50%, #ffffff 100%);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
        }
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #C2185B, #D32F2F, #FF5722, #FF9800, #FFC107);
            animation: shimmer 4s infinite;
        }
        .dashboard-header::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(211, 47, 47, 0.03) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            pointer-events: none;
        }
        .header-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            box-shadow: 0 8px 24px rgba(211, 47, 47, 0.3);
            margin-bottom: 24px;
            animation: pulse 3s infinite;
        }
        .header-icon i {
            font-size: 42px;
            color: white;
        }
        .dashboard-main-title {
            font-size: 48px;
            font-weight: 900;
            background: linear-gradient(135deg, #C2185B, #D32F2F, #FF5722);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 20px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1.2;
            animation: fadeIn 1s ease;
            position: relative;
            z-index: 1;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .header-badges {
            margin: 24px 0;
        }
        .period-badge-large {
            display: inline-block;
            padding: 12px 32px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 700;
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.3);
            animation: slideInRight 0.8s ease;
            letter-spacing: 0.5px;
        }
        .period-badge-large i {
            margin-right: 8px;
            font-size: 18px;
        }
        .header-timestamp {
            color: #7f8c8d;
            font-size: 15px;
            font-weight: 500;
            margin: 16px 0 0 0;
            animation: fadeIn 1.2s ease;
        }
        .header-timestamp i {
            margin-right: 8px;
            color: #D32F2F;
        }
        .header-divider {
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, transparent, #D32F2F, transparent);
            margin: 32px auto 0;
            border-radius: 2px;
            animation: expandWidth 1s ease;
        }
        @keyframes expandWidth {
            from { width: 0; opacity: 0; }
            to { width: 120px; opacity: 1; }
        }

        /* Filter Styles - MODERNE ET ATTRACTIF */
        .filter-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            padding: 28px 32px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            margin-bottom: 28px;
            animation: fadeInUp 0.6s ease;
            border: 1px solid rgba(211, 47, 47, 0.1);
            position: relative;
            overflow: hidden;
        }
        .filter-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #C2185B, #D32F2F, #FF5722, #FF9800);
            animation: shimmer 3s infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .filter-title {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: 0.5px;
        }
        .filter-title i {
            font-size: 24px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pulse 2s infinite;
        }
        .filter-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: end;
        }
        .filter-item {
            flex: 1;
            min-width: 180px;
            position: relative;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .filter-item[style*="display: none"] {
            opacity: 0;
            transform: translateY(-10px);
        }
        .filter-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .filter-label i {
            font-size: 14px;
            color: #D32F2F;
        }
        .filter-select {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
            color: #2c3e50;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23D32F2F' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
        }
        .filter-select:hover {
            border-color: #D32F2F;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.15);
        }
        .filter-select:focus {
            outline: none;
            border-color: #D32F2F;
            box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.1);
            transform: translateY(-2px);
        }
        .filter-item::before {
            content: '';
            position: absolute;
            left: 16px;
            top: 42px;
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            color: #D32F2F;
            font-size: 16px;
            pointer-events: none;
            z-index: 1;
        }
        .filter-item:nth-child(1)::before { content: '\f017'; } /* Clock icon */
        .filter-item:nth-child(2)::before { content: '\f073'; } /* Calendar icon */
        .filter-item:nth-child(3)::before { content: '\f133'; } /* Calendar-alt icon */
        .filter-btn {
            padding: 12px 28px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }
        .filter-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        .filter-btn:hover::before {
            left: 100%;
        }
        .filter-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.4);
        }
        .filter-btn:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }
        .filter-btn i {
            font-size: 16px;
        }
        .filter-btn-reset {
            padding: 12px 24px;
            background: white;
            color: #7f8c8d;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        .filter-btn-reset:hover {
            border-color: #D32F2F;
            color: #D32F2F;
            background: #fff5f5;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.15);
        }
        .filter-btn-reset i {
            transition: transform 0.3s ease;
        }
        .filter-btn-reset:hover i {
            transform: rotate(180deg);
        }
        .period-badge {
            display: inline-block;
            padding: 6px 16px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-left: 12px;
        }

        @media (max-width: 768px) {
            .stat-card-value { font-size: 24px; }
            .chart-container { padding: 16px; }
            .performer-card { flex-direction: column; text-align: center; }
            .filter-group { flex-direction: column; }
            .filter-item { min-width: 100%; }
            .dashboard-main-title { font-size: 32px; }
            .header-icon { width: 64px; height: 64px; }
            .header-icon i { font-size: 32px; }
            .period-badge-large { font-size: 14px; padding: 10px 24px; }
            .dashboard-header { padding: 32px 16px; }
        }
        @media (max-width: 480px) {
            .dashboard-main-title { font-size: 24px; }
            .period-badge-large { font-size: 12px; padding: 8px 20px; }
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Page Header - GRAND ET CENTRÉ -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="dashboard-header">
                    <div class="header-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h1 class="dashboard-main-title">
                        Dashboard Système de Gestion
                    </h1>
                    <div class="header-badges">
                        @if($filterType === 'monthly')
                            <span class="period-badge-large">
                                <i class="far fa-calendar"></i> 
                                {{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->locale('fr')->isoFormat('MMMM YYYY') }}
                            </span>
                        @elseif($filterType === 'yearly')
                            <span class="period-badge-large">
                                <i class="far fa-calendar-alt"></i> Année {{ $selectedYear }}
                            </span>
                        @else
                            <span class="period-badge-large">
                                <i class="fas fa-infinity"></i> Depuis le début
                            </span>
                        @endif
                    </div>
                    <p class="header-timestamp">
                        <i class="far fa-clock"></i> Dernière mise à jour: {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY à HH:mm') }}
                    </p>
                    <div class="header-divider"></div>
                </div>
            </div>
        </div>

        <!-- Filtres - JDID -->
        <div class="filter-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                <span>Filtrer les statistiques</span>
            </div>
            <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                <div class="filter-group">
                    <div class="filter-item">
                        <label class="filter-label">Période</label>
                        <select name="filter_type" class="filter-select" id="filterType" onchange="toggleFilters()">
                            <option value="all_time" {{ $filterType === 'all_time' ? 'selected' : '' }}>Depuis le début</option>
                            <option value="monthly" {{ $filterType === 'monthly' ? 'selected' : '' }}>Par mois</option>
                            <option value="yearly" {{ $filterType === 'yearly' ? 'selected' : '' }}>Par année</option>
                        </select>
                    </div>

                    <div class="filter-item" id="monthFilter" style="{{ $filterType !== 'monthly' ? 'display: none;' : '' }}">
                        <label class="filter-label">Mois</label>
                        <select name="month" class="filter-select">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->locale('fr')->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="filter-item" id="yearFilter" style="{{ $filterType === 'all_time' ? 'display: none;' : '' }}">
                        <label class="filter-label">Année</label>
                        <select name="year" class="filter-select">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-item" style="flex: 0; min-width: auto;">
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-search"></i>
                            <span>Appliquer</span>
                        </button>
                    </div>

                    <div class="filter-item" style="flex: 0; min-width: auto;">
                        <button type="button" class="filter-btn-reset" onclick="resetFilters()">
                            <i class="fas fa-redo"></i>
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </form>
        </div>
@if($isAdmin)
        <!-- Quick Actions -->
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
                <a href="{{ route('dashboard.export', request()->all()) }}" class="quick-action-btn d-block text-decoration-none text-dark">
                    <div class="quick-action-icon"><i class="fas fa-file-export"></i></div>
                    <div>Exporter Stats</div>
                </a>
            </div>
        </div>
@endif
        <!-- Admin Stats -->
        @if($isAdmin)
        <div class="row mb-4">
            @if($stats['users'])
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-icon gradient-red"><i class="fas fa-users"></i></div>
                    <div class="stat-card-value">{{ $stats['users']['total'] }}</div>
                    <div class="stat-card-label">Total Utilisateurs</div>
                    <div class="mt-3">
                        <span class="badge-success stat-card-badge"><i class="fas fa-check-circle"></i> {{ $stats['users']['active'] }} Actifs</span>
                        <span class="badge-info stat-card-badge ms-2"><i class="fas fa-user-plus"></i> {{ $stats['users']['new_in_period'] }} Nouveaux</span>
                    </div>
                </div>
            </div>
            @endif

            @if($stats['clients'])
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
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
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
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
                    <div class="stat-card-value">{{ $stats['pointages']['total_period'] }}</div>
                    <div class="stat-card-label">Pointages</div>
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
                    <div class="chart-title"><i class="fas fa-calendar-alt"></i> <span>Évolution Pointages</span></div>
                    <div class="chart-wrapper">
                        <canvas id="pointagesMonthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-tasks"></i> <span>Répartition des Tâches</span></div>
                    <div class="chart-wrapper">
                        <canvas id="tachesStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-bullseye"></i> <span>Progression Objectifs</span></div>
                    <div class="chart-wrapper">
                        <canvas id="objectifsProgressChart"></canvas>
                    </div>
                </div>
            </div>

            @if($isAdmin && $chartData['projets_status'])
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-project-diagram"></i> <span>Statut des Projets</span></div>
                    <div class="chart-wrapper">
                        <canvas id="projetsStatusChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-chart-line"></i> <span>Tendance Retards</span></div>
                    <div class="chart-wrapper">
                        <canvas id="retardsTrendChart"></canvas>
                    </div>
                </div>
            </div>

            @if($isAdmin && $chartData['users_performance'])
            <div class="col-lg-12 mb-4">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-trophy"></i> <span>Top 10 Performers</span></div>
                    <div class="chart-wrapper performance-wrapper">
                        <canvas id="usersPerformanceChart"></canvas>
                    </div>
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

        function toggleFilters() {
            const filterType = document.getElementById('filterType').value;
            const monthFilter = document.getElementById('monthFilter');
            const yearFilter = document.getElementById('yearFilter');
            
            if (filterType === 'all_time') {
                monthFilter.style.display = 'none';
                yearFilter.style.display = 'none';
            } else if (filterType === 'monthly') {
                monthFilter.style.display = 'block';
                yearFilter.style.display = 'block';
            } else if (filterType === 'yearly') {
                monthFilter.style.display = 'none';
                yearFilter.style.display = 'block';
            }
        }

        function resetFilters() {
            window.location.href = '{{ route('dashboard') }}';
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Options standards bach nsaghro l-charts
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false, // Darouri bach i-htarem l-height li f-CSS
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            };

            // 1. Pointages Monthly
            new Chart(document.getElementById('pointagesMonthlyChart'), {
                type: 'bar',
                data: {
                    labels: @json(collect($chartData['pointages_monthly'])->pluck('month')),
                    datasets: [
                        { label: 'Ponctuels', data: @json(collect($chartData['pointages_monthly'])->pluck('ponctuel')), backgroundColor: '#4CAF50' },
                        { label: 'Retards', data: @json(collect($chartData['pointages_monthly'])->pluck('retards')), backgroundColor: '#F44336' }
                    ]
                },
                options: { ...commonOptions, scales: { y: { beginAtZero: true } } }
            });

            // 2. Tâches Status
            new Chart(document.getElementById('tachesStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['taches_status']['labels'] ?? []),
                    datasets: [{
                        data: @json($chartData['taches_status']['data'] ?? []),
                        backgroundColor: @json($chartData['taches_status']['colors'] ?? []),
                        borderWidth: 2
                    }]
                },
                options: commonOptions
            });

            // 3. Objectifs Progress
            new Chart(document.getElementById('objectifsProgressChart'), {
                type: 'pie',
                data: {
                    labels: @json($chartData['objectifs_progress']['labels'] ?? []),
                    datasets: [{
                        data: @json($chartData['objectifs_progress']['data'] ?? []),
                        backgroundColor: @json($chartData['objectifs_progress']['colors'] ?? []),
                        borderWidth: 2
                    }]
                },
                options: commonOptions
            });

            // 4. Projets Status
            @if($isAdmin && isset($chartData['projets_status']))
            new Chart(document.getElementById('projetsStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['projets_status']['labels'] ?? []),
                    datasets: [{
                        data: @json($chartData['projets_status']['data'] ?? []),
                        backgroundColor: @json($chartData['projets_status']['colors'] ?? []),
                        borderWidth: 2
                    }]
                },
                options: commonOptions
            });
            @endif

            // 5. Retards Trend
            new Chart(document.getElementById('retardsTrendChart'), {
                type: 'line',
                data: {
                    labels: @json(collect($chartData['retards_trend'])->pluck('week')),
                    datasets: [{
                        label: 'Retards',
                        data: @json(collect($chartData['retards_trend'])->pluck('retards')),
                        borderColor: '#F44336',
                        backgroundColor: 'rgba(244, 67, 54, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: { ...commonOptions, scales: { y: { beginAtZero: true } } }
            });

            // 6. Users Performance
            @if($isAdmin && isset($chartData['users_performance']))
            new Chart(document.getElementById('usersPerformanceChart'), {
                type: 'bar',
                data: {
                    labels: @json($chartData['users_performance']['labels'] ?? []),
                    datasets: [{
                        label: 'Tâches terminées',
                        data: @json($chartData['users_performance']['data'] ?? []),
                        backgroundColor: '#2196F3'
                    }]
                },
                options: {
                    ...commonOptions,
                    indexAxis: 'y',
                    scales: { x: { beginAtZero: true } }
                }
            });
            @endif
        });
    </script>
</x-app-layout>