<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Gestion des Pointages') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            }

            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
                opacity: 0;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .card-hover-effect {
                transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            }

            .card-hover-effect:hover {
                transform: translateY(-5px) scale(1.01);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            .animate-pulse-subtle {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }

            .btn-primary-red {
                background: linear-gradient(to right, #D32F2F, #B71C1C);
                box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3);
                transition: all 0.3s ease;
            }

            .btn-primary-red:hover {
                background: linear-gradient(to right, #B71C1C, #D32F2F);
                box-shadow: 0 6px 15px rgba(211, 47, 47, 0.4);
                transform: translateY(-2px);
            }

            .btn-secondary-tailwind {
                background-color: #e5e7eb;
                color: #4b5563;
                transition: all 0.3s ease;
            }

            .btn-secondary-tailwind:hover {
                background-color: #d1d5db;
                transform: translateY(-2px);
            }

            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.6);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }

            .modal-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .modal-content {
                background: white;
                padding: 2.5rem;
                border-radius: 0.75rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                max-width: 90%;
                max-height: 90vh;
                overflow-y: auto;
                width: 500px;
                transform: translateY(-20px);
                transition: transform 0.3s ease-out;
            }

            .modal-overlay.show .modal-content {
                transform: translateY(0);
            }

            .late-pointage-row {
                color: #D32F2F;
                font-weight: bold;
            }

            .late-arrival-badge {
                background-color: #D32F2F;
                color: white;
                padding: 0.25rem 0.5rem;
                border-radius: 0.375rem;
                font-size: 0.75rem;
                margin-left: 0.5rem;
            }

            .stat-card {
                background: white;
                border-radius: 1rem;
                padding: 1.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
            }

            .chart-container {
                position: relative;
                height: 300px;
                background: white;
                border-radius: 1rem;
                padding: 1.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            }

            .absence-row {
                background-color: #fee;
            }

            .justificatif-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.25rem 0.75rem;
                border-radius: 0.375rem;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .justificatif-non-soumis {
                background-color: #fee;
                color: #dc2626;
            }

            .justificatif-pending {
                background-color: #fef3c7;
                color: #d97706;
            }

            .justificatif-valide {
                background-color: #d1fae5;
                color: #059669;
            }

            .action-btn {
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                font-size: 0.875rem;
                font-weight: 600;
                transition: all 0.2s;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

            .action-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body>
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight border-b-2 border-primary-red pb-3 mb-6 animate-fade-in">
            <i class="fas fa-fingerprint mr-3 text-primary-red"></i> {{ __('Gestion des Pointages') }}
        </h2>

        <div class="py-6">
            <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 shadow-md animate-fade-in animate-pulse-subtle" role="alert">
                        <strong class="font-bold">Succès!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 shadow-md animate-fade-in animate-pulse-subtle" role="alert">
                        <strong class="font-bold">Erreur!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                @endif

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8 animate-fade-in">
                    <div class="stat-card card-hover-effect">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Total Pointages</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_pointages'] }}</h3>
                            </div>
                            <div class="stat-icon bg-blue-100 text-blue-600">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card card-hover-effect">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">En Cours</p>
                                <h3 class="text-3xl font-bold text-yellow-600">{{ $stats['pointages_en_cours'] }}</h3>
                            </div>
                            <div class="stat-icon bg-yellow-100 text-yellow-600">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card card-hover-effect">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Retards</p>
                                <h3 class="text-3xl font-bold text-red-600">{{ $stats['retards'] }}</h3>
                            </div>
                            <div class="stat-icon bg-red-100 text-red-600">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card card-hover-effect">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Temps Total</p>
                                <h3 class="text-2xl font-bold text-green-600">{{ $stats['temps_total'] }}</h3>
                            </div>
                            <div class="stat-icon bg-green-100 text-green-600">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card card-hover-effect">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Absences</p>
                                <h3 class="text-3xl font-bold text-red-600">
                                    {{ $pointages->where('type', 'absence')->count() }}
                                </h3>
                            </div>
                            <div class="stat-icon bg-red-100 text-red-600">
                                <i class="fas fa-user-times"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-fade-in">
                    <div class="chart-container">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
                            Heures Travaillées (30 derniers jours)
                        </h3>
                        <canvas id="heuresChart"></canvas>
                    </div>

                    <div class="chart-container">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-pie mr-2 text-purple-600"></i>
                            Retards (30 derniers jours)
                        </h3>
                        <canvas id="retardsChart"></canvas>
                    </div>
                </div>
                @endif

                <!-- Filters Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 animate-fade-in">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <i class="fas fa-filter mr-3 text-indigo-600"></i>
                                {{ __('Filtres Avancés') }}
                            </h3>
                            
                            @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                            <div class="flex space-x-3">
                                <button onclick="exporterExcel()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 shadow-md">
                                    <i class="fas fa-file-excel mr-2"></i> Excel
                                </button>
                                <button onclick="exporterPdf()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 shadow-md">
                                    <i class="fas fa-file-pdf mr-2"></i> PDF
                                </button>
                            </div>
                            @endif
                        </div>

                        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                            <div>
                                <label for="periode" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Période') }}</label>
                                <select id="periode" name="periode" onchange="toggleDateInputs()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                                    <option value="">{{ __('Personnalisé') }}</option>
                                    <option value="today" {{ request('periode') == 'today' ? 'selected' : '' }}>{{ __('Aujourd\'hui') }}</option>
                                    <option value="yesterday" {{ request('periode') == 'yesterday' ? 'selected' : '' }}>{{ __('Hier') }}</option>
                                    <option value="this_week" {{ request('periode') == 'this_week' ? 'selected' : '' }}>{{ __('Cette semaine') }}</option>
                                    <option value="last_week" {{ request('periode') == 'last_week' ? 'selected' : '' }}>{{ __('Semaine dernière') }}</option>
                                    <option value="this_month" {{ request('periode') == 'this_month' ? 'selected' : '' }}>{{ __('Ce mois') }}</option>
                                    <option value="last_month" {{ request('periode') == 'last_month' ? 'selected' : '' }}>{{ __('Mois dernier') }}</option>
                                    <option value="this_year" {{ request('periode') == 'this_year' ? 'selected' : '' }}>{{ __('Cette année') }}</option>
                                </select>
                            </div>

                            <div id="date_debut_container">
                                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date début') }}</label>
                                <input type="date" id="date_debut" name="date_debut" value="{{ request('date_debut') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                            </div>

                            <div id="date_fin_container">
                                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date fin') }}</label>
                                <input type="date" id="date_fin" name="date_fin" value="{{ request('date_fin') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                            </div>

                            <div>
                                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Statut') }}</label>
                                <select id="statut" name="statut"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                                    <option value="">{{ __('Tous') }}</option>
                                    <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                    <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                                    <option value="retard" {{ request('statut') == 'retard' ? 'selected' : '' }}>{{ __('Retard') }}</option>
                                    <option value="depart_anticipe" {{ request('statut') == 'depart_anticipe' ? 'selected' : '' }}>{{ __('Départ anticipé') }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="type_pointage" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select id="type_pointage" name="type_pointage"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red">
                                    <option value="">Tous</option>
                                    <option value="presence" {{ request('type_pointage') == 'presence' ? 'selected' : '' }}>Présence</option>
                                    <option value="absence" {{ request('type_pointage') == 'absence' ? 'selected' : '' }}>Absence</option>
                                    <option value="conge" {{ request('type_pointage') == 'conge' ? 'selected' : '' }}>Congé</option>
                                </select>
                            </div>

                            @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Utilisateur') }}</label>
                                <select name="user_id" id="user_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                                    <option value="all">{{ __('Tous') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="justificatif_status" class="block text-sm font-medium text-gray-700 mb-1">Justificatif</label>
                                <select id="justificatif_status" name="justificatif_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red">
                                    <option value="">Tous</option>
                                    <option value="non_soumis" {{ request('justificatif_status') == 'non_soumis' ? 'selected' : '' }}>Non soumis</option>
                                    <option value="en_attente" {{ request('justificatif_status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="valide" {{ request('justificatif_status') == 'valide' ? 'selected' : '' }}>Validé</option>
                                </select>
                            </div>
                            @endif

                            <div class="flex items-end space-x-3">
                                <button type="submit"
                                    class="btn-primary-red flex-1 flex items-center justify-center px-5 py-2 rounded-md shadow-sm text-white font-medium">
                                    <i class="fas fa-search mr-2"></i>
                                    {{ __('Filtrer') }}
                                </button>
                                <a href="{{ route('pointage.index') }}"
                                    class="btn-secondary-tailwind flex items-center justify-center px-5 py-2 rounded-md shadow-sm font-bold text-sm uppercase tracking-wider">
                                    <i class="fas fa-undo mr-2"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg animate-fade-in">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <i class="fas fa-history mr-3 text-green-600"></i>
                                {{ __('Historique des Pointages') }}
                            </h3>
                            <div class="text-sm text-gray-500">
                                {{ $pointages->total() }} {{ __('résultat(s)') }}
                            </div>
                        </div>
                    </div>

                    @if($pointages->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Utilisateur') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Date') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Arrivée') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Départ') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Durée') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Statut') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Justificatif</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pointages as $pointage)
                                        @php
                                            $isLateForArrival = false;
                                            $isEarlyDeparture = false;

                                            if ($pointage->heure_arrivee) {
                                                $arriveeTime = \Carbon\Carbon::parse($pointage->heure_arrivee);
                                                $expectedArrivee = \Carbon\Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                                                if ($arriveeTime->greaterThan($expectedArrivee)) {
                                                    $isLateForArrival = true;
                                                }
                                            }

                                            if ($pointage->heure_depart) {
                                                $departTime = \Carbon\Carbon::parse($pointage->heure_depart);
                                                $expectedDepart = \Carbon\Carbon::parse($departTime->format('Y-m-d') . ' 17:30:00');
                                                if ($departTime->lessThan($expectedDepart)) {
                                                    $isEarlyDeparture = true;
                                                }
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition duration-200 {{ $pointage->type === 'absence' ? 'absence-row' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm font-medium">
                                                            {{ substr($pointage->user->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $pointage->user->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <i class="far fa-calendar-alt mr-1 text-gray-500"></i>{{ $pointage->date_pointage ? $pointage->date_pointage->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
    @if($pointage->type === 'absence')
        <span class="text-gray-400">-</span>
    @elseif($pointage->heure_arrivee)
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($isLateForArrival && !$pointage->retard_justifie) ? 'bg-primary-red text-white' : 'bg-green-100 text-green-800' }}">
            <i class="fas fa-play mr-1"></i>
            {{ \Carbon\Carbon::parse($pointage->heure_arrivee)->format('H:i') }}
        </span>
        @if($isLateForArrival)
            @if($pointage->retard_justifie)
                <span class="late-arrival-badge" style="background-color: #10b981; color: white;">
                    <i class="fas fa-check-circle mr-1"></i>{{ __('Justifié') }}
                </span>
            @else
                <span class="late-arrival-badge">
                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ __('Retard') }}
                </span>
            @endif
        @endif
    @else
        <span class="text-gray-400">-</span>
    @endif
</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($pointage->type === 'absence')
                                                    <span class="text-gray-400">-</span>
                                                @elseif($pointage->heure_depart)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isEarlyDeparture ? 'bg-primary-red text-white' : 'bg-primary-red text-white' }}">
                                                        <i class="fas fa-stop mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($pointage->heure_depart)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($pointage->type === 'absence')
                                                    <span class="text-gray-400">-</span>
                                                @elseif($pointage->heure_arrivee && $pointage->heure_depart)
                                                    @php
                                                        $arrivee = \Carbon\Carbon::parse($pointage->heure_arrivee);
                                                        $depart = \Carbon\Carbon::parse($pointage->heure_depart);
                                                        $duree = $arrivee->diffInMinutes($depart);
                                                        $heures = floor($duree / 60);
                                                        $minutes = $duree % 60;
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $heures }}h {{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}min
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($pointage->type === 'absence')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-ban mr-1"></i>
                                                        {{ __('Absent') }}
                                                    </span>
                                                @elseif($pointage->heure_depart)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        {{ __('Terminé') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 animate-pulse-subtle">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ __('En cours') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
    @if($pointage->type === 'absence')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
            <i class="fas fa-user-times mr-1"></i> Absence
        </span>
    @elseif($pointage->type === 'conge')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            <i class="fas fa-umbrella-beach mr-1"></i> En Congé
        </span>
    @else
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            <i class="fas fa-user-check mr-1"></i> Présence
        </span>
    @endif
</td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($pointage->type === 'absence')
                                                    @if($pointage->justificatif)
                                                        @if($pointage->justificatif_valide)
                                                            <span class="justificatif-badge justificatif-valide">
                                                                <i class="fas fa-check-circle mr-1"></i> Validé
                                                            </span>
                                                        @else
                                                            <span class="justificatif-badge justificatif-pending">
                                                                <i class="fas fa-clock mr-1"></i> En attente
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="justificatif-badge justificatif-non-soumis">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> Non soumis
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <div class="flex items-center gap-2">

                                                     <a href="{{ route('pointage.show', $pointage->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 transition duration-200 transform hover:scale-110" title="{{ __('Voir') }}">
                                                    <i class="fas fa-eye text-lg"></i>
                                                </a>
                                                    <!-- Bouton Voir Détails -->
                                                    <button onclick="voirHistorique({{ $pointage->id }})"
                                                        class="text-blue-600 hover:text-blue-800 transition duration-200 transform hover:scale-110" 
                                                        title="{{ __('Voir historique') }}">
                                                        <i class="fas fa-history text-lg"></i>
                                                    </button>
                                                    

                                                    <!-- Gestion RETARD (si présence en retard) -->
        @if($pointage->type === 'presence' && $pointage->isLate())
            @if(!$pointage->hasJustificatifRetard())
                <!-- Bouton Justifier le retard -->
                <button onclick="ouvrirModalJustificatifRetard({{ $pointage->id }})"
                    class="text-yellow-600 hover:text-yellow-800 transition duration-200 transform hover:scale-110" 
                    title="Justifier le retard">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                </button>
            @else
                <!-- Bouton Voir justificatif retard -->
                <button onclick="voirJustificatifRetard({{ $pointage->id }}, '{{ addslashes($pointage->justificatif_retard) }}', '{{ $pointage->justificatif_retard_file }}', {{ $pointage->retard_justifie ? 'true' : 'false' }})"
                    class="text-indigo-600 hover:text-indigo-800 transition duration-200 transform hover:scale-110" 
                    title="Voir justificatif retard">
                    <i class="fas fa-clock text-lg"></i>
                </button>
                
                @if(auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']) && !$pointage->retard_justifie)
                    <!-- Bouton Valider retard (Admin) -->
                    <button onclick="ouvrirModalValidationRetard({{ $pointage->id }})"
                        class="text-green-600 hover:text-green-800 transition duration-200 transform hover:scale-110" 
                        title="Valider/Rejeter retard">
                        <i class="fas fa-user-clock text-lg"></i>
                    </button>
                @endif
            @endif
        @endif
                                                    @if($pointage->type === 'absence')
                                                        @if(!$pointage->justificatif)
                                                            <!-- Bouton Soumettre Justificatif -->
                                                            <button onclick="ouvrirModalJustificatif({{ $pointage->id }})"
                                                                class="text-orange-600 hover:text-orange-800 transition duration-200 transform hover:scale-110" 
                                                                title="Soumettre justificatif">
                                                                <i class="fas fa-file-upload text-lg"></i>
                                                            </button>
                                                        @else
                                                            <!-- Bouton Voir Justificatif -->
                                                            <button onclick="voirJustificatif({{ $pointage->id }}, '{{ addslashes($pointage->justificatif) }}', '{{ $pointage->justificatif_file }}', {{ $pointage->justificatif_valide ? 'true' : 'false' }})"
                                                                class="text-purple-600 hover:text-purple-800 transition duration-200 transform hover:scale-110" 
                                                                title="Voir justificatif">
                                                                <i class="fas fa-file-alt text-lg"></i>
                                                            </button>
                                                            
                                                            @if(auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']) && !$pointage->justificatif_valide)
                                                                <!-- Bouton Valider (Admin uniquement) -->
                                                                <button onclick="ouvrirModalValidation({{ $pointage->id }})"
                                                                    class="text-green-600 hover:text-green-800 transition duration-200 transform hover:scale-110" 
                                                                    title="Valider/Rejeter">
                                                                    <i class="fas fa-check-double text-lg"></i>
                                                                </button>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $pointages->links('pagination::tailwind') }}
                        </div>
                    @else
                        <div class="p-6 text-center bg-gray-100 rounded-b-lg">
                            <div class="text-gray-400 text-6xl mb-4">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('Aucun pointage trouvé') }}</h3>
                            <p class="text-gray-600 mb-4">{{ __('Aucun pointage ne correspond à vos critères de recherche.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        
                                            <!-- Modal Justifier Retard -->
<div id="modalJustificatifRetard" class="modal-overlay">
    <div class="modal-content">
        <h3 class="text-xl font-bold mb-4 text-gray-800">
            <i class="fas fa-clock mr-2 text-yellow-600"></i>
            Justifier le retard
        </h3>
        <form id="formJustificatifRetard" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Un justificatif validé par l'administration annulera le retard.
                </p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Raison du retard <span class="text-red-500">*</span>
                </label>
                <textarea name="justificatif_retard" rows="4" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    placeholder="Ex: Embouteillage, problème de transport, urgence personnelle..."></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Document justificatif (optionnel)
                </label>
                <input type="file" name="justificatif_retard_file" accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPG, PNG, PDF (max 5MB)</p>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" class="btn-primary-red flex-1 px-4 py-2 rounded-md">
                    <i class="fas fa-paper-plane mr-2"></i> Soumettre
                </button>
                <button type="button" onclick="fermerModalJustificatifRetard()" 
                    class="btn-secondary-tailwind flex-1 px-4 py-2 rounded-md">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Voir Justificatif Retard -->
<div id="modalVoirJustificatifRetard" class="modal-overlay">
    <div class="modal-content">
        <h3 class="text-xl font-bold mb-4 text-gray-800">
            <i class="fas fa-clock mr-2 text-indigo-600"></i>
            Justificatif de retard
        </h3>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
            <div id="justifRetardStatut"></div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Raison</label>
            <div id="justifRetardRaison" class="bg-gray-50 p-3 rounded-md text-gray-800"></div>
        </div>
        
        <div id="justifRetardFichierContainer" class="mb-4" style="display:none;">
            <label class="block text-sm font-medium text-gray-700 mb-2">Document</label>
            <a id="justifRetardFichierLink" href="#" target="_blank" 
                class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                <i class="fas fa-download mr-2"></i> Télécharger le document
            </a>
        </div>
        
        <button type="button" onclick="fermerModalVoirJustificatifRetard()" 
            class="btn-secondary-tailwind w-full px-4 py-2 rounded-md">
            Fermer
        </button>
    </div>
</div>

<!-- Modal Validation Retard (Admin) -->
<div id="modalValidationRetard" class="modal-overlay">
    <div class="modal-content">
        <h3 class="text-xl font-bold mb-4 text-gray-800">
            <i class="fas fa-user-check mr-2 text-green-600"></i>
            Valider ou rejeter le justificatif de retard
        </h3>
        <form id="formValidationRetard" method="POST">
            @csrf
            
            <div class="mb-4 bg-blue-50 p-3 rounded-lg border border-blue-200">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Si validé, le retard ne sera plus comptabilisé dans les statistiques.
                </p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Décision <span class="text-red-500">*</span>
                </label>
                <select name="action" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-red">
                    <option value="">Choisir...</option>
                    <option value="valider">✅ Valider le justificatif (annuler le retard)</option>
                    <option value="rejeter">❌ Rejeter le justificatif (maintenir le retard)</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                <textarea name="commentaire_admin" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-red"
                    placeholder="Commentaire administratif..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" class="btn-primary-red flex-1 px-4 py-2 rounded-md">
                    <i class="fas fa-check mr-2"></i> Confirmer
                </button>
                <button type="button" onclick="fermerModalValidationRetard()" 
                    class="btn-secondary-tailwind flex-1 px-4 py-2 rounded-md">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
        <!-- Modal Historique Détaillé -->
        <div id="modalHistorique" class="modal-overlay">
            <div class="modal-content" style="max-width: 700px;">
                <h3 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">
                    <i class="fas fa-history mr-2 text-indigo-600"></i>
                    Détails du Pointage
                </h3>
                
                <div id="historiqueContent" class="space-y-4">
                    <!-- Le contenu sera injecté dynamiquement -->
                </div>
                
                <div class="mt-6 pt-4 border-t">
                    <button type="button" onclick="fermerModalHistorique()" 
                        class="btn-secondary-tailwind w-full px-4 py-3 rounded-md font-semibold">
                        <i class="fas fa-times mr-2"></i> Fermer
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Soumettre Justificatif -->
        <div id="modalJustificatif" class="modal-overlay">
            <div class="modal-content">
                <h3 class="text-xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-file-medical mr-2 text-orange-600"></i>
                    Soumettre un justificatif d'absence
                </h3>
                <form id="formJustificatif" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Raison de l'absence <span class="text-red-500">*</span>
                        </label>
                        <textarea name="justificatif" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-red"
                            placeholder="Ex: Maladie, rendez-vous médical, urgence familiale..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Document justificatif (optionnel)
                        </label>
                        <input type="file" name="justificatif_file" accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-red">
                        <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPG, PNG, PDF (max 5MB)</p>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" class="btn-primary-red flex-1 px-4 py-2 rounded-md">
                            <i class="fas fa-paper-plane mr-2"></i> Soumettre
                        </button>
                        <button type="button" onclick="fermerModalJustificatif()" 
                            class="btn-secondary-tailwind flex-1 px-4 py-2 rounded-md">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Modal Voir Justificatif -->
        <div id="modalVoirJustificatif" class="modal-overlay">
            <div class="modal-content">
                <h3 class="text-xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-file-alt mr-2 text-purple-600"></i>
                    Détails du justificatif
                </h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <div id="justifStatut"></div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Raison</label>
                    <div id="justifRaison" class="bg-gray-50 p-3 rounded-md text-gray-800"></div>
                </div>
                
                <div id="justifFichierContainer" class="mb-4" style="display:none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Document</label>
                    <a id="justifFichierLink" href="#" target="_blank" 
                        class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                        <i class="fas fa-download mr-2"></i> Télécharger le document
                    </a>
                </div>
                
                <button type="button" onclick="fermerModalVoirJustificatif()" 
                    class="btn-secondary-tailwind w-full px-4 py-2 rounded-md">
                    Fermer
                </button>
            </div>
        </div>
        
        <!-- Modal Validation (Admin) -->
        <div id="modalValidation" class="modal-overlay">
            <div class="modal-content">
                <h3 class="text-xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-user-check mr-2 text-green-600"></i>
                    Valider ou rejeter le justificatif
                </h3>
                <form id="formValidation" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Décision <span class="text-red-500">*</span>
                        </label>
                        <select name="action" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-red">
                            <option value="">Choisir...</option>
                            <option value="valider">✅ Valider le justificatif</option>
                            <option value="rejeter">❌ Rejeter le justificatif</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                        <textarea name="commentaire_admin" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-red"
                            placeholder="Commentaire administratif..."></textarea>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" class="btn-primary-red flex-1 px-4 py-2 rounded-md">
                            <i class="fas fa-check mr-2"></i> Confirmer
                        </button>
                        <button type="button" onclick="fermerModalValidation()" 
                            class="btn-secondary-tailwind flex-1 px-4 py-2 rounded-md">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
            <script>
                // Toggle date inputs
                function toggleDateInputs() {
                    const periode = document.getElementById('periode').value;
                    const dateDebutContainer = document.getElementById('date_debut_container');
                    const dateFinContainer = document.getElementById('date_fin_container');
                    
                    if (periode && periode !== '') {
                        dateDebutContainer.style.display = 'none';
                        dateFinContainer.style.display = 'none';
                    } else {
                        dateDebutContainer.style.display = 'block';
                        dateFinContainer.style.display = 'block';
                    }
                }

                document.addEventListener('DOMContentLoaded', () => {
                    toggleDateInputs();
                });

                // Export functions
                function exporterExcel() {
                    const currentUrl = new URL(window.location.href);
                    const params = currentUrl.searchParams.toString();
                    window.location.href = "{{ route('pointages.export.excel') }}" + (params ? '?' + params : '');
                }

                function exporterPdf() {
                    const currentUrl = new URL(window.location.href);
                    const params = currentUrl.searchParams.toString();
                    window.location.href = "{{ route('pointages.export.pdf') }}" + (params ? '?' + params : '');
                }

                // Voir Historique Détaillé
                function voirHistorique(pointageId) {
    fetch(`/suivre-pointages/${pointageId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.json();
    })
    .then(data => {
        let html = '<div class="space-y-4">';
        
        // Informations générales
        html += '<div class="bg-gray-50 p-4 rounded-lg">';
        html += '<h4 class="font-semibold text-gray-800 mb-3 flex items-center">';
        html += '<i class="fas fa-info-circle mr-2 text-blue-600"></i> Informations Générales';
        html += '</h4>';
        html += '<div class="grid grid-cols-2 gap-3 text-sm">';
        html += `<div><span class="text-gray-600">Utilisateur:</span> <span class="font-medium">${data.user}</span></div>`;
        html += `<div><span class="text-gray-600">Date:</span> <span class="font-medium">${data.date}</span></div>`;
        html += `<div><span class="text-gray-600">Type:</span> `;
        
        if (data.type === 'absence') {
            html += '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">';
            html += '<i class="fas fa-user-times mr-1"></i> Absence</span>';
        } else {
            html += '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">';
            html += '<i class="fas fa-user-check mr-1"></i> Présence</span>';
        }
        html += '</div>';
        html += `<div><span class="text-gray-600">Créé le:</span> <span class="font-medium">${data.created_at}</span></div>`;
        html += '</div></div>';

        // Si c'est une présence
        if (data.type === 'presence') {
            // Heures
            html += '<div class="bg-blue-50 p-4 rounded-lg">';
            html += '<h4 class="font-semibold text-gray-800 mb-3 flex items-center">';
            html += '<i class="fas fa-clock mr-2 text-blue-600"></i> Horaires';
            html += '</h4>';
            html += '<div class="grid grid-cols-2 gap-3 text-sm">';
            
            if (data.heure_arrivee) {
                html += '<div><span class="text-gray-600">Arrivée:</span> ';
                if (data.is_late) {
                    html += `<span class="font-bold text-red-600">${data.heure_arrivee}</span>`;
                    html += ` <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Retard: ${data.retard_minutes} min</span>`;
                } else {
                    html += `<span class="font-medium text-green-600">${data.heure_arrivee}</span>`;
                    html += ' <span class="text-xs text-green-600">✓ À l\'heure</span>';
                }
                html += '</div>';
            }
            
            if (data.heure_depart) {
                html += '<div><span class="text-gray-600">Départ:</span> ';
                if (data.is_early_departure) {
                    html += `<span class="font-bold text-orange-600">${data.heure_depart}</span>`;
                    html += ` <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">Anticipé: ${data.depart_minutes} min</span>`;
                } else {
                    html += `<span class="font-medium text-blue-600">${data.heure_depart}</span>`;
                }
                html += '</div>';
            } else {
                html += '<div><span class="text-gray-600">Départ:</span> ';
                html += '<span class="text-yellow-600 font-medium animate-pulse">En cours...</span></div>';
            }
            
            if (data.duree) {
                html += `<div class="col-span-2"><span class="text-gray-600">Durée totale:</span> `;
                html += `<span class="font-bold text-indigo-600 text-lg">${data.duree}</span></div>`;
            }
            
            html += '</div></div>';
        }

        // Si c'est une absence avec justificatif
        if (data.type === 'absence' && data.justificatif) {
            html += '<div class="bg-orange-50 p-4 rounded-lg">';
            html += '<h4 class="font-semibold text-gray-800 mb-3 flex items-center">';
            html += '<i class="fas fa-file-alt mr-2 text-orange-600"></i> Justificatif';
            html += '</h4>';
            
            if (data.justificatif_valide) {
                html += '<div class="mb-2"><span class="justificatif-badge justificatif-valide">';
                html += '<i class="fas fa-check-circle mr-1"></i> Validé par l\'administration</span></div>';
            } else {
                html += '<div class="mb-2"><span class="justificatif-badge justificatif-pending">';
                html += '<i class="fas fa-clock mr-1"></i> En attente de validation</span></div>';
            }
            
            html += `<div class="bg-white p-3 rounded border border-orange-200 text-sm">${data.justificatif}</div>`;
            html += '</div>';
        }

        // Localisation
        if (data.localisation) {
            html += '<div class="bg-green-50 p-4 rounded-lg">';
            html += '<h4 class="font-semibold text-gray-800 mb-2 flex items-center">';
            html += '<i class="fas fa-map-marker-alt mr-2 text-green-600"></i> Localisation';
            html += '</h4>';
            html += `<div class="text-sm text-gray-700">${data.localisation}</div>`;
            html += '</div>';
        }

        // Description
        if (data.description) {
            html += '<div class="bg-purple-50 p-4 rounded-lg">';
            html += '<h4 class="font-semibold text-gray-800 mb-2 flex items-center">';
            html += '<i class="fas fa-comment-alt mr-2 text-purple-600"></i> Description';
            html += '</h4>';
            html += `<div class="text-sm text-gray-700 whitespace-pre-wrap">${data.description}</div>`;
            html += '</div>';
        }

        html += '</div>';
        
        document.getElementById('historiqueContent').innerHTML = html;
        document.getElementById('modalHistorique').classList.add('show');
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du chargement des détails. Veuillez réessayer.');
    });
}

                function fermerModalHistorique() {
                    document.getElementById('modalHistorique').classList.remove('show');
                }

                // Justificatif functions
                function ouvrirModalJustificatif(pointageId) {
                    const modal = document.getElementById('modalJustificatif');
                    const form = document.getElementById('formJustificatif');
                    form.action = `/pointage/${pointageId}/justificatif/soumettre`;
                    modal.classList.add('show');
                }
                
                function fermerModalJustificatif() {
                    document.getElementById('modalJustificatif').classList.remove('show');
                }
                
                function voirJustificatif(pointageId, raison, fichier, valide) {
                    const modal = document.getElementById('modalVoirJustificatif');
                    
                    const statutHtml = valide 
                        ? '<span class="justificatif-badge justificatif-valide"><i class="fas fa-check-circle mr-1"></i> Validé</span>'
                        : '<span class="justificatif-badge justificatif-pending"><i class="fas fa-clock mr-1"></i> En attente de validation</span>';
                    document.getElementById('justifStatut').innerHTML = statutHtml;
                    
                    document.getElementById('justifRaison').textContent = raison;
                    
                    if (fichier) {
                        document.getElementById('justifFichierContainer').style.display = 'block';
                        document.getElementById('justifFichierLink').href = `/pointage/${pointageId}/justificatif/telecharger`;
                    } else {
                        document.getElementById('justifFichierContainer').style.display = 'none';
                    }
                    
                    modal.classList.add('show');
                }
                
                function fermerModalVoirJustificatif() {
                    document.getElementById('modalVoirJustificatif').classList.remove('show');
                }
                
                function ouvrirModalValidation(pointageId) {
                    const modal = document.getElementById('modalValidation');
                    const form = document.getElementById('formValidation');
                    form.action = `/pointage/${pointageId}/justificatif/valider`;
                    modal.classList.add('show');
                }
                
                function fermerModalValidation() {
                    document.getElementById('modalValidation').classList.remove('show');
                }
                
                // Close modals on outside click
                document.querySelectorAll('.modal-overlay').forEach(modal => {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            this.classList.remove('show');
                        }
                    });
                });

                // Charts initialization
                @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                document.addEventListener('DOMContentLoaded', async () => {
                    try {
                        const response = await fetch("{{ route('pointages.chart.data') }}");
                        const data = await response.json();

                        const heuresCtx = document.getElementById('heuresChart');
                        if (heuresCtx) {
                            new Chart(heuresCtx, {
                                type: 'line',
                                data: {
                                    labels: data.dates,
                                    datasets: [{
                                        label: 'Heures travaillées',
                                        data: data.heures_travaillees,
                                        borderColor: 'rgb(79, 70, 229)',
                                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                        tension: 0.4,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { display: false } },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: { callback: function(value) { return value + 'h'; } }
                                        }
                                    }
                                }
                            });
                        }

                        const retardsCtx = document.getElementById('retardsChart');
                        if (retardsCtx) {
                            new Chart(retardsCtx, {
                                type: 'bar',
                                data: {
                                    labels: data.dates,
                                    datasets: [{
                                        label: 'Retards',
                                        data: data.retards,
                                        backgroundColor: 'rgba(211, 47, 47, 0.8)',
                                        borderColor: 'rgb(211, 47, 47)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { display: false } },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: { stepSize: 1 }
                                        }
                                    }
                                }
                            });
                        }
                    } catch (error) {
                        console.error('Erreur graphiques:', error);
                    }
                });
                @endif

                function ouvrirModalJustificatifRetard(pointageId) {
    const modal = document.getElementById('modalJustificatifRetard');
    const form = document.getElementById('formJustificatifRetard');
    form.action = `/pointage/${pointageId}/justificatif-retard/soumettre`;
    modal.classList.add('show');
}

