{{-- resources/views/formations/show.blade.php (Complete and Responsive Code) --}}
<x-app-layout>
    <style>
        /* Custom Styles for the D32F2F color and animations (copied from show/users) */
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

        /* Badge pulse animation */
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
    </style>

    <div class="container mx-auto px-4 py-8">
        {{-- Header Section: Title and Buttons --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 space-y-4 sm:space-y-0">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 text-center sm:text-left">
                <i class="fas fa-graduation-cap mr-3 color-primary"></i> Détails de la Formation: <span class="color-primary">{{ $formation->name }}</span>
            </h2>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                @can('formation-edit')
                <a href="{{ route('formations.edit', $formation->id) }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-primary-custom border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover-bg-primary-darker focus:outline-none focus:border-primary-custom focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105 w-full sm:w-auto">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                @endcan
                <a href="{{ route('formations.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105 w-full sm:w-auto">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            {{-- Formation General Information Card --}}
            <div class="md:col-span-2 bg-white rounded-lg shadow-lg p-6 card-hover-scale">
                <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                    <i class="fas fa-info-circle mr-3 color-primary icon-bounce"></i> Informations Générales
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-gray-700">
                    <p class="break-words"><strong><i class="fas fa-tag mr-2 text-gray-500"></i> Nom:</strong> {{ $formation->name }}</p>
                    <p class="break-words">
                        <strong><i class="fas fa-map-marker-alt mr-2 text-gray-500"></i> Type:</strong>
                        @if($formation->status == 'en ligne')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 whitespace-nowrap"><i class="fas fa-globe mr-1"></i> En ligne</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 whitespace-nowrap"><i class="fas fa-map-marker-alt mr-1"></i> Lieu</span>
                        @endif
                    </p>
                    <p class="break-words"><strong><i class="fas fa-chalkboard-teacher mr-2 text-gray-500"></i> Formateur:</strong> {{ $formation->nomformateur }}</p>
                    <p class="break-words"><strong><i class="fas fa-calendar-alt mr-2 text-gray-500"></i> Date:</strong> {{ \Carbon\Carbon::parse($formation->date)->format('d/m/Y') }}</p>
                    <p class="break-words">
                        <strong><i class="fas fa-hourglass-half mr-2 text-gray-500"></i> Statut:</strong>
                        @if($formation->statut == 'nouveu')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-custom text-white badge-pulse whitespace-nowrap">Nouveau</span>
                        @elseif($formation->statut == 'encour')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 whitespace-nowrap">En cours</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 whitespace-nowrap">Terminée</span>
                        @endif
                    </p>
                    <p class="break-words"><strong><i class="fas fa-clock mr-2 text-gray-500"></i> Heures:</strong> {{ $formation->nombre_heures }}</p>
                    <p class="break-words"><strong><i class="fas fa-calendar-check mr-2 text-gray-500"></i> Séances:</strong> {{ $formation->nombre_seances }}</p>
                    <p class="break-words"><strong><i class="fas fa-dollar-sign mr-2 text-gray-500"></i> Prix:</strong> {{ number_format($formation->prix, 2) }} DH</p>
                    <p class="break-words"><strong><i class="fas fa-calendar-days mr-2 text-gray-500"></i> Durée:</strong>  {{ $formation->duree }} {{ $formation->duree_unit }}</p>
                    <p class="break-words"><strong><i class="fas fa-user-tie mr-2 text-gray-500"></i> Créé par:</strong> {{ $formation->createdBy->name ?? 'N/A' }}</p>
                </div>
            </div>

            {{-- Formation Description Card --}}
            <div class="md:col-span-1 bg-white rounded-lg shadow-lg p-6 card-hover-scale">
                <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                    <i class="fas fa-file-alt mr-3 color-primary icon-bounce"></i> Description
                </h3>
                <p class="text-gray-800 whitespace-pre-wrap">{{ $formation->description ?? 'Aucune description disponible.' }}</p>
            </div>
        </div>

        {{-- Horizontal Rule for mobile separation --}}
        <hr class="my-8 border-gray-200 block md:hidden">

        {{-- Attached File Card --}}
        @if ($formation->file_path)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 card-hover-scale">
            <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                <i class="fas fa-paperclip mr-3 color-primary icon-bounce"></i> Fichier Annexe
            </h3>
            <div class="flex flex-col sm:flex-row items-start sm:items-center p-3 bg-gray-50 rounded-lg border border-gray-200 shadow-sm space-y-2 sm:space-y-0 sm:space-x-3">
                <div class="flex-shrink-0 text-xl">
                    @php
                        $fileExtension = strtolower(pathinfo($formation->file_path, PATHINFO_EXTENSION));
                    @endphp
                    @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                        <i class="fas fa-image text-blue-500"></i>
                    @elseif(in_array($fileExtension, ['pdf']))
                        <i class="fas fa-file-pdf text-red-500"></i>
                    @elseif(in_array($fileExtension, ['doc', 'docx']))
                        <i class="fas fa-file-word text-blue-600"></i>
                    @elseif(in_array($fileExtension, ['xls', 'xlsx']))
                        <i class="fas fa-file-excel text-green-600"></i>
                    @else
                        <i class="fas fa-file text-gray-500"></i>
                    @endif
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ basename($formation->file_path) }}</p>
                    {{-- File size would need to be passed from controller or calculated here if possible --}}
                    {{-- <p class="text-xs text-gray-500">XX KB</p> --}}
                </div>
                <div class="ml-0 sm:ml-4 mt-2 sm:mt-0 w-full sm:w-auto"> {{-- Adjusted margin and width for responsiveness --}}
                    @can('formation-list') {{-- Adjust permission as needed for download --}}
                        <a href="{{ route('formations.download', $formation->id) }}"
                           class="inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto">
                            <i class="fas fa-download mr-1"></i> Télécharger
                        </a>
                    @else
                        <span class="text-gray-500 text-xs text-center sm:text-left block w-full">Fichier disponible (accès restreint)</span>
                    @endcan
                </div>
            </div>
        </div>
        @endif

        {{-- Horizontal Rule for mobile separation --}}
        <hr class="my-8 border-gray-200 block md:hidden">

        {{-- Assigned Participants Card --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 card-hover-scale">
            <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                <i class="fas fa-users mr-3 color-primary icon-bounce"></i> Participants Assignés
            </h3>
            @if ($formation->users->isNotEmpty())
                <ul class="divide-y divide-gray-200">
                    @foreach ($formation->users as $user)
                        <li class="py-3 flex items-center flex-wrap"> {{-- Added flex-wrap for better wrapping on small screens --}}
                            <i class="fas fa-user-circle mr-3 text-primary-custom text-lg flex-shrink-0"></i> {{-- Ensure icon doesn't shrink --}}
                            <p class="text-gray-800 flex-grow min-w-0 break-words">{{ $user->name }} (<span class="text-gray-600">{{ $user->email }}</span>)</p> {{-- Added flex-grow, min-w-0, break-words --}}
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded-md" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Aucun participant assigné à cette formation.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>