<x-app-layout>
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gradient-to-br from-pink-50 via-red-50 to-rose-50">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 backdrop-blur-sm">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-pink-600 to-red-600 text-white">
                <h1 class="text-3xl font-bold mb-2 flex items-center">
                    <i class="fas fa-history mr-3 text-pink-200"></i>
                    Historique des interventions
                </h1>
            </div>

            <div class="p-6 md:p-8">
                @if ($rendezVous->isEmpty())
                    <div class="text-center py-16 bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-100">
                        <div class="bg-gradient-to-br from-gray-300 to-gray-400 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-calendar-times text-white text-4xl"></i>
                        </div>
                        <h5 class="text-gray-600 font-bold text-2xl mb-3">
                            Aucune intervention trouvée pour le moment.
                        </h5>
                        <p class="text-gray-500 text-lg">
                            Profitez de cette pause bien méritée !
                        </p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($rendezVous as $rdv)
                            <a href="{{ route('client.client.rendez-vous.show', $rdv) }}" class="block p-6 rounded-2xl shadow-lg border border-gray-100 bg-white transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl no-underline">
                                <div class="flex justify-between items-start mb-3">
                                    <h5 class="text-xl font-bold text-gray-800 flex items-center">
                                        <i class="fas fa-clipboard-list text-pink-500 mr-2"></i>
                                        {{ $rdv->titre }}
                                    </h5>
                                    <small class="text-gray-500 font-medium flex items-center">
                                        <i class="fas fa-clock mr-1 text-red-500"></i>
                                        {{ $rdv->date_heure->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <div class="text-sm text-gray-600 space-y-2">
                                    <p class="flex items-center">
                                        <i class="fas fa-project-diagram text-pink-500 mr-2"></i>
                                        Projet : <span class="font-medium ml-1 text-gray-700">{{ $rdv->projet->titre }}</span>
                                    </p>
                                    @if ($rdv->description)
                                        <p class="flex items-center text-truncate">
                                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                         Description : <span class="ml-1 text-gray-700">{!! nl2br(e($rdv->description)) !!}</span>
                                        </p>
                                    @endif
                                    @if ($rdv->lieu)
                                        <p class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                            Lieu : <span class="ml-1 text-gray-700">{{ $rdv->lieu }}</span>
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <span class="text-sm font-bold px-4 py-2 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-110
                                        @switch($rdv->statut)
                                            @case('programmé') bg-gradient-to-r from-yellow-400 to-orange-500 text-white @break
                                            @case('confirmé') bg-gradient-to-r from-blue-500 to-indigo-600 text-white @break
                                            @case('terminé') bg-gradient-to-r from-green-500 to-emerald-600 text-white @break
                                            @case('annulé') bg-gradient-to-r from-red-500 to-rose-600 text-white @break
                                            @default bg-gradient-to-r from-gray-400 to-gray-600 text-white @break
                                        @endswitch">
                                        {{ ucfirst($rdv->statut) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $rendezVous->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>