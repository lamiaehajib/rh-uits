<x-app-layout>
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Projets</h1>
        <a href="{{ route('admin.projets.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nouveau Projet</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Projets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">En Cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->where('statut_projet', 'en cours')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Terminés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->where('statut_projet', 'terminé')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En Attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->where('statut_projet', 'en attente')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pause fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table des projets -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Projets</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Client</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projets as $projet)
                        <tr>
                            <td>
                                <strong>{{ $projet->titre }}</strong>
                                @if($projet->description)
                                    <br><small class="text-muted">{{ Str::limit($projet->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($projet->client)
                                        <div class="mr-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small font-weight-bold">{{ $projet->client->name }}</div>
                                            <div class="small text-gray-500">{{ $projet->client->email }}</div>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">Client non assigné</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $projet->date_debut->format('d/m/Y') }}</td>
                            <td>
                                @if($projet->date_fin)
                                    {{ $projet->date_fin->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $projet->statut_color }} badge-pill">
                                    {{ ucfirst($projet->statut_projet) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.projets.show', $projet) }}" class="btn btn-info btn-sm" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.projets.edit', $projet) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.avancements.index', $projet) }}" class="btn btn-primary btn-sm" title="Gérer l'avancement">
                                        <i class="fas fa-tasks"></i>
                                    </a>
                                    <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-folder-open fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-gray-500">Aucun projet trouvé</p>
                                    <a href="{{ route('admin.projets.create') }}" class="btn btn-primary">
                                        Créer le premier projet
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <p class="text-sm text-gray-700">
                        Affichage de {{ $projets->firstItem() ?? 0 }} à {{ $projets->lastItem() ?? 0 }}
                        sur {{ $projets->total() }} résultats
                    </p>
                </div>
                <div>
                    {{ $projets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts Bootstrap pour faire fonctionner les dropdowns -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<style>
.icon-circle {
    height: 2rem;
    width: 2rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-group .btn {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    margin-right: 2px;
}
.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
</style>
</x-app-layout>
