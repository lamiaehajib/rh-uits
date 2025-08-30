<x-app-layout>
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Mes Projets</h1>
        
        @if($projets->count() > 0)
            <div class="row">
                @foreach($projets as $projet)
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <h5 class="card-title text-primary">{{ $projet->titre }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($projet->description, 100) }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <span class="badge 
                                            @if($projet->statut_projet === 'en cours') bg-primary
                                            @elseif($projet->statut_projet === 'terminé') bg-success
                                            @elseif($projet->statut_projet === 'en attente') bg-warning
                                            @else bg-danger
                                            @endif
                                            text-white">
                                            {{ ucfirst($projet->statut_projet) }}
                                        </span>
                                        @if($projet->date_fin)
                                            <small class="text-muted ms-2">Fin prévue: {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}</small>
                                        @endif
                                    </div>
                                    <a href="{{ route('client.projets.show', $projet) }}" class="btn btn-sm btn-outline-primary">
                                        Voir les détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-5x text-gray-300 mb-3"></i>
                <p class="h4 text-gray-500">Aucun projet n'a été trouvé.</p>
                <p class="text-muted">Contactez l'administrateur pour plus d'informations.</p>
            </div>
        @endif
    </div>
</x-app-layout>
