<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Gestion des Pointages') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            // Configure Tailwind CSS to use a custom primary color (consistent with Tache/Reclamation)
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary-red': '#D32F2F', // Your specified color for consistency
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'], // Set Inter as the default font
                        }
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <style>
            /* Base font for body */
            body {
                font-family: 'Inter', sans-serif;
            }

            /* --- Shared Animations from Tache/Reclamation modules --- */
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

            .animate-pulse-subtle { /* Renamed from original 'animate-pulse' to avoid conflict if a stronger pulse is desired */
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; } /* Original pulse for alerts */
            }

            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .animate-spin-slow {
                animation: spin-slow 3s linear infinite;
            }

            /* --- Reclamation-specific animations (reused for consistency) --- */
            @keyframes fadeInDown {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes bounce-slow {
                0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                40% { transform: translateY(-10px); }
                60% { transform: translateY(-5px); }
            }
            .animate-bounce-slow { animation: bounce-slow 2s infinite; }

            @keyframes pulse-fast {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.03); }
            }
            .animate-pulse-fast { animation: pulse-fast 1.5s infinite; }

            @keyframes wobble {
                0%, 100% { transform: translateX(0); }
                15% { transform: translateX(-5px); }
                30% { transform: translateX(5px); }
                45% { transform: translateX(-5px); }
                60% { transform: translateX(5px); }
                75% { transform: translateX(-5px); }
            }
            .animate-wobble { animation: wobble 1s infinite; }

            @keyframes slide-in-up {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-slide-in-up { animation: slide-in-up 0.6s ease-out forwards; }

            /* Button gradients and shadows (Consistent with Tache/Reclamation) */
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

            /* Custom Modal Styling (Consistent with Tache/Reclamation) */
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
                position: relative;
                overflow: hidden;
            }

            .modal-overlay.show .modal-content {
                transform: translateY(0);
            }

            .modal-content .header-icon {
                font-size: 2.5rem;
                margin-bottom: 1rem;
                text-align: center;
            }

            .modal-content .message {
                text-align: center;
                margin-bottom: 1.5rem;
                font-size: 1.125rem;
                color: #374151;
            }

            .modal-content .buttons {
                display: flex;
                justify-content: center;
                gap: 1rem;
            }

            /* Custom style for late/early pointages */
            .late-pointage-row {
                color: #D32F2F; /* primary-red */
                font-weight: bold;
            }

            .late-arrival-badge {
                background-color: #D32F2F; /* primary-red */
                color: white;
                padding: 0.25rem 0.5rem;
                border-radius: 0.375rem; /* rounded-md */
                font-size: 0.75rem; /* text-xs */
                margin-left: 0.5rem;
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

        <h2 class="font-semibold text-2xl text-gray-800 leading-tight border-b-2 border-primary-red pb-3 mb-6 animate-fade-in delay-100">
            <i class="fas fa-fingerprint mr-3 text-primary-red"></i> {{ __('Gestion des Pointages') }}
        </h2>
        <div class="flex justify-end items-center mb-6">
            <div class="text-lg font-medium text-gray-600">
                <i class="far fa-calendar-alt mr-2"></i> {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

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

                @if(!auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Admin1'))
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 animate-fade-in delay-200">
                        <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">
                                <i class="fas fa-clock mr-3 text-blue-600"></i>
                                {{ __('Pointage Rapide') }}
                            </h3>

                            @if($pointageEnCours)
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 mb-4 rounded-r-lg shadow-md animate-fade-in animate-pulse-subtle">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-play-circle text-yellow-500 text-2xl mr-3"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium">
                                                {{ __('Pointage en cours depuis') }} <span class="font-bold">{{ $pointageEnCours->heure_arrivee->format('H:i') }}</span>
                                            </p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                {{ __('Durée:') }} <span id="duree-travail" data-debut="{{ $pointageEnCours->heure_arrivee->timestamp }}" class="font-bold"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('pointage.pointer') }}" method="POST" class="space-y-4" id="pointageSortieForm">
                                    @csrf
                                    {{-- Hidden input fields for geolocation --}}
                                    <input type="hidden" name="user_latitude" id="user_latitude_out">
                                    <input type="hidden" name="user_longitude" id="user_longitude_out">
                                    <input type="hidden" name="localisation" id="localisation_hidden_out">
                                    <div>
                                        <label for="description_out" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-comment mr-2 text-gray-500"></i>
                                            {{ __('Description du travail effectué (optionnel)') }}
                                        </label>
                                        <textarea id="description_out" name="description" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red transition duration-200"
                                            placeholder="{{ __('Décrivez brièvement les tâches accomplies...') }}"></textarea>
                                    </div>
                                    <button type="submit" id="pointerSortieBtn"
                                        class="w-full bg-primary-red hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg btn-primary-red">
                                        <i class="fas fa-stop-circle mr-2"></i>
                                        {{ __('Pointer la Sortie') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('pointage.pointer') }}" method="POST" class="space-y-4" id="pointageArriveeForm">
                                    @csrf
                                    {{-- Hidden input fields for geolocation --}}
                                    <input type="hidden" name="user_latitude" id="user_latitude_in">
                                    <input type="hidden" name="user_longitude" id="user_longitude_in">
                                    <input type="hidden" name="localisation" id="localisation_hidden_in">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="description_in" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-comment mr-2 text-gray-500"></i>
                                                {{ __('Description (optionnel)') }}
                                            </label>
                                            <textarea id="description_in" name="description" rows="2"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red transition duration-200"
                                                placeholder="{{ __('Description de la journée...') }}"></textarea>
                                        </div>
                                        <div>
                                            <label for="localisation_display" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>
                                                {{ __('Localisation détectée (automatique)') }}
                                            </label>
                                            <input type="text" id="localisation_display" disabled
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-600 cursor-not-allowed"
                                                value="{{ __('En attente de localisation...') }}">
                                        </div>
                                    </div>
                                    <button type="submit" id="pointerArriveeBtn"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-play-circle mr-2"></i>
                                        {{ __('Pointer l\'Arrivée') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 animate-fade-in delay-300">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            <i class="fas fa-filter mr-3 text-indigo-600"></i>
                            {{ __('Filtres et Recherche') }}
                        </h3>

                        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Recherche') }}</label>
                                <div class="relative">
                                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red"
                                        placeholder="{{ __('Nom ou date...') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date début') }}</label>
                                <input type="date" id="date_debut" name="date_debut" value="{{ request('date_debut') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                            </div>

                            <div>
                                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Statut') }}</label>
                                <select id="statut" name="statut"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                                    <option value="">{{ __('Tous les statuts') }}</option>
                                    <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                    <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                                </select>
                            </div>

                            <div class="flex items-end space-x-3">
                                <button type="submit"
                                    class="btn-primary-red flex-1 flex items-center justify-center px-5 py-2 rounded-md shadow-sm text-white font-medium">
                                    <i class="fas fa-filter mr-2"></i>
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

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg animate-fade-in delay-400">
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
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('Localisation') }}</th> {{-- Added this column --}}
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
                                            {{-- Display Localisation --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $pointage->localisation ?? 'Non spécifiée' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 flex items-center">
                                                <a href="{{ route('pointage.show', $pointage->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 transition duration-200 transform hover:scale-110" title="{{ __('Voir') }}">
                                                    <i class="fas fa-eye text-lg"></i>
                                                </a>
                                                {{-- Correction button for admins --}}
                                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Admin1'))
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
                        <div class="p-6 text-center bg-gray-100 rounded-b-lg animate-fade-in delay-500">
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

        {{-- Correction Modal --}}
        @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Admin1'))
            <div id="modalCorrection" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
                <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white animate-fade-in">
                    <div class="mt-3 text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Corriger le pointage') }}</h3>
                        <form id="formCorrection" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4 text-left">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Heure d\'arrivée') }}</label>
                                    <input type="datetime-local" name="heure_arrivee" id="modal_heure_arrivee" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Heure de départ') }}</label>
                                    <input type="datetime-local" name="heure_depart" id="modal_heure_depart"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                                    <textarea name="description" id="modal_description" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Localisation') }}</label>
                                    <input type="text" name="localisation" id="modal_localisation"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                                </div>
                                {{-- Added latitude and longitude inputs to the correction modal --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Latitude') }}</label>
                                    <input type="text" name="user_latitude" id="modal_user_latitude"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Longitude') }}</label>
                                    <input type="text" name="user_longitude" id="modal_user_longitude"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-primary-red">
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" onclick="fermerModalCorrection()"
                                    class="btn-secondary-tailwind px-4 py-2 rounded-md transition duration-200">
                                    {{ __('Annuler') }}
                                </button>
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                                    {{ __('Sauvegarder') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @push('scripts')
            <script>
                // Live duration update for current pointage
                function mettreAJourDuree() {
                    const elementDuree = document.getElementById('duree-travail');
                    if (elementDuree) {
                        const debut = parseInt(elementDuree.dataset.debut);
                        const maintenant = Math.floor(Date.now() / 1000);
                        const diffMinutes = Math.floor((maintenant - debut) / 60);
                        const heures = Math.floor(diffMinutes / 60);
                        const minutes = diffMinutes % 60;
                        elementDuree.textContent = `${heures}h ${minutes.toString().padStart(2, '0')}min`;
                    }
                }
                // Update every second
                setInterval(mettreAJourDuree, 1000);
                mettreAJourDuree(); // Initial call

                // Custom Alert/Confirmation Modal Logic (Copied from Tache/Reclamation for consistency)
                const customAlertModal = document.getElementById('custom-alert-modal');
                const alertModalMessage = document.getElementById('alert-modal-message');
                const alertModalButtons = document.getElementById('alert-modal-buttons');
                const alertModalIcon = document.getElementById('alert-modal-icon');
                let resolveAlertModalPromise;

                function showCustomAlertModal(message, type = 'alert', onConfirm = null) {
                    alertModalMessage.textContent = message;
                    alertModalButtons.innerHTML = ''; // Clear previous buttons
                    alertModalIcon.innerHTML = ''; // Clear previous icon

                    if (type === 'confirm') {
                        alertModalIcon.innerHTML = '<i class="fas fa-question-circle text-blue-500"></i>';
                        const confirmBtn = document.createElement('button');
                        confirmBtn.textContent = 'Confirmer';
                        confirmBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        confirmBtn.onclick = () => {
                            customAlertModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveAlertModalPromise(true);
                        };
                        alertModalButtons.appendChild(confirmBtn);

                        const cancelBtn = document.createElement('button');
                        cancelBtn.textContent = 'Annuler';
                        cancelBtn.className = 'px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wider shadow-md btn-secondary-tailwind';
                        cancelBtn.onclick = () => {
                            customAlertModal.classList.remove('show');
                            resolveAlertModalPromise(false);
                        };
                        alertModalButtons.appendChild(cancelBtn);
                    } else if (type === 'alert') {
                        alertModalIcon.innerHTML = '<i class="fas fa-info-circle text-gray-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        okBtn.onclick = () => {
                            customAlertModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveAlertModalPromise(true);
                        };
                        alertModalButtons.appendChild(okBtn);
                    } else if (type === 'success') {
                        alertModalIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        okBtn.onclick = () => {
                            customAlertModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveAlertModalPromise(true);
                        };
                        alertModalButtons.appendChild(okBtn);
                    } else if (type === 'error') {
                        alertModalIcon.innerHTML = '<i class="fas fa-times-circle text-primary-red"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        okBtn.onclick = () => {
                            customAlertModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveAlertModalPromise(true);
                        };
                        alertModalButtons.appendChild(okBtn);
                    }

                    customAlertModal.classList.add('show');
                    return new Promise(resolve => {
                        resolveAlertModalPromise = resolve;
                    });
                }

                // Convenience functions to replace native alert/confirm with custom modal
                window.showCustomAlert = function(message, callback = null) {
                    return showCustomAlertModal(message, 'alert', callback);
                }

                window.showCustomConfirm = function(message, callback = null) {
                    return showCustomAlertModal(message, 'confirm', callback);
                }

                window.showCustomSuccess = function(message, callback = null) {
                    return showCustomAlertModal(message, 'success', callback);
                }

                window.showCustomError = function(message, callback = null) {
                    return showCustomAlertModal(message, 'error', callback);
                }

                // Functions for the correction modal (already present, but ensure consistency)
                function ouvrirModalCorrection(pointageId, heureArrivee, heureDepart, description, localisation, userLatitude, userLongitude) {
                    document.getElementById('modalCorrection').classList.remove('hidden');
                    const form = document.getElementById('formCorrection');

                    form.action = `/pointage/${pointageId}/corriger`;

                    document.getElementById('modal_heure_arrivee').value = heureArrivee;
                    document.getElementById('modal_heure_depart').value = heureDepart;
                    document.getElementById('modal_description').value = description;
                    document.getElementById('modal_localisation').value = localisation;
                    document.getElementById('modal_user_latitude').value = userLatitude; // Populate latitude
                    document.getElementById('modal_user_longitude').value = userLongitude; // Populate longitude
                }

                function fermerModalCorrection() {
                    document.getElementById('modalCorrection').classList.add('hidden');
                    document.getElementById('modal_heure_arrivee').value = '';
                    document.getElementById('modal_heure_depart').value = '';
                    document.getElementById('modal_description').value = '';
                    document.getElementById('modal_localisation').value = '';
                    document.getElementById('modal_user_latitude').value = ''; // Clear latitude
                    document.getElementById('modal_user_longitude').value = ''; // Clear longitude
                }

                document.getElementById('modalCorrection')?.addEventListener('click', function(e) {
                    if (e.target === this) {
                        fermerModalCorrection();
                    }
                });

                // --- Geolocation Logic ---
                const pointageArriveeForm = document.getElementById('pointageArriveeForm');
                const pointageSortieForm = document.getElementById('pointageSortieForm');
                const localisationDisplay = document.getElementById('localisation_display');

                const userLatitudeIn = document.getElementById('user_latitude_in');
                const userLongitudeIn = document.getElementById('user_longitude_in');
                const localisationHiddenIn = document.getElementById('localisation_hidden_in');

                const userLatitudeOut = document.getElementById('user_latitude_out');
                const userLongitudeOut = document.getElementById('user_longitude_out');
                const localisationHiddenOut = document.getElementById('localisation_hidden_out');


                const pointerArriveeBtn = document.getElementById('pointerArriveeBtn');
                const pointerSortieBtn = document.getElementById('pointerSortieBtn');

                // Function to handle geolocation or fallbacks
                function handleGeolocation(callback, formButton, form) {
                    // Check if on HTTPS (secure context) AND if geolocation is supported
                    if (window.isSecureContext && navigator.geolocation) {
                        formButton.disabled = true;
                        formButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Récupération de la position...';

                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const lat = position.coords.latitude;
                                const lon = position.coords.longitude;
                                callback(lat, lon, 'Automatique'); // Pass 'Automatique' as a source hint
                                formButton.disabled = false;
                                if (form.id === 'pointageArriveeForm') {
                                    formButton.innerHTML = '<i class="fas fa-play-circle mr-2"></i> Pointer l\'Arrivée';
                                } else if (form.id === 'pointageSortieForm') {
                                    formButton.innerHTML = '<i class="fas fa-stop-circle mr-2"></i> Pointer la Sortie';
                                }
                            },
                            (error) => {
                                console.error("Erreur de géolocalisation: ", error);
                                let errorMessage = "Impossible de récupérer votre position. Le pointage sera enregistré sans localisation précise.";
                                if (error.code === error.PERMISSION_DENIED) {
                                    errorMessage += " (Accès à la localisation refusé)";
                                } else if (error.code === error.POSITION_UNAVAILABLE) {
                                    errorMessage += " (Position non déterminable)";
                                } else if (error.code === error.TIMEOUT) {
                                    errorMessage += " (Délai expiré)";
                                }
                                showCustomError(errorMessage);
                                // Fallback: Proceed without geolocation data, set as 'Non spécifiée' or similar
                                callback(null, null, 'Non détectée (Erreur)');
                                formButton.disabled = false;
                                if (form.id === 'pointageArriveeForm') {
                                    formButton.innerHTML = '<i class="fas fa-play-circle mr-2"></i> Pointer l\'Arrivée';
                                } else if (form.id === 'pointageSortieForm') {
                                    formButton.innerHTML = '<i class="fas fa-stop-circle mr-2"></i> Pointer la Sortie';
                                }
                            },
                            {
                                enableHighAccuracy: true,
                                timeout: 15000,
                                maximumAge: 0
                            }
                        );
                    } else {
                        // Fallback: Not on HTTPS or Geolocation not supported
                        let message = "La géolocalisation n'est pas disponible ou l'accès est bloqué (Site non sécurisé). Le pointage sera enregistré sans localisation précise.";
                        if (!navigator.geolocation) {
                             message = "Votre navigateur ne supporte pas la géolocalisation. Le pointage sera enregistré sans localisation précise.";
                        } else if (!window.isSecureContext) {
                            message = "Ce site n'est pas sécurisé (HTTPS). La géolocalisation automatique est désactivée. Le pointage sera enregistré sans localisation précise.";
                        }

                        showCustomAlert(message, 'info'); // Show info message instead of error
                        console.warn("Geolocation fallback: Not on HTTPS or not supported.");
                        
                        // Proceed with null coordinates and 'Non détectée' location
                        callback(null, null, 'Non détectée (HTTP)');
                        formButton.disabled = false; // Ensure button is re-enabled
                        if (form.id === 'pointageArriveeForm') {
                            formButton.innerHTML = '<i class="fas fa-play-circle mr-2"></i> Pointer l\'Arrivée';
                        } else if (form.id === 'pointageSortieForm') {
                            formButton.innerHTML = '<i class="fas fa-stop-circle mr-2"></i> Pointer la Sortie';
                        }
                    }
                }

                // Handle arrival form submission
                if (pointageArriveeForm) {
                    pointageArriveeForm.addEventListener('submit', function(event) {
                        event.preventDefault(); // Prevent default submission
                        handleGeolocation((lat, lon, status) => {
                            userLatitudeIn.value = lat;
                            userLongitudeIn.value = lon;
                            // Set the hidden localisation field based on status for backend to log/process
                            localisationHiddenIn.value = status; 
                            if (localisationDisplay) {
                                localisationDisplay.value = 'Position ' + status + '. Envoi du pointage...';
                            }
                            this.submit(); // Submit the form regardless of geolocation success
                        }, pointerArriveeBtn, pointageArriveeForm);
                    });
                }

                // Handle departure form submission
                if (pointageSortieForm) {
                    pointageSortieForm.addEventListener('submit', function(event) {
                        event.preventDefault(); // Prevent default submission
                        handleGeolocation((lat, lon, status) => {
                            userLatitudeOut.value = lat;
                            userLongitudeOut.value = lon;
                            // Set the hidden localisation field based on status for backend to log/process
                            localisationHiddenOut.value = status;
                            this.submit(); // Submit the form regardless of geolocation success
                        }, pointerSortieBtn, pointageSortieForm);
                    });
                }

                // Initialize localisation display on page load if user is not an admin
                document.addEventListener('DOMContentLoaded', () => {
                    const isAdmin = {{ auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Admin1') ? 'true' : 'false' }};
                    if (!isAdmin && localisationDisplay) {
                        if (window.isSecureContext && navigator.geolocation) {
                            localisationDisplay.value = 'Veuillez cliquer pour pointer afin de détecter votre position.';
                        } else {
                            localisationDisplay.value = 'Géolocalisation automatique non disponible (Site non sécurisé ou navigateur non compatible).';
                            if (pointerArriveeBtn) {
                                pointerArriveeBtn.textContent = 'Pointer l\'Arrivée (Manuel)';
                                pointerArriveeBtn.classList.add('bg-gray-500'); // Optional: change color to indicate manual/no-auto-location
                                pointerArriveeBtn.classList.remove('bg-green-600');
                            }
                        }
                    }
                });

                // Set initial state of buttons based on geolocation availability (optional visual hint)
                document.addEventListener('DOMContentLoaded', () => {
                    const isAdmin = {{ auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Admin1') ? 'true' : 'false' }};
                    if (!isAdmin) {
                        if (!window.isSecureContext || !navigator.geolocation) {
                            if (pointerArriveeBtn) {
                                pointerArriveeBtn.textContent = 'Pointer l\'Arrivée (بدون تحديد موقع)';
                                pointerArriveeBtn.classList.add('bg-orange-500'); // Change color to hint
                                pointerArriveeBtn.classList.remove('bg-green-600');
                            }
                            if (pointerSortieBtn) { // For the departure button as well
                                pointerSortieBtn.textContent = 'Pointer la Sortie (بدون تحديد موقع)';
                                pointerSortieBtn.classList.add('bg-orange-500');
                                pointerSortieBtn.classList.remove('bg-primary-red');
                            }
                        }
                    }
                });

            </script>
        @endpush
</x-app-layout>