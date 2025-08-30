<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Liste des Rendez-vous</h3>
                    <div>
                        <a href="{{ route('admin.rendez-vous.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau Rendez-vous
                        </a>
                        <a href="{{ route('admin.rendez-vous.planning') }}" class="btn btn-info">
                            <i class="fas fa-calendar-week"></i> Planning
                        </a>
                        <a href="{{ route('admin.rendez-vous.aujourdhui') }}" class="btn btn-warning">
                            <i class="fas fa-calendar-day"></i> Aujourd'hui
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($rendezVous->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date & Heure</th>
                                        <th>Titre</th>
                                        <th>Projet</th>
                                        <th>Client</th>
                                        <th>Lieu</th>
                                        <th>Statut</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rendezVous as $rdv)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $rdv->date_heure->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $rdv->date_heure->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $rdv->titre }}</div>
                                                @if($rdv->description)
                                                    <small class="text-muted">{{ Str::limit($rdv->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.projets.show', $rdv->projet) }}" class="text-decoration-none">
                                                    {{ $rdv->projet->titre }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $rdv->client->name }} 
                                            </td>
                                            <td>{{ $rdv->lieu ?? '-' }}</td>
                                            <td>
                                                @switch($rdv->statut)
                                                    @case('programmé')
                                                        <span class="badge bg-secondary">Programmé</span>
                                                        @break
                                                    @case('confirmé')
                                                        <span class="badge bg-primary">Confirmé</span>
                                                        @break
                                                    @case('terminé')
                                                        <span class="badge bg-success">Terminé</span>
                                                        @break
                                                    @case('annulé')
                                                        <span class="badge bg-danger">Annulé</span>
                                                        @break
                                                @endswitch
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

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $rendezVous->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun rendez-vous trouvé</h5>
                            <a href="{{ route('admin.rendez-vous.create') }}" class="btn btn-primary mt-2">
                                Créer le premier rendez-vous
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>