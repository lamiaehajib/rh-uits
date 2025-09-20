<x-app-layout>

<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Réclamations</h2>



<p class="alert alert-info">Cette liste contient toutes les réclamations qui ont été supprimées.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Référence</th>
            <th>Titre</th>
            <th>Créateur</th>
            <th>Statut</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reclamations as $reclamation)
            <tr>
                <td>{{ $reclamation->reference }}</td>
                <td>{{ $reclamation->titre }}</td>
                <td>{{ $reclamation->user->name ?? 'N/A' }}</td>
                <td>{{ $reclamation->status }}</td>
                <td>{{ $reclamation->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                   

                    <form method="POST" action="{{ route('reclamations.restore', $reclamation->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('reclamations.forceDelete', $reclamation->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette Réclamation DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($reclamations->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>