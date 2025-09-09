<x-app-layout>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #C2185B 0%, #D32F2F 50%, #ef4444 100%);
        }
        
        .card-custom {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(194, 24, 91, 0.15);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(194, 24, 91, 0.25);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 20px;
            border: none;
        }
        
        .stat-card {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(239, 68, 68, 0.1));
            border-left: 5px solid #C2185B;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(194, 24, 91, 0.2);
        }
        
        .icon-bg {
            background: linear-gradient(135deg, #C2185B, #ef4444);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .progress-custom {
            height: 8px;
            border-radius: 10px;
            background: rgba(194, 24, 91, 0.1);
        }
        
        .progress-bar-custom {
            background: linear-gradient(90deg, #C2185B, #ef4444);
            border-radius: 10px;
        }
        
        .list-item-custom {
            border: none;
            border-radius: 12px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.7);
        }
        
        .list-item-custom:hover {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(239, 68, 68, 0.05));
            transform: translateX(5px);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #C2185B, #ef4444);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
        }
        
        .container-fluid {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.03), rgba(239, 68, 68, 0.03));
            min-height: 100vh;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        .badge-custom {
            background: linear-gradient(135deg, #C2185B, #ef4444);
            color: white;
            border-radius: 20px;
            padding: 5px 15px;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card shadow h-100 py-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-gradient text-uppercase mb-1">
                                    Projets en cours
                                </div>
                                <div class="h4 mb-0 font-weight-bold" style="color: #C2185B;">{{ $projetsEnCours }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-bg">
                                    <i class="fas fa-folder-open fa-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card shadow h-100 py-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-gradient text-uppercase mb-1">
                                    Projets terminés
                                </div>
                                <div class="h4 mb-0 font-weight-bold" style="color: #D32F2F;">{{ $projetsTermines }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-bg">
                                    <i class="fas fa-check fa-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card shadow h-100 py-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-gradient text-uppercase mb-1">
                                    Projets en attente
                                </div>
                                <div class="h4 mb-0 font-weight-bold" style="color: #ef4444;">{{ $projetsEnAttente }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-bg">
                                    <i class="fas fa-hourglass-half fa-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card shadow h-100 py-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-gradient text-uppercase mb-1">
                                    Projets annulés
                                </div>
                                <div class="h4 mb-0 font-weight-bold" style="color: #C2185B;">{{ $projetsAnnules }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-bg">
                                    <i class="fas fa-times-circle fa-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card card-custom shadow">
                    <div class="card-header-custom d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-chart-pie mr-2"></i>Répartition des projets
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="projetsDoughnutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card card-custom shadow">
                    <div class="card-header-custom d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-chart-bar mr-2"></i>Progression de vos projets
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="projetsProgressBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card card-custom shadow">
                    <div class="card-header-custom">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-clock mr-2"></i>Projets récents
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($projetsRecents->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($projetsRecents as $projet)
                                    <a href="{{ route('client.projets.show', $projet) }}" class="list-group-item list-item-custom list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-gradient">{{ $projet->titre }}</h6>
                                                <p class="mb-2 text-muted">{{ Str::limit($projet->description, 50) }}</p>
                                                @php
                                                    $pourcentage = $projet->avancements->sum('pourcentage');
                                                    if ($pourcentage > 100) {
                                                        $pourcentage = 100;
                                                    }
                                                @endphp
                                                <div class="progress progress-custom mt-2">
                                                    <div class="progress-bar progress-bar-custom" role="progressbar" style="width: {{ $pourcentage }}%;" aria-valuenow="{{ $pourcentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="text-muted mt-1">{{ number_format($pourcentage, 0) }}% achevé</small>
                                            </div>
                                            <div class="ml-3 text-right">
                                                <small class="badge badge-custom">{{ $projet->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted fst-italic">Aucun projet récent.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card card-custom shadow">
                    <div class="card-header-custom">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-calendar-alt mr-2"></i>L'intervention à venir
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($rendezVous->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($rendezVous as $rdv)
                                    <div class="list-group-item list-item-custom">
                                        <div class="d-flex w-100 justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-gradient">{{ $rdv->titre }}</h6>
                                                <p class="mb-1 text-muted">{{ $rdv->description }}</p>
                                            </div>
                                            <div class="ml-3 text-right">
                                                <small class="badge badge-custom">
                                                    <i class="fas fa-clock mr-1"></i>{{ $rdv->date_heure->format('d/m/Y à H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted fst-italic">Aucun intervention  de prévu.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card card-custom shadow">
                    <div class="card-header-custom">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Réclamations récentes
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($reclamations->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($reclamations as $reclamation)
                                    <div class="list-group-item list-item-custom">
                                        <div class="d-flex w-100 justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-gradient">{{ $reclamation->sujet }}</h6>
                                                <p class="mb-1 text-muted">{{ Str::limit($reclamation->description, 80) }}</p>
                                            </div>
                                            <div class="ml-3 text-right">
                                                <small class="badge badge-custom">{{ $reclamation->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted fst-italic">Aucune réclamation récente.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Custom colors
            const colors = {
                primary: '#C2185B',
                secondary: '#D32F2F',
                accent: '#ef4444'
            };

            // Doughnut Chart for project status
            const projetsDoughnutCtx = document.getElementById('projetsDoughnutChart');
            if (projetsDoughnutCtx) {
                new Chart(projetsDoughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [{
                            data: @json($chartData['data']),
                            backgroundColor: [colors.primary, colors.secondary, colors.accent, '#ff6b6b'],
                            hoverBackgroundColor: ['#a91648', '#b71c1c', '#dc2626', '#e55555'],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255,255,255,0.95)',
                                titleColor: colors.primary,
                                bodyColor: '#666',
                                borderColor: colors.primary,
                                borderWidth: 1,
                                cornerRadius: 8,
                                displayColors: true,
                            }
                        },
                        cutout: '70%',
                    },
                });
            }

            const projetsProgressBarCtx = document.getElementById('projetsProgressBarChart');
if (projetsProgressBarCtx) {
    const projets = @json($projetsRecents->map(function ($p) {
        $pourcentage = $p->avancements->sum('pourcentage');

        // Ensure the total doesn't exceed 100%
        if ($pourcentage > 100) {
            $pourcentage = 100;
        }

        return [
            'titre' => $p->titre,
            'pourcentage' => number_format($pourcentage, 0)
        ];
    }));

                const labels = projets.map(p => p.titre);
                const data = projets.map(p => p.pourcentage);

                new Chart(projetsProgressBarCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Progression (%)',
                            data: data,
                            backgroundColor: data.map((value, index) => {
                                const gradient = projetsProgressBarCtx.getContext('2d').createLinearGradient(0, 0, 300, 0);
                                gradient.addColorStop(0, colors.primary);
                                gradient.addColorStop(1, colors.accent);
                                return gradient;
                            }),
                            borderColor: colors.primary,
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 100,
                                grid: {
                                    color: 'rgba(194, 24, 91, 0.1)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value + "%"
                                    },
                                    color: colors.primary
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: colors.primary
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255,255,255,0.95)',
                                titleColor: colors.primary,
                                bodyColor: '#666',
                                borderColor: colors.primary,
                                borderWidth: 1,
                                cornerRadius: 8,
                            }
                        },
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>