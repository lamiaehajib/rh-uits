<x-app-layout>
    
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du Pointage') }}
            </h2>
            <a href="{{ route('pointage.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>
   

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-bold">
                                        {{ substr($pointage->user->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $pointage->user->name }}</h3>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ $pointage->created_at ? $pointage->created_at->format('l d F Y') : 'Date non définie' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($pointage->heure_depart)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Pointage Terminé
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    En cours
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>
                                Informations Temporelles
                            </h4>

                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-play-circle text-green-600 text-2xl"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-green-800">Heure d'Arrivée</p>
                                            <p class="text-lg font-bold text-green-900">
                                                {{ $pointage->heure_arrivee ? \Carbon\Carbon::parse($pointage->heure_arrivee)->format('H:i:s') : 'Non définie' }}
                                            </p>
                                            @if($pointage->heure_arrivee)
                                                <p class="text-xs text-green-600">
                                                    {{ \Carbon\Carbon::parse($pointage->heure_arrivee)->format('d/m/Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-stop-circle text-red-600 text-2xl"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-red-800">Heure de Départ</p>
                                            <p class="text-lg font-bold text-red-900">
                                                {{ $pointage->heure_depart ? \Carbon\Carbon::parse($pointage->heure_depart)->format('H:i:s') : 'Non définie' }}
                                            </p>
                                            @if($pointage->heure_depart)
                                                <p class="text-xs text-red-600">
                                                    {{ \Carbon\Carbon::parse($pointage->heure_depart)->format('d/m/Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($pointage->heure_arrivee && $pointage->heure_depart)
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-hourglass-half text-blue-600 text-2xl"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-blue-800">Durée Totale</p>
                                                <p class="text-lg font-bold text-blue-900">
                                                    @php
                                                        $arrivee = \Carbon\Carbon::parse($pointage->heure_arrivee);
                                                        $depart = \Carbon\Carbon::parse($pointage->heure_depart);
                                                        $duration = $arrivee->diff($depart);
                                                        echo $duration->format('%Hh %Im %Ss');
                                                    @endphp
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                                <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                                Détails Supplémentaires
                            </h4>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-sm font-medium text-gray-800 flex items-center">
                                    <i class="fas fa-align-left mr-2 text-gray-600"></i>Description:
                                </p>
                                <p class="text-md text-gray-900 mt-1">
                                    {{ $pointage->description ?? 'Aucune description fournie' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-sm font-medium text-gray-800 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-600"></i>Localisation:
                                </p>
                                <p class="text-md text-gray-900 mt-1">
                                    {{ $pointage->localisation ?? 'Non spécifiée' }}
                                </p>
                            </div>

                            @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Admin1'))
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mt-8">
                                <i class="fas fa-edit mr-2 text-orange-500"></i>
                                Actions Administrateur
                            </h4>
                            <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                                <p class="text-sm font-medium text-orange-800 mb-3">Corriger le pointage :</p>
                                <form action="{{ route('suivre-pointages.corriger', $pointage->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label for="heure_arrivee" class="block text-sm font-medium text-gray-700">Heure d'Arrivée</label>
                                        <input type="datetime-local" id="heure_arrivee" name="heure_arrivee"
                                               value="{{ \Carbon\Carbon::parse($pointage->heure_arrivee)->format('Y-m-d\TH:i') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('heure_arrivee')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="heure_depart" class="block text-sm font-medium text-gray-700">Heure de Départ (Optionnel)</label>
                                        <input type="datetime-local" id="heure_depart" name="heure_depart"
                                               value="{{ $pointage->heure_depart ? \Carbon\Carbon::parse($pointage->heure_depart)->format('Y-m-d\TH:i') : '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('heure_depart')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="description_correction" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea id="description_correction" name="description" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ $pointage->description }}</textarea>
                                        @error('description')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="localisation_correction" class="block text-sm font-medium text-gray-700">Localisation</label>
                                        <input type="text" id="localisation_correction" name="localisation"
                                               value="{{ $pointage->localisation }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('localisation')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 active:bg-orange-900 focus:outline-none focus:border-orange-900 focus:ring ring-orange-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <i class="fas fa-check mr-2"></i>Appliquer la correction
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>