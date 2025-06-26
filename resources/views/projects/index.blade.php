<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- IMPORTANT: Add CSRF token for AJAX requests --}}
        <title>{{ __('Liste des Projets') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            // Configure Tailwind CSS to use a custom primary color
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary-red': '#D32F2F', // Your specified color for a consistent theme
                            'secondary-purple': '#C2185B', // The color from your original project list
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
            /* Custom CSS for animations and improved aesthetics (from index/tache) */
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

            /* Custom modal styling */
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
                overflow: hidden; /* For rounded corners on child elements */
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
                font-size: 1.125rem; /* text-lg */
                color: #374151; /* gray-700 */
            }

            .modal-content .buttons {
                display: flex;
                justify-content: center;
                gap: 1rem;
            }

            /* Button gradients and shadows */
            .btn-primary-red {
                background: linear-gradient(to right, #D32F2F, #B71C1C); /* Deeper red gradient */
                box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3);
                transition: all 0.3s ease;
            }

            .btn-primary-red:hover {
                background: linear-gradient(to right, #B71C1C, #D32F2F);
                box-shadow: 0 6px 15px rgba(211, 47, 47, 0.4);
                transform: translateY(-2px);
            }

            .btn-secondary-custom {
                background-color: #e5e7eb; /* gray-200 */
                color: #4b5563; /* gray-700 */
                transition: all 0.3s ease;
            }

            .btn-secondary-custom:hover {
                background-color: #d1d5db; /* gray-300 */
                transform: translateY(-2px);
            }

            /* Project specific button color */
            .btn-project-create {
                background-color: #C2185B; /* secondary-purple */
                box-shadow: 0 4px 10px rgba(194, 24, 91, 0.3); /* Shadow based on the color */
                transition: all 0.3s ease;
            }
            .btn-project-create:hover {
                background-color: #A00037; /* Darker secondary-purple */
                box-shadow: 0 6px 15px rgba(194, 24, 91, 0.4);
                transform: translateY(-2px);
            }

            /* Form input/select styles */
            .form-input-custom, .form-select-custom {
                @apply border-gray-300 focus:border-primary-red focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded-md shadow-sm p-3;
            }

            /* Pagination Links */
            .pagination-custom nav {
                @apply inline-flex -space-x-px rounded-md shadow-sm;
            }
            .pagination-custom a, .pagination-custom span {
                @apply px-4 py-2 text-sm leading-5 font-medium border border-gray-300;
            }
            .pagination-custom a.current {
                @apply bg-primary-red text-white border-primary-red;
            }
            .pagination-custom a:not(.current):hover {
                @apply bg-gray-100;
            }
            .pagination-custom .disabled {
                @apply text-gray-400 cursor-not-allowed;
            }
        </style>
    </head>
    <body>
        @can("project-list")
        <div id="custom-modal" class="modal-overlay">
            <div class="modal-content rounded-xl shadow-2xl">
                <div id="modal-icon" class="header-icon"></div>
                <div id="modal-message" class="message"></div>
                <div id="modal-buttons" class="buttons"></div>
            </div>
        </div>

       
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight border-b-2 border-secondary-purple pb-3 mb-6 animate-fade-in delay-100">
                <i class="fas fa-project-diagram mr-3 text-secondary-purple"></i> {{ __('Liste des Projets') }}
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
                                @can("project-create")
                                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-project-create">
                                    <i class="fas fa-plus-circle mr-2"></i> {{ __('Créer un Nouveau Projet') }}
                                </a>
                                @endcan
                            </div>
                        </div>

                        <form action="{{ route('projects.index') }}" method="GET" class="mb-6 bg-gray-50 p-6 rounded-lg shadow-inner animate-fade-in delay-800">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Filtres et Recherche') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700">{{ __('Recherche') }}</label>
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text" name="search" id="search" class="form-input-custom block w-full pl-10 sm:text-sm" placeholder="{{ __('Rechercher par titre, client, ville...') }}" value="{{ request('search') }}">
                                    </div>
                                </div>
                                {{-- You can add more filters here if needed, e.g., for user, date range --}}
                                {{-- Example: User filter for projects --}}
                                {{-- Assuming you pass a $users variable from your controller --}}
                                {{-- @if (isset($users) && count($users) > 0)
                                <div>
                                    <label for="user_filter" class="block text-sm font-medium text-gray-700">{{ __('Filtrer par Utilisateur') }}</label>
                                    <select id="user_filter" name="user_filter" class="form-select-custom mt-1 block w-full">
                                        <option value="">{{ __('Tous les utilisateurs') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif --}}
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                
                                <a href="{{ route('projects.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-full font-bold text-sm text-gray-700 uppercase tracking-wider shadow-md btn-secondary-custom">
                                    <i class="fas fa-undo mr-2"></i> {{ __('Réinitialiser') }}
                                </a>
                            </div>
                        </form>

                        @if ($projects->isEmpty())
                            <div class="bg-gray-100 p-8 text-center text-gray-600 rounded-lg shadow-lg animate-fade-in delay-900">
                                <i class="fas fa-info-circle text-6xl text-gray-400 mb-4"></i>
                                <p class="text-xl font-semibold">{{ __('Aucun projet trouvé.') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto relative shadow-lg sm:rounded-lg animate-fade-in delay-900">
                                <table class="w-full text-sm text-left text-gray-700">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 rounded-t-lg">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">{{ __('Titre') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Nom du Client') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Ville') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Besoins') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Utilisateurs Assignés') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Date de Création') }}</th>
                                            <th scope="col" class="py-3 px-6">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                        <tr class="bg-white border-b hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">{{ $project->titre }}</td>
                                            <td class="py-4 px-6 whitespace-nowrap">{{ $project->nomclient }}</td>
                                            <td class="py-4 px-6 whitespace-nowrap">{{ $project->ville }}</td>
                                            <td class="py-4 px-6 truncate max-w-xs" title="{{ $project->bessoins }}">{{ Str::limit($project->bessoins, 50) }}</td>
                                            <td class="py-4 px-6">
                                                <div class="flex flex-wrap gap-1">
                                                    @forelse($project->users as $user)
                                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ $user->name }}</span>
                                                    @empty
                                                        <span class="text-gray-500 text-xs">N/A</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 whitespace-nowrap">{{ \Carbon\Carbon::parse($project->date_project)->format('d/m/Y') }}</td>
                                            <td class="py-4 px-6 flex items-center space-x-4">
                                                @can("project-show")
                                                <a href="{{ route('projects.show', $project->id) }}" title="Voir" class="text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out transform hover:scale-110">
                                                    <i class="fas fa-eye text-lg"></i>
                                                </a>
                                                @endcan

                                                @can("project-edit")
                                                <a href="{{ route('projects.edit', $project->id) }}" title="Modifier" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out transform hover:scale-110">
                                                    <i class="fas fa-edit text-lg"></i>
                                                </a>
                                                @endcan

                                                @can("project-delete")
                                                <button type="button" title="Supprimer" onclick="showCustomConfirm('{{ __('Êtes-vous sûr de vouloir supprimer ce projet ?') }}', function() { document.getElementById('delete-form-{{ $project->id }}').submit(); });" class="text-primary-red hover:text-red-700 transition duration-150 ease-in-out transform hover:scale-110">
                                                    <i class="fas fa-trash text-lg"></i>
                                                </button>
                                                <form id="delete-form-{{$project->id}}" action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-center pagination-custom">
                                {{ $projects->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // Custom Modal Logic (from index/tache)
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
                        cancelBtn.className = 'px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wider shadow-md btn-secondary-custom';
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
                            if (onConfirm) onConfirm(); // Use onConfirm for alert callbacks too
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

                // Integrate custom modal with session messages (from index/tache)
                document.addEventListener('DOMContentLoaded', function () {
                    const successMessage = "{{ session('success') }}";
                    const errorMessage = "{{ session('error') }}";

                    if (successMessage) {
                        showCustomSuccess(successMessage);
                    } else if (errorMessage) {
                        showCustomError(errorMessage);
                    }
                });

                // Function to confirm delete with custom modal
                function confirmDelete(id) {
                    showCustomConfirm('Êtes-vous sûr de vouloir supprimer ce projet ?', function() {
                        document.getElementById('delete-form-' + id).submit();
                    });
                }
            </script>
        @endpush
        @endcan
    </body>
</x-app-layout>