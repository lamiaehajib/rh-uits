<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Liste des Objectifs') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary-red': '#DC2626',
                            'dark-red': '#991B1B',
                            'light-red': '#FEF2F2',
                            'accent-blue': '#3B82F6',
                            'accent-green': '#10B981',
                            'accent-purple': '#8B5CF6',
                            'accent-orange': '#F59E0B',
                            'glass-white': 'rgba(255, 255, 255, 0.9)',
                            'glass-gray': 'rgba(249, 250, 251, 0.8)',
                        },
                        fontFamily: {
                            sans: ['Inter', 'Poppins', 'sans-serif'],
                        },
                        animation: {
                            'fade-in': 'fadeIn 0.8s ease-out forwards',
                            'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                            'slide-in': 'slideIn 0.6s ease-out forwards',
                            'bounce-soft': 'bounceSoft 0.6s ease-out',
                            'pulse-glow': 'pulseGlow 2s infinite',
                            'scale-hover': 'scaleHover 0.3s ease-in-out',
                            'float': 'float 3s ease-in-out infinite',
                        },
                        keyframes: {
                            fadeIn: {
                                '0%': { opacity: '0', transform: 'translateY(20px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' }
                            },
                            fadeInUp: {
                                '0%': { opacity: '0', transform: 'translateY(40px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' }
                            },
                            slideIn: {
                                '0%': { transform: 'translateX(-100%)', opacity: '0' },
                                '100%': { transform: 'translateX(0)', opacity: '1' }
                            },
                            bounceSoft: {
                                '0%, 100%': { transform: 'translateY(0)' },
                                '50%': { transform: 'translateY(-10px)' }
                            },
                            pulseGlow: {
                                '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                                '50%': { opacity: '0.8', transform: 'scale(1.02)' }
                            },
                            scaleHover: {
                                '0%': { transform: 'scale(1)' },
                                '100%': { transform: 'scale(1.05)' }
                            },
                            float: {
                                '0%, 100%': { transform: 'translateY(0px)' },
                                '50%': { transform: 'translateY(-8px)' }
                            }
                        },
                        backdropBlur: {
                            'xs': '2px',
                        },
                        boxShadow: {
                            'modern': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                            'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
                            'glow': '0 0 30px rgba(220, 38, 38, 0.3)',
                            'soft': '0 4px 20px rgba(0, 0, 0, 0.08)',
                        }
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            /* Enhanced Custom Styles */
            

            .glass-morphism {
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            }

            .modern-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }

            .gradient-text {
                background: linear-gradient(135deg, #DC2626, #991B1B);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .btn-gradient {
                background: linear-gradient(135deg, #DC2626, #991B1B);
                box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .btn-gradient:hover {
                background: linear-gradient(135deg, #991B1B, #DC2626);
                transform: translateY(-3px);
                box-shadow: 0 15px 30px rgba(220, 38, 38, 0.4);
            }

            .btn-secondary-modern {
                background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
                color: #374151;
                border: 1px solid rgba(209, 213, 219, 0.5);
                backdrop-filter: blur(10px);
                transition: all 0.3s ease;
            }

            .btn-secondary-modern:hover {
                background: linear-gradient(135deg, #E5E7EB, #D1D5DB);
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            }

            .floating-elements::before {
                content: '';
                position: absolute;
                top: 10%;
                left: 10%;
                width: 100px;
                height: 100px;
                background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
                border-radius: 50%;
                animation: float 4s ease-in-out infinite;
            }

            .floating-elements::after {
                content: '';
                position: absolute;
                bottom: 10%;
                right: 10%;
                width: 80px;
                height: 80px;
                background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
                border-radius: 50%;
                animation: float 3s ease-in-out infinite reverse;
            }

            .progress-bar-animated {
                background: linear-gradient(90deg, #DC2626, #F59E0B, #10B981);
                background-size: 200% 100%;
                animation: progress-shimmer 2s infinite;
            }

            @keyframes progress-shimmer {
                0% { background-position: 200% 0; }
                100% { background-position: -200% 0; }
            }

            .table-row-hover {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .table-row-hover:hover {
                background: linear-gradient(90deg, rgba(255, 255, 255, 0.8), rgba(249, 250, 251, 0.9));
                transform: translateX(8px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(5px);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .modal-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .modal-content {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                padding: 2.5rem;
                border-radius: 20px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                max-width: 90%;
                width: 400px;
                transform: translateY(-50px) scale(0.9);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .modal-overlay.show .modal-content {
                transform: translateY(0) scale(1);
            }

            .tag-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem 1rem;
                border-radius: 50px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border: 1px solid rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(10px);
                transition: all 0.3s ease;
            }

            .tag-badge:hover {
                transform: scale(1.05);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .form-input-modern {
                background: rgba(255, 255, 255, 0.9);
                border: 1px solid rgba(229, 231, 235, 0.6);
                border-radius: 12px;
                padding: 0.75rem 1rem;
                transition: all 0.3s ease;
                backdrop-filter: blur(10px);
            }

            .form-input-modern:focus {
                background: rgba(255, 255, 255, 1);
                border-color: #DC2626;
                box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
                transform: translateY(-1px);
            }

            .action-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.5rem;
                border-radius: 50%;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .action-button:hover {
                transform: translateY(-2px) scale(1.1);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            .notification-modern {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            }

            .animate-delayed {
                animation-delay: var(--delay, 0s);
            }

            .background-pattern {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0.03;
                background-image: radial-gradient(circle at 25px 25px, #000 2px, transparent 0);
                background-size: 50px 50px;
                pointer-events: none;
                z-index: -1;
            }
        </style>
    </head>
    <body class="relative">
        <div class="background-pattern"></div>
        
        <div class="floating-elements fixed inset-0 pointer-events-none z-0"></div>

        <div id="custom-modal" class="modal-overlay">
            <div class="modal-content">
                <div id="modal-icon" class="text-center text-4xl mb-4"></div>
                <div id="modal-message" class="text-center text-lg text-gray-700 mb-6"></div>
                <div id="modal-buttons" class="flex justify-center gap-4"></div>
            </div>
        </div>

        <x-slot name="header">
            <div class="modern-card rounded-2xl p-6 mx-4 animate-fade-in">
                <h2 class="font-bold text-3xl gradient-text leading-tight flex items-center animate-float">
                    <i class="fas fa-bullseye mr-4 text-primary-red"></i> 
                    {{ __('Liste des Objectifs') }}
                </h2>
                <div class="w-32 h-1 bg-gradient-to-r from-primary-red to-accent-orange rounded-full mt-3 animate-pulse-glow"></div>
            </div>
        </x-slot>

        <div class="py-8 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                @if (session('success'))
                    <div class="notification-modern border-l-4 border-green-500 text-green-700 p-6 mb-6 animate-slide-in" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-2xl mr-4 text-green-500"></i>
                            <div>
                                <p class="font-bold text-lg">Succès!</p>
                                <p>{{ session('success') }}</p>
                            </div>
                            <button class="ml-auto text-green-500 hover:text-green-700 text-xl" onclick="this.parentElement.parentElement.style.display='none';">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="notification-modern border-l-4 border-red-500 text-red-700 p-6 mb-6 animate-slide-in animate-pulse-glow" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-2xl mr-4 text-red-500"></i>
                            <div>
                                <p class="font-bold text-lg">Erreur!</p>
                                <p>{{ session('error') }}</p>
                            </div>
                            <button class="ml-auto text-red-500 hover:text-red-700 text-xl" onclick="this.parentElement.parentElement.style.display='none';">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="modern-card rounded-3xl shadow-modern animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="p-8">
                        
                        <div class="flex flex-col lg:flex-row justify-between items-center mb-8">
                            <div class="text-center lg:text-left mb-6 lg:mb-0">
                                <h3 class="text-3xl font-bold gradient-text mb-2">Vos Objectifs</h3>
                                <p class="text-gray-600 text-lg">Gérez et suivez vos objectifs efficacement</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('objectifs.calendar.view') }}" class="btn-secondary-modern px-6 py-3 rounded-full font-semibold text-sm uppercase tracking-wider flex items-center justify-center">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Voir le Calendrier
                                </a>
                                <a href="{{ route('objectifs.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
                                @role('Sup_Admin')
                                <a href="{{ route('objectifs.create') }}" class="btn-gradient px-6 py-3 rounded-full font-semibold text-sm text-white uppercase tracking-wider flex items-center justify-center">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Créer un Objectif
                                </a>
                                @endrole
                            </div>
                        </div>

                        <div class="glass-morphism rounded-2xl p-6 mb-8 animate-fade-in" style="animation-delay: 0.4s;">
                            <form action="{{ route('objectifs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                                <div>
                                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Recherche</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text" name="search" id="search" placeholder="Rechercher..." 
                                               class="form-input-modern block w-full pl-10 text-sm" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div>
                                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                                    <select name="type" id="type" class="form-input-modern block w-full text-sm">
                                        <option value="">Tous les types</option>
                                        <option value="formations" {{ request('type') == 'formations' ? 'selected' : '' }}>Formations</option>
                                        <option value="projets" {{ request('type') == 'projets' ? 'selected' : '' }}>Projets</option>
                                        <option value="vente" {{ request('type') == 'vente' ? 'selected' : '' }}>Vente</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="duree_filter" class="block text-sm font-semibold text-gray-700 mb-2">Durée (Type)</label>
                                    <select name="duree_filter" id="duree_filter" class="form-input-modern block w-full text-sm">
                                        <option value="">Toutes les durées</option>
                                        <option value="jours" {{ request('duree_filter') == 'jours' ? 'selected' : '' }}>Jour</option>
                                        <option value="semaines" {{ request('duree_filter') == 'semaines' ? 'selected' : '' }}>Semaine</option>
                                        <option value="mois" {{ request('duree_filter') == 'mois' ? 'selected' : '' }}>Mois</option>
                                        <option value="annee" {{ request('duree_filter') == 'annee' ? 'selected' : '' }}>Année</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="date_from" class="block text-sm font-semibold text-gray-700 mb-2">Date Début</label>
                                    <input type="date" name="date_from" id="date_from" 
                                           class="form-input-modern block w-full text-sm" value="{{ request('date_from') }}">
                                </div>
                                <div>
                                    <label for="date_to" class="block text-sm font-semibold text-gray-700 mb-2">Date Fin</label>
                                    <input type="date" name="date_to" id="date_to" 
                                           class="form-input-modern block w-full text-sm" value="{{ request('date_to') }}">
                                </div>
                                <div class="flex flex-col justify-end gap-2">
                                    <button type="submit" class="btn-gradient px-6 py-3 rounded-full font-semibold text-sm text-white uppercase tracking-wider flex items-center justify-center">
                                        <i class="fas fa-filter mr-2"></i> Filtrer
                                    </button>
                                    <a href="{{ route('objectifs.index') }}" class="btn-secondary-modern px-6 py-3 rounded-full font-semibold text-sm uppercase tracking-wider flex items-center justify-center">
                                        <i class="fas fa-undo mr-2"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>

                        @if ($objectifs->isEmpty())
                            <div class="glass-morphism rounded-2xl p-12 text-center animate-bounce-soft">
                                <i class="fas fa-inbox text-6xl text-gray-400 mb-6"></i>
                                <p class="text-2xl font-semibold text-gray-600">Aucun objectif trouvé</p>
                                <p class="text-gray-500 mt-2">Commencez par créer votre premier objectif</p>
                            </div>
                        @else
                            <div class="modern-card rounded-2xl overflow-hidden shadow-modern animate-fade-in" style="animation-delay: 0.6s;">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gradient-to-r from-gray-800 to-gray-900 text-white">
                                            <tr>
                                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">
                                                    <i class="fas fa-calendar mr-2"></i>Date
                                                </th>
                                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">
                                                    <i class="fas fa-tag mr-2"></i>Type
                                                </th>
                                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">
                                                    <i class="fas fa-align-left mr-2"></i>Description
                                                </th>
                                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">
                                                    <i class="fas fa-user mr-2"></i>Utilisateur
                                                </th>
                                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">
                                                    <i class="fas fa-chart-line mr-2"></i>Progression
                                                </th>
                                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">
                                                    <i class="fas fa-cog mr-2"></i>Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach ($objectifs as $index => $objectif)
                                                <tr class="table-row-hover animate-fade-in animate-delayed" style="--delay: {{ 0.1 * $index }}s;">
                                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                                        {{ $objectif->date }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="tag-badge
                                                            @if($objectif->type == 'formations') bg-blue-100 text-blue-800 border-blue-200
                                                            @elseif($objectif->type == 'projets') bg-purple-100 text-purple-800 border-purple-200
                                                            @elseif($objectif->type == 'vente') bg-green-100 text-green-800 border-green-200
                                                            @else bg-gray-100 text-gray-800 border-gray-200
                                                            @endif">
                                                            @if($objectif->type == 'formations')
                                                                <i class="fas fa-graduation-cap mr-1"></i>
                                                            @elseif($objectif->type == 'projets')
                                                                <i class="fas fa-project-diagram mr-1"></i>
                                                            @elseif($objectif->type == 'vente')
                                                                <i class="fas fa-chart-line mr-1"></i>
                                                            @endif
                                                            {{ ucfirst($objectif->type) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-gray-800 max-w-xs">
                                                        <div class="truncate" title="{{ $objectif->description }}">
                                                            {{ Str::limit($objectif->description, 70) }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex flex-col space-y-1">
                                                            @forelse ($objectif->users as $assignedUser)
                                                                <span class="inline-flex items-center text-sm font-medium text-gray-900">
                                                                    <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                                                                        {{ substr($assignedUser->name, 0, 1) }}
                                                                    </div>
                                                                    {{ $assignedUser->name }}
                                                                </span>
                                                            @empty
                                                                <span class="text-sm text-gray-500">
                                                                    <i class="fas fa-user-slash mr-1"></i>
                                                                    Non assigné
                                                                </span>
                                                            @endforelse
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                                            <div class="h-3 rounded-full transition-all duration-1000 ease-out
                                                                {{ $objectif->calculated_progress == 100 ? 'bg-gradient-to-r from-green-400 to-green-600' : 'progress-bar-animated' }}" 
                                                                style="width: {{ $objectif->calculated_progress }}%"></div>
                                                        </div>
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-sm font-semibold text-gray-700">{{ $objectif->calculated_progress }}%</span>
                                                            @if ($objectif->calculated_progress == 100)
                                                                <i class="fas fa-check-circle text-green-500"></i>
                                                            @endif
                                                        </div>
                                                        @if ($objectif->needs_explanation && $objectif->calculated_progress < 100)
                                                            <div class="mt-2 p-2 bg-red-50 rounded-lg border border-red-200">
                                                                <p class="text-red-600 text-xs font-semibold animate-pulse-glow">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                                    Explication requise !
                                                                </p>
                                                                <a href="{{ route('objectifs.show', $objectif->id) }}#explanation" 
                                                                   class="text-blue-600 hover:text-blue-800 text-xs underline">
                                                                    Fournir une explication
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center space-x-2">
                                                            @can('objectif-show')
                                                            <button class="action-button bg-blue-100 text-blue-600 hover:bg-blue-200" 
                                                                    onclick="window.location.href='{{ route('objectifs.show', $objectif->id) }}'" 
                                                                    title="Voir">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            @endcan
                                                            @can('objectif-edit')
                                                            <button class="action-button bg-indigo-100 text-indigo-600 hover:bg-indigo-200" 
                                                                    onclick="window.location.href='{{ route('objectifs.edit', $objectif->id) }}'" 
                                                                    title="Modifier">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            @endcan
                                                    {{-- Nouveau bouton de duplication --}}
                                                    
                                                    @can('objectif-create')
                                                                <button type="button" title="Dupliquer l'objectif" onclick="confirmDuplicate({{ $objectif->id }})" class="action-button bg-teal-100 text-teal-600 hover:bg-teal-200">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                                <form id="duplicate-form-{{ $objectif->id }}" action="{{ route('objectifs.duplicate', $objectif->id) }}" method="POST" style="display: none;">
                                                                    @csrf
                                                                </form>
                                                                @endcan
                                                    
                                                    @can('objectif-delete')
                                                    <button type="button" title="Supprimer" onclick="confirmDelete({{ $objectif->id }})" class="action-button bg-red-100 text-primary-red hover:bg-red-200">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <form id="delete-form-{{ $objectif->id }}" action="{{ route('objectifs.destroy', $objectif->id) }}" method="POST" style="display: none;">
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
                                <div class="mt-8 flex justify-center animate-fade-in delay-300 pagination-custom">
                                    {{ $objectifs->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // Custom Modal Logic
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
                        confirmBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-gradient'; // Changed to btn-gradient
                        confirmBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(confirmBtn);

                        const cancelBtn = document.createElement('button');
                        cancelBtn.textContent = 'Annuler';
                        cancelBtn.className = 'px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wider shadow-md btn-secondary-modern'; // Changed to btn-secondary-modern
                        cancelBtn.onclick = () => {
                            customModal.classList.remove('show');
                            resolveModalPromise(false);
                        };
                        modalButtons.appendChild(cancelBtn);
                    } else if (type === 'alert') {
                        modalIcon.innerHTML = '<i class="fas fa-info-circle text-gray-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-gradient'; // Changed to btn-gradient
                        okBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm(); // Use onConfirm for alert callbacks too
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    } else if (type === 'success') {
                        modalIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-gradient'; // Changed to btn-gradient
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
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-gradient'; // Changed to btn-gradient
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

                // Function to handle delete confirmation using the custom modal
                function confirmDelete(id) {
                    showCustomConfirm("Êtes-vous sûr de vouloir supprimer cet objectif ?", () => {
                        document.getElementById('delete-form-' + id).submit();
                    });
                }

                // Function to handle duplicate confirmation using the custom modal
                function confirmDuplicate(id) {
                    showCustomConfirm("Voulez-vous vraiment dupliquer cet objectif ? Une nouvelle copie sera créée avec la progression à 0% et une nouvelle date.", () => {
                        document.getElementById('duplicate-form-' + id).submit();
                    });
                }
            </script>
        @endpush
    </body>
</x-app-layout>