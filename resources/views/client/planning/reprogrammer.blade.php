<x-app-layout>
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 backdrop-blur-sm max-w-2xl mx-auto">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div>
                        <h3 class="text-3xl font-bold mb-2 flex items-center">
                            <i class="fas fa-redo-alt mr-3 text-blue-200"></i>
                            Re-programmer la maintenance
                        </h3>
                        <p class="text-blue-100 text-sm">
                            Veuillez choisir une nouvelle date et heure pour ce rendez-vous.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8">
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-6 mb-8 shadow-inner">
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Détails de la maintenance annulée:</h4>
                    <p class="text-gray-600"><span class="font-medium text-gray-700">Titre:</span> {{ $rendezVous->titre }}</p>
                    <p class="text-gray-600"><span class="font-medium text-gray-700">Projet:</span> {{ $rendezVous->projet->titre }}</p>
                    <p class="text-gray-600"><span class="font-medium text-gray-700">Date et heure initiales:</span> {{ \Carbon\Carbon::parse($rendezVous->date_heure)->format('d/m/Y H:i') }}</p>
                </div>

                <form method="POST" action="{{ route('client.client.rendez-vous.reprogram-store', $rendezVous) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="date_heure" class="block text-sm font-medium text-gray-700 mb-2">Nouvelle date et heure</label>
                        <input type="datetime-local" 
                               id="date_heure" 
                               name="date_heure" 
                               required 
                               class="block w-full px-4 py-2 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-300 shadow-sm">
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('client.rendez-vous.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-300">
                           <i class="fas fa-arrow-left mr-2"></i> Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i> Re-programmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>