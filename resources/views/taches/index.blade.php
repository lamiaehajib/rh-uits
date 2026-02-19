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
            body { font-family: 'Inter', sans-serif; background: #f8f9fa; }
            .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; opacity: 0; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            .card-hover-effect { transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out; }
            .card-hover-effect:hover { transform: translateY(-5px) scale(1.01); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); }
            @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
            .animate-pulse-subtle { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
            .btn-primary-red { background: linear-gradient(to right, #D32F2F, #B71C1C); box-shadow: 0 4px 10px rgba(211,47,47,0.3); transition: all 0.3s ease; }
            .btn-primary-red:hover { background: linear-gradient(to right, #B71C1C, #D32F2F); box-shadow: 0 6px 15px rgba(211,47,47,0.4); transform: translateY(-2px); }
            .btn-secondary { background-color: #e5e7eb; color: #4b5563; transition: all 0.3s ease; }
            .btn-secondary:hover { background-color: #d1d5db; transform: translateY(-2px); }
            .btn-danger { background: linear-gradient(to right, #e74c3c, #c0392b); color: white; padding: 12px 24px; border-radius: 9999px; font-weight: bold; text-transform: uppercase; font-size: 0.875rem; box-shadow: 0 4px 10px rgba(231,76,60,0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; text-decoration: none; }
            .btn-danger:hover { background: linear-gradient(to right, #c0392b, #e74c3c); box-shadow: 0 6px 15px rgba(231,76,60,0.4); transform: translateY(-2px); color: white; }
            .full-width-container { max-width: 100% !important; padding-left: 24px !important; padding-right: 24px !important; }
            .content-wrapper { background: white; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); padding: 32px; margin-bottom: 24px; }
            .page-header { background: linear-gradient(135deg, #D32F2F 0%, #B71C1C 100%); color: white; padding: 24px 32px; border-radius: 16px; margin-bottom: 32px; box-shadow: 0 10px 25px rgba(211,47,47,0.3); }
            .page-header h2 { color: white; margin: 0; font-size: 2rem; font-weight: 700; display: flex; align-items: center; gap: 16px; }
            .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px; }
            .stat-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; position: relative; overflow: hidden; }
            .stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: currentColor; }
            .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
            .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
            .modern-table thead { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); }
            .modern-table thead th { padding: 18px 24px; text-align: left; font-weight: 600; color: #495057; text-transform: uppercase; font-size: 0.875rem; letter-spacing: 0.05em; }
            .modern-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid #f1f3f5; }
            .modern-table tbody tr:hover { background: #f8f9fa; transform: scale(1.01); }
            .modern-table tbody td { padding: 20px 24px; color: #495057; }
            .filter-section { background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-radius: 12px; padding: 28px; margin-bottom: 32px; border: 2px solid #e9ecef; }
            .filter-section h3 { color: #D32F2F; font-size: 1.25rem; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
            @media (max-width: 768px) {
                .full-width-container { padding-left: 16px !important; padding-right: 16px !important; }
                .content-wrapper { padding: 20px; }
                .page-header { padding: 20px; }
                .stats-grid { grid-template-columns: 1fr; }
            }
        </style>
    </head>
    <body>
        <div class="page-header animate-fade-in">
            <h2>
                <i class="fas fa-tasks"></i>
                {{ __('Gestion des Tâches') }}
            </h2>
        </div>

        <div class="py-6">
            <div class="full-width-container">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg relative mb-6 shadow-lg animate-fade-in" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-2xl mr-3"></i>
                            <div><strong class="font-bold">Succès!</strong><span class="block sm:inline ml-2">{{ session('success') }}</span></div>
                        </div>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';"><i class="fas fa-times text-green-500 hover:text-green-700"></i></span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg relative mb-6 shadow-lg animate-fade-in" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                            <div><strong class="font-bold">Erreur!</strong><span class="block sm:inline ml-2">{{ session('error') }}</span></div>
                        </div>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';"><i class="fas fa-times text-red-500 hover:text-red-700"></i></span>
                    </div>
                @endif

                <div class="content-wrapper animate-fade-in delay-200">
                    <!-- Boutons d'action -->
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                        <div class="flex gap-4 mb-4 md:mb-0">
                            <a href="{{ route('taches.corbeille') }}" class="btn-danger">
                                <i class="fa fa-trash mr-2"></i> Corbeille
                            </a>
                            @can('tache-create')
                                <a href="{{ route('taches.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red">
                                    <i class="fas fa-plus-circle mr-2"></i> {{ __('Ajouter une Tâche') }}
                                </a>
                            @endcan
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="stats-grid animate-fade-in delay-300">
                        <div class="stat-card text-blue-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Total des Tâches</h3>
                                    <p class="text-4xl font-extrabold">{{ $stats['total'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-list-ul text-5xl opacity-20"></i>
                            </div>
                        </div>

                        <div class="stat-card text-yellow-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Nouvelles Tâches</h3>
                                    <p class="text-4xl font-extrabold">{{ $stats['nouveau'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-bell text-5xl opacity-20"></i>
                            </div>
                        </div>

                        <div class="stat-card text-purple-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">En Cours</h3>
                                    <p class="text-4xl font-extrabold">{{ $stats['en_cours'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-spinner text-5xl opacity-20"></i>
                            </div>
                        </div>

                        <div class="stat-card text-green-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Terminées</h3>
                                    <p class="text-4xl font-extrabold">{{ $stats['termine'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-check-circle text-5xl opacity-20"></i>
                            </div>
                        </div>

                        <div class="stat-card text-red-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">En Retard</h3>
                                    <p class="text-4xl font-extrabold">{{ $stats['overdue'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-exclamation-triangle text-5xl opacity-20 animate-pulse-subtle"></i>
                            </div>
                        </div>

                        {{-- ✅ NOUVEAU : Carte Annulées (visible uniquement aux admins) --}}
                        @if (auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']))
                        <div class="stat-card text-gray-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Annulées</h3>
                                    <p class="text-4xl font-extrabold">{{ $stats['annule'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-ban text-5xl opacity-20"></i>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Filtres -->
                    <form action="{{ route('taches.index') }}" method="GET" class="filter-section animate-fade-in delay-400">
                        <h3>
                            <i class="fas fa-filter"></i>
                            {{ __('Filtres et Tri') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                            <!-- Recherche -->
                            <div>
                                <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Recherche') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" name="search" id="search" class="focus:ring-primary-red focus:border-primary-red block w-full pl-10 pr-3 py-3 sm:text-sm border-gray-300 rounded-lg" placeholder="{{ __('Rechercher...') }}" value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Statut -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Statut') }}</label>
                                <select id="status" name="status" class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('Tous les statuts') }}</option>
                                    <option value="nouveau" {{ request('status') == 'nouveau' ? 'selected' : '' }}>{{ __('Nouveau') }}</option>
                                    <option value="en cours" {{ request('status') == 'en cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                    <option value="termine" {{ request('status') == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                                    {{-- ✅ AJOUT : option Annulé (visible uniquement aux admins) --}}
                                    @if (auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']))
                                        <option value="annulé" {{ request('status') == 'annulé' ? 'selected' : '' }}>{{ __('Annulé') }}</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Filtre par Date -->
                            <div>
                                <label for="date_filter" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Filtre par Date') }}</label>
                                <select id="date_filter" name="date_filter" class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                    <option value="" {{ !request('date_filter') ? 'selected' : '' }}>{{ __('Aucun filtre') }}</option>
                                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>{{ __('Aujourd\'hui') }}</option>
                                    <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>{{ __('Cette Semaine') }}</option>
                                    <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>{{ __('Ce Mois-ci') }}</option>
                                    <option value="overdue" {{ request('date_filter') == 'overdue' ? 'selected' : '' }}>{{ __('En Retard') }}</option>
                                    <option value="future" {{ request('date_filter') == 'future' ? 'selected' : '' }}>{{ __('Futur') }}</option>
                                </select>
                            </div>

                            <!-- Filtre par Mois -->
                            <div>
                                <label for="month_filter" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt mr-1 text-primary-red"></i>{{ __('Mois') }}
                                </label>
                                <select id="month_filter" name="month_filter" class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                    <option value="all" {{ request('month_filter') == 'all' || !request('month_filter') ? 'selected' : '' }}>{{ __('Tous les mois') }}</option>
                                    <option value="1" {{ request('month_filter') == '1' ? 'selected' : '' }}>{{ __('Janvier') }}</option>
                                    <option value="2" {{ request('month_filter') == '2' ? 'selected' : '' }}>{{ __('Février') }}</option>
                                    <option value="3" {{ request('month_filter') == '3' ? 'selected' : '' }}>{{ __('Mars') }}</option>
                                    <option value="4" {{ request('month_filter') == '4' ? 'selected' : '' }}>{{ __('Avril') }}</option>
                                    <option value="5" {{ request('month_filter') == '5' ? 'selected' : '' }}>{{ __('Mai') }}</option>
                                    <option value="6" {{ request('month_filter') == '6' ? 'selected' : '' }}>{{ __('Juin') }}</option>
                                    <option value="7" {{ request('month_filter') == '7' ? 'selected' : '' }}>{{ __('Juillet') }}</option>
                                    <option value="8" {{ request('month_filter') == '8' ? 'selected' : '' }}>{{ __('Août') }}</option>
                                    <option value="9" {{ request('month_filter') == '9' ? 'selected' : '' }}>{{ __('Septembre') }}</option>
                                    <option value="10" {{ request('month_filter') == '10' ? 'selected' : '' }}>{{ __('Octobre') }}</option>
                                    <option value="11" {{ request('month_filter') == '11' ? 'selected' : '' }}>{{ __('Novembre') }}</option>
                                    <option value="12" {{ request('month_filter') == '12' ? 'selected' : '' }}>{{ __('Décembre') }}</option>
                                </select>
                            </div>

                            <!-- Filtre par Année -->
                            <div>
                                <label for="year_filter" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar mr-1 text-primary-red"></i>{{ __('Année') }}
                                </label>
                                <select id="year_filter" name="year_filter" class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                    <option value="all" {{ request('year_filter') == 'all' || !request('year_filter') ? 'selected' : '' }}>{{ __('Toutes les années') }}</option>
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}" {{ request('year_filter') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if (auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']))
                                <div>
                                    <label for="user_filter" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Utilisateur') }}</label>
                                    <select id="user_filter" name="user_filter" class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                        <option value="all" {{ request('user_filter') == 'all' ? 'selected' : '' }}>{{ __('Tous les utilisateurs') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Trier par -->
                            <div>
                                <label for="sort_by" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Trier par') }}</label>
                                <select id="sort_by" name="sort_by" class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('Date de Création') }}</option>
                                    <option value="datedebut" {{ request('sort_by') == 'datedebut' ? 'selected' : '' }}>{{ __('Date de Début') }}</option>
                                    <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>{{ __('Statut') }}</option>
                                    <option value="titre" {{ request('sort_by') == 'titre' ? 'selected' : '' }}>{{ __('Titre') }}</option>
                                    <option value="priorite" {{ request('sort_by') == 'priorite' ? 'selected' : '' }}>{{ __('Priorité') }}</option>
                                </select>
                            </div>

                            <!-- Direction -->
                            <div>
                                <label for="sort_direction" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Direction') }}</label>
                                <select id="sort_direction" name="sort_direction" class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                    <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>{{ __('Décroissant') }}</option>
                                    <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>{{ __('Croissant') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red">
                                <i class="fas fa-filter mr-2"></i> {{ __('Appliquer') }}
                            </button>
                            <a href="{{ route('taches.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-full font-bold text-sm text-gray-700 uppercase tracking-wider shadow-md btn-secondary">
                                <i class="fas fa-undo mr-2"></i> {{ __('Réinitialiser') }}
                            </a>
                        </div>
                    </form>

                    @if(request('month_filter') && request('month_filter') !== 'all')
                        <button type="button" onclick="exportOverdueTasks()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg hover:from-orange-600 hover:to-red-700 transition-all duration-300 transform hover:scale-105 mb-6">
                            <i class="fas fa-download mr-2"></i> {{ __('Export Retard') }}
                        </button>
                    @endif

                    @if ($taches->isEmpty())
                        <div class="bg-gray-50 p-12 text-center rounded-lg shadow-lg animate-fade-in delay-500">
                            <i class="fas fa-inbox text-8xl text-gray-300 mb-6"></i>
                            <p class="text-2xl font-semibold text-gray-600">{{ __('Aucune tâche trouvée') }}</p>
                            <p class="text-gray-500 mt-2">{{ __('Commencez par créer votre première tâche') }}</p>
                        </div>
                    @else
                        <div class="overflow-x-auto animate-fade-in delay-500">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <a href="{{ route('taches.index', array_merge(request()->query(), ['sort_by' => 'titre', 'sort_direction' => request('sort_by') == 'titre' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-primary-red">
                                                {{ __('Titre') }}
                                                @if (request('sort_by') == 'titre')
                                                    <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ml-2"></i>
                                                @else
                                                    <i class="fas fa-sort ml-2 text-gray-400"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>{{ __('Durée') }}</th>
                                        <th>{{ __('Date Début') }}</th>
                                        <th>{{ __('Date de fin') }}</th>
                                        <th>{{ __('Statut') }}</th>
                                        <th>
                                            <a href="{{ route('taches.index', array_merge(request()->query(), ['sort_by' => 'priorite', 'sort_direction' => request('sort_by') == 'priorite' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-primary-red">
                                                {{ __('Priorité') }}
                                                @if (request('sort_by') == 'priorite')
                                                    <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ml-2"></i>
                                                @else
                                                    <i class="fas fa-sort ml-2 text-gray-400"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>{{ __('Affectée à') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($taches as $tache)
                                        {{-- ✅ Ligne grisée si annulée --}}
                                        <tr class="{{ $tache->status == 'annulé' ? 'opacity-60 bg-gray-50' : '' }}">
                                            <td class="font-semibold text-gray-900">
                                                {{ Str::words($tache->titre, 5, '...') }}
                                                {{-- ✅ Badge annulé sur le titre --}}
                                                @if($tache->status == 'annulé')
                                                    <span class="ml-2 px-2 py-0.5 bg-gray-200 text-gray-600 text-xs rounded-full"><i class="fas fa-ban mr-1"></i>Annulée</span>
                                                @endif
                                            </td>
                                            <td>{{ $tache->duree }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tache->datedebut)->format('d/m/Y') }}</td>
                                            <td>
                                               @if($tache->date_fin_prevue)
    @if(in_array($tache->date, ['heure', 'minute']))
        {{ \Carbon\Carbon::parse($tache->date_fin_prevue)->format('d/m/Y H:i') }}
    @else
        {{ \Carbon\Carbon::parse($tache->date_fin_prevue)->format('d/m/Y') }}
    @endif
@else
    <span class="text-gray-400">—</span>
@endif
                                            </td>
                                            <td>
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if($tache->status == 'nouveau') bg-yellow-100 text-yellow-800
                                                    @elseif($tache->status == 'en cours') bg-purple-100 text-purple-800
                                                    @elseif($tache->status == 'termine') bg-green-100 text-green-800
                                                    @elseif($tache->status == 'annulé') bg-gray-200 text-gray-600
                                                    @endif">
                                                    {{-- ✅ Icône ban pour annulé --}}
                                                    @if($tache->status == 'annulé')<i class="fas fa-ban mr-1"></i>@endif
                                                    {{ ucfirst($tache->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if($tache->priorite == 'faible') bg-blue-100 text-blue-800
                                                    @elseif($tache->priorite == 'moyen') bg-orange-100 text-orange-800
                                                    @elseif($tache->priorite == 'élevé') bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($tache->priorite) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="flex items-center justify-center">
                                                    <i class="fas fa-user-circle text-gray-400 mr-2"></i>
                                                    @foreach ($tache->users as $assignedUser)
                                                        {{ $assignedUser->name }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex items-center justify-center space-x-3">
                                                    @can('tache-show')
                                                        <a href="{{ route('taches.show', array_merge(['tach' => $tache->id], request()->query())) }}" title="Voir" class="text-blue-600 hover:text-blue-800 transition transform hover:scale-110">
                                                            <i class="fas fa-eye text-lg"></i>
                                                        </a>
                                                    @endcan
                                                    @can('tache-edit')
                                                        <a href="{{ route('taches.edit', array_merge(['tach' => $tache->id], request()->query())) }}" title="Modifier" class="text-indigo-600 hover:text-indigo-800 transition transform hover:scale-110">
                                                            <i class="fas fa-edit text-lg"></i>
                                                        </a>
                                                    @endcan
                                                    @can('tache-delete')
                                                        <button type="button" title="Supprimer" onclick="if(confirm('{{ __('Êtes-vous sûr ?') }}')) { document.getElementById('delete-form-{{ $tache->id }}').submit(); }" class="text-red-600 hover:text-red-800 transition transform hover:scale-110">
                                                            <i class="fas fa-trash text-lg"></i>
                                                        </button>
                                                        <form id="delete-form-{{ $tache->id }}" action="{{ route('taches.destroy', $tache->id) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endcan
                                                    @can('tache-create')
                                                        <form action="{{ route('taches.duplicate', $tache->id) }}" method="POST" onsubmit="return confirm('Dupliquer cette tâche ?');" class="inline-block">
                                                            @csrf
                                                            <button type="submit" title="Dupliquer" class="text-gray-600 hover:text-gray-900 transition transform hover:scale-110">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                    {{-- ✅ Bouton "Terminer" masqué si tâche annulée --}}
                                                    @if($tache->status != 'termine' && $tache->status != 'annulé')
                                                        <form id="complete-form-{{ $tache->id }}" action="{{ route('taches.complete', $tache->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" title="Marquer comme Terminé" class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out" onclick="return confirm('Voulez-vous vraiment marquer cette tâche comme terminée ?');">
                                                                <i class="fas fa-check-square text-lg"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
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

        @push('scripts')
            <script>
                function exportOverdueTasks() {
                    const month = document.getElementById('month_filter').value;
                    const year = document.getElementById('year_filter').value;
                    if (!month || month === 'all') {
                        alert('Veuillez sélectionner un mois pour exporter les tâches en retard.');
                        return;
                    }
                    const monthNames = { '1': 'Janvier', '2': 'Février', '3': 'Mars', '4': 'Avril', '5': 'Mai', '6': 'Juin', '7': 'Juillet', '8': 'Août', '9': 'Septembre', '10': 'Octobre', '11': 'Novembre', '12': 'Décembre' };
                    const monthName = monthNames[month];
                    const yearLabel = year && year !== 'all' ? year : new Date().getFullYear();
                    if (confirm(`Télécharger les tâches en retard pour ${monthName} ${yearLabel} ?`)) {
                        const url = new URL('{{ route("taches.export.overdue") }}', window.location.origin);
                        url.searchParams.append('month_filter', month);
                        if (year && year !== 'all') url.searchParams.append('year_filter', year);
                        window.location.href = url.toString();
                    }
                }
            </script>
        @endpush
    </body>
</x-app-layout>