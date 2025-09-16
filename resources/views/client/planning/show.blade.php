<x-app-layout>
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gradient-to-br from-pink-50 via-red-50 to-rose-50">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 backdrop-blur-sm">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-pink-600 to-red-600 text-white flex justify-between items-center">
                <h4 class="text-3xl font-bold mb-0 flex items-center">
                    <i class="fas fa-clipboard-list mr-3 text-pink-200"></i>
                    Détails de l'intervention
                </h4>
                <a href="{{ url()->previous() }}" class="px-6 py-3 rounded-full bg-white text-pink-600 font-bold hover:bg-pink-100 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <h5 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-red-500 mr-2"></i>
                            Informations Générales
                        </h5>
                        <ul class="space-y-4 text-gray-700">
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-tag mr-2"></i> Titre :</strong> {{ $rendezVous->titre }}
                            </li>
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-project-diagram mr-2"></i> Projet :</strong> {{ $rendezVous->projet->titre }}
                            </li>
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-calendar-alt mr-2"></i> Date et Heure :</strong> {{ $rendezVous->date_heure->format('d/m/Y H:i') }}
                            </li>
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-map-marker-alt mr-2"></i> Lieu :</strong> {{ $rendezVous->lieu ?? 'Non spécifié' }}
                            </li>
                            <li>
                               <strong class="text-pink-600"><i class="fas fa-align-left mr-2"></i> Description :</strong> {!! nl2br(e($rendezVous->description ?? 'Aucune description')) !!}
                            </li>
                        </ul>
                    </div>
                    
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <h5 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-cogs text-red-500 mr-2"></i>
                            Historique et Statut
                        </h5>
                        <ul class="space-y-4 text-gray-700">
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-check-circle mr-2"></i> Statut :</strong>
                                <span class="text-sm font-bold px-4 py-2 rounded-full shadow-lg transform transition-all duration-300 hover:scale-110
                                    @switch($rendezVous->statut)
                                        @case('programmé') bg-gradient-to-r from-yellow-400 to-orange-500 text-white @break
                                        @case('confirmé') bg-gradient-to-r from-blue-500 to-indigo-600 text-white @break
                                        @case('terminé') bg-gradient-to-r from-green-500 to-emerald-600 text-white @break
                                        @case('annulé') bg-gradient-to-r from-red-500 to-rose-600 text-white @break
                                        @default bg-gradient-to-r from-gray-400 to-gray-600 text-white @break
                                    @endswitch">
                                    {{ ucfirst($rendezVous->statut) }}
                                </span>
                            </li>
                            @if($rendezVous->confirmePar)
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-user-check mr-2"></i> Confirmé par :</strong> {{ $rendezVous->confirmePar->name }}
                            </li>
                            @endif
                            @if($rendezVous->annulePar)
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-user-times mr-2"></i> Annulé par :</strong> {{ $rendezVous->annulePar->name }}
                            </li>
                            @endif
                            @if($rendezVous->reprogrammePar)
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-user-cog mr-2"></i> Reprogrammé par :</strong> {{ $rendezVous->reprogrammePar->name }}
                            </li>
                            @endif
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-plus-circle mr-2"></i> Créé le :</strong> {{ $rendezVous->created_at->format('d/m/Y H:i') }}
                            </li>
                            <li>
                                <strong class="text-pink-600"><i class="fas fa-edit mr-2"></i> Dernière mise à jour :</strong> {{ $rendezVous->updated_at->format('d/m/Y H:i') }}
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="my-8 border-gray-200">

                <div class="flex flex-wrap justify-center md:justify-end gap-4 mt-6">
                    @if ($rendezVous->statut === 'programmé' && !$rendezVous->date_heure->isPast())
                        <form action="{{ route('client.client.rendez-vous.confirm', $rendezVous) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-6 py-3 rounded-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center" onclick="return confirm('Êtes-vous sûr de vouloir confirmer cette intervention ?')">
                                <i class="fas fa-check-circle mr-2"></i> Confirmer
                            </button>
                        </form>
                        <form action="{{ route('client.client.rendez-vous.cancel', $rendezVous) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-6 py-3 rounded-full bg-gradient-to-r from-red-500 to-rose-600 text-white font-bold hover:from-red-600 hover:to-rose-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center" onclick="return confirm('Êtes-vous sûr de vouloir reporter cette intervention ?')">
                                <i class="fas fa-times-circle mr-2"></i> reporter
                            </button>
                        </form>
                    @endif
                    @if ($rendezVous->statut === 'annulé')
                        <a href="{{ route('client.client.rendez-vous.reprogrammer', $rendezVous) }}" class="px-6 py-3 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center">
                            <i class="fas fa-redo-alt mr-2"></i> Reprogrammer
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>