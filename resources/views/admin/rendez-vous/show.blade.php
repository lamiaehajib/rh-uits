<x-app-layout>
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden border-t-4 border-blue-500">
        <div class="px-6 py-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-extrabold text-gray-900">
                    Détails du Rendez-vous
                </h1>
                <a href="{{ route('admin.rendez-vous.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center mb-4">
                        <span class="p-2 bg-blue-100 rounded-full text-blue-600 mr-3">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </span>
                        <h2 class="text-xl font-semibold text-gray-800">Détails Principaux</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Titre</p>
                            <p class="mt-1 text-lg font-bold text-gray-900">{{ $rendezVous->titre }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Projet Associé</p>
                            <a href="#" class="mt-1 text-lg font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                {{-- Here is the fix: check if the 'projet' relationship exists --}}
                                @if ($rendezVous->projet)
                                    {{ $rendezVous->projet->titre }}
                                @else
                                    <span class="text-gray-500 italic">Aucun projet associé</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center mb-4">
                        <span class="p-2 bg-green-100 rounded-full text-green-600 mr-3">
                            <i class="fas fa-clock text-xl"></i>
                        </span>
                        <h2 class="text-xl font-semibold text-gray-800">Statut et Horaire</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Statut</p>
                            <p class="mt-1 text-lg font-bold">
                                @php
                                    $statusClass = [
                                        'programmé' => 'bg-blue-100 text-blue-800',
                                        'confirmé' => 'bg-green-100 text-green-800',
                                        'terminé' => 'bg-gray-100 text-gray-800',
                                        'annulé' => 'bg-red-100 text-red-800',
                                    ][$rendezVous->statut] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($rendezVous->statut) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date et Heure</p>
                            <p class="mt-1 text-lg font-bold text-gray-900">
                                {{ \Carbon\Carbon::parse($rendezVous->date_heure)->locale('fr')->isoFormat('D MMMM YYYY [à] HH:mm') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Lieu</p>
                            <p class="mt-1 text-lg font-semibold text-gray-700">{{ $rendezVous->lieu ?? 'Non spécifié' }}</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center mb-4">
                        <span class="p-2 bg-yellow-100 rounded-full text-yellow-600 mr-3">
                            <i class="fas fa-file-alt text-xl"></i>
                        </span>
                        <h2 class="text-xl font-semibold text-gray-800">Description et Notes</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Description</p>
                            <p class="mt-1 text-base text-gray-700 whitespace-pre-wrap">{{ $rendezVous->description ?? 'Aucune description fournie.' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Notes</p>
                            <p class="mt-1 text-base text-gray-700 whitespace-pre-wrap">{{ $rendezVous->notes ?? 'Aucune note supplémentaire.' }}</p>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 lg:col-span-3 bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center mb-4">
                        <span class="p-2 bg-purple-100 rounded-full text-purple-600 mr-3">
                            <i class="fas fa-users text-xl"></i>
                        </span>
                        <h2 class="text-xl font-semibold text-gray-800">Participants du Projet</h2>
                    </div>
                    <ul class="divide-y divide-gray-200 mt-4">
                        {{-- Here is the second fix: check if the 'projet' relationship exists before looping --}}
                        @if ($rendezVous->projet)
                            @forelse($rendezVous->projet->users as $user)
                                <li class="py-3 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Membre
                                    </span>
                                </li>
                            @empty
                                <li class="py-4 text-center text-gray-500 italic">Aucun participant associé à ce projet.</li>
                            @endforelse
                        @else
                            <li class="py-4 text-center text-gray-500 italic">Aucun participant associé à ce projet.</li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.rendez-vous.edit', $rendezVous->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <form action="{{ route('admin.rendez-vous.destroy', $rendezVous->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Supprimer
                    </button>
                </form>
            </div> --}}
        </div>
    </div>
</div>
</x-app-layout>