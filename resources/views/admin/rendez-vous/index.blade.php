<x-app-layout>
<style>
    :root {
        --primary-color: #C2185B;
        --secondary-color: #D32F2F;
        --accent-color: #ef4444;
        --dark-bg: #0a0a0a;
        --card-bg: rgba(255, 255, 255, 0.05);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --text-light: #ffffff;
        --text-muted: rgba(255, 255, 255, 0.7);
        --border-color: rgba(255, 255, 255, 0.1);
    }

    body {
        
        color: var(--text-light);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        min-height: 100vh;
    }

    .container-fluid {
        padding: 2rem;
        backdrop-filter: blur(10px);
    }

    .card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: 
            0 32px 64px rgba(0, 0, 0, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 40px 80px rgba(0, 0, 0, 0.5),
            inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .card-title {
        font-size: 2rem;
        font-weight: 800;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1;
    }

    .btn {
        border-radius: 12px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
        box-shadow: 0 8px 25px rgba(194, 24, 91, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(194, 24, 91, 0.4);
    }

    .btn-info {
        background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
        box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        box-shadow: 0 8px 25px rgba(255, 152, 0, 0.3);
    }

    .card-body {
        padding: 2rem;
        background: var(--glass-bg);
    }

    .alert {
        border-radius: 16px;
        border: none;
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
    }

    .alert-success {
        background: rgba(76, 175, 80, 0.15);
        border: 1px solid rgba(76, 175, 80, 0.3);
        color: #81c784;
    }

    .table-responsive {
        border-radius: 16px;
        overflow-x: auto;
        overflow-y: visible;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        max-width: 100%;
        /* Enhanced scrollbar styling */
        scrollbar-width: thin;
        scrollbar-color: var(--primary-color) rgba(255, 255, 255, 0.1);
    }

    /* Custom scrollbar for webkit browsers */
    .table-responsive::-webkit-scrollbar {
        height: 12px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 6px;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
    }

    .table {
        margin: 0;
        background: transparent;
        color: var(--text-light);
        min-width: 1200px; /* Force minimum width for horizontal scroll */
        white-space: nowrap; /* Prevent text wrapping */
    }

    .table-dark {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
    }

    .table-dark th {
        border: none;
        padding: 1.5rem 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
    }

    .table tbody tr {
        background: rgba(255, 255, 255, 0.02);
        transition: all 0.3s ease;
        border: none;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: scale(1.005);
    }

    .table tbody tr:nth-child(even) {
        background: rgba(255, 255, 255, 0.03);
    }

    .table tbody tr:nth-child(even):hover {
        background: rgba(255, 255, 255, 0.09);
    }

    .table td {
        border: none;
        padding: 1.5rem 1rem;
        vertical-align: middle;
        min-width: 150px; /* Minimum width for each column */
    }

    /* Specific widths for certain columns */
    .table td:first-child,
    .table th:first-child {
        min-width: 180px;
        position: sticky;
        left: 0;
        background: inherit;
        z-index: 5;
    }

    .table td:last-child,
    .table th:last-child {
        min-width: 200px;
        text-align: center;
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    .badge.bg-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important; }
    .badge.bg-primary { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important; }
    .badge.bg-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important; }
    .badge.bg-danger { background: linear-gradient(135deg, var(--accent-color) 0%, var(--secondary-color) 100%) !important; }
    .badge.bg-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important; }

    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        border-radius: 8px;
    }

    .btn-outline-info {
        border: 2px solid #17a2b8;
        color: #17a2b8;
        background: transparent;
    }

    .btn-outline-info:hover {
        background: #17a2b8;
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline-warning {
        border: 2px solid #ffc107;
        color: #ffc107;
        background: transparent;
    }

    .btn-outline-warning:hover {
        background: #ffc107;
        color: #000;
        transform: translateY(-1px);
    }

    .btn-outline-danger {
        border: 2px solid var(--accent-color);
        color: var(--accent-color);
        background: transparent;
    }

    .btn-outline-danger:hover {
        background: var(--accent-color);
        color: white;
        transform: translateY(-1px);
    }

    .fw-bold {
        font-weight: 700 !important;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    .text-decoration-none {
        text-decoration: none !important;
        color: var(--primary-color);
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .text-decoration-none:hover {
        color: var(--accent-color);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 16px;
        margin: 2rem 0;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }

    .page-link {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        border-radius: 8px;
        margin: 0 0.125rem;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        transform: translateY(-1px);
    }

    .page-item.active .page-link {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Scroll indicator */
    .scroll-indicator {
        position: relative;
        margin-bottom: 1rem;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .scroll-indicator::after {
        content: '← Faites défiler horizontalement pour voir plus de colonnes →';
        opacity: 0.7;
        font-style: italic;
    }

    /* Animation pour les éléments */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        animation: slideInUp 0.6s ease-out;
    }

    .table tbody tr {
        animation: slideInUp 0.3s ease-out;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }
        
        .card-header {
            padding: 1.5rem;
        }
        
        .card-title {
            font-size: 1.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .table {
            min-width: 1400px; /* Even wider on mobile to ensure all columns are visible */
        }
        
        .scroll-indicator::after {
            content: '← Balayez horizontalement →';
        }
    }

    small.text-muted {
        color: black !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tools me-3"></i>
                        Liste des interventions
                    </h3>
                    <div>
                        <a href="{{ route('admin.rendezvous.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
                        <a href="{{ route('admin.rendez-vous.create') }}" class="btn btn-primary me-2">
                            <i class="fas fa-plus me-2"></i> Nouveau l'intervention
                        </a>
                        <a href="{{ route('admin.rendez-vous.planning') }}" class="btn btn-info me-2">
                            <i class="fas fa-calendar-week me-2"></i> Planning
                        </a>
                        <a href="{{ route('admin.rendez-vous.aujourdhui') }}" class="btn btn-warning">
                            <i class="fas fa-calendar-day me-2"></i> Aujourd'hui
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($rendezVous->count() > 0)
                        <div class="scroll-indicator"></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-clock me-2"></i>Date & Heure</th>
                                        <th><i class="fas fa-tag me-2"></i>Titre</th>
                                        <th><i class="fas fa-project-diagram me-2"></i>Projet</th>
                                        <th><i class="fas fa-user me-2"></i>Client</th>
                                        <th><i class="fas fa-map-marker-alt me-2"></i>Lieu</th>
                                        <th><i class="fas fa-info-circle me-2"></i>Statut</th>
                                        <th><i class="fas fa-redo-alt me-2"></i>Date de Re-programmation</th>
                                        <th><i class="fas fa-redo-alt me-2"></i>confirmer</th>
                                        <th class="text-center"><i class="fas fa-cogs me-2"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rendezVous as $rdv)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $rdv->date_heure->format('d/m/Y') }}</div>
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $rdv->date_heure->format('H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $rdv->titre }}</div>
                                                @if($rdv->description)
                                                    <small class="text-muted">{{ Str::limit($rdv->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.projets.show', $rdv->projet) }}" class="text-decoration-none">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    {{ $rdv->projet->titre }}
                                                </a>
                                            </td>
                                            <td>
                                                @forelse($rdv->projet->users as $client)
                                                    <span class="badge bg-info text-dark mb-1 d-block">
                                                        <i class="fas fa-user me-1"></i>
                                                        {{ $client->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted fst-italic">
                                                        <i class="fas fa-minus-circle me-1"></i>
                                                        N/A
                                                    </span>
                                                @endforelse
                                            </td>
                                            <td>
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $rdv->lieu ?? '-' }}
                                            </td>
                                            <td>
                                                @switch($rdv->statut)
                                                    @case('programmé')
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            Programmé
                                                        </span>
                                                        @break
                                                    @case('confirmé')
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-check me-1"></i>
                                                            Confirmé
                                                        </span>
                                                        @break
                                                    @case('terminé')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Terminé
                                                        </span>
                                                        @break
                                                    @case('annulé')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i>
                                                            Annulé
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($rdv->reprogrammePar)
                                                    <span class="fw-bold">{{ $rdv->reprogrammePar->name }}</span>
                                                    <br>
                                                    <small class="text-muted">
                                                        Le {{ $rdv->date_heure->format('d/m/Y H:i') }}
                                                    </small>
                                                @else
                                                    <span class="text-muted fst-italic">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($rdv->confirmePar)
                                                    <span class="fw-bold">{{ $rdv->confirmePar->name }}</span>
                                                    <br>
                                                @else
                                                    <span class="text-muted fst-italic">N/A</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.rendez-vous.show', $rdv) }}"
                                                       class="btn btn-sm btn-outline-info" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.rendez-vous.edit', $rdv) }}"
                                                       class="btn btn-sm btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST"
                                                          action="{{ route('admin.rendez-vous.destroy', $rdv) }}"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Supprimer"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $rendezVous->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h5 class="text-muted mb-3">Aucun intervention trouvé</h5>
                            <a href="{{ route('admin.rendez-vous.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Créer le premier intervention
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</x-app-layout>