function fermerModalJustificatifRetard() {
    document.getElementById('modalJustificatifRetard').classList.remove('show');
}

function voirJustificatifRetard(pointageId, raison, fichier, valide) {
    const modal = document.getElementById('modalVoirJustificatifRetard');
    
    const statutHtml = valide 
        ? '<span class="justificatif-badge justificatif-valide"><i class="fas fa-check-circle mr-1"></i> Validé - Retard annulé</span>'
        : '<span class="justificatif-badge justificatif-pending"><i class="fas fa-clock mr-1"></i> En attente de validation</span>';
    document.getElementById('justifRetardStatut').innerHTML = statutHtml;
    
    document.getElementById('justifRetardRaison').textContent = raison;
    
    if (fichier) {
        document.getElementById('justifRetardFichierContainer').style.display = 'block';
        document.getElementById('justifRetardFichierLink').href = `/pointage/${pointageId}/justificatif-retard/telecharger`;
    } else {
        document.getElementById('justifRetardFichierContainer').style.display = 'none';
    }
    
    modal.classList.add('show');
}

function fermerModalVoirJustificatifRetard() {
    document.getElementById('modalVoirJustificatifRetard').classList.remove('show');
}

function ouvrirModalValidationRetard(pointageId) {
    const modal = document.getElementById('modalValidationRetard');
    const form = document.getElementById('formValidationRetard');
    form.action = `/pointage/${pointageId}/justificatif-retard/valider`;
    modal.classList.add('show');
}

function fermerModalValidationRetard() {
    document.getElementById('modalValidationRetard').classList.remove('show');
}

// Fermer les modales en cliquant à l'extérieur
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
        }
    });
});
            </script>
        @endpush
</x-app-layout>