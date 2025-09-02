<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-red-50 to-rose-100 py-6">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8 bg-white rounded-2xl p-6 shadow-xl border border-gray-100">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent flex items-center">
                    <i class="fas fa-project-diagram mr-4 text-pink-500 text-4xl"></i>
                    Projets
                </h1>
                <p class="text-gray-600 mt-2">Gérez tous vos projets en un seul endroit</p>
            </div>
            <a href="{{ route('admin.projets.create') }}" class="mt-4 sm:mt-0 bg-gradient-to-r from-pink-600 to-red-600 hover:from-pink-700 hover:to-red-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl flex items-center">
                <i class="fas fa-plus mr-3 text-xl"></i>
                <span class="text-lg">Nouveau Projet</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 rounded-2xl shadow-xl mb-8 border-l-8 border-green-400">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-3xl mr-4"></i>
                    <div>
                        <h4 class="font-bold text-xl">Succès !</h4>
                        <p class="text-green-100">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-2xl shadow-xl p-8 border-l-8 border-blue-500 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-2">Total Projets</p>
                        <p class="text-4xl font-bold text-gray-800">{{ $projets->total() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-folder text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border-l-8 border-green-500 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-green-600 uppercase tracking-wider mb-2">En Cours</p>
                        <p class="text-4xl font-bold text-gray-800">{{ $projets->where('statut_projet', 'en cours')->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-emerald-700 w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-play text-white text-2xl animate-pulse"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border-l-8 border-indigo-500 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-2">Terminés</p>
                        <p class="text-4xl font-bold text-gray-800">{{ $projets->where('statut_projet', 'terminé')->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-700 w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border-l-8 border-yellow-500 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-yellow-600 uppercase tracking-wider mb-2">En Attente</p>
                        <p class="text-4xl font-bold text-gray-800">{{ $projets->where('statut_projet', 'en attente')->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-500 to-orange-700 w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-pause text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table des projets -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-pink-600 to-red-600 p-6 text-white">
                <h6 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-list-alt mr-3 text-2xl"></i>
                    Liste des Projets
                </h6>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                                <th class="text-left py-4 px-6 font-bold text-gray-700 text-lg">
                                    <i class="fas fa-tag mr-2 text-pink-500"></i>Titre
                                </th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 text-lg">
                                    <i class="fas fa-user mr-2 text-pink-500"></i>Client
                                </th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 text-lg">
                                    <i class="fas fa-calendar-plus mr-2 text-pink-500"></i>Date Début
                                </th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 text-lg">
                                    <i class="fas fa-calendar-check mr-2 text-pink-500"></i>Date Fin
                                </th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 text-lg">
                                    <i class="fas fa-flag mr-2 text-pink-500"></i>Statut
                                </th>
                                <th class="text-center py-4 px-6 font-bold text-gray-700 text-lg">
                                    <i class="fas fa-cogs mr-2 text-pink-500"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projets as $projet)
                            <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-pink-50 hover:to-red-50 transition-all duration-300">
                                <td class="py-6 px-6">
                                    <div>
                                        <strong class="text-lg text-gray-800 flex items-center">
                                            <i class="fas fa-folder-open text-pink-500 mr-3"></i>
                                            {{ $projet->titre }}
                                        </strong>
                                        @if($projet->description)
                                            <small class="text-gray-500 mt-2 block bg-gray-50 p-2 rounded-lg">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ Str::limit($projet->description, 50) }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                             <td class="py-6 px-6">
    @if($projet->users->isNotEmpty())
        @foreach($projet->users as $user)
            <div class="flex items-center mb-2">
                <div class="bg-gradient-to-br from-pink-500 to-red-600 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                    <i class="fas fa-user text-white text-lg"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800 text-base">{{ $user->name }}</div>
                    <div class="text-sm text-gray-500 flex items-center">
                        <i class="fas fa-envelope mr-1 text-pink-400"></i>
                        {{ $user->email }}
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <span class="text-gray-400 italic flex items-center">
            <i class="fas fa-user-slash mr-2"></i>
            Client non assigné
        </span>
    @endif
</td>
                                <td class="py-6 px-6">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-2 rounded-lg font-medium flex items-center">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $projet->date_debut->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="py-6 px-6">
                                    @if($projet->date_fin)
                                        <span class="bg-purple-100 text-purple-800 px-3 py-2 rounded-lg font-medium flex items-center">
                                            <i class="fas fa-calendar-check mr-2"></i>
                                            {{ $projet->date_fin->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 px-3 py-2 rounded-lg font-medium flex items-center">
                                            <i class="fas fa-question-circle mr-2"></i>
                                            Non définie
                                        </span>
                                    @endif
                                </td>
                                <td class="py-6 px-6">
                                    <span class="px-4 py-2 rounded-xl font-bold text-sm shadow-lg transform transition-all duration-300 hover:scale-110 flex items-center justify-center w-fit
                                        @switch($projet->statut_projet)
                                            @case('en cours') bg-gradient-to-r from-green-500 to-emerald-600 text-white border-2 border-green-400 @break
                                            @case('terminé') bg-gradient-to-r from-blue-500 to-indigo-600 text-white border-2 border-blue-400 @break
                                            @case('en attente') bg-gradient-to-r from-yellow-400 to-orange-500 text-white border-2 border-yellow-300 @break
                                            @default bg-gradient-to-r from-gray-400 to-gray-600 text-white border-2 border-gray-400 @break
                                        @endswitch">
                                        @switch($projet->statut_projet)
                                            @case('en cours') <i class="fas fa-play mr-2 animate-pulse"></i> @break
                                            @case('terminé') <i class="fas fa-check-double mr-2"></i> @break
                                            @case('en attente') <i class="fas fa-pause mr-2"></i> @break
                                            @default <i class="fas fa-info-circle mr-2"></i> @break
                                        @endswitch
                                        {{ ucfirst($projet->statut_projet) }}
                                    </span>
                                </td>
                                <td class="py-6 px-6">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="{{ route('admin.projets.show', $projet) }}" class="bg-gradient-to-r from-pink-500 to-pink-700 hover:from-pink-600 hover:to-pink-800 text-white p-4 rounded-2xl shadow-xl transform transition-all duration-300 hover:scale-125 hover:shadow-2xl" title="Voir les détails">
                                            <i class="fas fa-eye text-xl"></i>
                                        </a>
                                        <a href="{{ route('admin.projets.edit', $projet) }}" class="bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white p-4 rounded-2xl shadow-xl transform transition-all duration-300 hover:scale-125 hover:shadow-2xl" title="Modifier">
                                            <i class="fas fa-edit text-xl"></i>
                                        </a>
                                        <a href="{{ route('admin.avancements.index', $projet) }}" class="bg-gradient-to-r from-pink-500 to-pink-700 hover:from-pink-600 hover:to-pink-800 text-white p-4 rounded-2xl shadow-xl transform transition-all duration-300 hover:scale-125 hover:shadow-2xl" title="Gérer l'avancement">
                                            <i class="fas fa-tasks text-xl"></i>
                                        </a>
                                        <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white p-4 rounded-2xl shadow-xl transform transition-all duration-300 hover:scale-125 hover:shadow-2xl border-2 border-red-400" title="Supprimer">
                                                <i class="fas fa-trash text-xl"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-16">
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-12 mx-4">
                                        <div class="bg-gradient-to-br from-gray-300 to-gray-500 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                                            <i class="fas fa-folder-open text-white text-4xl"></i>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-600 mb-4">Aucun projet trouvé</h3>
                                        <p class="text-gray-500 mb-6">Commencez par créer votre premier projet</p>
                                        <a href="{{ route('admin.projets.create') }}" class="bg-gradient-to-r from-pink-600 to-red-600 hover:from-pink-700 hover:to-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 inline-flex items-center">
                                            <i class="fas fa-plus mr-2"></i>
                                            Créer le premier projet
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($projets->hasPages())
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-8 bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl">
                        <div class="mb-4 sm:mb-0">
                            <p class="text-lg text-gray-700 font-medium flex items-center">
                                <i class="fas fa-info-circle mr-2 text-pink-500"></i>
                                Affichage de <span class="font-bold text-pink-600 mx-1">{{ $projets->firstItem() ?? 0 }}</span> à 
                                <span class="font-bold text-pink-600 mx-1">{{ $projets->lastItem() ?? 0 }}</span>
                                sur <span class="font-bold text-pink-600 mx-1">{{ $projets->total() }}</span> résultats
                            </p>
                        </div>
                        <div class="pagination-wrapper">
                            {{ $projets->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Styles personnalisés pour les icônes et animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.icon-circle {
    height: 3rem;
    width: 3rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: float 3s ease-in-out infinite;
}

/* Styles pour la pagination */
.pagination-wrapper .pagination {
    display: flex;
    gap: 0.5rem;
}

.pagination-wrapper .page-link {
    background: linear-gradient(135deg, #C2185B, #D32F2F);
    border: 2px solid #ef4444;
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    font-weight: bold;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.pagination-wrapper .page-link:hover {
    background: linear-gradient(135deg, #D32F2F, #ef4444);
    transform: scale(1.1);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #ef4444, #C2185B);
    border-color: #C2185B;
    transform: scale(1.05);
}

/* Animation pour les boutons d'action */
.action-btn {
    position: relative;
    overflow: hidden;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.action-btn:hover::before {
    left: 100%;
}

/* Hover effects pour les cartes statistiques */
.stat-card:hover {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #C2185B, #D32F2F);
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #D32F2F, #ef4444);
}
</style>
</x-app-layout>