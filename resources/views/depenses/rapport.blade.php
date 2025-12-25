<x-app-layout>
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="hight mb-2" style="font-size: 2.5rem;">
                            <i class="fas fa-chart-line me-2"></i>Rapport Mensuel
                        </h1>
                        <p class="text-muted">Analyse détaillée des dépenses du mois</p>
                    </div>
                    <div>
                        <form method="GET" action="{{ route('depenses.rapport') }}" class="d-flex gap-2">
                            <input type="month" name="mois" value="{{ $mois }}" 
                                   class="form-control" style="max-width: 200px;">
                            <button type="submit" class="btn text-white" 
                                    style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @php
            $totalFixe = $rapportFixe->sum('total');
            $totalVariable = $rapportVariable->sum('total');
            $grandTotal = $totalFixe + $totalVariable;
        @endphp

        <!-- Cards Overview -->
        <div class="row g-4 mb-4">
            <!-- Total Général -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" 
                     style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-white-50 mb-2">TOTAL GÉNÉRAL</h6>
                                <h2 class="mb-0 fw-bold">{{ number_format($grandTotal, 2) }} DH</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dépenses Fixes -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">DÉPENSES FIXES</h6>
                                <h2 class="mb-0 fw-bold" style="color: #D32F2F;">
                                    {{ number_format($totalFixe, 2) }} DH
                                </h2>
                                <small class="text-muted">
                                    {{ $grandTotal > 0 ? number_format(($totalFixe / $grandTotal) * 100, 1) : 0 }}% du total
                                </small>
                            </div>
                            <div class="p-3 rounded-circle" style="background: rgba(211, 47, 47, 0.1);">
                                <i class="fas fa-file-invoice-dollar fa-2x" style="color: #D32F2F;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dépenses Variables -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">DÉPENSES VARIABLES</h6>
                                <h2 class="mb-0 fw-bold" style="color: #C2185B;">
                                    {{ number_format($totalVariable, 2) }} DH
                                </h2>
                                <small class="text-muted">
                                    {{ $grandTotal > 0 ? number_format(($totalVariable / $grandTotal) * 100, 1) : 0 }}% du total
                                </small>
                            </div>
                            <div class="p-3 rounded-circle" style="background: rgba(194, 24, 91, 0.1);">
                                <i class="fas fa-shopping-cart fa-2x" style="color: #C2185B;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Pie Chart - Répartition Globale -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold" style="color: #D32F2F;">
                            <i class="fas fa-chart-pie me-2"></i>Répartition Globale
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="globalChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bar Chart - Dépenses Fixes par Type -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold" style="color: #D32F2F;">
                            <i class="fas fa-chart-bar me-2"></i>Dépenses Fixes par Type
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="fixesChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Tables Row -->
        <div class="row g-4 mb-4">
            <!-- Table Dépenses Fixes -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white py-3" 
                         style="background: linear-gradient(135deg, #D32F2F, #C2185B);">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>Détail Dépenses Fixes
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th class="py-3">TYPE</th>
                                        <th class="py-3">STATUT</th>
                                        <th class="py-3">NOMBRE</th>
                                        <th class="py-3">MONTANT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rapportFixe as $item)
                                        <tr>
                                            <td class="py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="background: rgba(211, 47, 47, 0.1); color: #D32F2F;">
                                                    {{ $item->type }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                @if($item->statut === 'payé')
                                                    <span class="badge bg-success">Payé</span>
                                                @elseif($item->statut === 'en_attente')
                                                    <span class="badge bg-warning text-dark">En attente</span>
                                                @else
                                                    <span class="badge bg-secondary">Annulé</span>
                                                @endif
                                            </td>
                                            <td class="py-3 fw-bold">{{ $item->nombre }}</td>
                                            <td class="py-3 fw-bold" style="color: #D32F2F;">
                                                {{ number_format($item->total, 2) }} DH
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>Aucune dépense fixe pour ce mois</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($rapportFixe->count() > 0)
                                    <tfoot style="background-color: #f8f9fa;">
                                        <tr class="fw-bold">
                                            <td colspan="3" class="py-3">TOTAL</td>
                                            <td class="py-3" style="color: #D32F2F;">
                                                {{ number_format($totalFixe, 2) }} DH
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Dépenses Variables -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white py-3" 
                         style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                        <h5 class="mb-0">
                            <i class="fas fa-list-ul me-2"></i>Détail Dépenses Variables
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th class="py-3">CATÉGORIE</th>
                                        <th class="py-3">NOMBRE</th>
                                        <th class="py-3">MONTANT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rapportVariable as $item)
                                        <tr>
                                            <td class="py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="background: rgba(194, 24, 91, 0.1); color: #C2185B;">
                                                    {{ ucfirst(str_replace('_', ' ', $item->categorie)) }}
                                                </span>
                                            </td>
                                            <td class="py-3 fw-bold">{{ $item->nombre }}</td>
                                            <td class="py-3 fw-bold" style="color: #C2185B;">
                                                {{ number_format($item->total, 2) }} DH
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>Aucune dépense variable pour ce mois</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($rapportVariable->count() > 0)
                                    <tfoot style="background-color: #f8f9fa;">
                                        <tr class="fw-bold">
                                            <td colspan="2" class="py-3">TOTAL</td>
                                            <td class="py-3" style="color: #C2185B;">
                                                {{ number_format($totalVariable, 2) }} DH
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart - Dépenses Variables -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold" style="color: #C2185B;">
                            <i class="fas fa-chart-pie me-2"></i>Répartition des Dépenses Variables
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="variablesChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex gap-2 justify-content-end">
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="fas fa-print me-2"></i>Imprimer
                    </button>
                    <a href="{{ route('depenses.index') }}" class="btn text-white" 
                       style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                        <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Couleurs du thème
        const colors = {
            primary: '#D32F2F',
            secondary: '#C2185B',
            gradients: [
                'rgba(211, 47, 47, 0.8)',
                'rgba(194, 24, 91, 0.8)',
                'rgba(233, 30, 99, 0.8)',
                'rgba(156, 39, 176, 0.8)',
                'rgba(103, 58, 183, 0.8)',
                'rgba(63, 81, 181, 0.8)'
            ]
        };

        // Global Chart - Pie
        const globalCtx = document.getElementById('globalChart').getContext('2d');
        new Chart(globalCtx, {
            type: 'pie',
            data: {
                labels: ['Dépenses Fixes', 'Dépenses Variables'],
                datasets: [{
                    data: [{{ $totalFixe }}, {{ $totalVariable }}],
                    backgroundColor: [colors.primary, colors.secondary],
                    borderWidth: 0
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
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value.toLocaleString() + ' DH (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Fixes Chart - Bar
        const fixesCtx = document.getElementById('fixesChart').getContext('2d');
        const fixesData = @json($rapportFixe);
        const fixesLabels = fixesData.map(item => item.type);
        const fixesValues = fixesData.map(item => item.total);

        new Chart(fixesCtx, {
            type: 'bar',
            data: {
                labels: fixesLabels,
                datasets: [{
                    label: 'Montant (DH)',
                    data: fixesValues,
                    backgroundColor: colors.primary,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Montant: ' + context.parsed.y.toLocaleString() + ' DH';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' DH';
                            }
                        }
                    }
                }
            }
        });

        // Variables Chart - Doughnut
        const variablesCtx = document.getElementById('variablesChart').getContext('2d');
        const variablesData = @json($rapportVariable);
        const variablesLabels = variablesData.map(item => item.categorie.replace('_', ' ').toUpperCase());
        const variablesValues = variablesData.map(item => item.total);

        new Chart(variablesCtx, {
            type: 'doughnut',
            data: {
                labels: variablesLabels,
                datasets: [{
                    data: variablesValues,
                    backgroundColor: colors.gradients,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            font: { size: 11, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value.toLocaleString() + ' DH (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>