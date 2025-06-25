<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Détails de l\'Objectif') }}</title>
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
            /* Custom Styles for the D32F2F color and animations */
            body {
                font-family: 'Inter', sans-serif;
            }

            .color-primary {
                color: #D32F2F;
            }

            .bg-primary-custom {
                background-color: #D32F2F;
            }

            .hover-bg-primary-darker:hover {
                background-color: #B71C1C; /* A darker shade for hover effect */
            }

            .border-primary-custom {
                border-color: #D32F2F;
            }

            /* Subtle hover effect for cards */
            .card-hover-scale {
                transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            }

            .card-hover-scale:hover {
                transform: translateY(-5px) scale(1.01);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            /* Icon bounce animation on hover */
            .icon-bounce:hover {
                animation: bounce 0.6s ease-in-out;
            }

            @keyframes bounce {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-5px);
                }
            }

            /* Badge pulse animation (for overdue or explanation needed) */
            .badge-pulse {
                animation: pulse 1.5s infinite;
            }

            @keyframes pulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(211, 47, 47, 0.7);
                }
                70% {
                    box-shadow: 0 0 0 10px rgba(211, 47, 47, 0);
                }
                100% {
                    box-shadow: 0 0 0 0 rgba(211, 47, 47, 0);
                }
            }

            /* Animations from the original objectif blade (keeping them if desired) */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
            }

            @keyframes bounceIn {
                0% { opacity: 0; transform: scale(0.8); }
                60% { opacity: 1; transform: scale(1.05); }
                80% { transform: scale(0.98); }
                100% { transform: scale(1); }
            }
            .animate-bounce-in {
                animation: bounceIn 0.6s ease-out forwards;
            }

            /* New Animations for Timeline/Calendar */
            @keyframes slideInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-slide-in-up {
                animation: slideInUp 0.6s ease-out forwards;
            }

            @keyframes zoomIn {
                from { opacity: 0; transform: scale(0.95); }
                to { opacity: 1; transform: scale(1); }
            }
            .animate-zoom-in {
                animation: zoomIn 0.7s ease-out forwards;
                animation-delay: 0.2s; /* Delay to appear after main content */
                opacity: 0;
            }

            @keyframes popIn {
                0% { opacity: 0; transform: scale(0.5); }
                70% { opacity: 1; transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            .animate-pop-in-start {
                animation: popIn 0.5s ease-out forwards;
                animation-delay: 0.8s;
                opacity: 0;
            }
            .animate-pop-in-mid {
                animation: popIn 0.5s ease-out forwards;
                animation-delay: 1.1s;
                opacity: 0;
            }
            .animate-pop-in-end {
                animation: popIn 0.5s ease-out forwards;
                animation-delay: 1.4s;
                opacity: 0;
            }

            @keyframes pulseFade {
                0% { opacity: 0.5; transform: scale(0.8); }
                50% { opacity: 1; transform: scale(1); }
                100% { opacity: 0.5; transform: scale(0.8); }
            }
            .animate-pulse-fade {
                animation: pulseFade 2s infinite ease-in-out;
            }

            /* Button styles adapted from create/users and primary-red */
            .btn-custom-primary {
                @apply inline-flex items-center px-4 py-2 bg-primary-red border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-md hover:bg-[#B71C1C] focus:outline-none focus:border-primary-red focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105;
            }

            .btn-custom-secondary {
                @apply inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest shadow-sm hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105;
            }

            /* Form input/textarea styles */
            .form-detail-display {
                @apply block w-full px-3 py-2 text-base text-gray-800 bg-gray-100 border border-gray-200 rounded-md shadow-inner;
            }
        </style>
    </head>
    <body>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails de l\'Objectif') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg card-hover-scale animate-fade-in">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                            <h3 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">
                                <i class="fas fa-bullseye mr-3 color-primary icon-bounce"></i> Objectif: <span class="color-primary">{{ Str::limit($objectif->description, 50) }}</span>
                            </h3>
                            <div class="flex flex-wrap items-center space-x-3 mt-4 md:mt-0">
                                @role('Admin')
                                <a href="{{ route('objectifs.edit', $objectif->id) }}" class="btn-custom-primary">
                                    <i class="fas fa-edit mr-2"></i> Modifier
                                </a>
                                <button type="button" onclick="showCustomConfirm('{{ __('Êtes-vous sûr de vouloir supprimer cet objectif ?') }}', function() { document.getElementById('delete-form-{{ $objectif->id }}').submit(); });" class="btn-custom-primary !bg-red-600 hover:!bg-red-800">
                                    <i class="fas fa-trash mr-2"></i> Supprimer
                                </button>
                                <form id="delete-form-{{ $objectif->id }}" action="{{ route('objectifs.destroy', $objectif->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endrole
                                <a href="{{ route('objectifs.index') }}" class="btn-custom-secondary mt-3 md:mt-0">
                                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                                </a>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-200">

                        {{-- Objective Overview Section --}}
                        <div class="bg-gray-50 p-6 rounded-lg mb-8 shadow-inner animate-slide-in-up card-hover-scale">
                            <h4 class="text-2xl font-bold text-gray-700 mb-4 border-b pb-3 flex items-center">
                                <i class="fas fa-info-circle mr-3 color-primary icon-bounce"></i> Aperçu de l'Objectif
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-gray-700">
                                <p><strong><i class="fas fa-tag mr-2 text-gray-500"></i> Type:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($objectif->type == 'formations') bg-blue-100 text-blue-800
                                    @elseif($objectif->type == 'projets') bg-purple-100 text-purple-800
                                    @elseif($objectif->type == 'vente') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($objectif->type) }}
                                </span></p>
                                <p><strong><i class="fas fa-hourglass-half mr-2 text-gray-500"></i> Statut (Durée):</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($objectif->status) }}</span></p>
                                <p class="sm:col-span-2"><strong><i class="fas fa-user-circle mr-2 text-gray-500"></i> Assigné à:</strong> {{ $objectif->user->name ?? 'N/A' }}</p>

                                <div class="sm:col-span-2">
                                    <p class="font-bold text-gray-700 mb-2 flex items-center"><i class="fas fa-comment-dots mr-2 text-gray-500"></i> Description:</p>
                                    <p class="form-detail-display">{{ $objectif->description }}</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <p class="font-bold text-gray-700 mb-2 flex items-center"><i class="fas fa-clipboard-list mr-2 text-gray-500"></i> À Faire:</p>
                                    <p class="form-detail-display">{{ $objectif->afaire }}</p>
                                </div>
                                <p><strong><i class="fas fa-money-bill-wave mr-2 text-gray-500"></i> CA Cible:</strong> {{ $objectif->ca }} MAD</p>
                                <p><strong><i class="fas fa-calendar-alt mr-2 text-gray-500"></i> Date de Début:</strong> {{ \Carbon\Carbon::parse($objectif->date)->format('d M Y') }}</p>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-200">

                        {{-- Timeline/Calendar-like Representation --}}
                        <div class="bg-white p-6 rounded-lg shadow-lg mb-8 border border-gray-200 animate-zoom-in card-hover-scale">
                            <h4 class="text-2xl font-bold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-chart-line mr-3 color-primary icon-bounce"></i> Progression de l'Objectif
                            </h4>

                            @php
                                $startDate = Carbon\Carbon::parse($objectif->date);
                                $currentDate = Carbon\Carbon::now();
                                $endDate = null;
                                $midPointDate = null;

                                if ($objectif->status === 'mois') {
                                    $endDate = $startDate->copy()->endOfMonth();
                                    $midPointDate = $startDate->copy()->addDays(floor($startDate->daysInMonth / 2));
                                } elseif ($objectif->status === 'annee') {
                                    $endDate = $startDate->copy()->addYear()->subDay(); // Sub day to make it end of the year *before* the next start date
                                    $midPointDate = $startDate->copy()->addMonths(6);
                                }

                                // Handle cases where endDate might be before startDate for some reason
                                if ($endDate && $startDate->greaterThan($endDate)) {
                                    $totalDurationDays = 1; // Prevent division by zero or negative duration
                                    $progressPercentageVisual = 0;
                                    $todayPosition = 0;
                                    $midpointPosition = 0;
                                } else {
                                    $totalDurationDays = $startDate->diffInDays($endDate) + 1; // Add 1 to include both start and end day
                                    $elapsedDays = $startDate->diffInDays($currentDate);
                                    $progressPercentageVisual = min(100, max(0, ($elapsedDays / $totalDurationDays) * 100));
                                    $todayPosition = min(100, max(0, ($startDate->diffInDays(Carbon\Carbon::now()) / $totalDurationDays) * 100));
                                    $midpointPosition = ($midPointDate && $totalDurationDays > 0) ? ($startDate->diffInDays($midPointDate) / $totalDurationDays) * 100 : 50; // Default to 50% if no midpoint
                                    $midpointPosition = min(100, max(0, $midpointPosition));
                                }
                            @endphp

                            <div class="relative w-full bg-gray-200 rounded-full h-8 flex items-center mb-6 overflow-hidden">
                                <div class="absolute inset-y-0 left-0 rounded-full {{ $objectif->calculated_progress == 100 ? 'bg-green-500' : 'bg-primary-red' }} transition-all duration-1000 ease-in-out" style="width: {{ $progressPercentageVisual }}%;"></div>

                                {{-- Current Progress Display --}}
                                <div class="absolute left-1/2 -translate-x-1/2 text-white font-bold text-sm z-10 transition-all duration-700"
                                    style="color: {{ $progressPercentageVisual > 50 ? 'white' : '#333' }};"
                                >
                                    {{ $objectif->calculated_progress }}%
                                </div>

                                {{-- Markers for Start, Midpoint, End --}}
                                <div class="absolute left-0 top-0 bottom-0 flex items-center z-20">
                                    <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md animate-pop-in-start">
                                        Début: {{ $startDate->format('d M Y') }}
                                    </span>
                                </div>

                                @if ($midPointDate)
                                    <div class="absolute top-0 bottom-0 flex items-center z-20" style="left: {{ $midpointPosition }}%;">
                                        <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md transform -translate-x-1/2 animate-pop-in-mid">
                                            50%: {{ $midPointDate->format('d M Y') }}
                                        </span>
                                    </div>
                                @endif

                                @if ($endDate)
                                    <div class="absolute right-0 top-0 bottom-0 flex items-center z-20">
                                        <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md animate-pop-in-end">
                                            Fin: {{ $endDate->format('d M Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Current Date Indicator --}}
                            <div class="relative w-full h-2 bg-gray-300 rounded-full mb-4">
                                <div class="absolute w-4 h-4 bg-blue-500 rounded-full -top-1 transform -translate-x-1/2 animate-pulse-fade" style="left: {{ $todayPosition }}%;"></div>
                                <span class="absolute text-blue-700 text-xs mt-4 transform -translate-x-1/2" style="left: {{ $todayPosition }}%;">Aujourd'hui</span>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-200">

                        {{-- Explanation Section --}}
                        @if ($objectif->needs_explanation && $objectif->calculated_progress < 100)
                            <div id="explanation" class="mt-8 p-6 bg-red-50 border border-red-200 rounded-lg animate-bounce-in card-hover-scale">
                                <h4 class="text-2xl font-bold text-red-700 mb-4 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-3 text-red-600 icon-bounce"></i> Explication Requise
                                </h4>
                                <p class="text-red-600 mb-4">Cet objectif n'a pas atteint 100% à la date prévue. Veuillez fournir une explication.</p>
                                <form action="#" method="POST"> {{-- Replace # with your actual route to update explanation --}}
                                    @csrf
                                    <div class="mb-4">
                                        <label for="explanation_for_incomplete" class="block text-red-700 text-sm font-bold mb-2">Raison de l'objectif non atteint:</label>
                                        <textarea name="explanation_for_incomplete" id="explanation_for_incomplete" rows="4" class="form-detail-display border-red-300 focus:border-red-500 focus:ring focus:ring-red-200 @error('explanation_for_incomplete') border-red-500 @enderror">{{ old('explanation_for_incomplete', $objectif->explanation_for_incomplete ?? '') }}</textarea>
                                        @error('explanation_for_incomplete')
                                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn-custom-primary !bg-red-600 hover:!bg-red-800">
                                        <i class="fas fa-paper-plane mr-2"></i> Soumettre l'Explication
                                    </button>
                                </form>
                            </div>
                        @elseif ($objectif->explanation_for_incomplete)
                            <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg animate-fade-in card-hover-scale">
                                <h4 class="text-2xl font-bold text-blue-700 mb-4 flex items-center">
                                    <i class="fas fa-info-circle mr-3 text-blue-600 icon-bounce"></i> Explication Fournie
                                </h4>
                                <p class="text-blue-600 form-detail-display">{{ $objectif->explanation_for_incomplete }}</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>