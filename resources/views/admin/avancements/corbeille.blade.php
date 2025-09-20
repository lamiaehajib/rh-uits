<x-app-layout>
<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Avancements</h2>

<p class="alert alert-info">Cette liste contient toutes les étapes d'avancement qui ont été supprimées. Vous pouvez les visualiser, les restaurer, ou les supprimer définitivement.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Étape</th>
            <th>Projet</th>
            <th>Pourcentage</th>
            <th>Statut</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($avancements as $avancement)
            <tr>
                <td>{{ $avancement->etape }}</td>
                <td>{{ $avancement->projet->titre ?? 'Projet Supprimé' }}</td>
                <td>{{ $avancement->pourcentage }}%</td>
                <td>
                    <span class="badge badge-{{ $avancement->statut_color }}">{{ $avancement->statut }}</span>
                </td>
                <td>{{ $avancement->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                   

                    <form method="POST" action="{{ route('admin.avancements.restore', $avancement->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.avancements.forceDelete', $avancement->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet Avancement DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($avancements->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>

</x-app-layout>