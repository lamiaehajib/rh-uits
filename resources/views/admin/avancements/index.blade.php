<x-app-layout>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1">Avancements du projet</h2>
                    <p class="text-muted mb-0">{{ $projet->nom }}</p>
                </div>
                <a href="{{ route('admin.avancements.create', $projet) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle étape
                </a>
            </div>
        </div>
    </div>

    <!-- Progress Global -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Progression globale</h5>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $pourcentageGlobal }}%"
                             aria-valuenow="{{ $pourcentageGlobal }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ round($pourcentageGlobal, 1) }}%
                        </div>
                    </div>
                    <small class="text-muted">Basé sur la moyenne de toutes les étapes</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des avancements -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Étapes d'avancement</h5>
                </div>
                <div class="card-body">
                    @if($avancements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Étape</th>
                                        <th>Statut</th>
                                        <th>Progression</th>
                                        <th>Date prévue</th>
                                        <th>Date réalisée</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($avancements as $avancement)
                                        <tr>
                                            <td>
                                                <strong>{{ $avancement->etape }}</strong>
                                                @if($avancement->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($avancement->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($avancement->statut)
                                                    @case('en cours')
                                                        <span class="badge bg-warning">En cours</span>
                                                        @break
                                                    @case('terminé')
                                                        <span class="badge bg-success">Terminé</span>
                                                        @break
                                                    @case('bloqué')
                                                        <span class="badge bg-danger">Bloqué</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 100px; height: 20px;">
                                                        <div class="progress-bar 
                                                            @if($avancement->pourcentage == 100) bg-success
                                                            @elseif($avancement->pourcentage >= 50) bg-info
                                                            @else bg-warning
                                                            @endif"
                                                            role="progressbar"
                                                            style="width: {{ $avancement->pourcentage }}%">
                                                        </div>
                                                    </div>
                                                    <span class="small">{{ $avancement->pourcentage }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $avancement->date_prevue ? $avancement->date_prevue->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                {{ $avancement->date_realisee ? $avancement->date_realisee->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.avancements.show', [$projet, $avancement]) }}" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.avancements.edit', [$projet, $avancement]) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.avancements.destroy', [$projet, $avancement]) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette étape ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
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
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune étape d'avancement créée pour ce projet.</p>
                            <a href="{{ route('admin.avancements.create', $projet) }}" class="btn btn-primary">
                                Créer la première étape
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Retour au projet -->
    <div class="row mt-3">
        <div class="col-12">
            <a href="{{ route('admin.projets.show', $projet) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au projet
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Script pour mise à jour rapide du pourcentage (optionnel)
function updatePourcentage(avancementId, newValue) {
    fetch(`/admin/projets/{{ $projet->id }}/avancements/${avancementId}/pourcentage`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            pourcentage: newValue
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
</x-app-layout>