<x-app-layout>
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="hight mb-2">
                            <i class="fas fa-receipt me-2"></i>
                            Dépenses Fixes
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Gestion des dépenses fixes et salaires
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addDepenseModal">
                            <i class="fas fa-plus-circle me-2"></i>
                            Nouvelle Dépense
                        </button>
                        <button type="button" class="btn btn-gradient-secondary" data-bs-toggle="modal" data-bs-target="#genererSalairesModal">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Générer Salaires
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres & Stats -->
        <div class="row mb-4">
            <!-- Stats Cards -->
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="stats-icon bg-gradient-pink">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stats-content">
                        <h6 class="text-muted mb-1">Total Dépenses</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($total, 2) }} DH</h3>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="stats-icon bg-gradient-red">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-content">
                        <h6 class="text-muted mb-1">Mois Actuel</h6>
                        <h3 class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($mois)->format('m/Y') }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="stats-icon bg-gradient-purple">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <div class="stats-content">
                        <h6 class="text-muted mb-1">Total Entrées</h6>
                        <h3 class="mb-0 fw-bold">{{ $depenses->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Row -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('depenses.fixes.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar me-2"></i>Mois
                        </label>
                        <input type="month" name="mois" value="{{ $mois }}" class="form-control custom-input">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-filter me-2"></i>Statut
                        </label>
                        <select name="statut" class="form-select custom-input">
                            <option value="">Tous les statuts</option>
                            <option value="payé" {{ request('statut') == 'payé' ? 'selected' : '' }}>Payé</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="annulé" {{ request('statut') == 'annulé' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="fas fa-search me-2"></i>Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th><i class="fas fa-tag me-2"></i>Type</th>
                                <th><i class="fas fa-align-left me-2"></i>Description</th>
                                <th><i class="fas fa-money-bill-wave me-2"></i>Montant</th>
                                <th><i class="fas fa-calendar me-2"></i>Date</th>
                                <th><i class="fas fa-info-circle me-2"></i>Statut</th>
                                <th><i class="fas fa-user me-2"></i>Créé par</th>
                                <th><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($depenses as $depense)
                            <tr>
                                <td class="fw-semibold">#{{ $depense->id }}</td>
                                <td>
                                    <span class="badge-type">
                                        @if($depense->type == 'SALAIRE')
                                            <i class="fas fa-user-tie me-1"></i>
                                        @elseif($depense->type == 'LOYER')
                                            <i class="fas fa-home me-1"></i>
                                        @elseif($depense->type == 'LYDEC')
                                            <i class="fas fa-bolt me-1"></i>
                                        @else
                                            <i class="fas fa-file-invoice me-1"></i>
                                        @endif
                                        {{ $depense->nom_affichage }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $depense->description ?? '-' }}</td>
                                <td class="fw-bold text-gradient">{{ number_format($depense->montant, 2) }} DH</td>
                                <td>
                                    <i class="fas fa-calendar-day me-1 text-muted"></i>
                                    {{ $depense->date_depense->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if($depense->statut == 'payé')
                                        <span class="badge-status badge-success">
                                            <i class="fas fa-check-circle me-1"></i>Payé
                                        </span>
                                    @elseif($depense->statut == 'en_attente')
                                        <span class="badge-status badge-warning">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    @else
                                        <span class="badge-status badge-danger">
                                            <i class="fas fa-times-circle me-1"></i>Annulé
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-user-circle me-1 text-muted"></i>
                                    {{ $depense->user->name ?? '-' }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-action btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editDepenseModal{{ $depense->id }}"
                                                title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('depenses.fixes.destroy', $depense) }}" 
                                              method="POST" 
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editDepenseModal{{ $depense->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-header">
                                            <h5 class="modal-title text-white">
                                                <i class="fas fa-edit me-2"></i>Modifier Dépense
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('depenses.fixes.update', $depense) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Type *</label>
                                                        <select name="type" class="form-select custom-input" required>
                                                            @foreach(\App\Models\DepenseFixe::$types as $key => $label)
                                                                <option value="{{ $key }}" {{ $depense->type == $key ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Montant (DH) *</label>
                                                        <input type="number" name="montant" step="0.01" value="{{ $depense->montant }}" class="form-control custom-input" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Date *</label>
                                                        <input type="date" name="date_depense" value="{{ $depense->date_depense->format('Y-m-d') }}" class="form-control custom-input" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Statut *</label>
                                                        <select name="statut" class="form-select custom-input" required>
                                                            <option value="payé" {{ $depense->statut == 'payé' ? 'selected' : '' }}>Payé</option>
                                                            <option value="en_attente" {{ $depense->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                            <option value="annulé" {{ $depense->statut == 'annulé' ? 'selected' : '' }}>Annulé</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Salarié</label>
                                                        <select name="salarie_id" class="form-select custom-input">
                                                            <option value="">-- Sélectionner --</option>
                                                            @foreach($salaries as $salarie)
                                                                <option value="{{ $salarie->id }}" {{ $depense->salarie_id == $salarie->id ? 'selected' : '' }}>
                                                                    {{ $salarie->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Description</label>
                                                        <input type="text" name="description" value="{{ $depense->description }}" class="form-control custom-input">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Notes</label>
                                                        <textarea name="notes" rows="3" class="form-control custom-input">{{ $depense->notes }}</textarea>
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
                                    <div class="empty-state">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Aucune dépense fixe trouvée</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($depenses->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $depenses->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addDepenseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-header">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-plus-circle me-2"></i>Nouvelle Dépense Fixe
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('depenses.fixes.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type *</label>
                                <select name="type" class="form-select custom-input" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach(\App\Models\DepenseFixe::$types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Montant (DH) *</label>
                                <input type="number" name="montant" step="0.01" class="form-control custom-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date *</label>
                                <input type="date" name="date_depense" class="form-control custom-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Statut *</label>
                                <select name="statut" class="form-select custom-input" required>
                                    <option value="en_attente">En attente</option>
                                    <option value="payé">Payé</option>
                                    <option value="annulé">Annulé</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Salarié</label>
                                <select name="salarie_id" class="form-select custom-input">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($salaries as $salarie)
                                        <option value="{{ $salarie->id }}">{{ $salarie->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <input type="text" name="description" class="form-control custom-input">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" rows="3" class="form-control custom-input"></textarea>
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

    <!-- Générer Salaires Modal -->
    <div class="modal fade" id="genererSalairesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient-header">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-money-bill-wave me-2"></i>Générer Salaires
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('depenses.fixes.generer-salaires') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Cette action va générer automatiquement les salaires pour tous les employés actifs du mois sélectionné.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mois *</label>
                            <input type="month" name="mois" value="{{ $mois }}" class="form-control custom-input" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-check me-2"></i>Générer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Gradient Buttons */
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .btn-gradient-secondary {
            background: linear-gradient(135deg, #8E24AA, #5E35B1);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(142, 36, 170, 0.3);
        }

        .btn-gradient-secondary:hover {
            background: linear-gradient(135deg, #5E35B1, #8E24AA);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(142, 36, 170, 0.4);
            color: white;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .bg-gradient-pink {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .bg-gradient-red {
            background: linear-gradient(135deg, #D32F2F, #F44336);
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #8E24AA, #5E35B1);
        }

        .stats-content h6 {
            margin: 0;
            font-size: 13px;
            font-weight: 500;
        }

        .stats-content h3 {
            margin: 0;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Custom Inputs */
        .custom-input {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.15);
        }

        /* Modern Table */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead tr {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .modern-table thead th {
            color: white;
            font-weight: 600;
            padding: 16px;
            border: none;
            text-align: center;
            font-size: 14px;
        }

        .modern-table thead th:first-child {
            border-top-left-radius: 8px;
        }

        .modern-table thead th:last-child {
            border-top-right-radius: 8px;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 16px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        /* Badges */
        .badge-type {
            display: inline-block;
            padding: 6px 12px;
            background: linear-gradient(135deg, #C2185B15, #D32F2F15);
            color: #D32F2F;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
        }

        .badge-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .text-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-edit {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .btn-edit:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }

        .btn-delete {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }

        .btn-delete:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
        }

        /* Modal */
        .bg-gradient-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        /* Empty State */
        .empty-state {
            padding: 40px 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-card {
                flex-direction: column;
                text-align: center;
            }

            .table-responsive {
                font-size: 12px;
            }

            .btn-gradient, .btn-gradient-secondary {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>

    @push('scripts')
    <script>
        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Cette action est irréversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Success message
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#D32F2F',
                timer: 3000
            });
        @endif
    </script>
    @endpush
</x-app-layout>