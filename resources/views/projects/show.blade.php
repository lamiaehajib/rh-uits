<!-- resources/views/projects/show.blade.php -->
<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Détails du Projet') }}</title>
        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            // Configure Tailwind CSS to use a custom primary color
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary-red': '#D32F2F', // Consistent primary color
                            'secondary-purple': '#C2185B', // Color from original project list
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'], // Set Inter as the default font
                        }
                    }
                }
            }
        </script>
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <style>
            /* Custom Styles for the D32F2F color and animations (from show/users) */
            body {
                font-family: 'Inter', sans-serif;
            }

            .color-primary {
                color: #D32F2F;
            }

            .bg-primary-custom {
                background-color: #D32F2F;
            }

            .hover-bg-primary-darker:hover {
                background-color: #B71C1C; /* A darker shade for hover effect */
            }

            .border-primary-custom {
                border-color: #D32F2F;
            }

            /* Subtle hover effect for cards */
            .card-hover-scale {
                transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            }

            .card-hover-scale:hover {
                transform: translateY(-5px) scale(1.01);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            /* Icon bounce animation on hover */
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

            /* Badge pulse animation (if needed for status, etc.) */
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

            /* General animations (from previous objectifs blades) */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
            }

            /* Button styles adapted for larger size and enhanced design */
            .btn-custom-primary {
                /* Increased padding (px-8 py-4) for larger size */
                /* Changed font size to text-base for better readability */
                /* Added rounded-full for a softer, more modern aesthetic */
                @apply inline-flex items-center px-8 py-4 bg-secondary-purple border border-transparent rounded-full font-semibold text-base text-white uppercase tracking-widest shadow-lg hover:bg-[#A00037] focus:outline-none focus:border-secondary-purple focus:ring-2 focus:ring-secondary-purple focus:ring-opacity-50 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105;
            }

            .btn-custom-secondary {
                /* Increased padding (px-8 py-4) for larger size */
                /* Changed font size to text-base for better readability */
                /* Added rounded-full for a softer, more modern aesthetic */
                @apply inline-flex items-center px-8 py-4 bg-gray-200 border border-gray-300 rounded-full font-semibold text-base text-gray-800 uppercase tracking-widest shadow-sm hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-300 focus:ring-opacity-50 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105;
            }

            .btn-delete-project {
                /* Increased padding (px-8 py-4) for larger size */
                /* Changed font size to text-base for better readability */
                /* Added rounded-full for a softer, more modern aesthetic */
                @apply inline-flex items-center px-8 py-4 bg-red-600 border border-transparent rounded-full font-semibold text-base text-white uppercase tracking-widest shadow-lg hover:bg-red-700 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-300 focus:ring-opacity-50 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105;
            }

            /* Display styling for detail fields */
            .detail-value {
                @apply block w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-md text-gray-800;
            }
        </style>
    </head>
    <body>
        @can("project-show")
       
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight border-b-2 border-secondary-purple pb-3 mb-6 animate-fade-in delay-100">
                <i class="fas fa-project-diagram mr-3 text-secondary-purple"></i> {{ __('Détails du Projet') }}
            </h2>
      

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg card-hover-scale animate-fade-in">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                            <h3 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">
                                <i class="fas fa-clipboard-check mr-3 text-secondary-purple icon-bounce"></i> Projet: <span class="text-secondary-purple">{{ $project->titre }}</span>
                            </h3>
                            <div class="flex flex-wrap items-center space-x-3 mt-4 md:mt-0">
                                @can("project-edit")
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn-custom-primary">
                                    <i class="fas fa-edit mr-2"></i> Modifier
                                </a>
                                @endcan

                                @can("project-delete")
                                <button type="button" onclick="confirmDelete({{ $project->id }})" class="btn-delete-project">
                                    <i class="fas fa-trash mr-2"></i> Supprimer
                                </button>
                                <form id="delete-form-{{$project->id}}" action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan

                                <a href="{{ route('projects.index') }}" class="btn-custom-secondary mt-3 md:mt-0">
                                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                                </a>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-200">

                        {{-- Project Information Card --}}
                        <div class="bg-gray-50 p-6 rounded-lg mb-8 shadow-inner card-hover-scale animate-fade-in delay-100">
                            <h4 class="text-2xl font-bold text-gray-700 mb-4 border-b pb-3 flex items-center">
                                <i class="fas fa-info-circle mr-3 text-primary-red icon-bounce"></i> Informations Générales
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-gray-700">
                                <p><strong><i class="fas fa-user-tie mr-2 text-gray-500"></i> Nom du Client:</strong> <span class="detail-value">{{ $project->nomclient }}</span></p>
                                <p><strong><i class="fas fa-city mr-2 text-gray-500"></i> Ville:</strong> <span class="detail-value">{{ $project->ville }}</span></p>
                                <p class="sm:col-span-2"><strong><i class="fas fa-calendar-alt mr-2 text-gray-500"></i> Date de Création:</strong> <span class="detail-value">{{ \Carbon\Carbon::parse($project->date_project)->format('d/m/Y') }}</span></p>
                                <div class="sm:col-span-2">
                                    <p class="font-bold text-gray-700 mb-2 flex items-center"><i class="fas fa-clipboard-list mr-2 text-gray-500"></i> Besoins:</p>
                                    <p class="detail-value">{{ $project->bessoins }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-200">

                        {{-- Assigned Users Card --}}
                        <div class="bg-white p-6 rounded-lg shadow-lg mb-8 border border-gray-200 card-hover-scale animate-fade-in delay-200">
                            <h4 class="text-2xl font-bold text-gray-700 mb-4 flex items-center border-b pb-3">
                                <i class="fas fa-users-cog mr-3 text-secondary-purple icon-bounce"></i> Utilisateurs Assignés
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @forelse($project->users as $user)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full shadow-sm">
                                        <i class="fas fa-user mr-1"></i> {{ $user->name }}
                                    </span>
                                @empty
                                    <p class="text-gray-500">Aucun utilisateur assigné à ce projet.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Custom Modal Structure (from index/tache, for delete confirmation) --}}
                        <div id="custom-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
                            <div class="bg-white p-8 rounded-xl shadow-2xl max-w-sm w-full transform -translate-y-10 transition-all duration-300 ease-out">
                                <div id="modal-icon" class="text-center text-5xl mb-4"></div>
                                <p id="modal-message" class="text-lg text-gray-700 text-center mb-6"></p>
                                <div id="modal-buttons" class="flex justify-center space-x-4"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // Custom Modal Logic (re-included for standalone operation or if not globally available)
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
                        confirmBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg bg-primary-red hover:bg-[#B71C1C] transition-colors duration-200';
                        confirmBtn.onclick = () => {
                            customModal.classList.add('hidden');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(confirmBtn);

                        const cancelBtn = document.createElement('button');
                        cancelBtn.textContent = 'Annuler';
                        cancelBtn.className = 'px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wider shadow-md bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors duration-200';
                        cancelBtn.onclick = () => {
                            customModal.classList.add('hidden');
                            resolveModalPromise(false);
                        };
                        modalButtons.appendChild(cancelBtn);
                    } else if (type === 'alert') {
                        modalIcon.innerHTML = '<i class="fas fa-info-circle text-gray-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg bg-primary-red hover:bg-[#B71C1C] transition-colors duration-200';
                        okBtn.onclick = () => {
                            customModal.classList.add('hidden');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    } else if (type === 'success') {
                        modalIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg bg-primary-red hover:bg-[#B71C1C] transition-colors duration-200';
                        okBtn.onclick = () => {
                            customModal.classList.add('hidden');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    } else if (type === 'error') {
                        modalIcon.innerHTML = '<i class="fas fa-times-circle text-primary-red"></i>';
                        const okBtn = document.createElement('button');
                        okBtn.textContent = 'OK';
                        okBtn.className = 'px-6 py-3 rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg bg-primary-red hover:bg-[#B71C1C] transition-colors duration-200';
                        okBtn.onclick = () => {
                            customModal.classList.add('hidden');
                            if (onConfirm) onConfirm();
                            resolveModalPromise(true);
                        };
                        modalButtons.appendChild(okBtn);
                    }

                    customModal.classList.remove('hidden');
                    // Add a small animation for the modal content
                    customModal.querySelector('.modal-content').classList.add('animate-modal-pop');
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
