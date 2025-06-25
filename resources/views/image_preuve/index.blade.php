<x-app-layout>
  
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease-in-out;
        margin-bottom: 25px;
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-header {
        background-color: #D32F2F;
        color: white;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        padding: 1rem 1.5rem;
        font-size: 1.25rem;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-primary {
        background-color: #D32F2F;
        border-color: #D32F2F;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #bb2828;
        border-color: #bb2828;
    }
    .form-control:focus {
        border-color: #D32F2F;
        box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
    }
    .table th {
        background-color: #f2f2f2;
        color: #333;
    }
    .table td {
        vertical-align: middle;
    }
    .pagination .page-item.active .page-link {
        background-color: #D32F2F;
        border-color: #D32F2F;
    }
    .pagination .page-link {
        color: #D32F2F;
    }
    .badge-info {
        background-color: #2196F3;
    }
    .badge-success {
        background-color: #4CAF50;
    }
    .badge-danger {
        background-color: #D32F2F;
    }
    .action-buttons .btn {
        margin-right: 5px;
        transition: transform 0.2s ease;
    }
    .action-buttons .btn:hover {
        transform: translateY(-2px);
    }
    .stats-card {
        background: linear-gradient(45deg, #D32F2F, #bb2828);
        color: white;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        animation: fadeIn 1s ease-in-out;
    }
    .stats-card h4 {
        margin-bottom: 10px;
        font-weight: 600;
    }
    .stats-card .count {
        font-size: 2.5rem;
        font-weight: bold;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .filter-section {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
        animation: slideInFromTop 0.8s ease-out;
    }
    @keyframes slideInFromTop {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-images me-2"></i>Liste des Images Preuves</span>
                    @can('image_preuve-create')
                        <a href="{{ route('image_preuve.create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus-circle me-1"></i>Ajouter une nouvelle preuve</a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-md-4">
                            <div class="stats-card">
                                <i class="fas fa-list-alt fa-2x mb-2"></i>
                                <h4>Total Preuves</h4>
                                <div class="count">{{ $imagePreuves->total() }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <i class="fas fa-file-image fa-2x mb-2"></i>
                                <h4>Preuves d'image</h4>
                                <div class="count">
                                    {{-- Assuming you have a way to differentiate image from video.
                                         For example, by checking the file extension in the `media` field. --}}
                                    {{ $imagePreuves->filter(function($preuve) {
                                        return preg_match('/\.(jpeg|png|jpg|gif|svg)$/i', $preuve->media);
                                    })->count() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <i class="fas fa-file-video fa-2x mb-2"></i>
                                <h4>Preuves vidéo</h4>
                                <div class="count">
                                    {{-- Assuming you have a way to differentiate image from video. --}}
                                    {{ $imagePreuves->filter(function($preuve) {
                                        return preg_match('/\.(mp4|mkv|avi|mov)$/i', $preuve->media);
                                    })->count() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="filter-section mb-4">
                        <form action="{{ route('image_preuve.index') }}" method="GET" class="row g-3 align-items-center">
                            <div class="col-md-8">
                                <label for="search" class="visually-hidden">Recherche</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control" placeholder="Rechercher par titre ou description..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Filtrer</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Utilisateur</th>
                                    <th width="280px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($imagePreuves as $imagePreuve)
                                    <tr>
                                        <td>{{ $imagePreuve->id }}</td>
                                        <td>{{ $imagePreuve->titre }}</td>
                                        <td>{{ Str::limit($imagePreuve->description, 50) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($imagePreuve->date)->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($imagePreuve->user)
                                                <span class="badge bg-info text-dark">{{ $imagePreuve->user->name }}</span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            <form action="{{ route('image_preuve.destroy', $imagePreuve->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette preuve ?');">
                                                @can('image_preuve-show')
                                                    <a class="btn btn-sm btn-info" href="{{ route('image_preuve.show', $imagePreuve->id) }}" title="Voir"><i class="fas fa-eye"></i></a>
                                                    <a class="btn btn-sm btn-secondary" href="{{ route('image_preuve.download', $imagePreuve->id) }}" title="Télécharger"><i class="fas fa-download"></i></a>
                                                @endcan
                                                @can('image_preuve-edit')
                                                    <a class="btn btn-sm btn-primary" href="{{ route('image_preuve.edit', $imagePreuve->id) }}" title="Modifier"><i class="fas fa-edit"></i></a>
                                                @endcan
                                                @can('image_preuve-delete')
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                                                @endcan
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune image preuve trouvée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $imagePreuves->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>