<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Gestion des Réclamations') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            // Configure Tailwind CSS to use a custom primary color (consistent with Tache)
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary-red': '#D32F2F', // Your specified color
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

            /* --- Shared Animations from Tache module --- */
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

            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .animate-spin-slow {
                animation: spin-slow 3s linear infinite;
            }

            /* --- Specific Animations for Reclamation (from original) --- */
            @keyframes fadeInDown {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .header-title {
                animation: fadeInDown 0.8s ease-out;
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


            /* --- Custom Modal Styling (Consistent with Tache) --- */
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

            /* Button gradients and shadows (Consistent with Tache) */
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

            .btn-secondary-tailwind { /* Renamed to avoid conflict with Bootstrap's btn-secondary */
                background-color: #e5e7eb;
                color: #4b5563;
                transition: all 0.3s ease;
            }

            .btn-secondary-tailwind:hover {
                background-color: #d1d5db;
                transform: translateY(-2px);
            }

            /* Input/Select focus styles (Consistent with Tache) */
            .filter-input:focus, .filter-select:focus {
                border-color: #D32F2F;
                box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.2);
                outline: none; /* Remove default outline */
            }

            /* Table Header Style (from original, adapted) */
            .table-header th {
                background-color: #FFF3F3; /* Light red background for headers */
                color: #D32F2F;
            }

            /* Status Badges (from original, adapted for Tailwind's rounded-full) */
            .status-badge {
                font-weight: bold;
                padding: 0.3em 0.7em;
                border-radius: 9999px; /* Pill shape */
                display: inline-flex; /* Use flex for icon alignment */
                align-items: center;
                gap: 0.25rem; /* Space between icon and text */
                animation: pulse 2s infinite cubic-bezier(0.4, 0, 0.6, 1); /* Keep the pulse */
            }

            .status-pending { background-color: #FFF8E1; color: #FFA000; }
            .status-in-progress { background-color: #E3F2FD; color: #1976D2; }
            .status-resolved { background-color: #E8F5E9; color: #388E3C; animation: none; }
            .status-closed { background-color: #EEEEEE; color: #616161; animation: none; }

            /* Priority Badges (from original, adapted for Tailwind's rounded-full) */
            .priority-badge {
                font-weight: bold;
                padding: 0.3em 0.7em;
                border-radius: 9999px;
                display: inline-flex; /* Use flex for icon alignment */
                align-items: center;
                gap: 0.25rem; /* Space between icon and text */
            }

            .priority-low { background-color: #E0F2F7; color: #00BCD4; }
            .priority-medium { background-color: #FFF8E1; color: #FFA000; }
            .priority-high {
                background-color: #FFEBEE;
                color: #D32F2F;
                animation: shake 0.8s cubic-bezier(.36,.07,.19,.97) both infinite;
                transform-origin: center;
            }
            @keyframes shake {
                10%, 90% { transform: translate3d(-1px, 0, 0); }
                20%, 80% { transform: translate3d(2px, 0, 0); }
                30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
                40%, 60% { transform: translate3d(4px, 0, 0); }
            }

            /* Pagination styles to match theme (adapted for Tailwind) */
            .pagination-links nav div:first-child div:nth-child(2) span,
            .pagination-links nav div:first-child div:nth-child(2) a {
                color: #D32F2F;
            }
            .pagination-links nav div:first-child div:nth-child(2) span.relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.bg-primary-red.border-primary-red.text-white {
                background-color: #D32F2F !important;
                border-color: #D32F2F !important;
                color: white !important;
            }

            .pagination-links nav div:first-child div:nth-child(2) a:hover {
                background-color: #FEF2F2;
            }

            /* Responsive adjustments for smaller screens */
            @media (max-width: 768px) {
                .header-title {
                    font-size: 1.875rem; /* text-3xl converted to smaller */
                    text-align: center;
                    margin-bottom: 1rem;
                }

                .flex-mobile-col {
                    flex-direction: column;
                    align-items: center;
                }

                .space-x-3-mobile > *:not(:first-child) {
                    margin-left: 0 !important;
                    margin-top: 0.75rem;
                }

                .grid-cols-1-mobile {
                    grid-template-columns: 1fr;
                }

                .overflow-x-auto-mobile {
                    overflow-x: auto;
                }

                .stat-card {
                    padding: 1rem;
                }

                .filter-form-mobile {
                    grid-template-columns: 1fr;
                }

                .filter-form-mobile > div {
                    width: 100%;
                }

                .filter-form-mobile .flex-space-x-3-mobile {
                    flex-direction: column;
                    align-items: stretch;
                }

                .filter-form-mobile .flex-space-x-3-mobile button,
                .filter-form-mobile .flex-space-x-3-mobile a {
                    width: 100%;
                    margin-left: 0 !important;
                    margin-top: 0.5rem;
                }
            }
        </style>
    </head>
    <body>
        <div id="custom-modal" class="modal-overlay">
            <div class="modal-content rounded-xl shadow-2xl">
                <div id="modal-icon" class="header-icon"></div>
                <div id="modal-message" class="message"></div>
                <div id="modal-buttons" class="buttons"></div>
            </div>
        </div>

        <h2 class="font-semibold text-2xl text-gray-800 leading-tight border-b-2 border-primary-red pb-3 mb-6 animate-fade-in delay-100">
            <i class="fas fa-ticket-alt mr-3 text-primary-red"></i> {{ __('Gestion des Réclamations') }}
        </h2>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 shadow-md animate-fade-in" role="alert">
                        <strong class="font-bold">Succès!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 shadow-md animate-fade-in animate-pulse-subtle" role="alert">
                        <strong class="font-bold">Erreur!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg animate-fade-in delay-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                            <div class="mb-4 md:mb-0">
                                @can('reclamation-create')
                                    <a href="{{ route('reclamations.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red">
                                        <i class="fas fa-plus-circle mr-2"></i> {{ __('Nouvelle Réclamation') }}
                                    </a>
                                @endcan
                            </div>
                        </div>

                        {{-- Dashboard Statistics Section (Adapted from Tache) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
                            <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 text-center card-hover-effect animate-fade-in delay-300">
                                <i class="fas fa-list-alt text-4xl text-blue-600 mb-3 animate-bounce-slow"></i>
                                <div class="text-3xl font-bold text-blue-900 mt-1">{{ $stats['total'] ?? 0 }}</div>
                                <div class="text-sm font-semibold text-blue-700">{{ __('Total Réclamations') }}</div>
                            </div>
                            <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 text-center card-hover-effect animate-fade-in delay-400">
                                <i class="fas fa-hourglass-half text-4xl text-yellow-600 mb-3 animate-spin-slow"></i>
                                <div class="text-3xl font-bold text-yellow-900 mt-1">{{ $stats['pending'] ?? 0 }}</div>
                                <div class="text-sm font-semibold text-yellow-700">{{ __('En attente') }}</div>
                            </div>
                            <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 text-center card-hover-effect animate-fade-in delay-500">
                                <i class="fas fa-sync-alt text-4xl text-orange-600 mb-3 animate-pulse-fast"></i>
                                <div class="text-3xl font-bold text-orange-900 mt-1">{{ $stats['in_progress'] ?? 0 }}</div>
                                <div class="text-sm font-semibold text-orange-700">{{ __('En cours') }}</div>
                            </div>
                            <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 text-center card-hover-effect animate-fade-in delay-600">
                                <i class="fas fa-check-circle text-4xl text-green-600 mb-3"></i>
                                <div class="text-3xl font-bold text-green-900 mt-1">{{ $stats['resolved'] ?? 0 }}</div>
                                <div class="text-sm font-semibold text-green-700">{{ __('Résolues') }}</div>
                            </div>
                            <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 text-center card-hover-effect animate-fade-in delay-700">
                                <i class="fas fa-exclamation-triangle text-4xl text-red-600 mb-3 animate-wobble"></i>
                                <div class="text-3xl font-bold text-red-900 mt-1">{{ $stats['high_priority'] ?? 0 }}</div>
                                <div class="text-sm font-semibold text-red-700">{{ __('Priorité haute') }}</div>
                            </div>
                             <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 text-center card-hover-effect animate-fade-in delay-800">
                                <i class="fas fa-calendar-alt text-4xl text-purple-600 mb-3 animate-slide-in-up"></i>
                                <div class="text-3xl font-bold text-purple-900 mt-1">{{ $stats['this_month'] ?? 0 }}</div>
                                <div class="text-sm font-semibold text-purple-700">{{ __('Ce mois') }}</div>
                            </div>
                        </div>

                        {{-- Filter Form (Adapted from Tache) --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-inner animate-fade-in delay-900">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Filtres et Tri') }}</h3>
                            <form method="GET" action="{{ route('reclamations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Rechercher') }}</label>
                                    <div class="relative">
                                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                                               placeholder="{{ __('Titre ou référence...') }}"
                                               class="filter-input w-full border-gray-300 rounded-md shadow-sm pl-10 pr-3 py-2">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Statut') }}</label>
                                    <select name="status" id="status" class="filter-select w-full border-gray-300 rounded-md shadow-sm py-2 px-3">
                                        <option value="">{{ __('Tous les statuts') }}</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('En attente') }}</option>
                                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>{{ __('Résolue') }}</option>
                                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ __('Fermée') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Priorité') }}</label>
                                    <select name="priority" id="priority" class="filter-select w-full border-gray-300 rounded-md shadow-sm py-2 px-3">
                                        <option value="">{{ __('Toutes les priorités') }}</option>
                                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>{{ __('Faible') }}</option>
                                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>{{ __('Moyenne') }}</option>
                                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>{{ __('Haute') }}</option>
                                    </select>
                                </div>
                                <div class="flex space-x-3 items-end">
                                    <button type="submit" class="btn-primary-red flex-1 flex items-center justify-center px-5 py-2 rounded-md shadow-sm text-white font-medium">
                                        <i class="fas fa-filter mr-2"></i>
                                        {{ __('Filtrer') }}
                                    </button>
                                    <a href="{{ route('reclamations.index') }}" class="btn-secondary-tailwind flex items-center justify-center px-5 py-2 rounded-md shadow-sm font-bold text-sm uppercase tracking-wider">
                                        <i class="fas fa-undo mr-2"></i>
                                        {{ __('Réinitialiser') }}
                                    </a>
                                </div>
                            </form>
                        </div>

                        {{-- Reclamations Table --}}
                        @if ($reclamations->isEmpty())
                            <div class="bg-gray-100 p-8 text-center text-gray-600 rounded-lg shadow-lg animate-fade-in delay-900 mt-8">
                                <i class="fas fa-box-open text-6xl text-gray-400 mb-4"></i>
                                <p class="text-xl font-semibold">{{ __('Aucune réclamation trouvée.') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto relative shadow-lg sm:rounded-lg animate-fade-in delay-900 mt-8">
                                <table class="w-full text-sm text-left text-gray-700">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 rounded-t-lg">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">
                                                <a href="{{ route('reclamations.index', array_merge(request()->query(), ['sort_by' => 'reference', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                    {{ __('Référence') }}
                                                    @if(request('sort_by') == 'reference')
                                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ml-2"></i>
                                                    @else
                                                        <i class="fas fa-sort ml-2 text-gray-400"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th scope="col" class="py-3 px-6">{{ __('Titre') }}</th>
                                            @if(auth()->user()->hasRole('Sup_Admin'))
                                            <th scope="col" class="py-3 px-6">{{ __('Utilisateur') }}</th>
                                            @endif
                                            <th scope="col" class="py-3 px-6">{{ __('Statut') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Priorité') }}</th>
                                            <th scope="col" class="py-3 px-6">
                                                <a href="{{ route('reclamations.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                    {{ __('Date') }}
                                                    @if(request('sort_by') == 'created_at' || !request('sort_by'))
                                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ml-2"></i>
                                                    @else
                                                        <i class="fas fa-sort ml-2 text-gray-400"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th scope="col" class="py-3 px-6 text-center">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($reclamations as $reclamation)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $reclamation->reference }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ Str::limit($reclamation->titre, 50) }}
                                            </td>
                                            @if(auth()->user()->hasRole('Sup_Admin'))
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $reclamation->user->name ?? 'N/A' }}
                                            </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="status-badge
                                                    @if($reclamation->status == 'pending') status-pending
                                                    @elseif($reclamation->status == 'in_progress') status-in-progress
                                                    @elseif($reclamation->status == 'resolved') status-resolved
                                                    @else status-closed
                                                    @endif">
                                                    @switch($reclamation->status)
                                                        @case('pending') <i class="far fa-clock"></i> {{ __('En attente') }} @break
                                                        @case('in_progress') <i class="fas fa-spinner fa-spin"></i> {{ __('En cours') }} @break
                                                        @case('resolved') <i class="fas fa-check-double"></i> {{ __('Résolue') }} @break
                                                        @case('closed') <i class="fas fa-lock"></i> {{ __('Fermée') }} @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="priority-badge
                                                    @if($reclamation->priority == 'high') priority-high
                                                    @elseif($reclamation->priority == 'medium') priority-medium
                                                    @else priority-low
                                                    @endif">
                                                    @switch($reclamation->priority)
                                                        @case('high') <i class="fas fa-fire"></i> {{ __('Haute') }} @break
                                                        @case('medium') <i class="fas fa-exclamation"></i> {{ __('Moyenne') }} @break
                                                        @case('low') <i class="fas fa-minus-circle"></i> {{ __('Faible') }} @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <i class="far fa-calendar-alt mr-1"></i>{{ $reclamation->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-3 items-center justify-center">
                                                    @can('reclamation-show')
                                                        <a href="{{ route('reclamations.show', $reclamation->id) }}"
                                                           class="text-blue-600 hover:text-blue-800 transform hover:scale-110 transition-transform duration-200"
                                                           title="{{ __('Voir') }}">
                                                            <i class="fas fa-eye text-lg"></i>
                                                        </a>
                                                    @endcan
                                                    @can('reclamation-edit')
                                                        <a href="{{ route('reclamations.edit', $reclamation->id) }}"
                                                           class="text-indigo-600 hover:text-indigo-800 transform hover:scale-110 transition-transform duration-200"
                                                           title="{{ __('Modifier') }}">
                                                            <i class="fas fa-edit text-lg"></i>
                                                        </a>
                                                    @endcan
                                                    @can('reclamation-delete')
                                                        <button type="button" title="{{ __('Supprimer') }}"
                                                                onclick="showCustomConfirm('{{ __('Êtes-vous sûr de vouloir supprimer cette réclamation ? Cette action est irréversible.') }}', function() { document.getElementById('delete-form-{{ $reclamation->id }}').submit(); });"
                                                                class="text-primary-red hover:text-red-700 transition duration-150 ease-in-out transform hover:scale-110">
                                                            <i class="fas fa-trash-alt text-lg"></i>
                                                        </button>
                                                        <form id="delete-form-{{ $reclamation->id }}" action="{{ route('reclamations.destroy', $reclamation->id) }}" method="POST" class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->hasRole('Sup_Admin') ? '8' : '7' }}" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                <i class="fas fa-box-open mr-2"></i>{{ __('Aucune réclamation trouvée.') }}
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-center pagination-links">
                                {{ $reclamations->links('pagination::tailwind') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // Custom Modal Logic (Copied directly from tache for consistency)
                const customModal = document.getElementById('custom-modal');
                const modalMessage = document.getElementById('modal-message');
                const modalButtons = document.getElementById('modal-buttons');
                const modalIcon = document.getElementById('modal-icon');
                let resolveModalPromise;

                function showCustomModal(message, type = 'alert', onConfirm = null) {
                    modalMessage.textContent = message;
                    modalButtons.innerHTML = ''; // Clear previous buttons
                    modalIcon.innerHTML = ''; // Clear previous icon

                    if (type === 'confirm') {
                        modalIcon.innerHTML = '<i class="fas fa-question-circle text-blue-500"></i>';
                        const confirmBtn = document.createElement('button');
                        confirmBtn.textContent = 'Confirmer';
                        confirmBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        confirmBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(confirmBtn);

                        const cancelBtn = document.createElement('button');
                        cancelBtn.textContent = 'Annuler';
                        cancelBtn.className = 'px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wider shadow-md btn-secondary-tailwind'; // Use renamed class
                        cancelBtn.onclick = () => {
                            customModal.classList.remove('show');
                            resolveModalPromise(false);
                        };
                        modalButtons.appendChild(cancelBtn);
                    } else if (type === 'alert') {
                        modalIcon.innerHTML = '<i class="fas fa-info-circle text-gray-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        okBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    } else if (type === 'success') {
                        modalIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        okBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    } else if (type === 'error') {
                        modalIcon.innerHTML = '<i class="fas fa-times-circle text-primary-red"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        okBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    }

                    customModal.classList.add('show');
                    return new Promise(resolve => {
                        resolveModalPromise = resolve;
                    });
                }

                // Convenience functions to replace native alert/confirm
                function showCustomAlert(message, callback = null) {
                    return showCustomModal(message, 'alert', callback);
                }

                function showCustomConfirm(message, callback = null) {
                    return showCustomModal(message, 'confirm', callback);
                }

                function showCustomSuccess(message, callback = null) {
                    return showCustomModal(message, 'success', callback);
                }

                function showCustomError(message, callback = null) {
                    return showCustomModal(message, 'error', callback);
                }
            </script>
        @endpush
    </body>
</x-app-layout>