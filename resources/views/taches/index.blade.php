<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Gestion des Tâches</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary-red': '#D32F2F',
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <style>
            /* CSS personnalisé pour les animations et l'esthétique améliorée */
            body {
                font-family: 'Inter', sans-serif;
            }

            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
                opacity: 0;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Effet de mise à l'échelle au survol des cartes */
            .card-hover-effect {
                transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            }

            .card-hover-effect:hover {
                transform: translateY(-5px) scale(1.01);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            /* Animation de pulsation pour les boutons/alertes importants */
            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.7;
                }
            }

            .animate-pulse-subtle {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            /* Dégradés et ombres des boutons */
            .btn-primary-red {
                background: linear-gradient(to right, #D32F2F, #B71C1C); /* Dégradé de rouge plus profond */
                box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3);
                transition: all 0.3s ease;
            }

            .btn-primary-red:hover {
                background: linear-gradient(to right, #B71C1C, #D32F2F);
                box-shadow: 0 6px 15px rgba(211, 47, 47, 0.4);
                transform: translateY(-2px);
            }

            .btn-secondary {
                background-color: #e5e7eb; /* gris-200 */
                color: #4b5563; /* gris-700 */
                transition: all 0.3s ease;
            }

            .btn-secondary:hover {
                background-color: #d1d5db; /* gris-300 */
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight border-b-2 border-primary-red pb-3 mb-6 animate-fade-in delay-100">
            <i class="fas fa-tasks mr-3 text-primary-red"></i> {{ __('Gestion des Tâches') }}
        </h2>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 shadow-md animate-fade-in" role="alert">
                        <strong class="font-bold">Succès!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Fermer</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 shadow-md animate-fade-in animate-pulse-subtle" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Fermer</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg animate-fade-in delay-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                            <div class="mb-4 md:mb-0">
                                <a href="{{ route('taches.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
                                @can('tache-create')
                                    <a href="{{ route('taches.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red">
                                        <i class="fas fa-plus-circle mr-2"></i> {{ __('Ajouter une Tâche') }}
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-blue-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-300">
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-700">{{ __('Total des Tâches') }}</h3>
                                    <p class="text-4xl font-extrabold text-blue-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-list-ul text-blue-500 text-5xl opacity-75"></i>
                            </div>
                            <div class="bg-yellow-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-400">
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-700">{{ __('Nouvelles Tâches') }}</h3>
                                    <p class="text-4xl font-extrabold text-yellow-900 mt-1">{{ $stats['nouveau'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-bell text-yellow-500 text-5xl opacity-75"></i>
                            </div>
                            <div class="bg-purple-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-500">
                                <div>
                                    <h3 class="text-lg font-semibold text-purple-700">{{ __('Tâches En Cours') }}</h3>
                                    <p class="text-4xl font-extrabold text-purple-900 mt-1">{{ $stats['en_cours'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-spinner text-purple-500 text-5xl animate-spin-slow opacity-75"></i>
                            </div>
                            <div class="bg-green-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-600">
                                <div>
                                    <h3 class="text-lg font-semibold text-green-700">{{ __('Tâches Terminées') }}</h3>
                                    <p class="text-4xl font-extrabold text-green-900 mt-1">{{ $stats['termine'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-check-circle text-green-500 text-5xl opacity-75"></i>
                            </div>
                            <div class="bg-red-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-700">
                                <div>
                                    <h3 class="text-lg font-semibold text-red-700">{{ __('Tâches en Retard') }}</h3>
                                    <p class="text-4xl font-extrabold text-red-900 mt-1">{{ $stats['overdue'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-exclamation-triangle text-primary-red text-5xl opacity-75 animate-pulse-subtle"></i>
                            </div>
                        </div>

                        <form action="{{ route('taches.index') }}" method="GET" class="mb-6 bg-gray-50 p-6 rounded-lg shadow-inner animate-fade-in delay-800">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Filtres et Tri') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700">{{ __('Recherche') }}</label>
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text" name="search" id="search" class="focus:ring-primary-red focus:border-primary-red block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="{{ __('Rechercher par titre, statut, etc.') }}" value="{{ request('search') }}">
                                    </div>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Statut') }}</label>
                                    <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('Tous les statuts') }}</option>
                                        <option value="nouveau" {{ request('status') == 'nouveau' ? 'selected' : '' }}>{{ __('Nouveau') }}</option>
                                        <option value="en cours" {{ request('status') == 'en cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                        <option value="termine" {{ request('status') == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="date_filter" class="block text-sm font-medium text-gray-700">{{ __('Filtre par Date') }}</label>
                                    <select id="date_filter" name="date_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                        <option value="" {{ !request('date_filter') ? 'selected' : '' }}>{{ __('Aucun filtre') }}</option>
                                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>{{ __('Aujourd\'hui') }}</option>
                                        <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>{{ __('Cette Semaine') }}</option>
                                        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>{{ __('Ce Mois-ci') }}</option>
                                        <option value="overdue" {{ request('date_filter') == 'overdue' ? 'selected' : '' }}>{{ __('En Retard') }}</option>
                                        <option value="future" {{ request('date_filter') == 'future' ? 'selected' : '' }}>{{ __('Futur') }}</option>
                                    </select>
                                </div>

                                @if (auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']))
                                    <div>
                                        <label for="user_filter" class="block text-sm font-medium text-gray-700">{{ __('Filtrer par Utilisateur') }}</label>
                                        <select id="user_filter" name="user_filter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                            <option value="all" {{ request('user_filter') == 'all' ? 'selected' : '' }}>{{ __('Tous les utilisateurs') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div>
                                    <label for="sort_by" class="block text-sm font-medium text-gray-700">{{ __('Trier par') }}</label>
                                    <select id="sort_by" name="sort_by" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('Date de Création') }}</option>
                                        <option value="datedebut" {{ request('sort_by') == 'datedebut' ? 'selected' : '' }}>{{ __('Date de Début') }}</option>
                                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>{{ __('Statut') }}</option>
                                        <option value="titre" {{ request('sort_by') == 'titre' ? 'selected' : '' }}>{{ __('Titre (A-Z)') }}</option>
                                        <option value="priorite" {{ request('sort_by') == 'priorite' ? 'selected' : '' }}>{{ __('Priorité') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="sort_direction" class="block text-sm font-medium text-gray-700">{{ __('Direction') }}</label>
                                    <select id="sort_direction" name="sort_direction" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>{{ __('Décroissant') }}</option>
                                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>{{ __('Croissant') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red">
                                    <i class="fas fa-filter mr-2"></i> {{ __('Appliquer les filtres') }}
                                </button>
                                <a href="{{ route('taches.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-full font-bold text-sm text-gray-700 uppercase tracking-wider shadow-md btn-secondary">
                                    <i class="fas fa-undo mr-2"></i> {{ __('Réinitialiser') }}
                                </a>
                            </div>
                        </form>

                        @if ($taches->isEmpty())
                            <div class="bg-gray-100 p-8 text-center text-gray-600 rounded-lg shadow-lg animate-fade-in delay-900">
                                <i class="fas fa-info-circle text-6xl text-gray-400 mb-4"></i>
                                <p class="text-xl font-semibold">{{ __('Aucune tâche trouvée.') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto relative shadow-lg sm:rounded-lg animate-fade-in delay-900">
                                <table class="w-full text-sm text-left text-gray-700">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 rounded-t-lg">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">
                                                <a href="{{ route('taches.index', array_merge(request()->query(), ['sort_by' => 'titre', 'sort_direction' => request('sort_by') == 'titre' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                    {{ __('Titre') }}
                                                    @if (request('sort_by') == 'titre')
                                                        @if (request('sort_direction') == 'asc')
                                                            <i class="fas fa-sort-alpha-down ml-1"></i>
                                                        @else
                                                            <i class="fas fa-sort-alpha-up-alt ml-1"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort ml-1 text-gray-400"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th scope="col" class="py-3 px-6">{{ __('Durée') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Date Début') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Statut') }}</th>
                                            <th scope="col" class="py-3 px-6">
                                                <a href="{{ route('taches.index', array_merge(request()->query(), ['sort_by' => 'priorite', 'sort_direction' => request('sort_by') == 'priorite' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                    {{ __('Priorité') }}
                                                    @if (request('sort_by') == 'priorite')
                                                        @if (request('sort_direction') == 'asc')
                                                            <i class="fas fa-sort-amount-down-alt ml-1"></i>
                                                        @else
                                                            <i class="fas fa-sort-amount-up-alt ml-1"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort ml-1 text-gray-400"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th scope="col" class="py-3 px-6">{{ __('Affectée à') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($taches as $tache)
                                            <tr class="bg-white border-b hover:bg-gray-50 transition duration-150 ease-in-out">
                                                <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                                    {{ Str::words($tache->titre, 5, '...') }}
                                                </td>
                                                <td class="py-4 px-6">{{ $tache->duree }}</td>
                                                <td class="py-4 px-6">{{ \Carbon\Carbon::parse($tache->datedebut)->format('d/m/Y') }}</td>
                                                <td class="py-4 px-6">
                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        @if($tache->status == 'nouveau') bg-yellow-200 text-yellow-800
                                                        @elseif($tache->status == 'en cours') bg-purple-200 text-purple-800
                                                        @elseif($tache->status == 'termine') bg-green-200 text-green-800
                                                        @endif">
                                                        {{ ucfirst($tache->status) }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        @if($tache->priorite == 'faible') bg-blue-200 text-blue-800
                                                        @elseif($tache->priorite == 'moyen') bg-orange-200 text-orange-800
                                                        @elseif($tache->priorite == 'élevé') bg-red-200 text-red-800
                                                        @endif">
                                                        {{ ucfirst($tache->priorite) }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-user-circle text-gray-400 mr-2"></i>
                                                        @foreach ($tache->users as $assignedUser)
                                                            {{ $assignedUser->name }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                    </div>
                                                </td>
                                                
                                                <td class="py-4 px-6 flex items-center space-x-4">
                                                    @can('tache-show')
        {{-- Hna kanswftou les filtres f link dial show --}}
        <a href="{{ route('taches.show', array_merge(['tach' => $tache->id], request()->query())) }}" title="Voir" class="text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out transform hover:scale-110">
            <i class="fas fa-eye text-lg"></i>
        </a>
    @endcan
                                                    @can('tache-edit')
    <a href="{{ route('taches.edit', array_merge(['tach' => $tache->id], request()->query())) }}" title="Modifier" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out transform hover:scale-110">
        <i class="fas fa-edit text-lg"></i>
    </a>
@endcan
                                                    @can('tache-delete')
                                                        <button type="button" title="Supprimer" onclick="if(confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette tâche ?') }}')) { document.getElementById('delete-form-{{ $tache->id }}').submit(); }" class="text-primary-red hover:text-red-700 transition duration-150 ease-in-out transform hover:scale-110">
                                                            <i class="fas fa-trash text-lg"></i>
                                                        </button>
                                                        <form id="delete-form-{{ $tache->id }}" action="{{ route('taches.destroy', $tache->id) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endcan
                                                    @can('tache-create')
                                                        <form action="{{ route('taches.duplicate', $tache->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir dupliquer cette tâche ?');" class="inline-block">
                                                            @csrf
                                                            <button type="submit" title="Dupliquer" class="text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                    @if($tache->status != 'termine')
                                                        <form id="complete-form-{{ $tache->id }}" action="{{ route('taches.complete', $tache->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" title="Marquer comme Terminé" class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out" onclick="return confirm('Voulez-vous vraiment marquer cette tâche comme terminée ?');">
                                                                <i class="fas fa-check-square text-lg"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-center">
                                {{ $taches->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // La fonction markAsComplete et la logique de modal personnalisée ont été supprimées
                // car les actions sont maintenant gérées via des soumissions de formulaire directes ou des confirmations natives.
            </script>
            
        @endpush
    </body>
</x-app-layout>