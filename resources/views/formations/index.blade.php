<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Gestion des Formations') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            // Configure Tailwind CSS to use a custom primary color (matching the Tache's red or adjusting as needed)
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary-red': '#D32F2F', // Consistent with Tache, or choose a primary color for formations
                            'primary-blue': '#2196F3', // Example: a primary blue for formations if distinct
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'], // Set Inter as the default font
                        }
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <style>
            /* Custom CSS for animations and improved aesthetics */
            body {
                font-family: 'Inter', sans-serif;
            }

            /* Fade-in animation for elements */
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
                opacity: 0;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Scale-up effect on hover for cards */
            .card-hover-effect {
                transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            }

            .card-hover-effect:hover {
                transform: translateY(-5px) scale(1.01);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            /* Pulse animation for important buttons/alerts */
            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.7;
                }
            }

            .animate-pulse-subtle {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            /* Spin animation for icons */
            @keyframes spin-slow {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }

            .animate-spin-slow {
                animation: spin-slow 3s linear infinite;
            }

            /* Custom modal styling (matching tache) */
            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.6);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }

            .modal-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .modal-content {
                background: white;
                padding: 2.5rem;
                border-radius: 0.75rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                max-width: 90%;
                width: 400px;
                transform: translateY(-20px);
                transition: transform 0.3s ease-out;
                position: relative;
                overflow: hidden;
            }

            .modal-overlay.show .modal-content {
                transform: translateY(0);
            }

            .modal-content .header-icon {
                font-size: 2.5rem;
                margin-bottom: 1rem;
                text-align: center;
            }

            .modal-content .message {
                text-align: center;
                margin-bottom: 1.5rem;
                font-size: 1.125rem;
                color: #374151;
            }

            .modal-content .buttons {
                display: flex;
                justify-content: center;
                gap: 1rem;
            }

            /* Button gradients and shadows (consistent with tache) */
            .btn-primary-red {
                background: linear-gradient(to right, #D32F2F, #B71C1C);
                box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3);
                transition: all 0.3s ease;
            }

            .btn-primary-red:hover {
                background: linear-gradient(to right, #B71C1C, #D32F2F);
                box-shadow: 0 6px 15px rgba(211, 47, 47, 0.4);
                transform: translateY(-2px);
            }

            .btn-secondary {
                background-color: #e5e7eb;
                color: #4b5563;
                transition: all 0.3s ease;
            }

            .btn-secondary:hover {
                background-color: #d1d5db;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <div id="custom-modal" class="modal-overlay">
            <div class="modal-content rounded-xl shadow-2xl">
                <div id="modal-icon" class="header-icon"></div>
                <div id="modal-message" class="message"></div>
                <div id="modal-buttons" class="buttons"></div>
            </div>
        </div>

        <h2 class="font-semibold text-2xl text-gray-800 leading-tight border-b-2 border-primary-red pb-3 mb-6 animate-fade-in delay-100">
            <i class="fas fa-graduation-cap mr-3 text-primary-red"></i> {{ __('Gestion des Formations') }}
        </h2>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 shadow-md animate-fade-in" role="alert">
                        <strong class="font-bold">Succès!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 shadow-md animate-fade-in animate-pulse-subtle" role="alert">
                        <strong class="font-bold">Erreur!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg animate-fade-in delay-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                            <div class="mb-4 md:mb-0">
                                @can('formation-create')
                                    <a href="{{ route('formations.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red">
                                        <i class="fas fa-plus-circle mr-2"></i> {{ __('Ajouter une Formation') }}
                                    </a>
                                @endcan
                            </div>
                        </div>

                        {{-- Dashboard Statistics Section (refactored to Tailwind) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-blue-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-300">
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-700">{{ __('Total Formations') }}</h3>
                                    <p class="text-4xl font-extrabold text-blue-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-layer-group text-blue-500 text-5xl opacity-75"></i>
                            </div>
                            <div class="bg-yellow-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-400">
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-700">{{ __('Formations En Cours') }}</h3>
                                    <p class="text-4xl font-extrabold text-yellow-900 mt-1">{{ $stats['en_cours'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-hourglass-half text-yellow-500 text-5xl opacity-75 animate-spin-slow"></i>
                            </div>
                            <div class="bg-green-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-500">
                                <div>
                                    <h3 class="text-lg font-semibold text-green-700">{{ __('Formations Terminées') }}</h3>
                                    <p class="text-4xl font-extrabold text-green-900 mt-1">{{ $stats['terminées'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-check-double text-green-500 text-5xl opacity-75"></i>
                            </div>
                            <div class="bg-indigo-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-600">
                                <div>
                                    <h3 class="text-lg font-semibold text-indigo-700">{{ __('Nouvelles Formations') }}</h3>
                                    <p class="text-4xl font-extrabold text-indigo-900 mt-1">{{ $stats['nouvelles'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-star text-indigo-500 text-5xl opacity-75"></i>
                            </div>
                        </div>

                        <form action="{{ route('formations.index') }}" method="GET" class="mb-6 bg-gray-50 p-6 rounded-lg shadow-inner animate-fade-in delay-700">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Filtres et Tri') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700">{{ __('Rechercher') }}</label>
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text" name="search" id="search" class="focus:ring-primary-red focus:border-primary-red block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Nom, formateur, statut..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Filtrer par Statut') }}</label>
                                    <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm">
                                        <option value="">{{ __('Tous les statuts') }}</option>
                                        <option value="nouveu" {{ request('status') == 'nouveu' ? 'selected' : '' }}>{{ __('Nouveau') }}</option>
                                        <option value="encour" {{ request('status') == 'encour' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                        <option value="fini" {{ request('status') == 'fini' ? 'selected' : '' }}>{{ __('Terminée') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="per_page" class="block text-sm font-medium text-gray-700">{{ __('Par page') }}</label>
                                    <select name="per_page" id="per_page" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm" onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red">
                                    <i class="fas fa-filter mr-2"></i> {{ __('Appliquer Filtres') }}
                                </button>
                                <a href="{{ route('formations.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-full font-bold text-sm text-gray-700 uppercase tracking-wider shadow-md btn-secondary">
                                    <i class="fas fa-undo mr-2"></i> {{ __('Réinitialiser') }}
                                </a>
                            </div>
                        </form>

                        @if ($formations->isEmpty())
                            <div class="bg-gray-100 p-8 text-center text-gray-600 rounded-lg shadow-lg animate-fade-in delay-800">
                                <i class="fas fa-info-circle text-6xl text-gray-400 mb-4"></i>
                                <p class="text-xl font-semibold">{{ __('Aucune formation trouvée.') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto relative shadow-lg sm:rounded-lg animate-fade-in delay-800">
                                <table class="w-full text-sm text-left text-gray-700">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 rounded-t-lg">
                                        <tr>
                                          
                                            <th scope="col" class="py-3 px-6">{{ __('Nom') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Type') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Formateur') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Date') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Statut') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Assigné(e)') }}</th>
                                            <th scope="col" class="py-3 px-6 text-center">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($formations as $formation)
                                            <tr class="bg-white border-b hover:bg-gray-50 transition duration-150 ease-in-out">
                                               
                                                <td class="py-4 px-6">{{ $formation->name }}</td>
                                                <td class="py-4 px-6">
                                                    @if($formation->status == 'en ligne')
                                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-200 text-blue-800"><i class="fas fa-globe mr-1"></i> {{ __('En ligne') }}</span>
                                                    @else
                                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800"><i class="fas fa-map-marker-alt mr-1"></i> {{ __('Lieu') }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6">{{ $formation->nomformateur }}</td>
                                                <td class="py-4 px-6">{{ \Carbon\Carbon::parse($formation->date)->format('d/m/Y') }}</td>
                                                <td class="py-4 px-6">
                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        @if($formation->statut == 'nouveu') bg-indigo-200 text-indigo-800
                                                        @elseif($formation->statut == 'encour') bg-yellow-200 text-yellow-800
                                                        @else bg-green-200 text-green-800
                                                        @endif">
                                                        {{ ucfirst($formation->statut) }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-700 text-white">{{ $formation->users->count() }}</span>
                                                </td>
                                                <td class="py-4 px-6 flex items-center justify-center space-x-2">
                                                    @can('formation-show')
                                                        <a href="{{ route('formations.show', $formation->id) }}" title="{{ __('Voir') }}" class="text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out transform hover:scale-110">
                                                            <i class="fas fa-eye text-lg"></i>
                                                        </a>
                                                    @endcan
                                                    @can('formation-edit')
                                                        <a href="{{ route('formations.edit', $formation->id) }}" title="{{ __('Modifier') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out transform hover:scale-110">
                                                            <i class="fas fa-pencil-alt text-lg"></i>
                                                        </a>
                                                    @endcan
                                                    @can('formation-create') {{-- Check 'formation-create' permission for duplicate --}}
                                                        <form action="{{ route('formations.duplicate', $formation->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <button type="submit" title="{{ __('Dupliquer') }}" class="text-purple-600 hover:text-purple-800 transition duration-150 ease-in-out transform hover:scale-110">
                                                                <i class="fas fa-copy text-lg"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                    @can('formation-delete')
                                                        <button type="button" title="{{ __('Supprimer') }}" onclick="showCustomConfirm('{{ __('Êtes-vous sûr de vouloir supprimer cette formation ?') }}', function() { document.getElementById('delete-form-{{ $formation->id }}').submit(); });" class="text-primary-red hover:text-red-700 transition duration-150 ease-in-out transform hover:scale-110">
                                                            <i class="fas fa-trash-alt text-lg"></i>
                                                        </button>
                                                        <form id="delete-form-{{ $formation->id }}" action="{{ route('formations.destroy', $formation->id) }}" method="POST" class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endcan
                                                    @if($formation->file_path)
                                                        @can('formation-list') {{-- Assuming 'formation-list' implies ability to download, or create a specific 'formation-download' permission --}}
                                                            <a href="{{ route('formations.download', $formation->id) }}" title="{{ __('Télécharger Fichier') }}" class="text-green-600 hover:text-green-800 transition duration-150 ease-in-out transform hover:scale-110">
                                                                <i class="fas fa-download text-lg"></i>
                                                            </a>
                                                        @endcan
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="py-8 text-center">
                                                    <div class="bg-gray-100 p-4 text-center text-gray-600 rounded-lg shadow-md animate-fade-in">
                                                        <i class="fas fa-exclamation-triangle mr-2"></i> {{ __('Aucune formation trouvée.') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-center">
                                {{ $formations->links('pagination::tailwind') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // Custom Modal Logic (Copied directly from tache for consistency)
                const customModal = document.getElementById('custom-modal');
                const modalMessage = document.getElementById('modal-message');
                const modalButtons = document.getElementById('modal-buttons');
                const modalIcon = document.getElementById('modal-icon');
                let resolveModalPromise;

                function showCustomModal(message, type = 'alert', onConfirm = null) {
                    modalMessage.textContent = message;
                    modalButtons.innerHTML = ''; // Clear previous buttons
                    modalIcon.innerHTML = ''; // Clear previous icon

                    if (type === 'confirm') {
                        modalIcon.innerHTML = '<i class="fas fa-question-circle text-blue-500"></i>';
                        const confirmBtn = document.createElement('button');
                        confirmBtn.textContent = 'Confirmer';
                        confirmBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        confirmBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(confirmBtn);

                        const cancelBtn = document.createElement('button');
                        cancelBtn.textContent = 'Annuler';
                        cancelBtn.className = 'px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wider shadow-md btn-secondary';
                        cancelBtn.onclick = () => {
                            customModal.classList.remove('show');
                            resolveModalPromise(false);
                        };
                        modalButtons.appendChild(cancelBtn);
                    } else if (type === 'alert') {
                        modalIcon.innerHTML = '<i class="fas fa-info-circle text-gray-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        okBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    } else if (type === 'success') {
                        modalIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        okBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    } else if (type === 'error') {
                        modalIcon.innerHTML = '<i class="fas fa-times-circle text-primary-red"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red';
                        okBtn.onclick = () => {
                            customModal.classList.remove('show');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    }

                    customModal.classList.add('show');
                    return new Promise(resolve => {
                        resolveModalPromise = resolve;
                    });
                }

                // Convenience functions to replace native alert/confirm
                function showCustomAlert(message, callback = null) {
                    return showCustomModal(message, 'alert', callback);
                }

                function showCustomConfirm(message, callback = null) {
                    return showCustomModal(message, 'confirm', callback);
                }

                function showCustomSuccess(message, callback = null) {
                    return showCustomModal(message, 'success', callback);
                }

                function showCustomError(message, callback = null) {
                    return showCustomModal(message, 'error', callback);
                }
            </script>
        @endpush
    </body>
</x-app-layout>