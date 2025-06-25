{{-- resources/views/formations/create.blade.php (Final complete and responsive code) --}}
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

        /* Custom styles for checkbox group */
        .checkbox-group-container {
            border: 1px solid #d1d5db; /* gray-300 */
            border-radius: 0.375rem; /* rounded-md */
            background-color: #f9fafb; /* gray-50 */
            padding: 0.75rem; /* p-3 */
            max-height: 15rem; /* max-h-60 */
            overflow-y: auto;
        }

        .checkbox-group-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem; /* mb-2 */
        }

        .checkbox-group-item:last-child {
            margin-bottom: 0;
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800 color-primary heading-bounce">
                <i class="fas fa-plus-circle mr-3"></i> Créer une nouvelle Formation
            </h2>
            <a href="{{ route('formations.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
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
                <i class="fas fa-info-circle mr-3 color-primary heading-bounce"></i> Informations de la Formation
            </h3>
            <form method="POST" action="{{ route('formations.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nom de la formation --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom de la formation <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-tag"></i>
                            </span>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('name') border-red-500 @enderror"
                                   placeholder="Nom de la formation">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nom du formateur --}}
                    <div>
                        <label for="nomformateur" class="block text-sm font-medium text-gray-700">Nom du formateur <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </span>
                            <input type="text" name="nomformateur" id="nomformateur" value="{{ old('nomformateur') }}" required
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('nomformateur') border-red-500 @enderror"
                                   placeholder="Nom du formateur">
                        </div>
                        @error('nomformateur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Type de formation --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Type de formation <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <select name="status" id="status" required
                                    class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('status') border-red-500 @enderror">
                                <option value="">Sélectionner un type</option>
                                <option value="en ligne" {{ old('status') == 'en ligne' ? 'selected' : '' }}>En ligne</option>
                                <option value="lieu" {{ old('status') == 'lieu' ? 'selected' : '' }}>En présentiel</option>
                            </select>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Statut --}}
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700">Statut <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-hourglass-half"></i>
                            </span>
                            <select name="statut" id="statut" required
                                    class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('statut') border-red-500 @enderror">
                                <option value="">Sélectionner un statut</option>
                                <option value="nouveu" {{ old('statut') == 'nouveu' ? 'selected' : '' }}>Nouveau</option>
                                <option value="encour" {{ old('statut') == 'encour' ? 'selected' : '' }}>En cours</option>
                                <option value="fini" {{ old('statut') == 'fini' ? 'selected' : '' }}>Terminé</option>
                            </select>
                        </div>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Date de début --}}
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date de début <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" name="date" id="date" value="{{ old('date') }}" required
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('date') border-red-500 @enderror">
                        </div>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Durée (jours) --}}
                    <div>
                        <label for="duree" class="block text-sm font-medium text-gray-700">Durée (jours) <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-calendar-days"></i>
                            </span>
                            <input type="number" name="duree" id="duree" value="{{ old('duree') }}" min="1" required
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('duree') border-red-500 @enderror">
                        </div>
                        @error('duree')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Prix (DH) --}}
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700">Prix (DH) <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <input type="number" name="prix" id="prix" value="{{ old('prix') }}" min="0" step="0.01" required
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('prix') border-red-500 @enderror">
                        </div>
                        @error('prix')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nombre d'heures --}}
                    <div>
                        <label for="nombre_heures" class="block text-sm font-medium text-gray-700">Nombre d'heures <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-clock"></i>
                            </span>
                            <input type="number" name="nombre_heures" id="nombre_heures" value="{{ old('nombre_heures') }}" min="1" required
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('nombre_heures') border-red-500 @enderror">
                        </div>
                        @error('nombre_heures')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nombre de séances --}}
                    <div>
                        <label for="nombre_seances" class="block text-sm font-medium text-gray-700">Nombre de séances <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            <input type="number" name="nombre_seances" id="nombre_seances" value="{{ old('nombre_seances') }}" min="1" required
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('nombre_seances') border-red-500 @enderror">
                        </div>
                        @error('nombre_seances')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Assigné(e) - Checkboxes (This is the section that needs to keep its structure for users) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assigné(e) <span class="text-red-500">*</span></label>
                    <div class="checkbox-group-container">
                        @foreach($users as $user)
                            <div class="checkbox-group-item">
                                <input type="checkbox" name="iduser[]" value="{{ $user->id }}"
                                       id="user_{{ $user->id }}"
                                       {{ in_array($user->id, old('iduser', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-custom focus:ring-primary-custom border-gray-300 rounded">
                                <label for="user_{{ $user->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $user->name }} ({{ $user->email }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('iduser')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fichier de support --}}
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">Fichier de support</label>
                    <div class="mt-1 flex rounded-md shadow-sm input-group"> {{-- Added input-group class --}}
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm input-group-text">
                            <i class="fas fa-file-upload"></i>
                        </span>
                        <input type="file" name="file" id="file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.mp4"
                               class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-md shadow-sm focus:outline-none focus:ring-primary-custom focus:border-primary-custom @error('file') border-red-500 @enderror">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Formats acceptés: PDF, DOC, DOCX, PNG, JPG, MP4 (Max: 10MB)</p>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('formations.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary-custom border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover-bg-primary-darker focus:outline-none focus:border-primary-custom focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Créer la Formation
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Any specific JavaScript for this form (e.g., dynamic field validation or interactions)
        // would go here. For example, if you wanted to limit the number of users assigned
        // to a formation, you'd add that logic here.
    </script>
    @endpush
</x-app-layout>