<x-app-layout>
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
                            <h3 class="text-xl font-bold text-gray-900 flex items-center mb-2">
                                <i class="fas fa-clipboard-list mr-3 text-blue-600"></i> {{ $tache->description }}
                            </h3>
                           
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

                            <div>
                                <p class="text-sm font-semibold text-gray-700 flex items-center mb-1">
                                    <i class="fas fa-user-tag mr-2 text-teal-500"></i> {{ __('Assigné à:') }}
                                </p>
                                <p class="text-gray-800 ml-5">{{ $tache->user->name ?? 'N/A' }}</p>
                            </div>
                            
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
                            <a href="{{ route('taches.edit', $tache->id) }}"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-wider
                                hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition ease-in-out duration-150">
                                <i class="fas fa-edit mr-2"></i> {{ __('Modifier la Tâche') }}
                            </a>
                        @endcan
                        <a href="{{ route('taches.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-md font-bold text-xs text-gray-700 uppercase tracking-wider
                            hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour à la Liste') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>