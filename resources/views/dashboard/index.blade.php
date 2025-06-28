<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Dashboard') }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        {{-- Using unpkg for Tailwind CSS directly for quick setup --}}
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <style>
        /* Define CSS Variables for theming */
        :root {
            --primary-color: #D32F2F; /* Red-700, plus sombre pour le primaire */
            --accent-color: #C2185B; /* Rose foncé pour l'accent */
            --success-color: #10b981; /* Green-500 */
            --warning-color: #f59e0b; /* Yellow-500 */
            --danger-color: #ef4444; /* Red-500 */
            --dark-color: #1f2937; /* Gray-800 */
            --text-light: #f9fafb; /* Gray-50 */
            --bg-light: #f3f4f6; /* Gray-100 */
            --bg-card: rgba(255, 255, 255, 0.95);
            --border-color: rgba(255, 255, 255, 0.2);
            --box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            --border-radius: 1rem;
            --transition: all 0.3s ease-in-out;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif; /* A modern, clean font */
        }

        .header-card, .stats-card, .section-header, .table-container, .chart-card, .activity-card, .pointage-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
            position: relative;
            z-index: 1; /* Ensure content is above pseudo-elements */
        }

        .header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 1.5s ease-in-out;
            z-index: -1; /* Place behind content */
        }

        .header-card:hover::before {
            left: 100%;
        }

        .search-wrapper {
            background: rgba(255,255,255,0.9);
            border-radius: 50px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: var(--transition);
        }

        .search-wrapper:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .search-input {
            background: transparent;
            color: var(--dark-color);
        }

        .search-input::placeholder {
            color: #94a3b8;
        }

        .modern-table {
            border-collapse: separate; /* Allows border-radius on table cells */
            border-spacing: 0;
        }

        .modern-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }

        .modern-table th {
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1.25rem 1rem; /* More padding for better spacing */
        }

        .modern-table th:first-child {
            border-top-left-radius: var(--border-radius);
        }
        .modern-table th:last-child {
            border-top-right-radius: var(--border-radius);
        }

        .modern-table td {
            color: var(--dark-color);
            font-weight: 500;
            transition: var(--transition);
            padding: 1rem; /* Consistent padding */
            border-bottom: 1px solid rgba(0,0,0,0.05); /* Lighter border */
        }

        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        .modern-table tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
            transform: scale(1.005); /* Subtle scale effect */
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 100px; /* Ensure badges have a minimum width */
            justify-content: center;
        }

        .status-active, .status-en-cours {
            background: rgba(59, 130, 246, 0.1); /* Blue background */
            color: var(--primary-color);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .status-pending, .status-en-attente {
            background: rgba(245, 158, 11, 0.1); /* Yellow background */
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-completed, .status-terminé {
            background: rgba(16, 185, 129, 0.1); /* Green background */
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        /* New status for tasks (Nouveau/New) */
        .status-nouveau, .status-new {
            background: rgba(107, 114, 128, 0.1); /* Gray background */
            color: #6b7280; /* Gray-500 */
            border: 1px solid rgba(107, 114, 128, 0.2);
        }

        .pagination-modern .page-link {
            background: rgba(255,255,255,0.9);
            color: var(--primary-color);
            font-weight: 600;
            border: 2px solid transparent;
            transition: var(--transition);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            min-width: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination-modern .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            border-color: var(--primary-color);
        }

        .pagination-modern .page-link:hover:not(.page-item.active .page-link) {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Ripple effect for buttons */
        .ripple-btn {
            position: relative;
            overflow: hidden;
            transform: translate3d(0, 0, 0);
        }

        .ripple-btn:after {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform .5s, opacity 1s;
        }

        .ripple-btn:active:after {
            transform: scale(0, 0);
            opacity: .2;
            transition: 0s;
        }

        /* Loading spinner for search button */
        .loading-spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Productivity metrics cards */
        .metric-card {
            background: var(--bg-card);
            backdrop-filter: blur(15px);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }
        .metric-card .icon {
            font-size: 2.5rem;
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        .metric-card .value {
            font-size: 2.25rem;
            font-weight: 700;
        }
        .metric-card .label {
            font-size: 1rem;
            color: #6b7280; /* Gray-500 */
        }
        .trend-up { color: var(--success-color); }
        .trend-down { color: var(--danger-color); }

        /* Recent Activities */
        .activity-item {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .activity-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .activity-icon.blue { background-color: var(--primary-color); }
        .activity-icon.green { background-color: var(--success-color); }
        .activity-icon.yellow { background-color: var(--warning-color); }
        .activity-icon.gray { background-color: #6b7280; } /* For 'Nouveau' status */

        /* Styles for reclamation status badges */
        .reclamation-status-pending { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5; } /* Yellowish */
        .reclamation-status-in_progress { background-color: #cfe2ff; color: #084298; border: 1px solid #b6d4fe; } /* Blueish */
        .reclamation-status-resolved { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; } /* Greenish */
        .reclamation-status-closed { background-color: #e2e3e5; color: #495057; border: 1px solid #d3d6db; } /* Grayish */


        @media (max-width: 768px) {
            .modern-table {
                min-width: 600px;
            }
            .modern-table th, .modern-table td {
                padding: 0.8rem 0.5rem;
                font-size: 0.85rem;
            }
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .modern-table th, .modern-table td {
                padding: 0.6rem 0.4rem;
                font-size: 0.8rem;
            }
            .metric-card .value {
                font-size: 1.75rem;
            }
            .metric-card .label {
                font-size: 0.9rem;
            }
        }
        .table-container {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid var(--border-color);
            overflow-x: auto;
            overflow-y: auto; /* Enable vertical scrolling */
            max-height: 400px; /* Fixed height, adjust as needed */
        }

        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            min-width: 600px; /* Ensure table doesn't shrink too much */
        }

        .modern-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            position: sticky;
            top: 0; /* Keep header fixed while scrolling */
            z-index: 1;
        }

        .modern-table th, .modern-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        .modern-table tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
            transform: scale(1.005);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        /* Modal specific styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid var(--border-color);
            padding: 0; /* Important: remove padding here as the inner card provides it */
            max-width: 600px; /* Adjusted to fit the pointage card better */
            width: 90%;
            transform: translateY(-50px);
            opacity: 0;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
            position: relative;
            overflow: hidden; /* Ensures inner card's rounded corners and shadows are contained */
        }

        .modal-overlay.show .modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-close-button {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--dark-color);
            cursor: pointer;
            transition: color 0.2s ease-in-out;
            z-index: 10; /* Ensure it's above the content */
        }

        .modal-close-button:hover {
            color: var(--danger-color);
        }

        .modal-pointage-button {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 1rem 2rem;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .modal-pointage-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .modal-pointage-button:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }

        .pointage-card {
            /* No fade-in animation here if it's always in a modal */
            /* animation: fade-in 0.8s ease-out; */
        }

        /* Enhanced ripple effect */
        .group:active .absolute {
            animation: ripple 0.6s linear;
        }

        @keyframes ripple {
            0% { transform: scale(0); opacity: 1; }
            100% { transform: scale(1); opacity: 0; }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"> {{-- Changed space-y-8 to space-y-6 for slightly less overall spacing --}}
            <div class="header-card fade-in p-8 text-center relative z-10">
                <h1 class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-red-600 to-red-400 bg-clip-text text-transparent mb-2">
                    <?php
                        $hour = \Carbon\Carbon::now()->format('H');
                        $greeting = ($hour >= 6 && $hour < 19) ? 'Bonjour' : 'Bonsoir';
                    ?>
                    {{ $greeting }}, {{ Auth::user()->name }}
                </h1>
                @php
                    \Carbon\Carbon::setLocale('fr');
                @endphp
                <p class="text-gray-600 text-lg mt-2">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>

                <div class="search-container mt-8 max-w-xl mx-auto">
                    <form action="{{ route('dashboard') }}" method="GET" class="flex items-center search-wrapper rounded-full shadow-lg">
                        <input class="search-input flex-1 border-none outline-none py-4 px-6 text-lg rounded-l-full" type="text" name="search" placeholder="Rechercher dans le système..." value="{{ request()->get('search') }}">
                        <button class="search-btn bg-gradient-to-r from-red-600 to-red-400 text-white p-4 flex items-center justify-center rounded-r-full hover:from-blue-700 hover:to-red-500 transition duration-300 ripple-btn text-xl" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- NEW SMALL BUTTON TO OPEN POINTAGE MODAL --}}
            <div class="text-center mt-6">
                <button id="openPointageModalBtn" class="flex items-center justify-center mx-auto px-6 py-3 bg-gradient-to-r from-red-600 to-red-400 text-white rounded-full shadow-lg hover:from-red-700 hover:to-red-500 transition duration-300 transform hover:scale-105 text-lg font-semibold">
                    <i class="fas fa-clock mr-3 text-2xl"></i> Gérer mon Pointage
                </button>
            </div>

            {{-- Welcome Modal (Your original welcome modal, kept separate) --}}
            <div id="welcomeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-2xl p-8 max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-rocket text-white text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Bienvenue sur votre Dashboard!</h2>
                        <p class="text-gray-600 mb-6">C'est votre première visite. Nous sommes ravis de vous accueillir dans notre système de gestion.</p>
                        <div class="flex gap-3">
                            <button onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                                Fermer
                            </button>
                            <button onclick="closeModal()" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all">
                                Commencer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content (excluding the pointage card which is now in a modal) --}}
            {{-- Applied space-y-6 here for consistent spacing between major sections --}}
            <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 space-y-6"> 
                <div class="container mx-auto px-4 py-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 stats-grid">
                        @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                            <div class="metric-card fade-in p-6 text-center">
                                <div class="icon bg-gradient-to-r from-green-500 to-green-600"><i class="fas fa-users"></i></div>
                                <div class="value bg-gradient-to-r from-green-500 to-green-600 bg-clip-text text-transparent">{{ $userCount }}</div>
                                <div class="label text-gray-800 mt-2">Effectif de l’équipe UITS</div>
                            </div>
                            <div class="metric-card fade-in p-6 text-center">
                                <div class="icon bg-gradient-to-r from-blue-500 to-blue-600"><i class="fas fa-tasks"></i></div>
                                <div class="value bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">{{ $stats['total_tasks'] ?? 0 }}</div>
                                <div class="label text-gray-800 mt-2">Tâches Totales</div>
                            </div>
                            <div class="metric-card fade-in p-6 text-center">
                                <div class="icon bg-gradient-to-r from-purple-500 to-purple-600"><i class="fas fa-project-diagram"></i></div>
                                <div class="value bg-gradient-to-r from-purple-500 to-purple-600 bg-clip-text text-transparent">{{ $stats['total_projects'] ?? 0 }}</div>
                                <div class="label text-gray-800 mt-2">Projets Totaux</div>
                            </div>
                            <div class="metric-card fade-in p-6 text-center">
                                <div class="icon bg-gradient-to-r from-red-500 to-red-600"><i class="fas fa-graduation-cap"></i></div>
                                <div class="value bg-gradient-to-r from-red-500 to-red-600 bg-clip-text text-transparent">{{ $stats['total_formations'] ?? 0 }}</div>
                                <div class="label text-gray-800 mt-2">Formations Totales</div>
                            </div>
                        @else
                            <div class="metric-card fade-in p-6 text-center">
                                <div class="icon bg-gradient-to-r from-blue-500 to-blue-600"><i class="fas fa-tasks"></i></div>
                                <div class="value bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">{{ $stats['my_tasks'] ?? 0 }}</div>
                                <div class="label text-gray-800 mt-2">Mes Tâches</div>
                            </div>
                            <div class="metric-card fade-in p-6 text-center">
                                <div class="icon bg-gradient-to-r from-green-500 to-green-600"><i class="fas fa-check-circle"></i></div>
                                <div class="value bg-gradient-to-r from-green-500 to-green-600 bg-clip-text text-transparent">{{ $stats['completed_tasks'] ?? 0 }}</div>
                                <div class="label text-gray-800 mt-2">Tâches Terminées</div>
                            </div>
                            <div class="metric-card fade-in p-6 text-center">
                                <div class="icon bg-gradient-to-r from-red-500 to-red-600"><i class="fas fa-hourglass-half"></i></div>
                                <div class="value bg-gradient-to-r from-red-500 to-red-600 bg-clip-text text-transparent">{{ $stats['pending_tasks'] ?? 0 }}</div>
                                <div class="label text-gray-800 mt-2">Tâches en Cours</div>
                            </div>
                            <div class="metric-card fade-in p-6 text-center">
                                <div class="icon bg-gradient-to-r from-gray-500 to-gray-600"><i class="fas fa-plus-circle"></i></div>
                                <div class="value bg-gradient-to-r from-gray-500 to-gray-600 bg-clip-text text-transparent">{{ $stats['new_tasks'] ?? 0 }}</div>
                                <div class="label text-gray-800 mt-2">Nouvelles Tâches</div>
                            </div>
                        @endif
                    </div>

                    {{-- This container now holds the two chart cards and has its own gap --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6"> {{-- Added mt-6 here for spacing from the metric cards --}}
                        <div class="chart-card fade-in p-6">
                            <h3 class="section-title flex items-center gap-3 text-xl font-bold text-gray-800 mb-6">
                              <div class="section-icon w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-400 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                Statut des Tâches
                            </h3>
                            <div class="flex justify-center items-center h-80">
                                <canvas id="tasksStatusChart"></canvas>
                            </div>
                        </div>

                        {{-- NEW: Pointage Punctuality Chart (Doughnut) --}}
                        <div class="chart-card fade-in p-6">
                            <h3 class="section-title flex items-center gap-3 text-xl font-bold text-gray-800 mb-6">
                                <div class="section-icon w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-400 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                Ponctualité des Arrivées
                            </h3>
                            {{-- Add dropdown for period selection, visible only to Admin and Custom_Admin --}}
                            @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                            <div class="flex justify-end mb-4">
                                <select id="punctualityPeriodSelect" class="form-select border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    <option value="all">Tout</option>
                                    <option value="today">Aujourd'hui</option>
                                    <option value="week">Cette semaine</option>
                                    <option value="month" selected>Ce mois</option>
                                    <option value="year">Cette année</option>
                                </select>
                            </div>
                            @endif

                            @if ($pointagePunctualityChartData['total'] > 0)
                                <div class="flex justify-center items-center h-80">
                                    <canvas id="pointagePunctualityChart"></canvas>
                                </div>
                                <div class="text-center text-sm text-gray-600 mt-4">
                                    Nombre total d'arrivées : <span id="totalPunctualityArrivals">{{ $pointagePunctualityChartData['total'] }}</span>
                                </div>
                            @else
                                <div class="text-center text-gray-500 py-8" id="noPunctualityDataMessage">
                                    Aucune donnée de pointage disponible pour l'analyse de ponctualité.
                                </div>
                            @endif
                        </div>

                    </div>
                    
                    

                    <div class="activity-card fade-in p-6 mt-6"> {{-- Added mt-6 here for spacing from the previous chart --}}
                        <h3 class="section-title flex items-center gap-3 text-xl font-bold text-gray-800 mb-6">
                            <div class="section-icon w-10 h-10 bg-gradient-to-r from-red-600 to-red-400 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-history"></i>
                            </div>
                            Activités Récentes
                        </h3>
                        <div class="space-y-4">
                            @forelse($recentActivities as $activity)
                                <div class="activity-item flex items-start gap-4">
                                    <div class="activity-icon {{ $activity['color'] }}">
                                        <i class="{{ $activity['icon'] }} text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $activity['title'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $activity['type'] === 'task' ? 'Tâche' : 'Projet' }} - {{ $activity['status'] }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500">Aucune activité récente.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- NEW BLOCK FOR RECLAMATIONS --}}
                    <div class="table-container fade-in p-6 mt-6 mb-6"> {{-- Added mt-6 here for spacing from recent activities --}}
                        <h3 class="section-title flex items-center gap-3 text-xl font-bold text-gray-800 mb-6">
                            <div class="section-icon w-10 h-10 bg-gradient-to-r from-red-600 to-yellow-400 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            {{ (auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin')) ? __('Réclamations Non Résolues') : __('Mes Réclamations Résolues') }}
                        </h3>
                        @if($reclamations->count())
                            <div class="overflow-x-auto">
                                <table class="modern-table w-full whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="p-4 text-left rounded-tl-xl">Référence</th>
                                            <th class="p-4 text-left">Titre</th>
                                            @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                                            <th class="p-4 text-center">Utilisateur</th>
                                            @endif
                                            <th class="p-4 text-center">Priorité</th>
                                            <th class="p-4 text-center">Statut</th>
                                            <th class="p-4 text-center rounded-tr-xl">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reclamations as $reclamation)
                                        <tr>
                                            <td class="p-4 text-left">{{ $reclamation->reference }}</td>
                                            <td class="p-4 text-left">{{ $reclamation->titre }}</td>
                                            @if(auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin'))
                                            <td class="p-4 text-center">{{ $reclamation->user->name ?? 'N/A' }}</td>
                                            @endif
                                            <td class="p-4 text-center">{{ ucfirst($reclamation->priority) }}</td>
                                            <td class="p-4 text-center">
                                                <span class="status-badge reclamation-status-{{ strtolower(str_replace(' ', '_', $reclamation->status)) }}">
                                                    {{ ucfirst($reclamation->status) }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-center">{{ \Carbon\Carbon::parse($reclamation->created_at)->format('d/m/Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-8">
                                {{ (auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin')) ? __('Aucune réclamation non résolue.') : __('Aucune de vos réclamations n\'a été résolue.') }}
                            </p>
                        @endif
                    </div>
                    {{-- END OF NEW BLOCK --}}


                    @can("tache-list")
                        <div class="section-header fade-in p-6 mt-6 mb-4"> {{-- Added mt-6 here for spacing from previous section --}}
                            <h3 class="section-title flex items-center gap-3 text-xl font-bold text-gray-800">
                                <div class="section-icon w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-400 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                Liste des Tâches
                            </h3>
                        </div>
                        <div class="table-container fade-in mb-6 overflow-x-auto shadow-lg">
                            <table class="modern-table w-full whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th class="p-4 text-left rounded-tl-xl">Description</th>
                                        <th class="p-4 text-center">Statut</th>
                                        <th class="p-4 text-center">Date Début</th>
                                        <th class="p-4 text-center">Durée</th>
                                        <th class="p-4 text-center rounded-tr-xl">Assigné(e)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tasks as $tache)
                                        <tr class="border-b last:border-none hover:bg-gray-50">
                                            <td class="p-4 text-left">{{ $tache->description }}</td>
                                            <td class="p-4 text-center">
                                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $tache->status)) }}">
                                                    {{ $tache->status }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-center">{{ \Carbon\Carbon::parse($tache->datedebut)->format('d/m/Y') }}</td>
                                            <td class="p-4 text-center">{{ $tache->duree }}</td>
                                            <td class="p-4 text-center">{{ $tache->user->name ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-4 text-center text-gray-500">Aucune tâche trouvée.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-container flex justify-center mb-6">
                            <ul class="pagination-modern flex gap-2 list-none">
                                {{ $tasks->links('pagination::tailwind') }}
                            </ul>
                        </div>
                    @endcan

                    @can("project-list")
                        <div class="section-header fade-in p-4 mt-6 mb-4"> {{-- Added mt-6 here for spacing from previous section --}}
                            <h3 class="section-title flex items-center gap-3 text-xl font-bold text-gray-800">
                                <div class="section-icon w-10 h-10 bg-gradient-to-r from-purple-600 to-purple-400 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                                Liste des Projets
                            </h3>
                        </div>
                        <div class="table-container fade-in mb-6 overflow-x-auto shadow-lg">
                            <table class="modern-table w-full whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th class="p-4 text-left rounded-tl-xl">Titre</th>
                                        <th class="p-4 text-center">Nom du Client</th>
                                        <th class="p-4 text-center">Ville</th>
                                        <th class="p-4 text-center">Besoins</th>
                                        <th class="p-4 text-center">Assignées</th>
                                        <th class="p-4 text-center rounded-tr-xl">Date de Création</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($projects as $project)
                                        <tr class="border-b last:border-none hover:bg-gray-50">
                                            <td class="p-4 text-left">{{ $project->titre }}</td>
                                            <td class="p-4 text-center">{{ $project->nomclient }}</td>
                                            <td class="p-4 text-center">{{ $project->ville }}</td>
                                            <td class="p-4 text-center">{{ $project->bessoins }}</td>
                                            <td class="p-4 text-center">
                                                @forelse($project->users as $user)
                                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $user->name }}</span>{{ !$loop->last ? ' ' : '' }}
                                                @empty
                                                    N/A
                                                @endforelse
                                            </td>
                                            <td class="p-4 text-center">{{ \Carbon\Carbon::parse($project->date_project)->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-4 text-center text-gray-500">Aucun projet trouvé.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-container flex justify-center mb-6">
                            <ul class="pagination-modern flex gap-2 list-none">
                                {{ $projects->links('pagination::tailwind') }}
                            </ul>
                        </div>
                    @endcan

                    @can("formation-list")
                        <div class="section-header fade-in p-4 mt-6 mb-4"> {{-- Added mt-6 here for spacing from previous section --}}
                            <h3 class="section-title flex items-center gap-3 text-xl font-bold text-gray-800">
                                <div class="section-icon w-10 h-10 bg-gradient-to-r from-red-600 to-red-400 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                Liste des Formations
                            </h3>
                        </div>
                        <div class="table-container fade-in mb-6 overflow-x-auto shadow-lg">
                            <table class="modern-table w-full whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th class="p-4 text-left rounded-tl-xl">Nom</th>
                                        <th class="p-4 text-center">Statut</th>
                                        <th class="p-4 text-center">Formateur</th>
                                        <th class="p-4 text-center">Date</th>
                                        <th class="p-4 text-center">Fichier</th>
                                        <th class="p-4 text-center rounded-tr-xl">Assigné(e)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($formations as $formation)
                                        <tr class="border-b last:border-none hover:bg-gray-50">
                                            <td class="p-4 text-left">{{ $formation->name }}</td>
                                            <td class="p-4 text-center">
                                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $formation->status)) }}">
                                                    {{ $formation->status }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-center">{{ $formation->nomformateur }}</td>
                                            <td class="p-4 text-center">{{ \Carbon\Carbon::parse($formation->date)->format('d/m/Y') }}</td>
                                            <td class="p-4 text-center">
                                                @if($formation->file_path)
                                                    <a href="{{ Storage::url($formation->file_path) }}" title="Télécharger" target="_blank" class="text-blue-500 hover:text-blue-700">
                                                        <i class="fas fa-download text-lg"></i>
                                                    </a>
                                                @else
                                                    <span class="text-gray-500">Pas de fichier</span>
                                                @endif
                                            </td>
                                            <td class="p-4 text-center">
                                                @forelse($formation->users as $user)
                                                    <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">{{ $user->name }}</span>{{ !$loop->last ? ' ' : '' }}
                                                @empty
                                                    N/A
                                                @endforelse
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-4 text-center text-gray-500">Aucune formation trouvée.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-container flex justify-center mb-6">
                            <ul class="pagination-modern flex gap-2 list-none">
                                {{ $formations->links('pagination::tailwind') }}
                            </ul>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL STRUCTURE FOR POINTAGE - NOW CONTAINS THE FULL POINTAGE CARD --}}
    <div id="pointageModalOverlay" class="modal-overlay">
        <div class="modal-content">
            <button id="closePointageModalButton" class="modal-close-button">&times;</button>
            {{-- Content of the original pointage-card goes here --}}
            <div class="pointage-card bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300">
                {{-- Gradient Header --}}
                <div class="bg-gradient-to-r from-red-700 to-red-500 p-8 text-white relative overflow-hidden shadow-inner">
                    <div class="absolute inset-0 bg-black opacity-10"></div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-white opacity-10 rounded-full"></div>

                    <div class="relative z-10 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-30 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-clock text-3xl"></i> {{-- Made icon larger --}}
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Gestion du Pointage</h3>
                        <div class="w-16 h-1 bg-white bg-opacity-50 mx-auto rounded-full"></div>
                    </div>
                </div>

                {{-- Content Body --}}
                <div class="p-8">
                    {{-- Status Message --}}
                    <div class="text-center mb-8">
                        @if($hasClockedInToday && !$hasClockedOutToday)
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                Vous êtes actuellement pointé(e)
                            </div>
                        @elseif($hasClockedInToday && $hasClockedOutToday)
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-semibold">
                                <i class="fas fa-check-circle"></i>
                                Votre journée de travail est terminée
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-full font-semibold">
                                <i class="fas fa-clock"></i>
                                Cliquez pour pointer votre arrivée
                            </div>
                        @endif
                    </div>

                    {{-- Pointage Form --}}
                    <form action="{{ route('pointage.pointer') }}" method="POST" class="text-center" id="pointageFormInModal">
                        @csrf
                        <button type="submit"
                                class="group relative w-full py-4 px-8 rounded-2xl text-xl font-bold transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-opacity-50
                                {{ ($hasClockedInToday && !$hasClockedOutToday) ? 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:ring-red-300' : 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:ring-green-300' }}
                                {{ ($hasClockedInToday && $hasClockedOutToday) ? 'opacity-70 cursor-not-allowed bg-gray-400' : 'text-white shadow-lg hover:shadow-xl' }}"
                                {{ ($hasClockedInToday && $hasClockedOutToday) ? 'disabled' : '' }}>

                            {{-- Button Content --}}
                            <div class="flex items-center justify-center gap-3">
                                @if($hasClockedInToday && !$hasClockedOutToday)
                                    <i class="fas fa-sign-out-alt text-3xl group-hover:animate-bounce"></i> {{-- Made icon larger --}}
                                    <span>Pointer Départ</span>
                                @elseif($hasClockedInToday && $hasClockedOutToday)
                                    <i class="fas fa-calendar-check text-3xl"></i> {{-- Made icon larger --}}
                                    <span>Journée Terminée</span>
                                @else
                                    <i class="fas fa-sign-in-alt text-3xl group-hover:animate-bounce"></i> {{-- Made icon larger --}}
                                    <span>Pointer Arrivée</span>
                                @endif
                            </div>

                            {{-- Ripple Effect --}}
                            <div class="absolute inset-0 rounded-2xl overflow-hidden">
                                <div class="absolute inset-0 transform scale-0 group-active:scale-100 transition-transform duration-300 bg-white opacity-20 rounded-2xl"></div>
                            </div>
                        </button>
                    </form>

                    {{-- Alert Messages --}}
                    <div class="mt-6 space-y-4">
                        @if(session('success'))
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg animate-fade-in">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-400 mr-3 text-xl"></i> {{-- Icon size increased --}}
                                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        @if(session('info'))
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg animate-fade-in">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-blue-400 mr-3 text-xl"></i> {{-- Icon size increased --}}
                                    <p class="text-blue-800 font-medium">{{ session('info') }}</p>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg animate-fade-in">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle text-red-400 mr-3 text-xl"></i> {{-- Icon size increased --}}
                                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL STRUCTURE --}}

    <script>
        // Fade-in animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Stop observing once visible
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Search button loading state
        document.querySelectorAll('.search-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<div class="loading-spinner"></div>';
                btn.disabled = true;
                setTimeout(() => {
                    btn.closest('form').submit();
                }, 800); // Shorter loading simulation
            });
        });

        // Table row interaction (visual feedback on click)
        document.querySelectorAll('.modern-table tbody tr').forEach(row => {
            row.addEventListener('click', function() {
                this.classList.add('scale-1005', 'shadow-md');
                setTimeout(() => {
                    this.classList.remove('scale-1005', 'shadow-md');
                }, 200);
            });
        });

        // Chart.js instances
        let tasksStatusChart;
        let pointageChart;
        let pointagePunctualityChart; // Declare new chart instance globally

        async function fetchAndRenderCharts(periodForWorkTime = 'month', periodForPunctuality = 'all') {
            try {
                // Fetch all chart data from the analytics endpoint
                const response = await fetch("{{ route('dashboard.analytics') }}?period=" + periodForWorkTime + "&punctuality_period=" + periodForPunctuality);
                const data = await response.json();

                // Tasks Status Chart (Doughnut chart)
                const tasksStatusCtx = document.getElementById('tasksStatusChart');
                if (tasksStatusCtx) {
                    if (tasksStatusChart) {
                        tasksStatusChart.destroy();
                    }
                    tasksStatusChart = new Chart(tasksStatusCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: data.tasks_chart.labels, // Correctly use data from fetched JSON
                            datasets: [{
                                label: 'Nombre de tâches',
                                data: data.tasks_chart.data, // Correctly use data from fetched JSON
                                backgroundColor: data.tasks_chart.colors.map(colorName => {
                                    switch(colorName) {
                                        case 'green': return 'rgba(16, 185, 129, 0.8)';
                                        case 'blue': return 'rgba(59, 130, 246, 0.8)';
                                        case 'yellow': return 'rgba(245, 158, 11, 0.8)';
                                        case 'gray': return 'rgba(107, 114, 128, 0.8)';
                                        default: return 'rgba(150, 150, 150, 0.8)';
                                    }
                                }),
                                borderColor: 'white',
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        font: {
                                            size: 14,
                                            weight: 'bold'
                                        },
                                        color: 'var(--dark-color)'
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Répartition des Tâches par Statut',
                                    font: {
                                        size: 18,
                                        weight: 'bold'
                                    },
                                    color: 'var(--dark-color)'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed !== null) {
                                                label += context.parsed + ' tâches';
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Pointage Chart (Bar chart)
                const pointageCtx = document.getElementById('pointageChart');
                if (pointageCtx) {
                    if (pointageChart) {
                        pointageChart.destroy();
                    }
                    pointageChart = new Chart(pointageCtx.getContext('2d'), {
                        type: 'bar', // Bar chart for time worked
                        data: {
                            labels: data.pointage_chart.labels,
                            datasets: [{
                                label: data.pointage_chart.title,
                                data: data.pointage_chart.data,
                                backgroundColor: 'rgba(194, 24, 91, 0.8)', // A nice pink/purple color
                                borderColor: 'rgba(194, 24, 91, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Heures Travaillées',
                                        font: {
                                            size: 14,
                                            weight: 'bold'
                                        },
                                        color: 'var(--dark-color)'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Période',
                                        font: {
                                            size: 14,
                                            weight: 'bold'
                                        },
                                        color: 'var(--dark-color)'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Temps de Travail par Période',
                                    font: {
                                        size: 18,
                                        weight: 'bold'
                                    },
                                    color: 'var(--dark-color)'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed.y !== null) {
                                                label += context.parsed.y + 'h';
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // NEW CHART: Pointage Punctuality Chart (Doughnut)
                const pointagePunctualityCtx = document.getElementById('pointagePunctualityChart');
                const totalPunctualityArrivalsSpan = document.getElementById('totalPunctualityArrivals');
                const noPunctualityDataMessageDiv = document.getElementById('noPunctualityDataMessage');

                if (data.pointage_punctuality_chart.total > 0) {
                    // Show canvas and total, hide no data message
                    if (pointagePunctualityCtx) pointagePunctualityCtx.style.display = 'block';
                    if (totalPunctualityArrivalsSpan) totalPunctualityArrivalsSpan.closest('div').style.display = 'block';
                    if (noPunctualityDataMessageDiv) noPunctualityDataMessageDiv.style.display = 'none';

                    if (pointagePunctualityChart) {
                        pointagePunctualityChart.destroy();
                    }
                    pointagePunctualityChart = new Chart(pointagePunctualityCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: data.pointage_punctuality_chart.labels, // ['En Retard', 'À l\'heure']
                            datasets: [{
                                data: data.pointage_punctuality_chart.data, // [% Late, % On Time]
                                backgroundColor: data.pointage_punctuality_chart.colors, // ['#D32F2F', '#4CAF50']
                                borderColor: 'white',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        font: {
                                            size: 14,
                                            weight: 'bold'
                                        },
                                        color: 'var(--dark-color)'
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Pourcentage de Ponctualité des Arrivées',
                                    font: {
                                        size: 18,
                                        weight: 'bold'
                                    },
                                    color: 'var(--dark-color)'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            const label = tooltipItem.label || '';
                                            const value = tooltipItem.raw;
                                            return `${label}: ${value}%`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                    // Update total arrivals count
                    if (totalPunctualityArrivalsSpan) {
                        totalPunctualityArrivalsSpan.textContent = data.pointage_punctuality_chart.total;
                    }
                } else {
                    // Hide canvas and total, show no data message
                    if (pointagePunctualityCtx) pointagePunctualityCtx.style.display = 'none';
                    if (totalPunctualityArrivalsSpan) totalPunctualityArrivalsSpan.closest('div').style.display = 'none';
                    if (noPunctualityDataMessageDiv) noPunctualityDataMessageDiv.style.display = 'block';

                    if (pointagePunctualityChart) {
                        pointagePunctualityChart.destroy();
                        pointagePunctualityChart = null; // Clear the instance
                    }
                }

            } catch (error) {
                console.error("Error fetching chart data:", error);
            }
        }

        // MODAL JAVASCRIPT LOGIC
        document.addEventListener('DOMContentLoaded', () => {
            const pointageModalOverlay = document.getElementById('pointageModalOverlay');
            const closePointageModalButton = document.getElementById('closePointageModalButton'); // Corrected ID
            const openPointageModalBtn = document.getElementById('openPointageModalBtn');
            const pointageFormInModal = document.getElementById('pointageFormInModal');

            // Function to show the pointage modal
            function showPointageModal() {
                pointageModalOverlay.classList.add('show');
                document.body.style.overflow = 'hidden'; // Disable scrolling on the body
            }

            // Function to hide the pointage modal
            function hidePointageModal() {
                pointageModalOverlay.classList.remove('show');
                document.body.style.overflow = ''; // Re-enable scrolling
            }

            // Event listener for the new small button to open the pointage modal
            if (openPointageModalBtn) {
                openPointageModalBtn.addEventListener('click', showPointageModal);
            }

            // Event listeners for pointage modal close button and overlay click
            if (closePointageModalButton) {
                closePointageModalButton.addEventListener('click', hidePointageModal);
            }
            if (pointageModalOverlay) {
                pointageModalOverlay.addEventListener('click', (e) => {
                    if (e.target === pointageModalOverlay) {
                        hidePointageModal();
                    }
                });
            }
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && pointageModalOverlay.classList.contains('show')) {
                    hidePointageModal();
                }
            });

            // Handle pointage modal form submission
            if (pointageFormInModal) {
                pointageFormInModal.addEventListener('submit', function() {
                    const submitButton = this.querySelector('button[type="submit"]');
                    submitButton.innerHTML = '<div class="loading-spinner"></div>';
                    submitButton.disabled = true;
                });
            }

            // ORIGINAL Welcome Modal Logic
            function checkFirstVisit() {
                const hasVisited = localStorage.getItem('hasVisitedDashboard');
                if (!hasVisited) {
                    const welcomeModal = document.getElementById('welcomeModal');
                    const welcomeModalContent = document.getElementById('modalContent');

                    if (welcomeModal && welcomeModalContent) {
                        welcomeModal.classList.remove('hidden');
                        setTimeout(() => {
                            welcomeModalContent.classList.remove('scale-95', 'opacity-0');
                            welcomeModalContent.classList.add('scale-100', 'opacity-100');
                        }, 50);
                    }
                    localStorage.setItem('hasVisitedDashboard', 'true');
                }
            }

            // Function for the welcome modal's close button (made global for onclick)
            window.closeModal = function() {
                const welcomeModal = document.getElementById('welcomeModal');
                const welcomeModalContent = document.getElementById('modalContent');
                if (welcomeModalContent) {
                    welcomeModalContent.classList.add('scale-95', 'opacity-0');
                    welcomeModalContent.classList.remove('scale-100', 'opacity-100');
                    setTimeout(() => {
                        if (welcomeModal) welcomeModal.classList.add('hidden');
                    }, 300);
                }
            };

            // Close welcome modal when clicking outside
            const welcomeModalOverlay = document.getElementById('welcomeModal');
            if (welcomeModalOverlay) {
                welcomeModalOverlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        window.closeModal();
                    }
                });
            }

            // Logic to automatically show pointage modal on first visit for non-admins
            const isUserAdmin = {{ Auth::user()->hasRole('Sup_Admin') || Auth::user()->hasRole('Custom_Admin') ? 'true' : 'false' }};
            const hasClockedInToday = {{ $hasClockedInToday ? 'true' : 'false' }};
            const hasClockedOutToday = {{ $hasClockedOutToday ? 'true' : 'false' }};
            const today = new Date().toDateString();
            const lastPointageModalShownDate = localStorage.getItem('pointageModalShownDate');

            if (!isUserAdmin && !hasClockedInToday && !hasClockedOutToday && lastPointageModalShownDate !== today) {
                showPointageModal(); // Show the pointage modal automatically
                localStorage.setItem('pointageModalShownDate', today);
            }

            // Initial render of all charts on page load
            const initialPointagePeriod = document.getElementById('pointagePeriodSelect')?.value || 'month';
            // For punctuality, if not admin, force 'all' to ensure it gets user's total data without period filter.
            // If admin, use selected value or default to 'month'.
            const initialPunctualityPeriod = "{{ Auth::user()->hasRole('Sup_Admin') || Auth::user()->hasRole('Custom_Admin') ? 'month' : 'all' }}";

            fetchAndRenderCharts(initialPointagePeriod, initialPunctualityPeriod);

            // Event listener for "Temps de Travail" period select
            const pointagePeriodSelect = document.getElementById('pointagePeriodSelect');
            if (pointagePeriodSelect) {
                pointagePeriodSelect.addEventListener('change', (event) => {
                    const currentPunctualityPeriod = document.getElementById('punctualityPeriodSelect') ? document.getElementById('punctualityPeriodSelect').value : 'all';
                    fetchAndRenderCharts(event.target.value, currentPunctualityPeriod);
                });
            }

            // Event listener for "Ponctualité des Arrivées" period select
            const punctualityPeriodSelect = document.getElementById('punctualityPeriodSelect');
            if (punctualityPeriodSelect) {
                punctualityPeriodSelect.addEventListener('change', (event) => {
                    const currentPointagePeriod = document.getElementById('pointagePeriodSelect')?.value || 'month';
                    fetchAndRenderCharts(currentPointagePeriod, event.target.value);
                });
            }
        });
    </script>
</x-app-layout>