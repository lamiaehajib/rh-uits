<x-app-layout>
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gradient-to-br from-pink-50 via-red-50 to-rose-50">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 backdrop-blur-sm">
             <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-pink-600 to-red-600 text-white">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div>
                        <h3 class="text-3xl font-bold mb-2 flex items-center">
                            <i class="fas fa-calendar-week mr-3 text-pink-200"></i>
                            Mon Planning de la semaine
                        </h3>
                        @if ($rendezVous->count() > 0)
                            @php
                                $startOfWeek = \Carbon\Carbon::parse($rendezVous->first()->date_heure)->startOfWeek(\Carbon\Carbon::MONDAY);
                                $endOfWeek = \Carbon\Carbon::parse($rendezVous->last()->date_heure)->endOfWeek(\Carbon\Carbon::SUNDAY);
                            @endphp
                            <small class="text-pink-100 flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Du {{ $startOfWeek->format('d/m/Y') }} au {{ $endOfWeek->format('d/m/Y') }}
                            </small>
                        @else
                            <small class="text-pink-100 flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Du {{ \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('d/m/Y') }} au {{ \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY)->format('d/m/Y') }}
                            </small>
                        @endif
                    </div>
                    <a href="{{ route('client.client.planning.historique') }}" class="mt-4 sm:mt-0 ml-auto px-6 py-3 rounded-full bg-white text-pink-600 font-bold hover:bg-pink-100 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center">
                        <i class="fas fa-history mr-2"></i>
                        Historique complet
                    </a>
                </div>
             </div>

            <div class="p-6 md:p-8">
                {{-- Navigation pour les semaines --}}
                <div class="flex justify-between items-center mb-8 text-center bg-white rounded-2xl p-4 shadow-lg border border-gray-100">
                   
                    <span class="text-xl font-bold text-gray-700 bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent">
                        <i class="fas fa-calendar-day mr-2 text-pink-500"></i>
                        Semaine {{ \Carbon\Carbon::now()->weekOfYear }} - {{ \Carbon\Carbon::now()->year }}
                    </span>
                    
                </div>

                @if ($rendezVous->count() > 0)
                    {{-- Vue en grille par jour --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @php
                            $days = [
                                'lundi' => \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY),
                                'mardi' => \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDay(),
                                'mercredi' => \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(2),
                                'jeudi' => \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(3),
                                'vendredi' => \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(4),
                                'samedi' => \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(5),
                                'dimanche' => \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(6),
                            ];
                        @endphp

                        @foreach($days as $dayName => $date)
                            @php
                                $dayRdv = $rendezVous->filter(function($rdv) use ($date) {
                                    return \Carbon\Carbon::parse($rdv->date_heure)->isSameDay($date);
                                })->sortBy('date_heure');
                                
                                $isToday = $date->isToday();
                                $isWeekend = $date->isWeekend();
                            @endphp

                            <div class="flex flex-col h-full rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-2xl
                                         @if($isToday) border-3 border-pink-500 bg-gradient-to-br from-white to-pink-50 @elseif($isWeekend) bg-gradient-to-br from-gray-50 to-gray-100 @else bg-white @endif">
                                <div class="p-5 text-center
                                             @if($isToday) bg-gradient-to-r from-pink-600 to-red-600 text-white @elseif($isWeekend) bg-gradient-to-r from-gray-400 to-gray-500 text-white @else bg-gradient-to-r from-pink-500 to-red-500 text-white @endif">
                                    <h6 class="font-bold text-xl mb-2 flex items-center justify-center">
                                        <i class="fas fa-calendar-day mr-2"></i>
                                        {{ ucfirst($dayName) }}
                                        @if($isToday)
                                            <span class="ml-2 text-xs px-3 py-1 rounded-full bg-white text-pink-600 font-bold animate-pulse">
                                                <i class="fas fa-star mr-1"></i>Aujourd'hui
                                            </span>
                                        @endif
                                    </h6>
                                    <small class="text-white/90 font-medium">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $date->format('d/m/Y') }}
                                    </small>
                                </div>

                                <div class="p-5 flex-grow">
                                    @if($dayRdv->count() > 0)
                                        <div class="space-y-4">
                                           @foreach($dayRdv as $rdv)
                                            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-100 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                                <div class="flex justify-between items-start mb-3">
                                                    <h6 class="font-bold text-gray-800 flex items-center">
                                                        <i class="fas fa-clock text-pink-500 mr-2"></i>
                                                        <span class="text-pink-600">{{ \Carbon\Carbon::parse($rdv->date_heure)->format('H:i') }}</span>
                                                        <span class="mx-2">-</span>
                                                        <span>{{ $rdv->titre }}</span>
                                                    </h6>
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-bold px-4 py-2 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-110 
                                                                     @switch($rdv->statut)
                                                                         @case('programmé') bg-gradient-to-r from-yellow-400 to-orange-500 text-white border-2 border-yellow-300 @break
                                                                         @case('confirmé') bg-gradient-to-r from-blue-500 to-indigo-600 text-white border-2 border-blue-400 @break
                                                                         @case('terminé') bg-gradient-to-r from-green-500 to-emerald-600 text-white border-2 border-green-400 @break
                                                                         @case('annulé') bg-gradient-to-r from-red-500 to-rose-600 text-white border-2 border-red-400 @break
                                                                         @default bg-gradient-to-r from-gray-400 to-gray-600 text-white border-2 border-gray-400 @break
                                                                     @endswitch">
                                                             @switch($rdv->statut)
                                                                 @case('programmé') <i class="fas fa-clock mr-2 text-lg animate-pulse"></i> @break
                                                                 @case('confirmé') <i class="fas fa-check-circle mr-2 text-lg"></i> @break
                                                                 @case('terminé') <i class="fas fa-check-double mr-2 text-lg"></i> @break
                                                                 @case('annulé') <i class="fas fa-times-circle mr-2 text-lg"></i> @break
                                                                 @default <i class="fas fa-info-circle mr-2 text-lg"></i> @break
                                                             @endswitch
                                                             {{ ucfirst($rdv->statut) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="text-sm text-gray-600 space-y-1">
                                                    <p class="flex items-center">
                                                        <i class="fas fa-project-diagram text-pink-500 mr-2"></i> 
                                                        <span class="font-medium">{{ $rdv->projet->titre }}</span>
                                                    </p>
                                                    @if($rdv->lieu)
                                                        <p class="flex items-center">
                                                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i> 
                                                            <span>{{ $rdv->lieu }}</span>
                                                        </p>
                                                    @endif
                                                </div>

                                                {{-- Boutons d'action --}}
                                                <div class="mt-4 flex flex-col space-y-2">
                                                    @if ($rdv->statut !== 'annulé' && $rdv->statut !== 'terminé' && !$rdv->date_heure->isPast())
                                                        <form action="{{ route('client.client.rendez-vous.cancel', $rdv) }}" method="POST" >
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="w-full text-center text-sm py-2 px-4 rounded-lg bg-gradient-to-r from-red-500 to-red-600 text-white hover:from-red-600 hover:to-red-700 transition-all duration-300 transform hover:scale-105 font-medium shadow-md hover:shadow-lg" onclick="return confirm('Êtes-vous sûr de vouloir reporter cette maintenance sur site ?')">
                                                                <i class="fas fa-times-circle mr-2"></i> reporter la maintenance
                                                            </button>
                                                        </form>
                                                    @endif

                                                    
                                                      {{-- Bouton pour confirmer la maintenance (Nouveau !) --}}
    @if ($rdv->statut === 'programmé' && !$rdv->date_heure->isPast())
        <form action="{{ route('client.client.rendez-vous.confirm', $rdv) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="w-full text-center text-sm py-2 px-4 rounded-lg bg-gradient-to-r from-green-500 to-green-600 text-white hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 font-medium shadow-md hover:shadow-lg" onclick="return confirm('Êtes-vous sûr de vouloir confirmer cette maintenance sur site ?')">
                <i class="fas fa-check-circle mr-2"></i> Confirmer la maintenance
            </button>
        </form>
    @endif

    

                                                    {{-- Bouton de re-programmation (ajouté ici) --}}
                                                    @if ($rdv->statut === 'annulé')
                                                        <a href="{{ route('client.client.rendez-vous.reprogrammer', $rdv) }}" class="w-full text-center text-sm py-2 px-4 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">
                                                            <i class="fas fa-redo-alt mr-2"></i> Re-programmer la maintenance
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                           @endforeach
                                        </div>
                                    @else
                                        <div class="text-center text-gray-400 py-8">
                                            <i class="fas fa-calendar-times text-5xl mb-4 text-gray-300"></i>
                                            <p class="text-base font-medium">Aucune maintenance sur site</p>
                                            <p class="text-sm mt-1">Journée libre</p>
                                        </div>
                                    @endif
                                </div>

                                @if($dayRdv->count() > 0)
                                    <div class="p-3 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
                                        <small class="text-sm text-gray-600 font-medium flex items-center justify-center">
                                            <i class="fas fa-list-ul mr-2 text-pink-500"></i>
                                            {{ $dayRdv->count() }} maintenance(s) sur site
                                            @if($dayRdv->where('statut', 'confirmé')->count() > 0)
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                                {{ $dayRdv->where('statut', 'confirmé')->count() }} confirmé(s)
                                            @endif
                                        </small>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Résumé de la semaine --}}
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl mt-10 p-8 border border-gray-100">
                        <h6 class="text-2xl font-bold text-gray-700 mb-6 flex items-center justify-center">
                            <i class="fas fa-chart-bar mr-3 text-pink-500 text-3xl"></i> 
                            <span class="bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent">Résumé de la semaine</span>
                        </h6>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="text-center p-6 bg-white rounded-xl shadow-lg border border-blue-100 transform transition-all duration-300 hover:scale-105">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                                </div>
                                <h4 class="text-blue-600  font-bold mb-2">{{ $rendezVous->count() }}</h4>
                                <small class="text-gray-600 font-medium">Total</small>
                            </div>
                            <div class="text-center p-6 bg-white rounded-xl shadow-lg border border-green-100 transform transition-all duration-300 hover:scale-105">
                                <div class="bg-gradient-to-br from-green-500 to-green-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-check-circle text-white text-2xl"></i>
                                </div>
                                <h4 class="text-green-600  font-bold mb-2">{{ $rendezVous->where('statut', 'terminé')->count() }}</h4>
                                <small class="text-gray-600 font-medium">Terminés</small>
                            </div>
                            <div class="text-center p-6 bg-white rounded-xl shadow-lg border border-yellow-100 transform transition-all duration-300 hover:scale-105">
                                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clock text-white text-2xl"></i>
                                </div>
                                <h4 class="text-yellow-600  font-bold mb-2">{{ $rendezVous->where('statut', 'programmé')->count() }}</h4>
                                <small class="text-gray-600 font-medium">Programmés</small>
                            </div>
                            <div class="text-center p-6 bg-white rounded-xl shadow-lg border border-red-100 transform transition-all duration-300 hover:scale-105">
                                <div class="bg-gradient-to-br from-red-500 to-red-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-times-circle text-white text-2xl"></i>
                                </div>
                                <h4 class="text-red-600  font-bold mb-2">{{ $rendezVous->where('statut', 'annulé')->count() }}</h4>
                                <small class="text-gray-600 font-medium">reportes</small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-16 bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-100">
                        <div class="bg-gradient-to-br from-gray-300 to-gray-400 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-calendar-week text-white text-4xl"></i>
                        </div>
                        <h5 class="text-gray-600 font-bold text-2xl mb-3">Aucune maintenance sur site cette semaine</h5>
                        <p class="text-gray-500 text-lg">Votre planning est vide pour le moment.</p>
                        <div class="mt-6">
                            <i class="fas fa-coffee text-pink-400 text-3xl"></i>
                            <p class="text-pink-500 font-medium mt-2">Profitez de cette pause bien méritée !</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>