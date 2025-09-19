<x-app-layout>
    <h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Tâches</h2>

<p class="alert alert-info">Cette liste contient toutes les tâches qui ont été supprimées. Vous pouvez les visualiser, les restaurer, ou les supprimer définitivement.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Titre</th>
            <th>Créateur</th>
            <th>Assigné(s)</th>
            <th>Priorité</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($taches as $tache)
            <tr>
                <td>{{ $tache->titre }}</td>
                <td>{{ $tache->creator->name ?? 'N/A' }}</td>
                <td>
                    @foreach ($tache->users as $user)
                        <span class="badge badge-info">{{ $user->name }}</span>
                    @endforeach
                </td>
                <td>{{ $tache->priorite }}</td>
                <td>{{ $tache->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('taches.show', $tache->id) }}" class="btn btn-info btn-sm" title="Voir Détails" style="display:inline-block;">
                        <i class="fas fa-eye"></i> Voir
                    </a>

                    <form method="POST" action="{{ route('taches.restore', $tache->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('taches.forceDelete', $tache->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette Tâche DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($taches->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>