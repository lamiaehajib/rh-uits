<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Détails de la Tâche</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            // Configure Tailwind CSS to use a custom primary color
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
            /* Custom CSS for animations and improved aesthetics */
            body {
                font-family: 'Inter', sans-serif;
            }

            /* Fade-in animation for elements */
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

            /* Pulse animation for important alerts/errors */
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

            /* Button gradients and shadows */
            .btn-primary-red {
                background: linear-gradient(to right, #D32F2F, #B71C1C); /* Deeper red gradient */
                box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3);
                transition: all 0.3s ease;
            }

            .btn-primary-red:hover {
                background: linear-gradient(to right, #B71C1C, #D32F2F);
                box-shadow: 0 6px 15px rgba(211, 47, 47, 0.4);
                transform: translateY(-2px);
            }

            .btn-secondary {
                background-color: #e5e7eb; /* gray-200 */
                color: #4b5563; /* gray-700 */
                transition: all 0.3s ease;
            }

            .btn-secondary:hover {
                background-color: #d1d5db; /* gray-300 */
                transform: translateY(-2px);
            }

            /* Input/Select focus animation */
            input:focus,
            textarea:focus,
            select:focus {
                transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            }
        </style>
    </head>
    <body>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="fas fa-eye text-teal-600 mr-3"></i>
                {{ __('Détails de la Tâche') }}
                <span class="ml-3 px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm font-medium">#{{ $tache->id }}</span>
            </h2>
        </x-slot>

        <div class="py-12 bg-gray-50">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-2xl sm:rounded-xl">
                    <div class="p-8 bg-white border-b border-gray-200">

                        <div class="space-y-6">
                            <div class="pb-4 border-b border-gray-200">
                                <h3 class="text-2xl font-bold text-gray-900 flex items-center mb-2">
                                    <i class="fas fa-heading mr-3 text-indigo-600"></i> {{ $tache->titre }}
                                </h3>
                                <p class="text-lg text-gray-800 flex items-center">
                                    <i class="fas fa-file-alt mr-3 text-blue-600"></i> {{ $tache->description }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                        <i class="fas fa-info-circle mr-2 text-yellow-500"></i> {{ __('Statut:') }}
                                    </p>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        @if($tache->status == 'nouveau') bg-yellow-200 text-yellow-800
                                        @elseif($tache->status == 'en cours') bg-purple-200 text-purple-800
                                        @elseif($tache->status == 'termine') bg-green-200 text-green-800
                                        @endif">
                                        {{ ucfirst($tache->status) }}
                                    </span>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                        <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i> {{ __('Priorité:') }}
                                    </p>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        @if($tache->priorite == 'faible') bg-blue-200 text-blue-800
                                        @elseif($tache->priorite == 'moyen') bg-orange-200 text-orange-800
                                        @elseif($tache->priorite == 'élevé') bg-red-200 text-red-800
                                        @endif">
                                        {{ ucfirst($tache->priorite) }}
                                    </span>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                        <i class="fas fa-hourglass-half mr-2 text-green-500"></i> {{ __('Durée Estimée:') }}
                                    </p>
                                    <p class="text-gray-800 ml-5">{{ $tache->duree }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i> {{ __('Date de Début:') }}
                                    </p>
                                    <p class="text-gray-800 ml-5">{{ \Carbon\Carbon::parse($tache->datedebut)->format('d F Y') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                        <i class="fas fa-clock mr-2 text-orange-500"></i> {{ __('Type de Planification:') }}
                                    </p>
                                    <p class="text-gray-800 ml-5">{{ ucfirst($tache->date) }}</p>
                                </div>

                                <p class="sm:col-span-2">
                                    <strong><i class="fas fa-users mr-2 text-gray-500"></i> Assigné(s) à:</strong>
                                    <span class="inline-flex flex-wrap items-center space-x-2 mt-1">
                                        @forelse ($tache->users as $assignedUser)
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 flex items-center">
                                                <i class="fas fa-user mr-1"></i> {{ $assignedUser->name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-500">Non assigné</span>
                                        @endforelse
                                    </span>
                                </p>
                                
                                @if ($tache->retour)
                                    <div class="md:col-span-2">
                                        <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                            <i class="fas fa-comment-dots mr-2 text-gray-500"></i> {{ __('Retour/Notes:') }}
                                        </p>
                                        <p class="text-gray-800 ml-5 bg-gray-100 p-3 rounded-lg shadow-inner">{{ $tache->retour }}</p>
                                    </div>
                                @endif

                                @if ($tache->creator)
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                            <i class="fas fa-user-plus mr-2 text-gray-500"></i> {{ __('Créé par:') }}
                                        </p>
                                        <p class="text-gray-800 ml-5">{{ $tache->creator->name }}</p>
                                    </div>
                                @endif

                                @if ($tache->updated_by && $tache->updater)
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                            <i class="fas fa-user-edit mr-2 text-gray-500"></i> {{ __('Dernière mise à jour par:') }}
                                        </p>
                                        <p class="text-gray-800 ml-5">{{ $tache->updater->name }}</p>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                        <i class="fas fa-calendar-plus mr-2 text-gray-500"></i> {{ __('Créée le:') }}
                                    </p>
                                    <p class="text-gray-800 ml-5">{{ $tache->created_at->format('d F Y H:i') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                        <i class="fas fa-calendar-check mr-2 text-gray-500"></i> {{ __('Mise à jour le:') }}
                                    </p>
                                    <p class="text-gray-800 ml-5">{{ $tache->updated_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex items-center justify-end space-x-4 border-t pt-6 border-gray-200">
                            @can('tache-edit')
                                {{-- The edit link should also pass the filterParams --}}
                                <a href="{{ route('taches.edit', array_merge(['tach' => $tache->id], $filterParams)) }}"
                                    class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-wider
                                    hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition ease-in-out duration-150">
                                    <i class="fas fa-edit mr-2"></i> {{ __('Modifier la Tâche') }}
                                </a>
                            @endcan
                            {{-- This "Retour à la Liste" link will now pass the original filter parameters --}}
                            <a href="{{ route('taches.index', $filterParams) }}"
                                class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-md font-bold text-xs text-gray-700 uppercase tracking-wider
                                hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition ease-in-out duration-150">
                                <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour à la Liste') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>