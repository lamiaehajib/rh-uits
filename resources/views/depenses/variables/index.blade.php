<x-app-layout>
    <style>
        .gradient-card {
            background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);
            border-radius: 16px;
            padding: 24px;
            color: white;
            box-shadow: 0 8px 24px rgba(211, 47, 47, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gradient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(211, 47, 47, 0.4);
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            transform: translateX(5px);
        }

        .stat-card.primary {
            border-color: #D32F2F;
        }

        .stat-card.secondary {
            border-color: #C2185B;
        }

        .stat-card.success {
            border-color: #4CAF50;
        }

        .stat-card.warning {
            border-color: #FF9800;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .table-custom {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .table-custom thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .table-custom thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 16px;
            border: none;
        }

        .table-custom tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-custom tbody tr:hover {
            background: linear-gradient(90deg, rgba(211, 47, 47, 0.05), rgba(194, 24, 91, 0.05));
            transform: scale(1.01);
        }

        .table-custom tbody td {
            padding: 14px 16px;
            vertical-align: middle;
        }

        .badge-custom {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-primes {
            background: linear-gradient(135deg, #E91E63, #F06292);
            color: white;
        }

        .badge-achats {
            background: linear-gradient(135deg, #2196F3, #64B5F6);
            color: white;
        }

        .badge-menages {
            background: linear-gradient(135deg, #4CAF50, #81C784);
            color: white;
        }

        .badge-bancaires {
            background: linear-gradient(135deg, #FF9800, #FFB74D);
            color: white;
        }

        .badge-publications {
            background: linear-gradient(135deg, #9C27B0, #BA68C8);
            color: white;
        }

        .badge-autres {
            background: linear-gradient(135deg, #607D8B, #90A4AE);
            color: white;
        }

        .icon-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 12px;
        }

        .icon-circle.primary {
            background: linear-gradient(135deg, rgba(211, 47, 47, 0.1), rgba(194, 24, 91, 0.1));
            color: #D32F2F;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .btn-action.edit {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .btn-action.delete {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }

        .btn-action:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 24px;
        }

        .page-title {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
            font-size: 2rem;
            margin-bottom: 24px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
        }

        .modal-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .amount-display {
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>

    <div class="container-fluid px-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">
                <i class="fas fa-chart-line me-2"></i>Dépenses Variables
            </h1>
            <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addDepenseModal">
                <i class="fas fa-plus me-2"></i>Nouvelle Dépense
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card primary">
                    <div class="icon-circle primary">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h6 class="text-muted mb-2">Total Dépenses</h6>
                    <div class="amount-display">{{ number_format($total, 2) }} DH</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card secondary">
                    <div class="icon-circle primary">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h6 class="text-muted mb-2">Nombre Transactions</h6>
                    <div class="amount-display">{{ $depenses->total() }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card success">
                    <div class="icon-circle primary">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h6 class="text-muted mb-2">Mois Actuel</h6>
                    <div class="amount-display" style="font-size: 1.5rem;">{{ \Carbon\Carbon::parse($mois)->format('M Y') }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card warning">
                    <div class="icon-circle primary">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h6 class="text-muted mb-2">Moyenne Journalière</h6>
                    <div class="amount-display" style="font-size: 1.3rem;">
                        {{ $depenses->count() > 0 ? number_format($total / \Carbon\Carbon::parse($mois)->daysInMonth, 2) : '0.00' }} DH
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('depenses.variables.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar me-2" style="color: #D32F2F;"></i>Mois
                    </label>
                    <input type="month" name="mois" class="form-control" value="{{ $mois }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-filter me-2" style="color: #D32F2F;"></i>Catégorie
                    </label>
                    <select name="categorie" class="form-select">
                        <option value="">Toutes les catégories</option>
                        @foreach(\App\Models\DepenseVariable::$categories as $key => $label)
                            <option value="{{ $key }}" {{ request('categorie') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-gradient me-2">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('depenses.variables.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>

       

        <!-- Table -->
        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-2"></i>ID</th>
                        <th><i class="fas fa-tag me-2"></i>Type</th>
                        <th><i class="fas fa-list me-2"></i>Catégorie</th>
                        <th><i class="fas fa-coins me-2"></i>Montant</th>
                        <th><i class="fas fa-calendar me-2"></i>Date</th>
                        <th><i class="fas fa-user me-2"></i>Bénéficiaire</th>
                        <th><i class="fas fa-file-alt me-2"></i>Justificatif</th>
                        <th><i class="fas fa-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($depenses as $depense)
                        <tr>
                            <td class="fw-bold">#{{ $depense->id }}</td>
                            <td>{{ $depense->type }}</td>
                            <td>
                                <span class="badge-custom badge-{{ str_replace('_', '-', $depense->categorie) }}">
                                    {{ \App\Models\DepenseVariable::$categories[$depense->categorie] ?? $depense->categorie }}
                                </span>
                            </td>
                            <td class="fw-bold" style="color: #D32F2F;">
                                {{ number_format($depense->montant, 2) }} DH
                            </td>
                            <td>
                                <i class="fas fa-calendar-day me-1" style="color: #C2185B;"></i>
                                {{ $depense->date_depense->format('d/m/Y') }}
                            </td>
                            <td>
                                @if($depense->beneficiaire)
                                    <i class="fas fa-user-circle me-1" style="color: #D32F2F;"></i>
                                    {{ $depense->beneficiaire->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                {{-- @if($depense->justificatif)
                                    <a href="{{ Storage::url($depense->justificatif) }}" target="_blank" 
                                       class="btn btn-sm btn-gradient">
                                        <i class="fas fa-file-download"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif --}}
                            </td>
                            <td>
                                <button class="btn-action edit" data-bs-toggle="modal" 
                                        data-bs-target="#editDepenseModal{{ $depense->id }}"
                                        title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('depenses.variables.destroy', $depense) }}" 
                                      class="d-inline" onsubmit="return confirm('Confirmer la suppression?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editDepenseModal{{ $depense->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="fas fa-edit me-2"></i>Modifier Dépense #{{ $depense->id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('depenses.variables.update', $depense) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Type *</label>
                                                    <select name="type" class="form-select" required>
                                                        @foreach(\App\Models\DepenseVariable::$types as $key => $label)
                                                            <option value="{{ $key }}" {{ $depense->type == $key ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Catégorie *</label>
                                                    <select name="categorie" class="form-select" required>
                                                        @foreach(\App\Models\DepenseVariable::$categories as $key => $label)
                                                            <option value="{{ $key }}" {{ $depense->categorie == $key ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Montant (DH) *</label>
                                                    <input type="number" name="montant" class="form-control" step="0.01" 
                                                           value="{{ $depense->montant }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Date *</label>
                                                    <input type="date" name="date_depense" class="form-control" 
                                                           value="{{ $depense->date_depense->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Bénéficiaire</label>
                                                    <select name="beneficiaire_id" class="form-select">
                                                        <option value="">- Aucun -</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}" 
                                                                {{ $depense->beneficiaire_id == $user->id ? 'selected' : '' }}>
                                                                {{ $user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Justificatif</label>
                                                    <input type="file" name="justificatif" class="form-control" 
                                                           accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold">Description</label>
                                                    <textarea name="description" class="form-control" rows="2">{{ $depense->description }}</textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold">Notes</label>
                                                    <textarea name="notes" class="form-control" rows="2">{{ $depense->notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Annuler
                                            </button>
                                            <button type="submit" class="btn btn-gradient">
                                                <i class="fas fa-save me-2"></i>Enregistrer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x mb-3" style="color: #ddd;"></i>
                                <p class="text-muted">Aucune dépense variable trouvée</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $depenses->links() }}
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addDepenseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Nouvelle Dépense Variable
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('depenses.variables.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Type *</label>
                                <select name="type" class="form-select" required>
                                    <option value="">- Sélectionner -</option>
                                    @foreach(\App\Models\DepenseVariable::$types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Catégorie *</label>
                                <select name="categorie" class="form-select" required>
                                    <option value="">- Sélectionner -</option>
                                    @foreach(\App\Models\DepenseVariable::$categories as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Montant (DH) *</label>
                                <input type="number" name="montant" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date *</label>
                                <input type="date" name="date_depense" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Bénéficiaire</label>
                                <select name="beneficiaire_id" class="form-select">
                                    <option value="">- Aucun -</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Justificatif</label>
                                <input type="file" name="justificatif" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Catégorie Chart
        const categorieData = @json($depenses->groupBy('categorie')->map(function($items, $key) {
            return [
                'label' => \App\Models\DepenseVariable::$categories[$key] ?? $key,
                'total' => $items->sum('montant')
            ];
        })->values());

        new Chart(document.getElementById('categorieChart'), {
            type: 'doughnut',
            data: {
                labels: categorieData.map(item => item.label),
                datasets: [{
                    data: categorieData.map(item => item.total),
                    backgroundColor: [
                        'rgba(233, 30, 99, 0.8)',
                        'rgba(33, 150, 243, 0.8)',
                        'rgba(76, 175, 80, 0.8)',
                        'rgba(255, 152, 0, 0.8)',
                        'rgba(156, 39, 176, 0.8)',
                        'rgba(96, 125, 139, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Top Dépenses Chart
        const topDepenses = @json($depenses->sortByDesc('montant')->take(5)->values());

        new Chart(document.getElementById('topDepensesChart'), {
            type: 'bar',
            data: {
                labels: topDepenses.map(d => d.type.substring(0, 20)),
                datasets: [{
                    label: 'Montant (DH)',
                    data: topDepenses.map(d => d.montant),
                    backgroundColor: 'rgba(211, 47, 47, 0.8)',
                    borderColor: 'rgba(211, 47, 47, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>