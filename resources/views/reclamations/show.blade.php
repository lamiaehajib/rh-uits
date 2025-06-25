{{-- resources/views/reclamations/show.blade.php --}}
<x-app-layout>
    <style>
        /* Custom Styles for the D32F2F color and animations (copy from show/users) */
        .color-primary {
            color: #D32F2F;
        }

        .bg-primary-custom {
            background-color: #D32F2F;
        }

        .hover-bg-primary-darker:hover {
            background-color: #B71C1C;
        }

        .border-primary-custom {
            border-color: #D32F2F;
        }

        .card-hover-scale {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card-hover-scale:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .icon-bounce:hover {
            animation: bounce 0.6s ease-in-out;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .badge-pulse {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(211, 47, 47, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(211, 47, 47, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(211, 47, 47, 0);
            }
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 space-y-4 sm:space-y-0">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 text-center sm:text-left">
                <i class="fas fa-ticket-alt mr-3 color-primary"></i> Détails de la réclamation: <span class="color-primary">#{{ $reclamation->reference }}</span>
            </h2>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                @can('reclamation-edit')
                <a href="{{ route('reclamations.edit', $reclamation) }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-primary-custom border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover-bg-primary-darker focus:outline-none focus:border-primary-custom focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105 w-full sm:w-auto">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                @endcan
                <a href="{{ route('reclamations.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105 w-full sm:w-auto">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            {{-- Reclamation Details Card --}}
            <div class="md:col-span-2 bg-white rounded-lg shadow-lg p-6 card-hover-scale">
                <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                    <i class="fas fa-info-circle mr-3 color-primary icon-bounce"></i> Informations Générales
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-gray-700">
                    <p><strong><i class="fas fa-heading mr-2 text-gray-500"></i> Titre:</strong> {{ $reclamation->titre }}</p>
                    <p><strong><i class="fas fa-hashtag mr-2 text-gray-500"></i> Référence:</strong> {{ $reclamation->reference }}</p>
                    <p><strong><i class="fas fa-calendar-alt mr-2 text-gray-500"></i> Date de l'incident:</strong> {{ \Carbon\Carbon::parse($reclamation->date)->format('d/m/Y') }}</p>
                    <p><strong><i class="fas fa-user mr-2 text-gray-500"></i> Utilisateur:</strong> {{ $reclamation->user->name }} ({{ $reclamation->user->email }})</p>
                    <p>
                        <strong><i class="fas fa-exclamation-triangle mr-2 text-gray-500"></i> Priorité:</strong>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($reclamation->priority == 'low') bg-green-100 text-green-800
                                @elseif($reclamation->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 badge-pulse @endif">
                                {{ ucfirst($reclamation->priority) }}
                        </span>
                    </p>
                    <p><strong><i class="fas fa-tags mr-2 text-gray-500"></i> Catégorie:</strong> {{ $reclamation->category }}</p>
                    <p>
                        <strong><i class="fas fa-check-circle mr-2 text-gray-500"></i> Statut:</strong>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($reclamation->status == 'pending') bg-blue-100 text-blue-800 badge-pulse
                            @elseif($reclamation->status == 'in_progress') bg-purple-100 text-purple-800
                            @elseif($reclamation->status == 'resolved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $reclamation->status)) }}
                        </span>
                        @if(auth()->user()->hasRole('Admin'))
                        <div class="mt-2">
                            <button type="button" onclick="openStatusModal()" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                                <i class="fas fa-sync-alt mr-1"></i> Mettre à jour le statut
                            </button>
                        </div>
                        @endif
                    </p>
                    @if($reclamation->resolved_at)
                    <p><strong><i class="fas fa-calendar-check mr-2 text-gray-500"></i> Date de résolution:</strong> {{ \Carbon\Carbon::parse($reclamation->resolved_at)->format('d/m/Y à H:i') }}</p>
                    @endif
                </div>
            </div>

            {{-- Description Card --}}
            <div class="md:col-span-1 bg-white rounded-lg shadow-lg p-6 card-hover-scale">
                <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                    <i class="fas fa-file-alt mr-3 color-primary icon-bounce"></i> Description
                </h3>
                <p class="text-gray-800 mt-2 whitespace-pre-wrap">{{ $reclamation->description }}</p>
            </div>
        </div>

        {{-- Added a horizontal rule for visual separation on smaller screens --}}
        <hr class="my-8 border-gray-200 block md:hidden">

        @if($reclamation->admin_notes && auth()->user()->hasRole('Admin'))
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 card-hover-scale">
            <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                <i class="fas fa-sticky-note mr-3 color-primary icon-bounce"></i> Notes Administrateur
            </h3>
            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <p class="text-base text-yellow-800 whitespace-pre-wrap">{{ $reclamation->admin_notes }}</p>
            </div>
        </div>
        @endif

        {{-- Added a horizontal rule for visual separation on smaller screens --}}
        <hr class="my-8 border-gray-200 block md:hidden">

        @if($reclamation->attachments)
            @php $attachments = json_decode($reclamation->attachments, true); @endphp
            @if($attachments && count($attachments) > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8 card-hover-scale">
                <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                    <i class="fas fa-paperclip mr-3 color-primary icon-bounce"></i> Pièces jointes
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($attachments as $index => $attachment)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex-shrink-0 text-xl">
                            @if(in_array(strtolower(pathinfo($attachment['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                <i class="fas fa-image text-blue-500"></i>
                            @elseif(in_array(strtolower(pathinfo($attachment['name'], PATHINFO_EXTENSION)), ['pdf']))
                                <i class="fas fa-file-pdf text-red-500"></i>
                            @elseif(in_array(strtolower(pathinfo($attachment['name'], PATHINFO_EXTENSION)), ['doc', 'docx']))
                                <i class="fas fa-file-word text-blue-600"></i>
                            @else
                                <i class="fas fa-file text-gray-500"></i>
                            @endif
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($attachment['size'] / 1024, 2) }} KB</p>
                        </div>
                        <div class="ml-4">
                            <a href="{{ route('reclamations.downloadAttachment', [$reclamation, $index]) }}"
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-download mr-1"></i> Télécharger
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endif

        {{-- Added a horizontal rule for visual separation on smaller screens --}}
        <hr class="my-8 border-gray-200 block md:hidden">

        {{-- Activity History Card --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 card-hover-scale">
            <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                <i class="fas fa-history mr-3 color-primary icon-bounce"></i> Historique des activités
            </h3>
            @if($reclamation->activities->count() > 0)
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($reclamation->activities->sortByDesc('created_at') as $activity)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div class="flex-shrink-0"> {{-- Added flex-shrink-0 here --}}
                                        <span class="h-8 w-8 rounded-full bg-primary-custom flex items-center justify-center ring-8 ring-white">
                                            <i class="fas fa-clock text-white"></i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex flex-col sm:flex-row sm:justify-between space-y-2 sm:space-y-0 sm:space-x-4"> {{-- Changed to flex-col on small screens --}}
                                        <div class="min-w-0 flex-1"> {{-- Added min-w-0 and flex-1 --}}
                                            <p class="text-sm text-gray-800">
                                                {{ $activity->description }}
                                                @if($activity->causer)
                                                    <span class="font-medium text-gray-900 block sm:inline">par {{ $activity->causer->name }}</span> {{-- Added block on small screens --}}
                                                @endif
                                                @if($activity->changes && count($activity->changes['attributes'] ?? []) > 0)
                                                    <ul class="text-xs text-gray-600 mt-1 list-disc list-inside break-words"> {{-- Added break-words --}}
                                                        @foreach($activity->changes['attributes'] as $key => $newValue)
                                                            <li>
                                                                <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                @if(isset($activity->changes['old'][$key]))
                                                                    <span class="font-normal text-red-500 line-through">{{ $activity->changes['old'][$key] }}</span>
                                                                    <i class="fas fa-arrow-right text-gray-400 mx-1"></i>
                                                                @endif
                                                                <span class="font-semibold text-green-700">{{ is_array($newValue) ? json_encode($newValue) : $newValue }}</span> {{-- Handle array values --}}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right text-xs sm:text-sm whitespace-nowrap text-gray-500 sm:self-start"> {{-- Adjusted text size and alignment --}}
                                            <time datetime="{{ $activity->created_at->format('Y-m-d H:i') }}">
                                                {{ $activity->created_at->format('d/m/Y H:i') }}
                                            </time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-gray-500">Aucune activité enregistrée pour cette réclamation.</p>
            @endif
        </div>

        @if(auth()->user()->hasRole('Admin'))
        <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 sm:w-96 shadow-lg rounded-md bg-white"> {{-- Adjusted width for mobile --}}
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Mettre à jour le statut</h3>
                    <div class="mt-2 px-4 py-3 sm:px-7"> {{-- Adjusted padding for mobile --}}
                        <form id="updateStatusForm">
                            @csrf
                            <div class="mb-4 text-left">
                                <label for="modal_status" class="block text-sm font-medium text-gray-700 mb-2">Nouveau statut</label>
                                <select name="status" id="modal_status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="pending" {{ $reclamation->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="in_progress" {{ $reclamation->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                    <option value="resolved" {{ $reclamation->status == 'resolved' ? 'selected' : '' }}>Résolue</option>
                                    <option value="closed" {{ $reclamation->status == 'closed' ? 'selected' : '' }}>Fermée</option>
                                </select>
                            </div>
                            <div class="mb-4 text-left">
                                <label for="modal_admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes administrateur (facultatif)</label>
                                <textarea name="admin_notes" id="modal_admin_notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ajouter des notes..."></textarea>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-4"> {{-- Adjusted button layout for mobile --}}
                                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none w-full sm:w-auto">Annuler</button>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none w-full sm:w-auto">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openStatusModal() {
                document.getElementById('statusModal').classList.remove('hidden');
                document.getElementById('modal_status').value = '{{ $reclamation->status }}';
                document.getElementById('modal_admin_notes').value = '{{ $reclamation->admin_notes ?? '' }}';
            }

            function closeStatusModal() {
                document.getElementById('statusModal').classList.add('hidden');
            }

            document.getElementById('updateStatusForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const form = event.target;
                const formData = new FormData(form);
                const status = formData.get('status');
                const adminNotes = formData.get('admin_notes');

                fetch('{{ route('reclamations.updateStatus', $reclamation) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: status,
                        admin_notes: adminNotes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Erreur: ' + (data.message || 'La mise à jour du statut a échoué.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur s\'est produite lors de la mise à jour du statut.');
                });
            });
        </script>
        @endif
</x-app-layout>