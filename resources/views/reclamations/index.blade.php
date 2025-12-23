<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Gestion des Réclamations') }}</title>
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
            /* RESET COMPLET - FULL WIDTH */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
                min-height: 100vh;
                margin: 0;
                padding: 0;
            }

            /* Container principal FULL WIDTH */
            .reclamations-container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 2rem 3rem;
            }

            /* Animations */
            .animate-fade-in {
                animation: fadeIn 0.6s ease-out forwards;
                opacity: 0;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .delay-100 { animation-delay: 0.1s; }
            .delay-200 { animation-delay: 0.2s; }
            .delay-300 { animation-delay: 0.3s; }
            .delay-400 { animation-delay: 0.4s; }
            .delay-500 { animation-delay: 0.5s; }
            .delay-600 { animation-delay: 0.6s; }
            .delay-700 { animation-delay: 0.7s; }
            .delay-800 { animation-delay: 0.8s; }
            .delay-900 { animation-delay: 0.9s; }

            .card-hover-effect {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .card-hover-effect:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(211, 47, 47, 0.15);
            }

            @keyframes bounce-slow {
                0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                40% { transform: translateY(-10px); }
                60% { transform: translateY(-5px); }
            }
            .animate-bounce-slow { animation: bounce-slow 2s infinite; }

            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .animate-spin-slow { animation: spin-slow 3s linear infinite; }

            @keyframes pulse-fast {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
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
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-slide-in-up { animation: slide-in-up 0.6s ease-out forwards; }

            /* Modal Styles */
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
                backdrop-filter: blur(4px);
            }

            .modal-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .modal-content {
                background: white;
                padding: 2.5rem;
                border-radius: 1rem;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
                max-width: 90%;
                width: 450px;
                transform: translateY(-30px);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .modal-overlay.show .modal-content {
                transform: translateY(0);
            }

            /* Button Styles */
            .btn-primary-red {
                background: linear-gradient(135deg, #D32F2F 0%, #B71C1C 100%);
                box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-primary-red::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s;
            }

            .btn-primary-red:hover::before {
                left: 100%;
            }

            .btn-primary-red:hover {
                background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 100%);
                box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4);
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
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            /* Input Focus */
            .filter-input:focus, .filter-select:focus {
                border-color: #D32F2F;
                box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
                outline: none;
            }

            /* Status Badges */
            .status-badge {
                font-weight: 600;
                padding: 0.4rem 1rem;
                border-radius: 9999px;
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                font-size: 0.875rem;
                animation: pulse 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
            }

            .status-pending { background-color: #FFF8E1; color: #F57C00; border: 2px solid #FFB74D; }
            .status-in-progress { background-color: #E3F2FD; color: #1565C0; border: 2px solid #64B5F6; }
            .status-resolved { background-color: #E8F5E9; color: #2E7D32; border: 2px solid #81C784; animation: none; }
            .status-closed { background-color: #F5F5F5; color: #424242; border: 2px solid #BDBDBD; animation: none; }

            /* Priority Badges */
            .priority-badge {
                font-weight: 600;
                padding: 0.4rem 1rem;
                border-radius: 9999px;
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                font-size: 0.875rem;
            }

            .priority-low { background-color: #E0F2F7; color: #00838F; border: 2px solid #4DD0E1; }
            .priority-medium { background-color: #FFF8E1; color: #F57C00; border: 2px solid #FFB74D; }
            .priority-high {
                background-color: #FFEBEE;
                color: #C62828;
                border: 2px solid #EF5350;
                animation: shake 0.8s infinite;
            }

            @keyframes shake {
                10%, 90% { transform: translate3d(-1px, 0, 0); }
                20%, 80% { transform: translate3d(2px, 0, 0); }
                30%, 50%, 70% { transform: translate3d(-3px, 0, 0); }
                40%, 60% { transform: translate3d(3px, 0, 0); }
            }

            /* Table Styles */
            .table-container {
                background: white;
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            }

            table thead {
                background: linear-gradient(135deg, #FFEBEE 0%, #FFCDD2 100%);
            }

            table thead th {
                color: #C62828;
                font-weight: 700;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                padding: 1rem 1.5rem;
            }

            table tbody tr {
                transition: all 0.2s ease;
                border-bottom: 1px solid #f0f0f0;
            }

            table tbody tr:hover {
                background: linear-gradient(90deg, #FFF5F5 0%, #FFEBEE 100%);
                transform: scale(1.01);
            }

            /* Stats Cards */
            .stat-card {
                background: white;
                border-radius: 1rem;
                padding: 1.5rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 100px;
                height: 100px;
                background: radial-gradient(circle, rgba(211,47,47,0.1) 0%, transparent 70%);
                border-radius: 50%;
                transform: translate(30%, -30%);
            }

            /* Alert Styles */
            .alert-success {
                background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
                border-left: 4px solid #4CAF50;
            }

            .alert-error {
                background: linear-gradient(135deg, #FFEBEE 0%, #FFCDD2 100%);
                border-left: 4px solid #F44336;
            }

            /* Page Header */
            .page-header {
                background: linear-gradient(135deg, #D32F2F 0%, #B71C1C 100%);
                color: white;
                padding: 2rem;
                border-radius: 1rem;
                margin-bottom: 2rem;
                box-shadow: 0 8px 25px rgba(211, 47, 47, 0.3);
            }

            /* Filter Section */
            .filter-section {
                background: white;
                border-radius: 1rem;
                padding: 1.5rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
                border: 1px solid #f0f0f0;
            }

            /* Action Buttons */
            .action-btn {
                transition: all 0.2s ease;
            }

            .action-btn:hover {
                transform: scale(1.15);
            }

            /* Pagination */
            .pagination-links nav div:first-child div:nth-child(2) a {
                transition: all 0.2s ease;
            }

            .pagination-links nav div:first-child div:nth-child(2) a:hover {
                background-color: #FFEBEE;
                transform: translateY(-2px);
            }

            /* Responsive - GARDER FULL WIDTH */
            @media (max-width: 1200px) {
                .reclamations-container {
                    padding: 2rem;
                }
            }

            @media (max-width: 768px) {
                .reclamations-container {
                    padding: 1.5rem;
                }

                .stat-card {
                    padding: 1rem;
                }
                
                table thead th {
                    padding: 0.75rem 0.5rem;
                    font-size: 0.7rem;
                }
                
                .page-header {
                    padding: 1.5rem;
                }

                .page-header h1 {
                    font-size: 1.5rem;
                }
            }

            @media (max-width: 576px) {
                .reclamations-container {
                    padding: 1rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Custom Modal -->
        <div id="custom-modal" class="modal-overlay">
            <div class="modal-content">
                <div id="modal-icon" class="text-5xl mb-4 text-center"></div>
                <div id="modal-message" class="text-center text-lg text-gray-700 mb-6"></div>
                <div id="modal-buttons" class="flex justify-center gap-3"></div>
            </div>
        </div>

        <!-- Main Container FULL WIDTH -->
        <div class="reclamations-container">
            
            <!-- Page Header -->
            <div class="page-header animate-fade-in">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-ticket-alt text-4xl mr-4"></i>
                        <div>
                            <h1 class="text-3xl font-bold">{{ __('Gestion des Réclamations') }}</h1>
                            <p class="text-red-100 mt-1">Gérez et suivez toutes vos réclamations</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('reclamations.corbeille') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 border border-white border-opacity-30 rounded-full font-semibold text-sm text-primary-red uppercase tracking-wider transition duration-300">
                            <i class="fa fa-trash mr-2"></i> Corbeille
                        </a>
                        @can('reclamation-create')
                            <a href="{{ route('reclamations.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-white border-2 border-white rounded-full font-bold text-sm text-primary-red uppercase tracking-wider shadow-lg hover:bg-red-50 transition duration-300">
                                <i class="fas fa-plus-circle mr-2"></i> {{ __('Nouvelle Réclamation') }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert-success rounded-lg px-6 py-4 mb-6 shadow-lg animate-fade-in flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                        <div>
                            <strong class="font-bold text-green-800">Succès!</strong>
                            <span class="block sm:inline text-green-700 ml-2">{{ session('success') }}</span>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error rounded-lg px-6 py-4 mb-6 shadow-lg animate-fade-in flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 text-2xl mr-3"></i>
                        <div>
                            <strong class="font-bold text-red-800">Erreur!</strong>
                            <span class="block sm:inline text-red-700 ml-2">{{ session('error') }}</span>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
                <div class="stat-card card-hover-effect animate-fade-in delay-100">
                    <i class="fas fa-list-alt text-4xl text-blue-600 mb-3 animate-bounce-slow"></i>
                    <div class="text-3xl font-bold text-blue-900">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm font-semibold text-blue-700 mt-1">{{ __('Total') }}</div>
                </div>
                <div class="stat-card card-hover-effect animate-fade-in delay-200">
                    <i class="fas fa-hourglass-half text-4xl text-yellow-600 mb-3 animate-spin-slow"></i>
                    <div class="text-3xl font-bold text-yellow-900">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-sm font-semibold text-yellow-700 mt-1">{{ __('En attente') }}</div>
                </div>
                <div class="stat-card card-hover-effect animate-fade-in delay-300">
                    <i class="fas fa-sync-alt text-4xl text-orange-600 mb-3 animate-pulse-fast"></i>
                    <div class="text-3xl font-bold text-orange-900">{{ $stats['in_progress'] ?? 0 }}</div>
                    <div class="text-sm font-semibold text-orange-700 mt-1">{{ __('En cours') }}</div>
                </div>
                <div class="stat-card card-hover-effect animate-fade-in delay-400">
                    <i class="fas fa-check-circle text-4xl text-green-600 mb-3"></i>
                    <div class="text-3xl font-bold text-green-900">{{ $stats['resolved'] ?? 0 }}</div>
                    <div class="text-sm font-semibold text-green-700 mt-1">{{ __('Résolues') }}</div>
                </div>
                <div class="stat-card card-hover-effect animate-fade-in delay-500">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600 mb-3 animate-wobble"></i>
                    <div class="text-3xl font-bold text-red-900">{{ $stats['high_priority'] ?? 0 }}</div>
                    <div class="text-sm font-semibold text-red-700 mt-1">{{ __('Priorité haute') }}</div>
                </div>
                <div class="stat-card card-hover-effect animate-fade-in delay-600">
                    <i class="fas fa-calendar-alt text-4xl text-purple-600 mb-3 animate-slide-in-up"></i>
                    <div class="text-3xl font-bold text-purple-900">{{ $stats['this_month'] ?? 0 }}</div>
                    <div class="text-sm font-semibold text-purple-700 mt-1">{{ __('Ce mois') }}</div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section animate-fade-in delay-700 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-filter text-primary-red mr-2"></i>
                    {{ __('Filtres et Tri') }}
                </h3>
                <form method="GET" action="{{ route('reclamations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Rechercher') }}</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="{{ __('Titre ou référence...') }}"
                                   class="filter-input w-full border-2 border-gray-300 rounded-lg pl-10 pr-3 py-2.5 focus:ring-2 focus:ring-primary-red transition">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Statut') }}</label>
                        <select name="status" id="status" class="filter-select w-full border-2 border-gray-300 rounded-lg py-2.5 px-3 focus:ring-2 focus:ring-primary-red transition">
                            <option value="">{{ __('Tous les statuts') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('En attente') }}</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>{{ __('Résolue') }}</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ __('Fermée') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Priorité') }}</label>
                        <select name="priority" id="priority" class="filter-select w-full border-2 border-gray-300 rounded-lg py-2.5 px-3 focus:ring-2 focus:ring-primary-red transition">
                            <option value="">{{ __('Toutes les priorités') }}</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>{{ __('Faible') }}</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>{{ __('Moyenne') }}</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>{{ __('Haute') }}</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary-red flex-1 flex items-center justify-center px-5 py-2.5 rounded-lg text-white font-semibold uppercase tracking-wide">
                            <i class="fas fa-filter mr-2"></i>
                            {{ __('Filtrer') }}
                        </button>
                        <a href="{{ route('reclamations.index') }}" class="btn-secondary-tailwind flex items-center justify-center px-5 py-2.5 rounded-lg font-semibold uppercase tracking-wide">
                            <i class="fas fa-undo mr-2"></i>
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            @if ($reclamations->isEmpty())
                <div class="bg-white p-12 text-center rounded-xl shadow-lg animate-fade-in delay-800">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl font-semibold text-gray-600">{{ __('Aucune réclamation trouvée.') }}</p>
                    <p class="text-gray-500 mt-2">Essayez de modifier vos filtres ou créez une nouvelle réclamation.</p>
                </div>
            @else
                <div class="table-container animate-fade-in delay-800">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="py-4 px-6 text-left">
                                        <a href="{{ route('reclamations.index', array_merge(request()->query(), ['sort_by' => 'reference', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-primary-red transition">
                                            {{ __('Référence') }}
                                            @if(request('sort_by') == 'reference')
                                                <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ml-2"></i>
                                            @else
                                                <i class="fas fa-sort ml-2 opacity-50"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="py-4 px-6 text-left">{{ __('Titre') }}</th>
                                    @if(auth()->user()->hasRole('Sup_Admin'))
                                    <th class="py-4 px-6 text-left">{{ __('Utilisateur') }}</th>
                                    @endif
                                    <th class="py-4 px-6 text-center">{{ __('Statut') }}</th>
                                    <th class="py-4 px-6 text-center">{{ __('Priorité') }}</th>
                                    <th class="py-4 px-6 text-left">
                                        <a href="{{ route('reclamations.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-primary-red transition">
                                            {{ __('Date') }}
                                            @if(request('sort_by') == 'created_at' || !request('sort_by'))
                                                <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ml-2"></i>
                                            @else
                                                <i class="fas fa-sort ml-2 opacity-50"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="py-4 px-6 text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reclamations as $reclamation)
                                <tr>
                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        {{ $reclamation->reference }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-900">
                                        {{ Str::limit($reclamation->titre, 50) }}
                                    </td>
                                    @if(auth()->user()->hasRole('Sup_Admin'))
                                    <td class="px-6 py-4 text-gray-700">
                                        <i class="fas fa-user text-gray-400 mr-1"></i>
                                        {{ $reclamation->user->name ?? 'N/A' }}
                                    </td>
                                    @endif
                                    <td class="px-6 py-4 text-center">
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
                                        <td class="px-6 py-4 text-center">
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
                                        <td class="px-6 py-4 text-gray-700">
                                            <i class="far fa-calendar-alt text-gray-400 mr-2"></i>
                                            {{ $reclamation->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-3">
                                                @can('reclamation-show')
                                                    <a href="{{ route('reclamations.show', $reclamation->id) }}"
                                                       class="action-btn text-blue-600 hover:text-blue-800"
                                                       title="{{ __('Voir') }}">
                                                        <i class="fas fa-eye text-xl"></i>
                                                    </a>
                                                @endcan
                                                @can('reclamation-edit')
                                                    <a href="{{ route('reclamations.edit', $reclamation->id) }}"
                                                       class="action-btn text-indigo-600 hover:text-indigo-800"
                                                       title="{{ __('Modifier') }}">
                                                        <i class="fas fa-edit text-xl"></i>
                                                    </a>
                                                @endcan
                                                @can('reclamation-delete')
                                                    <button type="button" title="{{ __('Supprimer') }}"
                                                            onclick="showCustomConfirm('{{ __('Êtes-vous sûr de vouloir supprimer cette réclamation ?') }}', function() { document.getElementById('delete-form-{{ $reclamation->id }}').submit(); });"
                                                            class="action-btn text-red-600 hover:text-red-800">
                                                        <i class="fas fa-trash-alt text-xl"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $reclamation->id }}" action="{{ route('reclamations.destroy', $reclamation->id) }}" method="POST" class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center pagination-links">
                        {{ $reclamations->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>

        @push('scripts')
            <script>
                const customModal = document.getElementById('custom-modal');
                const modalMessage = document.getElementById('modal-message');
                const modalButtons = document.getElementById('modal-buttons');
                const modalIcon = document.getElementById('modal-icon');
                let resolveModalPromise;

                function showCustomModal(message, type = 'alert', onConfirm = null) {
                    modalMessage.textContent = message;
                    modalButtons.innerHTML = '';
                    modalIcon.innerHTML = '';

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
                        cancelBtn.className = 'px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wider shadow-md btn-secondary-tailwind';
                        cancelBtn.onclick = () => {
                            customModal.classList.remove('show');
                            resolveModalPromise(false);
                        };
                        modalButtons.appendChild(cancelBtn);
                    } else {
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
                    }

                    customModal.classList.add('show');
                    return new Promise(resolve => {
                        resolveModalPromise = resolve;
                    });
                }

                function showCustomConfirm(message, callback = null) {
                    return showCustomModal(message, 'confirm', callback);
                }
            </script>
        @endpush
    </body>
</x-app-layout>