<!-- resources/views/vente_objectifs/index.blade.php -->
<x-app-layout>
    <style>
        body {
             font-family: 'Arial', sans-serif;
             background-color: #f9f9f9;
             color: #333;
             padding: 20px;
         }
 
         h2 {
             color: #D32F2F;
             font-size: 1.8rem;
             margin-bottom: 20px;

         }
 
         .search-form {
             display: flex;
             justify-content: center;
             margin-bottom: 20px;
            
         }
 
         .form-control {
             width: 400px;
             padding: 10px;
             border: 1px solid #ddd;
             border-radius: 20px 0 0 20px;
             font-size: 16px;
             outline: none;
             margin-left: 200px;
             
         }
 
         .form-control:focus {
             border-color: #D32F2F;
         }
 
         .btn {
             background-color: #C2185B;
             color: #fff;
             border: none;
             padding: 10px 15px;
             border-radius: 0 20px 20px 0;
             cursor: pointer;
             transition: background-color 0.3s ease;
         }
 
         .btn:hover {
             background-color: #D32F2F;
         }
 
         .btn-primary {
     margin-top: 35px; /* Ajoute un espace au-dessus du bouton */
     margin-bottom: 15px; /* Espace en dessous */
     background-color: #D32F2F;
     border: none;
     padding: 10px 20px;
     font-size: 1rem;
     color: white;
     border-radius: 5px;
     
 }
 .button-container {
     
     display: flex;
     justify-content: center; /* Ajuste cette valeur pour descendre le bouton */
 }
 
 
 
 
         .btn-primary:hover {
             background-color: #C2185B;
         }
 
         .table {
             width:50%;
             border-collapse: collapse;
             background-color: #FFF;
             box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.05);
             border-radius: 8px;
             overflow: hidden;

         }
 
         th {
             background-color: #C2185B;
             color: white;
             padding: 12px;
             text-align: left;
         }
 
         td {
             padding: 10px;
             border: 1px solid #EEE;
         }
 
         tr:nth-child(even) {
             background-color: #FFEFF3;
         }
 
         tr:hover {
             background-color: #FFCDD2;
             transition: background-color 0.3s ease-in-out;
         }
 
         .alert-success {
             background-color: #388E3C;
             color: white;
             padding: 10px;
             border-radius: 5px;
         }
 
         .pagination {
             margin-top: 20px;
             display: flex;
             justify-content: center;
         }
 
         .pagination a {
             color: #D32F2F;
             text-decoration: none;
             padding: 8px 12px;
             border: 1px solid #D32F2F;
             border-radius: 5px;
         }
 
         .pagination a:hover {
             background-color: #D32F2F;
             color: white;
         }
 
         .btn-info {
             background-color: #1976D2;
             border: none;
             padding: 5px 10px;
             border-radius: 5px;
             color: white;
         }
 
         .btn-warning {
             background-color: #FF9800;
             border: none;
             padding: 5px 10px;
             border-radius: 5px;
             color: white;
         }
 
         .btn-danger {
             background-color: #D32F2F;
             border: none;
             padding: 5px 10px;
             border-radius: 5px;
             color: white;
         }
 
         .btn-danger:hover {
             background-color: #C2185B;
         }
 
         td.td-action {
             display: flex;
             gap: 10px;
         }
 
         .input-group {
             display: flex;
             justify-content: center;
         }
         .table-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 20vh; /* Ajuste la hauteur si nécessaire */
}

.table {
    margin: 0 auto; /* Assure que la table est bien centrée */
}
.h-container{
    text-align: center;
}

     </style>
         @can("vente-list")
<div class="container">

    <form method="GET" action="{{ route('vente_objectifs.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher par description" value="{{ request('search') }}">
            <button type="submit" class="btn"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <div class="button-container">
        @can("vente-create")
        <a href="{{ route('vente_objectifs.create') }}" class="btn btn-primary mb-3">Ajouter un Vente</a>
        @endcan
        </div>
        <div class="h-container">
    <h2>les Ventes</h2>
        </div>
    
   
    {{-- @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif --}}
    <div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Quantité PC</th>
                <th>Prix Achat</th>
                <th>Total Achat</th>
                <th>Prix Vendu</th>
                <th>Quantité Vendu</th>
                <th>Total Vendu</th>
                <th>marge</th>
                <th>user</th>
                <th>En Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venteObjectifs as $objectif)
                <tr>
                    <td>{{ $objectif->date }}</td>
                    <td>{{ $objectif->description }}</td>
                    <td>{{ $objectif->Quantitépc }}</td>
                    <td>{{ $objectif->prixachat }}</td>
                    <td>{{ $objectif->totalachat }}</td>
                    <td>{{ $objectif->prixvendu }}</td>
                    <td>{{ $objectif->Quantitévendu }}</td>
                    <td>{{ $objectif->totalvendu }}</td>
                    <td>{{ $objectif->marge }}</td>
                    <td>{{ $objectif->user->name }}</td>
                    <td>{{ $objectif->enstock }}</td>
                    <td class="td-action">
                        @can("vente-show")
                        <a href="{{ route('vente_objectifs.show', $objectif->id) }}" class="btn btn-info btn-sm"title="Voir">
                            <i class="fa fa-eye"></i>
                        </a>
                        @endcan
                        @can("vente-edit")
                        <a href="{{ route('vente_objectifs.edit', $objectif->id) }}" class="btn btn-warning"title="Modifier">
                            <i class="fa fa-edit"></i>
                        </a>
                        @endcan
                        @can("vente-delete")
                        <form action="{{ route('vente_objectifs.destroy', $objectif->id) }}" method="POST" style="display:inline;" id="delete-form-{{$objectif->id}}">
                            
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger"title="Supprimer"  onclick="confirmDelete({{$objectif->id }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>

                        
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    
    <div class="d-flex justify-content-center my-4">
        <nav aria-label="Page navigation">
            {{  $venteObjectifs->links('pagination.custom') }}
        </nav>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
               
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                
            });
        @endif
    });
</script>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette action est irréversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Soumettre le formulaire si l'utilisateur confirme
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endcan
</x-app-layout>
