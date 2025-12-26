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
                width: 400px;
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
        </style>
    </head>
    <body>
        <div id="custom-alert-modal" class="modal-overlay">
            <div class="modal-content rounded-xl shadow-2xl">
                <div id="alert-modal-icon" class="header-icon"></div>
                <div id="alert-modal-message" class="message"></div>
                <div id="alert-modal-buttons" class="buttons"></div>
            </div>
        </div>

        <h2 class="font-semibold text-3xl text-gray-800 leading-tight border-b-2 border-primary-red pb-3 mb-6 animate-fade-in">
            <i class="fas fa-fingerprint mr-3 text-primary-red"></i> {{ __('Gestion des Pointages') }}
        </h2>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade-in">
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
                            <!-- Période rapide -->
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

                            <!-- Date début -->
                            <div id="date_debut_container">
                                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date début') }}</label>
                                <input type="date" id="date_debut" name="date_debut" value="{{ request('date_debut') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                            </div>

                            <!-- Date fin -->
                            <div id="date_fin_container">
                                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date fin') }}</label>
                                <input type="date" id="date_fin" name="date_fin" value="{{ request('date_fin') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                            </div>

                            <!-- Statut -->
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

                            <!-- Utilisateur -->
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
                            @endif

                            <!-- Boutons -->
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
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Localisation') }}</th>
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

                                            $isProblematicPointage = $isLateForArrival || $isEarlyDeparture;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition duration-200 {{ $isProblematicPointage ? 'late-pointage-row' : '' }}">
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
                                                @if($pointage->heure_arrivee)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isLateForArrival ? 'bg-primary-red text-white' : 'bg-green-100 text-green-800' }}">
                                                        <i class="fas fa-play mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($pointage->heure_arrivee)->format('H:i') }}
                                                    </span>
                                                    @if($isLateForArrival)
                                                        <span class="late-arrival-badge">{{ __('Retard') }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($pointage->heure_depart)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isEarlyDeparture ? 'bg-primary-red text-white' : 'bg-primary-red text-white' }}">
                                                        <i class="fas fa-stop mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($pointage->heure_depart)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($pointage->heure_arrivee && $pointage->heure_depart)
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
                                                @if($pointage->heure_depart)
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $pointage->localisation ?? 'Non spécifiée' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 flex items-center">
                                                <a href="{{ route('pointage.show', $pointage->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 transition duration-200 transform hover:scale-110" title="{{ __('Voir') }}">
                                                    <i class="fas fa-eye text-lg"></i>
                                                </a>
                                                @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                                                    <button type="button" onclick="ouvrirModalCorrection(
                                                        {{ $pointage->id }},
                                                        '{{ \Carbon\Carbon::parse($pointage->heure_arrivee)->format('Y-m-d\TH:i') }}',
                                                        '{{ $pointage->heure_depart ? \Carbon\Carbon::parse($pointage->heure_depart)->format('Y-m-d\TH:i') : '' }}',
                                                        '{{ addslashes($pointage->description ?? '') }}',
                                                        '{{ addslashes($pointage->localisation ?? '') }}',
                                                        '{{ addslashes($pointage->user_latitude ?? '') }}',
                                                        '{{ addslashes($pointage->user_longitude ?? '') }}'
                                                    )" class="text-indigo-600 hover:text-indigo-800 transition duration-200 transform hover:scale-110" title="{{ __('Corriger') }}">
                                                        <i class="fas fa-edit text-lg"></i>
                                                    </button>
                                                @endif
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

        @push('scripts')
            <script>
                // Toggle date inputs based on period selection
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

                // Initialize on load
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

                // Charts initialization
                @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                document.addEventListener('DOMContentLoaded', async () => {
                    try {
                        const response = await fetch("{{ route('pointages.chart.data') }}");
                        const data = await response.json();

                        // Heures travaillées chart
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
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                callback: function(value) {
                                                    return value + 'h';
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }

                        // Retards chart
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
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    } catch (error) {
                        console.error('Erreur lors du chargement des graphiques:', error);
                    }
                });
                @endif

                // Modal functions
                function ouvrirModalCorrection(pointageId, heureArrivee, heureDepart, description, localisation, userLatitude, userLongitude) {
                    document.getElementById('modalCorrection').classList.remove('hidden');
                    const form = document.getElementById('formCorrection');
                    form.action = `/pointage/${pointageId}/corriger`;
                    document.getElementById('modal_heure_arrivee').value = heureArrivee;
                    document.getElementById('modal_heure_depart').value = heureDepart;
                    document.getElementById('modal_description').value = description;
                    document.getElementById('modal_localisation').value = localisation;
                    document.getElementById('modal_user_latitude').value = userLatitude;
                    document.getElementById('modal_user_longitude').value = userLongitude;
                }

                function fermerModalCorrection() {
                    document.getElementById('modalCorrection').classList.add('hidden');
                }

                // Custom alert modal
                const customAlertModal = document.getElementById('custom-alert-modal');
                function showCustomAlert(message, type = 'alert') {
                    const alertModalMessage = document.getElementById('alert-modal-message');
                    const alertModalButtons = document.getElementById('alert-modal-buttons');
                    const alertModalIcon = document.getElementById('alert-modal-icon');
                    
                    alertModalMessage.textContent = message;
                    alertModalButtons.innerHTML = '';
                    alertModalIcon.innerHTML = '';

                    if (type === 'alert') {
                        alertModalIcon.innerHTML = '<i class="fas fa-info-circle text-gray-500"></i>';
                    } else if (type === 'error') {
                        alertModalIcon.innerHTML = '<i class="fas fa-times-circle text-red-600"></i>';
                    }

                    const okBtn = document.createElement('button');
                    okBtn.textContent = 'OK';
                    okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                    okBtn.onclick = () => customAlertModal.classList.remove('show');
                    alertModalButtons.appendChild(okBtn);

                    customAlertModal.classList.add('show');
                }
            </script>
        @endpush
</x-app-layout>