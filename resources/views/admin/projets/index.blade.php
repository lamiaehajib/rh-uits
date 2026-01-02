<x-app-layout>
    <style>

        html, body {
    height: auto !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch; /* Scroll rtab f iPhone */
}
        /* --- Styles existants mhafdi 3lihom --- */
        .gradient-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 25px;
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
        }
        .gradient-card:hover { transform: translateY(-5px); }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }
        .stat-card.blue { border-color: #3B82F6; }
        .stat-card.green { border-color: #10B981; }
        .stat-card.yellow { border-color: #F59E0B; }
        .stat-card.red { border-color: #EF4444; }
        
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }
        
        .projet-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .projet-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            transform: translateX(5px);
        }
        .projet-card.en-cours { border-left-color: #3B82F6; }
        .projet-card.termine { border-left-color: #10B981; }
        .projet-card.en-attente { border-left-color: #F59E0B; }
        .projet-card.annule { border-left-color: #EF4444; }
        
        .badge-custom {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-en-cours { background: #DBEAFE; color: #1E40AF; }
        .badge-termine { background: #D1FAE5; color: #065F46; }
        .badge-en-attente { background: #FEF3C7; color: #92400E; }
        .badge-annule { background: #FEE2E2; color: #991B1B; }
        
        .btn-action {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.4);
        }
        
        .progress-bar-custom {
            height: 8px;
            border-radius: 10px;
            background: #E5E7EB;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10B981, #059669);
            transition: width 0.5s ease;
            border-radius: 10px;
        }
        
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            height: 400px;
            display: flex;
            flex-direction: column;
        }

        .chart-container canvas {
            max-height: 320px !important;
        }
        
        .btn-trash {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* --- T3dilat dyal l-Mobile (Responsiveness) --- */
        @media (max-width: 768px) {
            .header-actions {
                text-align: left !important;
                margin-top: 15px;
            }
            .btn-trash, .btn-primary-custom {
                width: 100%;
                margin-bottom: 10px;
                display: block;
                text-align: center;
            }
            .stat-card h3 {
                font-size: 24px !important;
            }
            .projet-card .text-end {
                text-align: left !important;
                margin-top: 15px;
            }
            .chart-container {
                height: 350px;
            }
        }
    </style>

    <div class="container-fluid px-2 px-md-4 py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="hight mb-0" style="font-size: 1.5rem;">
                    <i class="fas fa-project-diagram me-2"></i>Gestion des Projets
                </h2>
                <p class="text-muted mt-1">Tableau de bord complet et statistiques avancées</p>
            </div>
            <div class="col-md-6 text-end header-actions">
                <a href="{{ route('admin.projets.corbeille') }}" class="btn btn-trash me-md-2">
                    <i class="fas fa-trash-alt me-2"></i>Corbeille
                </a>
                <a href="{{ route('admin.projets.create') }}" class="btn btn-primary-custom">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Projet
                </a>
            </div>
        </div>

        <div class="row mb-4">
            @foreach([
                ['total_projets', 'Total Projets', 'folder', 'blue', '#3B82F6', '#DBEAFE'],
                ['projets_en_cours', 'En Cours', 'spinner', 'blue', '#3B82F6', '#DBEAFE'],
                ['projets_termines', 'Terminés', 'check-circle', 'green', '#10B981', '#D1FAE5'],
                ['projets_en_attente', 'En Attente', 'clock', 'yellow', '#F59E0B', '#FEF3C7']
            ] as $card)
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card {{ $card[3] }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: {{ $card[4] }}; font-size: 32px;">{{ $stats[$card[0]] }}</h3>
                            <p class="text-muted mb-0">{{ $card[1] }}</p>
                        </div>
                        <div class="p-3 rounded-circle" style="background: {{ $card[5] }};">
                            <i class="fas fa-{{ $card[2] }} fa-2x" style="color: {{ $card[4] }};"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row mb-4">
            <div class="col-lg-4 col-12 mb-3">
                <div class="chart-container">
                    <h5 class="hight mb-3"><i class="fas fa-chart-pie me-2"></i>Distribution</h5>
                    <canvas id="statutsChart"></canvas>
                </div>
            </div>

            <div class="col-lg-8 col-12 mb-3">
                <div class="chart-container">
                    <h5 class="hight mb-3"><i class="fas fa-chart-line me-2"></i>Évolution (12 mois)</h5>
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6 col-12 mb-3">
                <div class="chart-container">
                    <h5 class="hight mb-3"><i class="fas fa-chart-bar me-2"></i>Top 10 - Avancement</h5>
                    <canvas id="avancementChart"></canvas>
                </div>
            </div>

            <div class="col-lg-6 col-12 mb-3">
                <div class="chart-container">
                    <h5 class="hight mb-3"><i class="fas fa-calendar-check me-2"></i>RDV par Statut</h5>
                    <canvas id="rdvChart"></canvas>
                </div>
            </div>
        </div>

        <div class="filter-card">
    <h5 class="hight mb-4"><i class="fas fa-filter me-2"></i>Filtres</h5>
    <form method="GET" action="{{ route('admin.projets.index') }}">
        <div class="row g-3">
            <div class="col-12 col-md-3">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Titre..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    @foreach($statutsDisponibles as $statut)
                        <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ ucfirst($statut) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Client</label>
                <select name="client_id" class="form-select">
                    <option value="">Tous</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-12 col-md-4 d-flex align-items-end">
                <div class="d-flex w-100 gap-2">
                    <button type="submit" class="btn btn-primary-custom flex-grow-1">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('admin.projets.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" style="width: 45px;">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

        <div class="row">
            <div class="col-12">
                <h5 class="hight mb-3"><i class="fas fa-list me-2"></i>Liste des Projets ({{ $projets->total() }})</h5>
                @forelse($projets as $projet)
                    <div class="projet-card {{ str_replace(' ', '-', $projet->statut_projet) }}">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-5 mb-2 mb-lg-0">
                                <h6 class="mb-1 fw-bold">{{ $projet->titre }}</h6>
                                <p class="text-muted mb-2" style="font-size: 13px;">
                                    <i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}
                                </p>
                                <span class="badge-custom badge-{{ str_replace(' ', '-', $projet->statut_projet) }}">
                                    {{ $projet->statut_projet }}
                                </span>
                            </div>
                            <div class="col-12 col-lg-3 mb-3 mb-lg-0">
                                @php $pourcentage = min($projet->avancements->sum('pourcentage'), 100); @endphp
                                <div class="progress-bar-custom mb-1">
                                    <div class="progress-fill" style="width: {{ $pourcentage }}%"></div>
                                </div>
                                <small class="text-muted">{{ number_format($pourcentage, 1) }}%</small>
                            </div>
                            <div class="col-12 col-lg-4 text-end">
                                <div class="d-flex justify-content-lg-end gap-1">
                                    <a href="{{ route('admin.projets.show', $projet) }}" class="btn btn-action btn-primary-custom flex-fill flex-lg-grow-0"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.avancements.index', $projet) }}" class="btn btn-action flex-fill flex-lg-grow-0" style="background: #10B981; color: white;" title="Voir avancements">
                                        <i class="fas fa-tasks"></i>
                                    </a>
                                    <a href="{{ route('admin.projets.edit', $projet) }}" class="btn btn-action bg-warning text-white flex-fill flex-lg-grow-0"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" class="d-inline flex-fill flex-lg-grow-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-action bg-danger text-white w-100"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5"><p class="text-muted">Aucun projet trouvé</p></div>
                @endforelse
                <div class="mt-4 d-flex justify-content-center">
                    {{ $projets->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            if (typeof Chart === 'undefined') return;

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { boxWidth: 12, font: { size: 11 } }
                    }
                }
            };

            // Chart 1: Statuts
            new Chart(document.getElementById('statutsChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($statutsChart['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($statutsChart['data'] ?? []) !!},
                        backgroundColor: {!! json_encode($statutsChart['colors'] ?? []) !!}
                    }]
                },
                options: { ...commonOptions, plugins: { legend: { position: 'bottom' } } }
            });

            // Chart 2: Evolution
            new Chart(document.getElementById('timelineChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($projetsTimeline['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Projets',
                        data: {!! json_encode($projetsTimeline['data'] ?? []) !!},
                        borderColor: '#D32F2F',
                        backgroundColor: 'rgba(211, 47, 47, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { ...commonOptions, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });

            // Chart 3: Avancement
            new Chart(document.getElementById('avancementChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($avancementChart['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Avancement (%)',
                        data: {!! json_encode($avancementChart['data'] ?? []) !!},
                        backgroundColor: '#10B981'
                    }]
                },
                options: { ...commonOptions, indexAxis: 'y', scales: { x: { max: 100 } } }
            });

            // Chart 4: RDV
            new Chart(document.getElementById('rdvChart').getContext('2d'), {
                type: 'polarArea',
                data: {
                    labels: {!! json_encode($rdvChart['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($rdvChart['data'] ?? []) !!},
                        backgroundColor: {!! json_encode($rdvChart['colors'] ?? []) !!}
                    }]
                },
                options: { ...commonOptions, plugins: { legend: { position: 'bottom' } } }
            });
        });
    </script>
    @endpush
</x-app-layout>