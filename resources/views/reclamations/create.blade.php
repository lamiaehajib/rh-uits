{{-- resources/views/reclamations/create.blade.php (Restyled to match create/users) --}}
<x-app-layout>
    <style>
        /* Custom Styles for the D32F2F color and animations (copied from create/users) */
        .color-primary {
            color: #D32F2F;
        }

        .bg-primary-custom {
            background-color: #D32F2F;
        }

        .btn-primary-custom {
            background-color: #D32F2F;
            border-color: #D32F2F;
            transition: background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: #B71C1C; /* A darker shade for hover effect */
            border-color: #B71C1C;
            transform: translateY(-2px); /* Slight lift on hover */
        }

        .btn-outline-primary-custom {
            color: #D32F2F;
            border-color: #D32F2F;
            transition: all 0.3s ease;
        }

        .btn-outline-primary-custom:hover {
            background-color: #D32F2F;
            color: white;
        }

        /* Subtle hover effect for cards */
        .card-hover-scale {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card-hover-scale:hover {
            transform: translateY(-5px) scale(1.005); /* Slightly less scale for form */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Input group icon color */
        .input-group-text i {
            color: #D32F2F;
            transition: transform 0.3s ease;
        }

        .input-group:focus-within .input-group-text i {
            transform: scale(1.1); /* Slight grow on focus */
        }

        /* Bounce animation for headings */
        .heading-bounce:hover {
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
    </style>

    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800 color-primary heading-bounce">
                <i class="fas fa-plus-circle mr-3"></i> Nouvelle Réclamation
            </h2>
            <a href="{{ route('reclamations.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

        @if (count($errors) > 0)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Whoops!</strong> Il y a eu quelques problèmes avec votre saisie.<br><br>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg p-6 card-hover-scale">
            <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                <i class="fas fa-file-alt mr-3 color-primary heading-bounce"></i> Détails de la Réclamation
            </h3>
            <form action="{{ route('reclamations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div class="md:col-span-2">
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de la réclamation <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-heading"></i>
                            </span>
                            <input type="text"
                                   name="titre"
                                   id="titre"
                                   value="{{ old('titre') }}"
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('titre') border-red-500 @enderror"
                                   placeholder="Titre de votre réclamation"
                                   required>
                        </div>
                        @error('titre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de l'incident <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date"
                                   name="date"
                                   id="date"
                                   value="{{ old('date', date('Y-m-d')) }}"
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('date') border-red-500 @enderror"
                                   required>
                        </div>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priorité -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priorité <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <select name="priority"
                                    id="priority"
                                    class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('priority') border-red-500 @enderror"
                                    required>
                                <option value="">Sélectionner une priorité</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Faible</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                            </select>
                        </div>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div class="md:col-span-2">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-tags"></i>
                            </span>
                            <select name="category"
                                    id="category"
                                    class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('category') border-red-500 @enderror"
                                    required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="Service Client" {{ old('category') == 'Service Client' ? 'selected' : '' }}>Service Client</option>
                                <option value="Technique" {{ old('category') == 'Technique' ? 'selected' : '' }}>Technique</option>
                                <option value="Facturation" {{ old('category') == 'Facturation' ? 'selected' : '' }}>Facturation</option>
                                <option value="Livraison" {{ old('category') == 'Livraison' ? 'selected' : '' }}>Livraison</option>
                                <option value="Qualité" {{ old('category') == 'Qualité' ? 'selected' : '' }}>Qualité</option>
                                <option value="Autre" {{ old('category') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description détaillée <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group">
                            <span class="inline-flex items-center px-3 pt-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text self-start">
                                <i class="fas fa-pencil-alt"></i>
                            </span>
                            <textarea name="description"
                                      id="description"
                                      rows="6"
                                      class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('description') border-red-500 @enderror"
                                      placeholder="Décrivez votre réclamation en détail..."
                                      required>{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pièces jointes -->
                    <div class="md:col-span-2">
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                            Pièces jointes
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-primary-custom focus-within:ring-2 focus-within:ring-primary-custom focus-within:ring-offset-2">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-custom hover:text-red-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-custom">
                                        <span>Télécharger des fichiers</span>
                                        <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple accept=".jpg,.png,.pdf,.doc,.docx">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, PDF, DOC, DOCX jusqu'à 5MB chacun
                                </p>
                            </div>
                        </div>
                        @error('attachments.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Preview des fichiers sélectionnés -->
                        <div id="file-preview" class="mt-4 hidden border border-gray-200 rounded-md p-4 bg-gray-50">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Fichiers sélectionnés:</h4>
                            <div id="file-list" class="space-y-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('reclamations.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary-custom border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover-bg-primary-darker focus:outline-none focus:border-primary-custom focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Créer la réclamation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('attachments');
            const filePreview = document.getElementById('file-preview');
            const fileList = document.getElementById('file-list');

            function updateFileList() {
                const files = Array.from(fileInput.files);

                if (files.length > 0) {
                    filePreview.classList.remove('hidden');
                    fileList.innerHTML = ''; // Clear previous list

                    files.forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center justify-between p-3 bg-white rounded border border-gray-200 shadow-sm';
                        
                        // Determine icon based on file type
                        let iconClass = 'fas fa-file text-gray-500';
                        const fileExtension = file.name.split('.').pop().toLowerCase();
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                            iconClass = 'fas fa-image text-blue-500';
                        } else if (fileExtension === 'pdf') {
                            iconClass = 'fas fa-file-pdf text-red-500';
                        } else if (['doc', 'docx'].includes(fileExtension)) {
                            iconClass = 'fas fa-file-word text-blue-600';
                        }
                        // Note: MP4 is in accept attribute but not handled by specific icon, will use general file icon

                        fileItem.innerHTML = `
                            <div class="flex items-center">
                                <i class="${iconClass} mr-2 text-xl"></i>
                                <span class="text-sm text-gray-700 truncate">${file.name}</span>
                                <span class="text-xs text-gray-500 ml-2 whitespace-nowrap">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                            </div>
                            <button type="button" data-index="${index}" class="remove-file-btn text-red-500 hover:text-red-700 ml-4">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        fileList.appendChild(fileItem);
                    });

                    // Attach event listeners to new remove buttons
                    document.querySelectorAll('.remove-file-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const indexToRemove = parseInt(this.dataset.index);
                            removeFile(indexToRemove);
                        });
                    });

                } else {
                    filePreview.classList.add('hidden');
                }
            }

            fileInput.addEventListener('change', updateFileList);

            // Function to remove a file by index
            function removeFile(indexToRemove) {
                const dt = new DataTransfer();
                Array.from(fileInput.files).forEach((file, i) => {
                    if (i !== indexToRemove) {
                        dt.items.add(file);
                    }
                });
                fileInput.files = dt.files; // Update the file input's file list
                updateFileList(); // Re-render the file list
            }

            // Initial render in case of old input from validation errors
            updateFileList();
        });
    </script>
</x-app-layout>