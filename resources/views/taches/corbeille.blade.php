<x-app-layout>
    <style>
        .gradient-primary {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #D32F2F, #ef4444);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .card-custom {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
        }

        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .table-custom thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .table-custom tbody tr {
            transition: all 0.2s ease;
            opacity: 0.7;
        }

        .table-custom tbody tr:hover {
            background-color: #fff5f5;
            opacity: 1;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .priority-badge-élevé {
            background-color: #ef4444;
            color: white;
        }

        .priority-badge-moyen {
            background-color: #f59e0b;
            color: white;
        }

        .priority-badge-faible {
            background-color: #10b981;
            color: white;
        }

        .empty-trash {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-trash i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 20px;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 2rem;">
                    <i class="fas fa-trash me-2"></i>Corbeille
                </h2>
                <p class="text-muted">Tâches supprimées - Vous pouvez les restaurer ou les supprimer définitivement</p>
            </div>
            <div>
                <a href="{{ route('taches.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux Tâches
                </a>
            </div>
        </div>

        <!-- Alert Info -->
        @if($taches->count() > 0)
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
            <div>
                <strong>Attention!</strong> Les tâches supprimées définitivement ne peuvent pas être récupérées.
            </div>
        </div>
        @endif

        <!-- Trash Table -->
        <div class="card card-custom">
            <div class="card-body p-0">
                @if($taches->count() > 0)
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Priorité</th>
                                <th>Date Création</th>
                                <th>Supprimé le</th>
                                <th>Créé par</th>
                                <th>Utilisateurs Assignés</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taches as $tache)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clipboard-list me-2 text-muted"></i>
                                        <span class="text-muted">{{ $tache->titre }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom priority-badge-{{ $tache->priorite }}">
                                        {{ ucfirst($tache->priorite) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($tache->created_at)->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($tache->deleted_at)->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    @if($tache->creator)
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $tache->creator->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        @foreach($tache->users->take(3) as $user)
                                        <span class="badge bg-secondary" title="{{ $user->name }}">
                                            {{ substr($user->name, 0, 2) }}
                                        </span>
                                        @endforeach
                                        @if($tache->users->count() > 3)
                                        <span class="badge bg-secondary">+{{ $tache->users->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- Restore Button -->
                                        <form action="{{ route('taches.restore', $tache->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="action-btn" style="background: #10b981;" 
                                                    title="Restaurer" 
                                                    onclick="return confirm('Voulez-vous restaurer cette tâche?')">
                                                <i class="fas fa-undo text-white"></i>
                                            </button>
                                        </form>

                                        <!-- Force Delete Button -->
                                        <form action="{{ route('taches.forceDelete', $tache->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn" style="background: #ef4444;" 
                                                    title="Supprimer définitivement" 
                                                    onclick="return confirm('⚠️ ATTENTION! Cette action est irréversible. Voulez-vous vraiment supprimer définitivement cette tâche?')">
                                                <i class="fas fa-trash-alt text-white"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <!-- Empty State -->
                <div class="empty-trash">
                    <i class="fas fa-trash"></i>
                    <h4 class="text-muted mb-2">La corbeille est vide</h4>
                    <p class="text-muted">Aucune tâche supprimée pour le moment</p>
                    <a href="{{ route('taches.index') }}" class="btn btn-gradient mt-3">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour aux Tâches
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        @if($taches->count() > 0)
        <div class="card card-custom mt-4" style="border-left: 4px solid #C2185B;">
            <div class="card-body">
                <h6 class="gradient-text mb-3">
                    <i class="fas fa-info-circle me-2"></i>Informations
                </h6>
                <ul class="mb-0 text-muted small">
                    <li class="mb-2">
                        <i class="fas fa-undo text-success me-2"></i>
                        <strong>Restaurer:</strong> Restaure la tâche dans la liste principale avec toutes ses données
                    </li>
                    <li>
                        <i class="fas fa-trash-alt text-danger me-2"></i>
                        <strong>Supprimer définitivement:</strong> Supprime la tâche de manière permanente de la base de données
                    </li>
                </ul>
            </div>
        </div>
        @endif
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès!',
            text: '{{ session("success") }}',
            timer: 3000,
            showConfirmButton: false,
            background: '#fff',
            iconColor: '#10b981'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Erreur!',
            text: '{{ session("error") }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif
</x-app-layout>