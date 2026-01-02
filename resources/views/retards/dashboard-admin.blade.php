<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Retards - Admin</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-50">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight border-b-2 border-indigo-600 pb-3 mb-6">
            <i class="fas fa-tachometer-alt mr-3 text-indigo-600"></i> Dashboard des Retards
        </h2>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Filtres Période -->
                <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
                    <form method="GET" class="flex items-end space-x-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mois</label>
                            <select name="mois" class="px-3 py-2 border border-gray-300 rounded-md">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                            <select name="annee" class="px-3 py-2 border border-gray-300 rounded-md">
                                @for($a = now()->year; $a >= now()->year - 2; $a--)
                                    <option value="{{ $a }}" {{ $annee == $a ? 'selected' : '' }}>{{ $a }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                            <i class="fas fa-filter mr-2"></i> Filtrer
                        </button>
                        <form method="POST" action="{{ route('retards.executer-deductions') }}" class="ml-auto">
                            @csrf
                            <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir exécuter les déductions maintenant ?')"
                                class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 font-semibold">
                                <i class="fas fa-bolt mr-2"></i> Exécuter Déductions
                            </button>
                        </form>
                    </form>
                </div>

                <!-- Stats Globales -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    @php
                        $totalUtilisateurs = count($utilisateurs);
                        $utilisateursAlerte = $utilisateurs->where('alerte.doit_alerter', true)->count();
                        $totalMinutesRetard = $utilisateurs->sum('stats.total_minutes');
                        $totalJoursADeduire = $utilisateurs->sum('stats.jours_a_deduire');
                    @endphp

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Utilisateurs</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $totalUtilisateurs }}</h3>
                            </div>
                            <div class="bg-blue-100 rounded-full p-4">
                                <i class="fas fa-users text-2xl text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">En Alerte</p>
                                <h3 class="text-3xl font-bold text-orange-600">{{ $utilisateursAlerte }}</h3>
                            </div>
                            <div class="bg-orange-100 rounded-full p-4">
                                <i class="fas fa-exclamation-triangle text-2xl text-orange-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Total Retard (min)</p>
                                <h3 class="text-3xl font-bold text-red-600">{{ $totalMinutesRetard }}</h3>
                            </div>
                            <div class="bg-red-100 rounded-full p-4">
                                <i class="fas fa-stopwatch text-2xl text-red-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Jours à Déduire</p>
                                <h3 class="text-3xl font-bold text-purple-600">{{ $totalJoursADeduire }}</h3>
                            </div>
                            <div class="bg-purple-100 rounded-full p-4">
                                <i class="fas fa-calendar-minus text-2xl text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Retards en Attente de Validation -->
                @if($retardsEnAttente->count() > 0)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="bg-yellow-50 p-6 border-b border-yellow-200">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-clock mr-3 text-yellow-600"></i>
                            Justificatifs de Retard en Attente ({{ $retardsEnAttente->count() }})
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Utilisateur</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Retard (min)</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Justificatif</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($retardsEnAttente as $retard)
                                <tr class="hover:bg-yellow-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm font-medium mr-3">
                                                {{ substr($retard->user->name, 0, 1) }}
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $retard->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $retard->date_pointage->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800">
                                            {{ $retard->getRetardMinutes() }} min
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">
                                        {{ $retard->justificatif_retard }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <a href="{{ route('pointage.show', $retard->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            <i class="fas fa-eye mr-1"></i> Voir
                                        </a>
                                        <button onclick="ouvrirModalValidationRetard({{ $retard->id }})"
                                                class="text-green-600 hover:text-green-800 font-medium">
                                            <i class="fas fa-check mr-1"></i> Valider
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Liste des Utilisateurs -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-bar mr-3 text-indigo-600"></i>
                            Statistiques par Utilisateur
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Utilisateur</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nb Retards</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Total (min)</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Jours à Déduire</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($utilisateurs as $item)
                                <tr class="hover:bg-gray-50 transition {{ $item['alerte']['doit_alerter'] ? 'bg-orange-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold mr-3">
                                                {{ substr($item['user']->name, 0, 1) }}
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $item['user']->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $item['stats']['nombre_retards'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $item['stats']['nombre_retards'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold
                                            @if($item['stats']['total_minutes'] >= 30) bg-red-500 text-white
                                            @elseif($item['stats']['total_minutes'] >= 15) bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $item['stats']['total_minutes'] }} min
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item['stats']['jours_a_deduire'] > 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-500 text-white">
                                                <i class="fas fa-minus-circle mr-1"></i>
                                                {{ $item['stats']['jours_a_deduire'] }} jour(s)
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item['alerte']['doit_alerter'])
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 animate-pulse">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Alerte
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> OK
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('pointage.index', ['user_id' => $item['user']->id]) }}" 
                                           class="text-indigo-600 hover:text-indigo-800 font-medium">
                                            <i class="fas fa-eye mr-1"></i> Voir Pointages
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2"></i>
                                        <p>Aucune donnée disponible pour cette période</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Informations -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i> Règles de Gestion
                    </h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Heure limite d'arrivée : <strong>9h10</strong></li>
                        <li>• Alerte automatique à partir de <strong>15 minutes</strong> de retard cumulé</li>
                        <li>• <strong>30 minutes de retard cumulé</strong> = 1 jour de congé déduit</li>
                        <li>• Les retards justifiés et validés ne sont <strong>pas comptabilisés</strong></li>
                        <li>• Les déductions sont calculées <strong>mensuellement</strong></li>
                        <li>• Maximum <strong>1 jour déduit par mois</strong> (car seuil = 30 min)</li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>