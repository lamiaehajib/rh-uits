<x-app-layout>
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 fw-bold mb-2">
                            <i class="fas fa-chart-line me-2"></i>
                            <span class="hight">Tableau de Bord</span>
                        </h1>
                        <p class="text-muted mb-0">Vue d'ensemble des dépenses - {{ \Carbon\Carbon::parse($moisActuel)->locale('fr')->isoFormat('MMMM YYYY') }}</p>
                    </div>
                    <div>
                        <form method="GET" action="{{ route('depenses.index') }}" class="d-flex gap-2">
                            <input type="month" name="mois" value="{{ $moisActuel }}" 
                                   class="form-control shadow-sm" style="max-width: 200px;">
                            <button type="submit" class="btn btn-gradient px-4">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards Stats -->
        <div class="row g-4 mb-4">
            <!-- Total Général -->
            <div class="col-xl-4 col-md-6">
                <div class="stats-card card-total">
                    <div class="stats-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stats-content">
                        <h6 class="text-uppercase mb-2">Total Général</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($totalGeneral, 2, ',', ' ') }} DH</h2>
                        <p class="mb-0 mt-2 text-white-50">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ \Carbon\Carbon::parse($moisActuel)->locale('fr')->isoFormat('MMMM YYYY') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dépenses Fixes -->
            <div class="col-xl-4 col-md-6">
                <div class="stats-card card-fixe">
                    <div class="stats-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stats-content">
                        <h6 class="text-uppercase mb-2">Dépenses Fixes</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($totalFixe, 2, ',', ' ') }} DH</h2>
                        <p class="mb-0 mt-2 text-white-50">
                            <i class="fas fa-percentage me-1"></i>
                            {{ $totalGeneral > 0 ? number_format(($totalFixe / $totalGeneral) * 100, 1) : 0 }}% du total
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dépenses Variables -->
            <div class="col-xl-4 col-md-6">
                <div class="stats-card card-variable">
                    <div class="stats-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="stats-content">
                        <h6 class="text-uppercase mb-2">Dépenses Variables</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($totalVariable, 2, ',', ' ') }} DH</h2>
                        <p class="mb-0 mt-2 text-white-50">
                            <i class="fas fa-percentage me-1"></i>
                            {{ $totalGeneral > 0 ? number_format(($totalVariable / $totalGeneral) * 100, 1) : 0 }}% du total
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Évolution Mensuelle -->
            <div class="col-xl-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-line me-2 text-danger"></i>
                            Évolution sur 6 Mois
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="evolutionChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Répartition -->
            <div class="col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-pie me-2 text-danger"></i>
                            Répartition
                        </h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="repartitionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails par Catégorie -->
        <div class="row g-4 mb-4">
            <!-- Dépenses Fixes Détail -->
            <div class="col-xl-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-header text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-building me-2"></i>
                            Détail Dépenses Fixes
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Type</th>
                                        <th class="text-end pe-4">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($depensesFixesDetail as $detail)
                                    <tr>
                                        <td class="ps-4">
                                            <i class="fas fa-circle me-2" style="color: #D32F2F; font-size: 8px;"></i>
                                            <strong>{{ $detail->type }}</strong>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-danger-soft">
                                                {{ number_format($detail->total, 2, ',', ' ') }} DH
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p class="mb-0">Aucune dépense fixe</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dépenses Variables Détail -->
            <div class="col-xl-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-header text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-pie me-2"></i>
                            Détail Dépenses Variables
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Catégorie</th>
                                        <th class="text-end pe-4">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($depensesVariablesDetail as $detail)
                                    <tr>
                                        <td class="ps-4">
                                            <i class="fas fa-circle me-2" style="color: #C2185B; font-size: 8px;"></i>
                                            <strong>{{ ucfirst(str_replace('_', ' ', $detail->categorie)) }}</strong>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-pink-soft">
                                                {{ number_format($detail->total, 2, ',', ' ') }} DH
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p class="mb-0">Aucune dépense variable</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières Dépenses -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2 text-danger"></i>
                            Dernières Transactions
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Type</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th class="text-end pe-4">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allDepenses = collect($dernieresDepenses['fixes'])->map(function($d) {
                                            return (object)[
                                                'type_badge' => 'Fixe',
                                                'badge_class' => 'badge-fixe',
                                                'icon' => 'fa-building',
                                                'description' => $d->description ?? $d->type,
                                                'date' => $d->date_depense,
                                                'montant' => $d->montant
                                            ];
                                        })->merge(
                                            collect($dernieresDepenses['variables'])->map(function($d) {
                                                return (object)[
                                                    'type_badge' => 'Variable',
                                                    'badge_class' => 'badge-variable',
                                                    'icon' => 'fa-chart-pie',
                                                    'description' => $d->description ?? $d->type,
                                                    'date' => $d->date_depense,
                                                    'montant' => $d->montant
                                                ];
                                            })
                                        )->sortByDesc('date')->take(10);
                                    @endphp

                                    @forelse($allDepenses as $depense)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge {{ $depense->badge_class }}">
                                                <i class="fas {{ $depense->icon }} me-1"></i>
                                                {{ $depense->type_badge }}
                                            </span>
                                        </td>
                                        <td>{{ $depense->description }}</td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-end pe-4">
                                            <strong class="text-danger">{{ number_format($depense->montant, 2, ',', ' ') }} DH</strong>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p class="mb-0">Aucune transaction récente</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 bg-gradient-action">
                    <div class="card-body text-center py-4">
                        <h5 class="text-white mb-3 fw-bold">Actions Rapides</h5>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('depenses.fixes.index') }}" class="btn btn-white-outline">
                                <i class="fas fa-building me-2"></i>Gérer Dépenses Fixes
                            </a>
                            <a href="{{ route('depenses.variables.index') }}" class="btn btn-white-outline">
                                <i class="fas fa-chart-pie me-2"></i>Gérer Dépenses Variables
                            </a>
                            <a href="{{ route('depenses.rapport', ['mois' => $moisActuel]) }}" class="btn btn-white-outline">
                                <i class="fas fa-file-pdf me-2"></i>Rapport Mensuel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        /* Gradient Button */
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4);
            color: white;
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-radius: 15px;
            padding: 25px;
            color: white;
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.2);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(211, 47, 47, 0.3);
        }

        .stats-icon {
            font-size: 3rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .stats-content {
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .card-total {
            background: linear-gradient(135deg, #D32F2F, #B71C1C);
        }

        .card-fixe {
            background: linear-gradient(135deg, #C2185B, #880E4F);
        }

        .card-variable {
            background: linear-gradient(135deg, #E91E63, #AD1457);
        }

        /* Cards */
        .card {
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }

        /* Header Gradient */
        .bg-gradient-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        /* Badges */
        .badge-fixe {
            background: linear-gradient(135deg, #C2185B, #880E4F);
            padding: 8px 12px;
        }

        .badge-variable {
            background: linear-gradient(135deg, #E91E63, #AD1457);
            padding: 8px 12px;
        }

        .bg-danger-soft {
            background-color: rgba(211, 47, 47, 0.1);
            color: #D32F2F;
            font-weight: 600;
            padding: 8px 15px;
        }

        .bg-pink-soft {
            background-color: rgba(194, 24, 91, 0.1);
            color: #C2185B;
            font-weight: 600;
            padding: 8px 15px;
        }

        /* Table */
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(211, 47, 47, 0.05);
            transform: scale(1.01);
        }

        /* Gradient Action Card */
        .bg-gradient-action {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .btn-white-outline {
            background: white;
            color: #D32F2F;
            border: 2px solid white;
            font-weight: 600;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }

        .btn-white-outline:hover {
            background: transparent;
            color: white;
            transform: translateY(-2px);
        }

        /* Form Control */
        .form-control:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .row > div {
            animation: fadeIn 0.6s ease-out;
        }

        .row > div:nth-child(1) { animation-delay: 0.1s; }
        .row > div:nth-child(2) { animation-delay: 0.2s; }
        .row > div:nth-child(3) { animation-delay: 0.3s; }
    </style>

    <script>
        // Évolution Chart
        const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
        const evolutionData = @json($evolutionMensuelle);
        
        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: Object.keys(evolutionData).map(m => {
                    const d = new Date(m + '-01');
                    return d.toLocaleDateString('fr-FR', { month: 'short', year: '2-digit' });
                }),
                datasets: [{
                    label: 'Dépenses Fixes',
                    data: Object.values(evolutionData).map(v => v.fixe),
                    borderColor: '#C2185B',
                    backgroundColor: 'rgba(194, 24, 91, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#C2185B',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }, {
                    label: 'Dépenses Variables',
                    data: Object.values(evolutionData).map(v => v.variable),
                    borderColor: '#D32F2F',
                    backgroundColor: 'rgba(211, 47, 47, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#D32F2F',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 13, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + 
                                       context.parsed.y.toLocaleString('fr-FR') + ' DH';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR') + ' DH';
                            },
                            font: { size: 12 }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 12 }
                        }
                    }
                }
            }
        });

        // Répartition Chart
        const repartitionCtx = document.getElementById('repartitionChart').getContext('2d');
        
        new Chart(repartitionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Dépenses Fixes', 'Dépenses Variables'],
                datasets: [{
                    data: [{{ $totalFixe }}, {{ $totalVariable }}],
                    backgroundColor: [
                        'rgba(194, 24, 91, 0.8)',
                        'rgba(211, 47, 47, 0.8)'
                    ],
                    borderColor: ['#C2185B', '#D32F2F'],
                    borderWidth: 3,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 13, weight: 'bold' },
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + 
                                       context.parsed.toLocaleString('fr-FR') + ' DH (' + 
                                       percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>
    @endpush
</x-app-layout>