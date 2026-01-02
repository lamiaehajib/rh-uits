<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rapport des Retards</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight border-b-2 border-red-600 pb-3 mb-6">
            <i class="fas fa-clock mr-3 text-red-600"></i> Rapport des Retards
        </h2>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Alerte si retards accumulés -->
                @if(isset($rapport['alerte']) && $rapport['alerte']['doit_alerter'])
                <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-6 rounded-lg shadow-md animate-pulse">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-2xl mr-3 mt-1"></i>
                        <div>
                            <p class="font-bold text-lg mb-2">⚠️ Alerte Retards</p>
                            <p class="text-sm">{{ $rapport['alerte']['message'] }}</p>
                            <p class="text-xs mt-2 font-semibold">
                                Il vous reste {{ $rapport['alerte']['minutes_restantes'] }} minutes avant la prochaine déduction.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Total Minutes Retard -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Total Retards (min)</p>
                                <h3 class="text-3xl font-bold text-gray-800">
                                    {{ $rapport['stats']['total_minutes'] }}
                                </h3>
                            </div>
                            <div class="bg-red-100 rounded-full p-4">
                                <i class="fas fa-stopwatch text-2xl text-red-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre de Retards -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Nombre de Retards</p>
                                <h3 class="text-3xl font-bold text-gray-800">
                                    {{ $rapport['stats']['nombre_retards'] }}
                                </h3>
                            </div>
                            <div class="bg-yellow-100 rounded-full p-4">
                                <i class="fas fa-exclamation-circle text-2xl text-yellow-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jours à Déduire -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Jours à Déduire</p>
                                <h3 class="text-3xl font-bold text-gray-800">
                                    {{ $rapport['stats']['jours_a_deduire'] }}
                                </h3>
                            </div>
                            <div class="bg-orange-100 rounded-full p-4">
                                <i class="fas fa-minus-circle text-2xl text-orange-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Solde Restant -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Congés Restants</p>
                                <h3 class="text-3xl font-bold text-gray-800">
                                    {{ $rapport['solde_conge']['restants'] }}
                                </h3>
                                <p class="text-xs text-gray-500">sur {{ $rapport['solde_conge']['total'] }} jours</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-4">
                                <i class="fas fa-calendar-check text-2xl text-green-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau des Retards -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900">
                            <i class="fas fa-list mr-3 text-blue-600"></i>
                            Détail des Retards du Mois
                        </h3>
                    </div>

                    @if(count($rapport['retards_details']) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Heure Arrivée</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Minutes Retard</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Justificatif</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($rapport['retards_details'] as $retard)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <i class="far fa-calendar mr-2 text-gray-500"></i>
                                        {{ $retard['date'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $retard['heure_arrivee'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold 
                                            {{ $retard['minutes_retard'] >= 30 ? 'bg-red-500 text-white' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $retard['minutes_retard'] }} min
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($retard['justifie'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Justifié
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Non justifié
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($retard['statut_justificatif'] === 'non_soumis')
                                        <span class="text-gray-500">-</span>
                                        @elseif($retard['statut_justificatif'] === 'en_attente')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> En attente
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Validé
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-8 text-center">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun retard ce mois-ci</h3>
                        <p class="text-gray-600">Félicitations ! Vous êtes toujours à l'heure.</p>
                    </div>
                    @endif
                </div>

                <!-- Informations -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i> Règles de Déduction
                    </h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Retard = Arrivée après 9h10</li>
                        <li>• Alerte à partir de 15 minutes de retard cumulé</li>
                        <li>• <strong>30 minutes de retard cumulé = 1 jour de congé déduit</strong></li>
                        <li>• Les retards justifiés et validés par l'admin ne sont pas comptabilisés</li>
                        <li>• Les déductions sont effectuées automatiquement en fin de mois</li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>