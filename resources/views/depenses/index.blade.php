<x-app-layout>
    <style>
        .card-depense {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        
        .card-depense:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 16px;
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            padding: 1rem;
        }
        
        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .timeline-item {
            border-left: 3px solid #e5e7eb;
            padding-left: 1.5rem;
            padding-bottom: 1.5rem;
            position: relative;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
        }
        
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">
                        <i class="fas fa-chart-line mr-3"></i>Gestion des Dépenses
                    </h1>
                    <p class="text-white text-opacity-90">Vue d'ensemble de vos finances</p>
                </div>
                
                <div class="filter-section">
                    <form method="GET" action="{{ route('depenses.index') }}" class="flex items-center space-x-3">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Sélectionner le mois</label>
                            <input type="month" name="mois" value="{{ $moisActuel }}" 
                                   class="border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-pink-600 focus:ring-2 focus:ring-pink-200 transition">
                        </div>
                        <button type="submit" class="mt-6 bg-gradient-to-r from-pink-600 to-red-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition transform hover:scale-105">
                            <i class="fas fa-search mr-2"></i>Filtrer
                        </button>
                    </form>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-white text-opacity-90 text-sm font-semibold">Dépenses Fixes</p>
                                <h2 class="text-4xl font-bold mt-2">{{ number_format($totalFixe, 2) }} DH</h2>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-4">
                                <i class="fas fa-lock text-3xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('depenses.fixes.index', ['mois' => $moisActuel]) }}" 
                           class="inline-flex items-center text-sm font-semibold text-white hover:text-opacity-80 transition">
                            Voir détails <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-white text-opacity-90 text-sm font-semibold">Dépenses Variables</p>
                                <h2 class="text-4xl font-bold mt-2">{{ number_format($totalVariable, 2) }} DH</h2>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-4">
                                <i class="fas fa-chart-bar text-3xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('depenses.variables.index', ['mois' => $moisActuel]) }}" 
                           class="inline-flex items-center text-sm font-semibold text-white hover:text-opacity-80 transition">
                            Voir détails <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-white text-opacity-90 text-sm font-semibold">Total Général</p>
                                <h2 class="text-4xl font-bold mt-2">{{ number_format($totalGeneral, 2) }} DH</h2>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-4">
                                <i class="fas fa-calculator text-3xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('depenses.rapport', ['mois' => $moisActuel]) }}" 
                           class="inline-flex items-center text-sm font-semibold text-white hover:text-opacity-80 transition">
                            Rapport complet <i class="fas fa-file-alt ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="card-depense">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-pie-chart text-purple-600 mr-2"></i>
                            Répartition Dépenses Fixes
                        </h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="chartDepensesFixes"></canvas>
                    </div>
                </div>

                <div class="card-depense">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-chart-pie text-pink-600 mr-2"></i>
                            Répartition Dépenses Variables
                        </h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="chartDepensesVariables"></canvas>
                    </div>
                </div>
            </div>

            {{-- Evolution Chart --}}
            <div class="card-depense mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                        Évolution sur 6 mois
                    </h3>
                </div>
                <div style="height: 350px; padding: 1.5rem;">
                    <canvas id="chartEvolution"></canvas>
                </div>
            </div>

            {{-- Recent Expenses --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card-depense">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-history text-purple-600 mr-2"></i>
                            Dernières Dépenses Fixes
                        </h3>
                    </div>
                    <div class="p-6">
                        @forelse($dernieresDepenses['fixes'] as $dep)
                        <div class="timeline-item">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ $dep->type }}</h4>
                                    <p class="text-sm text-gray-600">{{ $dep->date_depense->format('d/m/Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-lg text-purple-600">{{ number_format($dep->montant, 2) }} DH</p>
                                    <span class="badge-custom {{ $dep->statut == 'payé' ? 'bg-green-100 text-green-800' : ($dep->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $dep->statut }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-3"></i>
                            <p>Aucune dépense fixe</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="card-depense">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-pink-50 to-pink-100">
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-history text-pink-600 mr-2"></i>
                            Dernières Dépenses Variables
                        </h3>
                    </div>
                    <div class="p-6">
                        @forelse($dernieresDepenses['variables'] as $dep)
                        <div class="timeline-item">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ $dep->type }}</h4>
                                    <p class="text-sm text-gray-600">{{ $dep->date_depense->format('d/m/Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-lg text-pink-600">{{ number_format($dep->montant, 2) }} DH</p>
                                    <span class="badge-custom bg-blue-100 text-blue-800">
                                        {{ $dep->categorie }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-3"></i>
                            <p>Aucune dépense variable</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Colors
        const colors = {
            purple: ['#667eea', '#764ba2', '#9575cd', '#7e57c2', '#673ab7'],
            pink: ['#f093fb', '#f5576c', '#ec407a', '#d81b60', '#c2185b'],
            gradient: ['#fa709a', '#fee140', '#4facfe', '#00f2fe', '#43e97b']
        };

        // Chart Dépenses Fixes
        const ctxFixe = document.getElementById('chartDepensesFixes').getContext('2d');
        new Chart(ctxFixe, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($depensesFixesDetail->pluck('type')) !!},
                datasets: [{
                    data: {!! json_encode($depensesFixesDetail->pluck('total')) !!},
                    backgroundColor: colors.purple,
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'right',
                        labels: {
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed.toLocaleString() + ' DH';
                            }
                        }
                    }
                }
            }
        });

        // Chart Dépenses Variables
        const ctxVariable = document.getElementById('chartDepensesVariables').getContext('2d');
        new Chart(ctxVariable, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($depensesVariablesDetail->pluck('categorie')) !!},
                datasets: [{
                    data: {!! json_encode($depensesVariablesDetail->pluck('total')) !!},
                    backgroundColor: colors.pink,
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'right',
                        labels: {
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed.toLocaleString() + ' DH';
                            }
                        }
                    }
                }
            }
        });

        // Chart Évolution
        const ctxEvolution = document.getElementById('chartEvolution').getContext('2d');
        new Chart(ctxEvolution, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($evolutionMensuelle)) !!},
                datasets: [
                    {
                        label: 'Dépenses Fixes',
                        data: {!! json_encode(array_column($evolutionMensuelle, 'fixe')) !!},
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Dépenses Variables',
                        data: {!! json_encode(array_column($evolutionMensuelle, 'variable')) !!},
                        borderColor: '#f5576c',
                        backgroundColor: 'rgba(245, 87, 108, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#f5576c',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { 
                        position: 'top',
                        labels: {
                            padding: 20,
                            font: { size: 13, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' DH';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' DH';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>