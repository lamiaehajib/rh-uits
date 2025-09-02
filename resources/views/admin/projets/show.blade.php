<x-app-layout>
    <style>
        :root {
            --primary-color: #C2185B;
            --secondary-color: #D32F2F;
            --accent-color: #ef4444;
            --gradient-bg: linear-gradient(135deg, #C2185B 0%, #D32F2F 50%, #ef4444 100%);
            --gradient-light: linear-gradient(135deg, rgba(194, 24, 91, 0.1) 0%, rgba(211, 47, 47, 0.1) 100%);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        .container-fluid {
            padding: 2rem;
        }

        .page-header {
            background: var(--gradient-bg);
            padding: 2.5rem;
            border-radius: 24px;
            margin-bottom: 2rem;
            box-shadow: 0 25px 60px rgba(194, 24, 91, 0.25);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .page-header h1 {
            color: white;
            font-weight: 700;
            font-size: 2.2rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .breadcrumb {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 12px 20px;
            margin: 1rem 0 0 0;
            position: relative;
            z-index: 2;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: white;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.7);
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-back {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 14px 28px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
            color: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            color: white;
            padding: 14px 28px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        .btn-edit:hover {
            transform: translateY(-3px);
            color: white;
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        .card-header-custom {
            background: var(--gradient-light);
            padding: 2rem;
            border-bottom: 1px solid rgba(194, 24, 91, 0.1);
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header-custom::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--gradient-bg);
        }

        .card-header-custom h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header-custom h5::before {
            content: '\f1c0';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            width: 45px;
            height: 45px;
            background: var(--gradient-bg);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 18px;
        }

        .status-badge-large {
            padding: 12px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-en-cours {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.9) 0%, rgba(211, 47, 47, 0.9) 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(194, 24, 91, 0.3);
        }

        .badge-termine {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.9) 0%, rgba(22, 163, 74, 0.9) 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
        }

        .badge-en-attente {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.9) 0%, rgba(245, 158, 11, 0.9) 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.3);
        }

        .badge-annule {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 38, 0.9) 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        .info-row {
            display: flex;
            padding: 1.2rem 0;
            border-bottom: 1px solid rgba(194, 24, 91, 0.08);
            align-items: flex-start;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1rem;
            min-width: 180px;
            flex-shrink: 0;
            position: relative;
        }

        .info-label::after {
            content: '';
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: var(--gradient-bg);
            border-radius: 2px;
        }

        .info-value {
            color: #1f2937;
            font-weight: 500;
            font-size: 1rem;
            flex: 1;
            margin-left: 20px;
        }

        .client-badge {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(99, 102, 241, 0.2) 100%);
            color: #1e40af;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 600;
            border: 1px solid rgba(59, 130, 246, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .progress-container {
            background: rgba(194, 24, 91, 0.05);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(194, 24, 91, 0.1);
        }

        .progress-bar-custom {
            height: 12px;
            border-radius: 10px;
            background: #e5e7eb;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: var(--gradient-bg);
            border-radius: 10px;
            transition: width 1s ease-in-out;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        .avancement-item {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(194, 24, 91, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .avancement-item:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 25px rgba(194, 24, 91, 0.15);
        }

        .avancement-badge {
            background: var(--gradient-bg);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .avancement-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 3s infinite;
        }

        .sidebar-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .sidebar-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.12);
        }

        .sidebar-card .card-header {
            background: var(--gradient-light);
            padding: 1.5rem;
            border-bottom: 1px solid rgba(194, 24, 91, 0.1);
            position: relative;
        }

        .sidebar-card .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--gradient-bg);
        }

        .sidebar-card .card-header h5 {
            margin: 0;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.1rem;
        }

        .sidebar-card .card-header h5::before {
            content: attr(data-icon);
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            width: 40px;
            height: 40px;
            background: var(--gradient-bg);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 16px;
        }

        .action-btn {
            width: 100%;
            padding: 14px 20px;
            border-radius: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.8rem;
            border: 2px solid transparent;
        }

        .btn-edit-action {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-edit-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            color: white;
        }

        .btn-complete {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
        }

        .btn-complete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
            color: white;
        }

        .btn-download {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: var(--gradient-light);
            border-radius: 16px;
            border: 1px solid rgba(194, 24, 91, 0.1);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(194, 24, 91, 0.15);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            display: block;
            text-shadow: 0 2px 4px rgba(194, 24, 91, 0.1);
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .client-avatar {
            width: 80px;
            height: 80px;
            background: var(--gradient-bg);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            margin: 0 auto 1rem;
            box-shadow: 0 15px 35px rgba(194, 24, 91, 0.3);
            position: relative;
        }

        .client-avatar::after {
            content: '';
            position: absolute;
            inset: -3px;
            background: var(--gradient-bg);
            border-radius: 50%;
            z-index: -1;
            animation: pulse-ring 2s infinite;
        }

        .rdv-table {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .rdv-table thead {
            background: var(--gradient-bg);
            color: white;
        }

        .rdv-table th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .rdv-table td {
            border: none;
            padding: 1rem;
            border-bottom: 1px solid rgba(194, 24, 91, 0.05);
        }

        .rdv-table tbody tr:hover {
            background: rgba(194, 24, 91, 0.02);
        }

        .rdv-badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-programme {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(99, 102, 241, 0.2) 100%);
            color: #1e40af;
        }

        .badge-confirme {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(22, 163, 74, 0.2) 100%);
            color: #166534;
        }

        .badge-reporte {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%);
            color: #92400e;
        }

        .badge-annule-rdv {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.2) 100%);
            color: #991b1b;
        }

        .alert-success-custom {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(22, 163, 74, 0.1) 100%);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-left: 4px solid #22c55e;
            border-radius: 16px;
            padding: 1.5rem;
            color: #166534;
            font-weight: 500;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .modal-header {
            background: var(--gradient-bg);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-footer {
            border-top: 1px solid rgba(194, 24, 91, 0.1);
            padding: 1.5rem;
            background: rgba(194, 24, 91, 0.02);
        }

        .btn-gradient {
            background: var(--gradient-bg);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(194, 24, 91, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            color: #374151;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-secondary-custom:hover {
            background: #e5e7eb;
            color: #374151;
            transform: translateY(-2px);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            100% {
                transform: scale(1.1);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.8rem;
                margin-bottom: 1rem;
            }

            .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .info-row {
                flex-direction: column;
                gap: 0.5rem;
            }

            .info-label::after {
                display: none;
            }

            .info-label {
                min-width: auto;
            }

            .info-value {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center page-header">
            <div>
                <h1 class="mb-0">Détails du Projet</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.projets.index') }}">Projets</a></li>
                        <li class="breadcrumb-item active">{{ $projet->titre }}</li>
                    </ol>
                </nav>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.projets.edit', $projet) }}" class="btn-edit">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('admin.projets.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <!-- Messages de succès -->
        @if(session('success'))
            <div class="alert-success-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="glass-card">
                    <div class="card-header-custom" style="padding: 2rem;">
                        <h5>Informations du Projet</h5>
                        <span class="status-badge-large 
                        @if($projet->statut_projet === 'en cours') badge-en-cours
                        @elseif($projet->statut_projet === 'terminé') badge-termine
                        @elseif($projet->statut_projet === 'en attente') badge-en-attente
                        @else badge-annule
                        @endif">
                            <i class="fas 
                            @if($projet->statut_projet === 'en cours') fa-play-circle
                            @elseif($projet->statut_projet === 'terminé') fa-check-circle
                            @elseif($projet->statut_projet === 'en attente') fa-pause-circle
                            @else fa-times-circle
                            @endif"></i>
                            {{ ucfirst($projet->statut_projet) }}
                        </span>
                    </div>
                    <div class="card-body" style="padding: 2.5rem;">
                        <div class="info-row">
                            <div class="info-label">Titre:</div>
                            <div class="info-value">{{ $projet->titre }}</div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Clients:</div>
                            <div class="info-value">
                                @forelse($projet->users as $client)
                                    <span class="client-badge mb-1">
                                        <i class="fas fa-user"></i>
                                        {{ $client->name }}
                                    </span>
                                    <small class="text-muted d-block mt-1 mb-2">{{ $client->email }}</small>
                                @empty
                                    <em style="color: #9ca3af;">Aucun client assigné</em>
                                @endforelse
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Description:</div>
                            <div class="info-value">
                                @if($projet->description)
                                    {{ $projet->description }}
                                @else
                                    <em style="color: #9ca3af;">Aucune description fournie</em>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Date de début:</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-plus text-success me-2"></i>
                                {{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Date de fin:</div>
                            <div class="info-value">
                                @if($projet->date_fin)
                                    <i class="fas fa-calendar-check text-warning me-2"></i>
                                    {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}
                                @else
                                    <em style="color: #9ca3af;">Non définie</em>
                                @endif
                            </div>
                        </div>

                        @if($projet->fichier)
                            <div class="info-row">
                                <div class="info-label">Fichier joint:</div>
                                <div class="info-value">
                                    <a href="{{ route('admin.projets.download', $projet) }}" class="btn-download">
                                        <i class="fas fa-download"></i> Télécharger le fichier du projet
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="info-row">
                            <div class="info-label">Créé le:</div>
                            <div class="info-value">
                                <i class="fas fa-clock text-info me-2"></i>
                                {{ $projet->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avancement du projet -->
                @if($projet->avancements->count() > 0)
                    <div class="glass-card">
                        <div class="card-header-custom" style="padding: 2rem;">
                            <h5 data-icon="\f201">Avancement du Projet</h5>
                        </div>
                        <div class="card-body" style="padding: 2.5rem;">
                            <div class="progress-container">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span
                                        style="font-weight: 700; color: var(--primary-color); font-size: 1.1rem;">Progression
                                        globale</span>
                                    <span
                                        style="font-weight: 800; font-size: 1.5rem; color: var(--primary-color);">{{ number_format($pourcentageGlobal, 1) }}%</span>
                                </div>
                                <div class="progress-bar-custom">
                                    <div class="progress-fill" style="width: {{ $pourcentageGlobal }}%"></div>
                                </div>
                            </div>

                            <h6
                                style="color: var(--primary-color); font-weight: 700; margin-bottom: 1.5rem; font-size: 1.1rem;">
                                Détails des avancements:</h6>
                            <div>
                                @foreach($projet->avancements->sortBy('created_at') as $avancement)
                                    <div class="avancement-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div style="flex: 1;">
                                                <h6
                                                    style="color: var(--primary-color); font-weight: 700; margin-bottom: 0.5rem;">
                                                    {{ $avancement->titre }}</h6>
                                                <p style="color: #4b5563; margin-bottom: 0.8rem; line-height: 1.5;">
                                                    {{ $avancement->description }}</p>
                                                <small style="color: #9ca3af; font-weight: 500;">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $avancement->created_at->format('d/m/Y à H:i') }}
                                                </small>
                                            </div>
                                            <span class="avancement-badge">{{ $avancement->pourcentage }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- La maintenance sur site  -->
                @if($projet->rendezVous->count() > 0)
                    <div class="glass-card">
                        <div class="card-header-custom" style="padding: 2rem;">
                            <h5 data-icon="\f073"> intervention programmés</h5>
                        </div>
                        <div class="card-body" style="padding: 2.5rem;">
                            <div class="table-responsive">
                                <table class="table rdv-table">
                                    <thead>
                                        <tr>
                                            <th>Date & Heure</th>
                                            <th>Lieu</th>
                                            <th>Description</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projet->rendezVous->sortBy('date_heure') as $rdv)
                                            <tr>
                                                <td>
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m/Y à H:i') }}
                                                </td>
                                                <td>
                                                    <i class="fas fa-map-marker-alt text-warning me-2"></i>
                                                    {{ $rdv->lieu ?? 'Non défini' }}
                                                </td>
                                                <td>{{ Str::limit($rdv->description, 50) }}</td>
                                                <td>
                                                    <span class="rdv-badge 
                                                             @if($rdv->statut === 'programmé') badge-programme
                                                             @elseif($rdv->statut === 'confirmé') badge-confirme
                                                             @elseif($rdv->statut === 'reporté') badge-reporte
                                                             @else badge-annule-rdv
                                                             @endif">
                                                        {{ ucfirst($rdv->statut) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar avec actions et statistiques -->
            <div class="col-lg-4">
                <!-- Actions rapides -->
                <div class="sidebar-card">
                    <div class="card-header">
                        <h5 data-icon="\f0e7">Actions rapides</h5>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        <a href="{{ route('admin.projets.edit', $projet) }}" class="action-btn btn-edit-action">
                            <i class="fas fa-edit"></i> Modifier le projet
                        </a>

                        @if($projet->statut_projet !== 'terminé')
                            <button type="button" class="action-btn btn-complete"
                                onclick="marquerTermine({{ $projet->id }})">
                                <i class="fas fa-check"></i> Marquer comme terminé
                            </button>
                        @endif

                        <button type="button" class="action-btn btn-delete"
                            onclick="confirmerSuppression({{ $projet->id }})">
                            <i class="fas fa-trash"></i> Supprimer le projet
                        </button>
                    </div>
                </div>

                <!-- Statistiques du projet -->
                <div class="sidebar-card">
                    <div class="card-header">
                        <h5 data-icon="\f080">Statistiques</h5>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-number">{{ $projet->rendezVous->count() }}</span>
                                <span class="stat-label">intervention</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $projet->avancements->count() }}</span>
                                <span class="stat-label">Avancements</span>
                            </div>
                        </div>

                        @if($projet->date_debut && $projet->date_fin)
                            <div
                                style="text-align: center; padding: 1.5rem; background: var(--gradient-light); border-radius: 16px; border: 1px solid rgba(194, 24, 91, 0.1);">
                                <h6 style="color: var(--primary-color); font-weight: 700; margin-bottom: 0.5rem;">Durée du
                                    projet</h6>
                                <p style="margin: 0; font-size: 1.2rem; font-weight: 600; color: #1f2937;">
                                    {{ \Carbon\Carbon::parse($projet->date_debut)->diffInDays(\Carbon\Carbon::parse($projet->date_fin)) }}
                                    jours
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations du client -->
                <div class="sidebar-card">
                    <div class="card-header">
                        <h5 data-icon="\f007">Informations Client</h5>
                    </div>
                    <div class="card-body" style="padding: 2rem; text-align: center;">
                        @forelse($projet->users as $client)
                            <div class="client-avatar">
                                {{ strtoupper(substr($client->name, 0, 2)) }}
                            </div>
                            <h6 style="color: var(--primary-color); font-weight: 700; margin-bottom: 0.5rem;">
                                {{ $client->name }}</h6>
                            <p style="color: #6b7280; margin-bottom: 1.5rem; font-weight: 500;">{{ $client->email }}</p>

                            @if($client->tele)
                                <a href="tel:{{ $client->tele }}" class="btn-download"
                                    style="width: 100%; justify-content: center;">
                                    <i class="fas fa-phone"></i> {{ $client->tele }}
                                </a>
                            @endif
                            @if(!$loop->last)
                                <hr style="margin: 1.5rem 0;">
                            @endif
                        @empty
                            <p class="text-muted fst-italic">Aucun client assigné à ce projet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <i class="fas fa-trash-alt"
                            style="font-size: 3rem; color: var(--accent-color); margin-bottom: 1rem;"></i>
                    </div>
                    <p style="color: #4b5563; font-size: 1.1rem; line-height: 1.6;">Êtes-vous sûr de vouloir supprimer
                        ce projet ? Cette action est irréversible.</p>
                    <div
                        style="background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 12px; border-left: 4px solid var(--accent-color);">
                        <strong style="color: var(--accent-color);">Projet:</strong>
                        <span style="color: #1f2937;">{{ $projet->titre }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-gradient"
                            style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                            <i class="fas fa-trash"></i> Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>