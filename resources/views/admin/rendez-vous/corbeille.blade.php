<x-app-layout>
<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Rendez-Vous</h2>

<p class="alert alert-info">Cette liste contient tous les rendez-vous qui ont été supprimés. Vous pouvez les visualiser, les restaurer, ou les supprimer définitivement.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Titre</th>
            <th>Projet</th>
            <th>Date & Heure</th>
            <th>Statut</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rendezVous as $rdv)
            <tr>
                <td>{{ $rdv->titre }}</td>
                <td>{{ $rdv->projet->titre ?? 'N/A' }}</td>
                <td>{{ $rdv->date_formatee }}</td>
                <td>
                    <span class="badge badge-secondary">{{ $rdv->statut }}</span>
                </td>
                <td>{{ $rdv->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                    

                    <form method="POST" action="{{ route('admin.rendezvous.restore', $rdv->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.rendezvous.forceDelete', $rdv->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce Rendez-vous DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($rendezVous->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>