<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard des Tâches') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ $stats['total'] }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total des tâches</p>
                            <p class="text-xs text-gray-400">Toutes les tâches</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ $stats['nouveau'] }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Nouvelles</p>
                            <p class="text-xs text-gray-400">À traiter</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ $stats['en_cours'] }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">En cours</p>
                            <p class="text-xs text-gray-400">En progression</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ $stats['termine'] }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Terminées</p>
                            <p class="text-xs text-gray-400">Complétées</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ $stats['overdue'] }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">En retard</p>
                            <p class="text-xs text-gray-400">Urgent</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Overdue Tasks --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 py-4 bg-red-50 border-b border-red-100">
                        <h3 class="text-lg font-medium text-red-800">Tâches en retard</h3>
                        <p class="text-sm text-red-600">Nécessitent une attention immédiate</p>
                    </div>
                    <div class="p-6">
                        @forelse($overdueTasks as $task)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($task->description, 40) }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $task->user->name ?? 'N/A' }} • 
                                    {{ Carbon\Carbon::parse($task->datedebut)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('taches.show', $task->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 text-xs">Voir</a>
                                <a href="{{ route('taches.edit', $task->id) }}" 
                                   class="text-green-600 hover:text-green-900 text-xs">Modifier</a>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Aucune tâche en retard</p>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Tasks --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
                        <h3 class="text-lg font-medium text-blue-800">Tâches récentes</h3>
                        <p class="text-sm text-blue-600">Dernières tâches créées</p>
                    </div>
                    <div class="p-6">
                        @forelse($recentTasks as $task)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($task->description, 40) }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $task->user->name ?? 'N/A' }} • 
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($task->status == 'nouveau') bg-yellow-100 text-yellow-800
                                        @elseif($task->status == 'en cours') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('taches.show', $task->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 text-xs">Voir</a>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Aucune tâche récente</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-800">Actions rapides</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('taches.create') }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center">
                            Nouvelle tâche
                        </a>
                        <a href="{{ route('taches.index', ['status' => 'nouveau']) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded text-center">
                            Voir les nouvelles
                        </a>
                        <a href="{{ route('taches.index', ['status' => 'en cours']) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center">
                            Voir en cours
                        </a>
                        <a href="{{ route('taches.export') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center">
                            Exporter CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>