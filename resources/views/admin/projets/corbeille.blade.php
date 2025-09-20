<x-app-layout>
<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Projets</h2>

<p class="alert alert-info">Cette liste contient tous les projets qui ont été supprimés. Vous pouvez les visualiser, les restaurer, ou les supprimer définitivement.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Titre</th>
            <th>Statut</th>
            <th>Assigné(s)</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($projets as $projet)
            <tr>
                <td>{{ $projet->titre }}</td>
                <td>
                    <span class="badge badge-{{ $projet->statut_color }}">{{ $projet->statut_projet }}</span>
                </td>
                <td>
                    @foreach ($projet->users as $user)
                        <span class="badge badge-primary">{{ $user->name }}</span>
                    @endforeach
                </td>
                <td>{{ $projet->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                    

                    <form method="POST" action="{{ route('admin.projets.restore', $projet->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.projets.forceDelete', $projet->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce Projet DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($projets->isEmpty())
            <tr>
                <td colspan="5" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>