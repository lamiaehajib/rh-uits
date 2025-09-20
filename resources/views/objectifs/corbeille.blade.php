<x-app-layout>
<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Objectifs</h2>

<p class="alert alert-info">Cette liste contient tous les objectifs qui ont été supprimés (Suppression logique).</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Description</th>
            <th>Créateur</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($objectifs as $objectif)
            <tr>
                <td>{{ $objectif->date ? $objectif->date->format('Y-m-d') : 'N/A' }}</td>
                <td>{{ $objectif->type }}</td>
                <td>{{ \Illuminate\Support\Str::limit($objectif->description, 50) }}</td>
                <td>{{ $objectif->creator->name ?? 'Système' }}</td>
                <td>{{ $objectif->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                   

                    <form method="POST" action="{{ route('objectifs.restore', $objectif->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('objectifs.forceDelete', $objectif->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet Objectif DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($objectifs->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>