<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier la Tâche</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            // Configure Tailwind CSS to use a custom primary color
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary-red': '#D32F2F', // Your specified color
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

            /* Pulse animation for important alerts/errors */
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

            .btn-secondary {
                background-color: #e5e7eb; /* gray-200 */
                color: #4b5563; /* gray-700 */
                transition: all 0.3s ease;
            }

            .btn-secondary:hover {
                background-color: #d1d5db; /* gray-300 */
                transform: translateY(-2px);
            }

            /* Input/Select focus animation */
            input:focus,
            textarea:focus,
            select:focus {
                transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                border-color: #D32F2F; /* Primary red color for focus */
                box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.2); /* Soft shadow on focus */
            }

            /* Styles specific to audio recording buttons */
            #recordButton, #stopButton, #playButton, #clearButton {
                @apply px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 ease-in-out;
            }
            #recordButton { @apply bg-blue-500 hover:bg-blue-600 text-white; }
            #stopButton { @apply bg-red-500 hover:bg-red-600 text-white; }
            #playButton { @apply bg-green-500 hover:bg-green-600 text-white; }
            #clearButton { @apply bg-yellow-500 hover:bg-yellow-600 text-white; }
            
            /* Styles for disabled buttons */
            button:disabled {
                @apply opacity-50 cursor-not-allowed;
            }
        </style>
    </head>
    <body>
        <x-slot name="header">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center border-b-2 border-primary-red pb-3 animate-fade-in delay-100">
                <i class="fas fa-edit text-primary-red mr-3"></i>
                {{ __('Modifier la Tâche') }}
                <span class="ml-3 px-4 py-1 bg-primary-red text-white rounded-full text-base font-bold shadow-md">#{{ $tache->id }}</span>
            </h2>
        </x-slot>

        <div class="py-12 bg-gray-50">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-2xl sm:rounded-xl animate-fade-in delay-200">
                    <div class="p-8 bg-white border-b border-gray-200">

                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6 shadow-md animate-fade-in" role="alert">
                                <strong class="font-bold">Succès!</strong>
                                <span class="block sm:inline">{{ session('success') }}</span>
                                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                                </span>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 shadow-md animate-fade-in animate-pulse-subtle" role="alert">
                                <strong class="font-bold">Erreur!</strong>
                                <span class="block sm:inline">{{ session('error') }}</span>
                                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                                </span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 p-6 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md animate-fade-in animate-pulse-subtle">
                                <strong class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i> {{ __('Oups !') }}</strong>
                                <span class="block sm:inline">{{ __('Il y a eu des problèmes avec votre soumission. Veuillez vérifier les champs ci-dessous.') }}</span>
                                <ul class="mt-3 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('taches.update', $tache->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT') {{-- Use PUT method for update requests --}}

                            {{-- Hidden fields to pass existing filter parameters --}}
                            @foreach ($filterParams as $key => $value)
                                @if ($key === 'status')
                                    {{-- Rename status filter parameter to avoid conflict with task status --}}
                                    <input type="hidden" name="original_status_filter" value="{{ $value }}">
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach

                           @php
                         $user = auth()->user();
                          $isAdminOrAdmin1 = $user->hasAnyRole(['Sup_Admin', 'Custom_Admin']);
                       $canModifyRetourRoles = ['USER_MULTIMEDIA', 'USER_TRAINING', 'Sales_Admin', 'USER_TECH','Custom_Admin'];
                       $canEditRetour = $user->hasAnyRole($canModifyRetourRoles);
                          @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label for="titre" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-heading mr-2 text-indigo-500"></i> {{ __('Titre de la Tâche') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <input type="text" name="titre" id="titre"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('titre') border-primary-red ring-red-200 @enderror
                                        {{ !$isAdminOrAdmin1 ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        value="{{ old('titre', $tache->titre) }}" placeholder="{{ __('Entrez un titre concis pour la tâche...') }}"
                                        {{ !$isAdminOrAdmin1 ? 'readonly' : '' }} required>
                                    @error('titre')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Description de la Tâche (Texte) --}}
                                <div>
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-file-alt mr-2 text-blue-500"></i> {{ __('Description de la Tâche (Texte)') }}
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('description') border-primary-red ring-red-200 @enderror
                                        {{ !$isAdminOrAdmin1 ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        placeholder="{{ __('Décrivez la tâche en détail ou utilisez l\'enregistrement audio...') }}"
                                        {{ !$isAdminOrAdmin1 ? 'readonly' : '' }}
                                    >{{ old('description', ($tache->description === '-' && $tache->audio_description_path) ? '' : $tache->description) }}</textarea>
                                    @error('description')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Audio Recording Section --}}
                                <div class="md:col-span-2"> <label for="audio_record_section" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-microphone mr-2 text-purple-500"></i> {{ __('Ou Enregistrer une Description Audio') }}
                                    </label>
                                    <div class="flex flex-wrap items-center space-x-2 mt-1">
                                        <button type="button" id="recordButton" class="btn btn-primary-red"><i class="fas fa-microphone mr-1"></i> Démarrer Enregistrement</button>
                                        <button type="button" id="stopButton" class="btn bg-red-500 text-white hover:bg-red-600"><i class="fas fa-stop mr-1"></i> Arrêter</button>
                                        <button type="button" id="playButton" class="btn bg-green-500 text-white hover:bg-green-600"><i class="fas fa-play mr-1"></i> Écouter</button>
                                        <button type="button" id="clearButton" class="btn bg-yellow-500 text-white hover:bg-yellow-600"><i class="fas fa-trash-alt mr-1"></i> Effacer</button>
                                    </div>
                                    <audio id="audioPlayback" controls class="w-full mt-4" style="display: none;"></audio>
                                    {{-- The hidden input will store new recorded audio or signal old audio to be kept --}}
                                    <input type="hidden" name="audio_data" id="audioDataInput" value="{{ old('audio_data') }}">
                                    @error('audio_data')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                    {{-- Display existing audio if available --}}
                                    @if($tache->audio_description_path)
                                        <p class="mt-4 text-gray-700">Fichier audio actuel:</p>
                                        {{-- IMPORTANT: Use Storage::disk('public')->url() here --}}
                                        <audio src="{{ Storage::disk('public')->url($tache->audio_description_path) }}" controls class="w-full mt-2"></audio>
                                        <div class="flex items-center mt-2 text-gray-600">
                                            <input type="checkbox" id="remove_existing_audio" name="remove_existing_audio" value="1" class="rounded border-gray-300 text-primary-red shadow-sm focus:border-primary-red focus:ring focus:ring-primary-red focus:ring-opacity-50">
                                            <label for="remove_existing_audio" class="ml-2 text-sm">{{ __('Supprimer l\'audio actuel') }}</label>
                                        </div>
                                    @endif
                                </div>


                               <div>
    <label for="duree" class="block text-sm font-semibold text-gray-700 mb-1">
        <i class="fas fa-hourglass-half mr-2 text-green-500"></i> {{ __('Durée Estimée') }} <span class="text-primary-red text-lg">*</span>
    </label>
    <input type="text" name="duree" id="duree"
        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
        focus:ring-primary-red focus:border-primary-red
        @error('duree') border-primary-red ring-red-200 @enderror
        {{ !$isAdminOrAdmin1 ? 'bg-gray-100 cursor-not-allowed' : '' }}" {{-- Add these classes --}}
        value="{{ old('duree', $tache->duree) }}"
        placeholder="{{ __('Ex: 1 jour, 3 jours, 2 semaines, 1 mois') }}"
        {{ !$isAdminOrAdmin1 ? 'readonly' : '' }} required> {{-- Add 'readonly' attribute --}}
    @error('duree')
        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
    @enderror
    {{-- If disabled (readonly for text input), ensure value is sent via hidden input for consistency --}}
    @if (!$isAdminOrAdmin1)
        <input type="hidden" name="duree" value="{{ old('duree', $tache->duree) }}">
    @endif
</div>

                                <div>
                                    <label for="datedebut" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i> {{ __('Date de Début') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <input type="date" name="datedebut" id="datedebut"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('datedebut') border-primary-red ring-red-200 @enderror
                                        {{ !$isAdminOrAdmin1 ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        value="{{ old('datedebut', \Carbon\Carbon::parse($tache->datedebut)->format('Y-m-d')) }}"
                                        {{ !$isAdminOrAdmin1 ? 'readonly' : '' }} required>
                                    @error('datedebut')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-info-circle mr-2 text-yellow-500"></i> {{ __('Statut de la Tâche') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <select name="status" id="status"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('status') border-primary-red ring-red-200 @enderror" required>
                                        <option value="nouveau" {{ old('status', $tache->status) == 'nouveau' ? 'selected' : '' }}>{{ __('Nouveau') }}</option>
                                        <option value="en cours" {{ old('status', $tache->status) == 'en cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                        <option value="termine" {{ old('status', $tache->status) == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                                    </select>
                                    @error('status')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-clock mr-2 text-orange-500"></i> {{ __('Type de Planification') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <select name="date" id="date"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('date') border-primary-red ring-red-200 @enderror
                                        {{ !$isAdminOrAdmin1 ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ !$isAdminOrAdmin1 ? 'disabled' : '' }} required>
                                        <option value="jour" {{ old('date', $tache->date) == 'jour' ? 'selected' : '' }}>{{ __('Journalier') }}</option>
                                        <option value="semaine" {{ old('date', $tache->date) == 'semaine' ? 'selected' : '' }}>{{ __('Hebdomadaire') }}</option>
                                        <option value="mois" {{ old('date', $tache->date) == 'mois' ? 'selected' : '' }}>{{ __('Mensuel') }}</option>
                                    </select>
                                    @error('date')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    {{-- If disabled, ensure value is sent via hidden input --}}
                                    @if (!$isAdminOrAdmin1)
                                        <input type="hidden" name="date" value="{{ old('date', $tache->date) }}">
                                    @endif
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label" for="user_ids">Assigné(e) <span class="text-danger">*</span></label>
                                    <div class="border rounded p-3">
                                        @foreach ($users as $userItem) {{-- Corrected variable name from $user to $userItem --}}
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $userItem->id }}" id="user_{{ $userItem->id }}"
                                                    {{-- Logique de pré-sélection: coche si l'ID est dans les old() values ou dans les utilisateurs assignés à la tâche --}}
                                                    {{ in_array($userItem->id, old('user_ids', $tache->users->pluck('id')->toArray())) ? 'checked' : '' }}
                                                    {{ !$isAdminOrAdmin1 ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="user_{{ $userItem->id }}">
                                                    {{ $userItem->name }} ({{ $userItem->email }})
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('user_ids')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    {{-- If disabled, ensure selected user_ids are sent via hidden inputs --}}
                                    @if (!$isAdminOrAdmin1)
                                        @foreach ($tache->users->pluck('id')->toArray() as $userId)
                                            <input type="hidden" name="user_ids[]" value="{{ $userId }}">
                                        @endforeach
                                    @endif
                                </div>

                                <div>
                                    <label for="priorite" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i> {{ __('Priorité') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <select name="priorite" id="priorite"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('priorite') border-primary-red ring-red-200 @enderror
                                        {{ !$isAdminOrAdmin1 ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ !$isAdminOrAdmin1 ? 'disabled' : '' }} required>
                                        <option value="faible" {{ old('priorite', $tache->priorite) == 'faible' ? 'selected' : '' }}>{{ __('Faible') }}</option>
                                        <option value="moyen" {{ old('priorite', $tache->priorite) == 'moyen' ? 'selected' : '' }}>{{ __('Moyen') }}</option>
                                        <option value="élevé" {{ old('priorite', $tache->priorite) == 'élevé' ? 'selected' : '' }}>{{ __('Élevé') }}</option>
                                    </select>
                                    @error('priorite')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    {{-- If disabled, ensure value is sent via hidden input --}}
                                    @if (!$isAdminOrAdmin1)
                                        <input type="hidden" name="priorite" value="{{ old('priorite', $tache->priorite) }}">
                                    @endif
                                </div>

                                <div class="md:col-span-2">
                                    <label for="retour" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-comment-dots mr-2 text-gray-500"></i> {{ __('Retour/Notes (Optionnel)') }}
                                    </label>
                                    <textarea name="retour" id="retour" rows="3"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('retour') border-primary-red ring-red-200 @enderror
                                       {{ !$isAdminOrAdmin1 && !$canEditRetour ? 'bg-gray-100 cursor-not-allowed' : '' }}"
placeholder="{{ __('Ajoutez des notes ou un retour sur la tâche...') }}"
{{ !$isAdminOrAdmin1 && !$canEditRetour ? 'readonly' : '' }}>{{ old('retour', $tache->retour) }}</textarea>
                                    @error('retour')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-8 flex items-center justify-end space-x-4">
                                <a href="{{ route('taches.index', $filterParams) }}"
                                    class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-full font-bold text-sm text-gray-700 uppercase tracking-wider shadow-md btn-secondary transform hover:scale-105">
                                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Annuler') }}
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red transform hover:scale-105">
                                    <i class="fas fa-save mr-2"></i> {{ __('Mettre à Jour la Tâche') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const descriptionField = document.getElementById('description');
                const recordButton = document.getElementById('recordButton');
                const stopButton = document.getElementById('stopButton');
                const playButton = document.getElementById('playButton');
                const clearButton = document.getElementById('clearButton');
                const audioPlayback = document.getElementById('audioPlayback');
                const audioDataInput = document.getElementById('audioDataInput'); // Hidden input for Base64 audio
                const removeExistingAudioCheckbox = document.getElementById('remove_existing_audio'); // Checkbox for existing audio

                let mediaRecorder;
                let audioChunks = [];
                let recordedAudioBlob = null;

                // Variable to track if page loaded with existing audio to influence initial state
                const hasInitialAudio = {{ $tache->audio_description_path ? 'true' : 'false' }};
                const hasInitialDescription = {{ ($tache->description && $tache->description !== '-') ? 'true' : 'false' }};
                const isAdmin = {{ $isAdminOrAdmin1 ? 'true' : 'false' }};

                // Function to enable/disable description field based on audio input/state
                function toggleDescriptionField() {
                    // Disable description field if there's recorded audio or if the 'remove existing audio' checkbox is checked (meaning old audio is involved)
                    // Or if there was initial audio and the checkbox is NOT checked (meaning we are keeping it)
                    if (audioDataInput.value || (removeExistingAudioCheckbox && removeExistingAudioCheckbox.checked) || (hasInitialAudio && !(removeExistingAudioCheckbox && removeExistingAudioCheckbox.checked))) {
                        descriptionField.disabled = true;
                        descriptionField.value = ''; // Clear text description if audio is present
                    } else {
                        descriptionField.disabled = false;
                        // Restore old description if available and no audio being used
                        if (hasInitialDescription && !audioDataInput.value && !(removeExistingAudioCheckbox && removeExistingAudioCheckbox.checked)) {
                            descriptionField.value = "{{ old('description', ($tache->description === '-' && $tache->audio_description_path) ? '' : $tache->description) }}";
                        } else if (!hasInitialDescription && !audioDataInput.value && !hasInitialAudio) {
                            descriptionField.value = "{{ old('description', '') }}"; // Fallback for new creation/clear
                        }
                    }
                    if (!isAdmin) {
                        descriptionField.disabled = true; // Admins always modify all, others only status/retour
                    }
                }

                // Function to enable/disable audio controls based on description field
                function toggleAudioControls() {
                    if (!isAdmin) { // Non-admins cannot record/modify audio
                        recordButton.disabled = true;
                        stopButton.disabled = true;
                        playButton.disabled = true;
                        clearButton.disabled = true;
                        if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = true;
                        return; // Exit early for non-admins
                    }

                    // For admins:
                    if (descriptionField.value.trim() !== '' && descriptionField.value.trim() !== '-') { // If there's actual text description
                        recordButton.disabled = true;
                        stopButton.disabled = true;
                        playButton.disabled = true;
                        clearButton.disabled = true;
                        if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = true;
                        
                        // Clear any recorded audio if description is typed
                        audioChunks = [];
                        recordedAudioBlob = null;
                        audioPlayback.src = '';
                        audioPlayback.style.display = 'none';
                        audioDataInput.value = '';

                    } else { // If description is empty or '-', allow audio input
                        recordButton.disabled = false;
                        if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = false; // Enable checkbox if description is empty
                        
                        // Set play/clear based on whether there's recorded audio (new or old)
                        if (!recordedAudioBlob && !audioDataInput.value && !hasInitialAudio) {
                            playButton.disabled = true;
                            clearButton.disabled = true;
                        } else { // Either new recorded audio or existing one
                            playButton.disabled = false;
                            clearButton.disabled = false;
                        }
                    }
                }

                descriptionField.addEventListener('input', toggleAudioControls);

                if (removeExistingAudioCheckbox) {
                    removeExistingAudioCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            // When 'remove old audio' is checked
                            descriptionField.disabled = true;
                            descriptionField.value = '';
                            recordButton.disabled = true; // Cannot record new if removing old
                            audioChunks = []; // Clear any new recording in progress
                            recordedAudioBlob = null;
                            audioPlayback.src = '';
                            audioPlayback.style.display = 'none';
                            audioDataInput.value = '';
                            playButton.disabled = true;
                            clearButton.disabled = true; // Clear current player actions
                        } else {
                            // When 'remove old audio' is unchecked
                            toggleDescriptionField(); // Re-evaluate description field
                            toggleAudioControls(); // Re-evaluate audio buttons (record button should be enabled if description is empty)
                        }
                    });
                }

                // Handle record button click
                recordButton.addEventListener('click', async () => {
                    recordedAudioBlob = null; // Reset any previous recording
                    audioChunks = []; // Clear old chunks
                    audioPlayback.style.display = 'none'; // Hide player
                    audioPlayback.src = ''; // Clear audio source
                    audioDataInput.value = ''; // Clear hidden input
                    playButton.disabled = true;
                    clearButton.disabled = true;
                    stopButton.disabled = true; // Initially disable stop until recording starts
                    if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = true; // Disable if recording new

                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        const options = { mimeType: 'audio/webm;codecs=opus' }; 
                        mediaRecorder = new MediaRecorder(stream, options);
                        
                        mediaRecorder.ondataavailable = event => {
                            if (event.data.size > 0) {
                                audioChunks.push(event.data);
                            }
                        };

                        mediaRecorder.onstop = () => {
                            recordedAudioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                            const audioUrl = URL.createObjectURL(recordedAudioBlob);
                            audioPlayback.src = audioUrl;
                            audioPlayback.style.display = 'block';
                            playButton.disabled = false;
                            clearButton.disabled = false;

                            const reader = new FileReader();
                            reader.readAsDataURL(recordedAudioBlob);
                            reader.onloadend = () => {
                                audioDataInput.value = reader.result;
                                toggleDescriptionField(); // Update field states after recording
                            };

                            stream.getTracks().forEach(track => track.stop());
                        };

                        mediaRecorder.start();
                        recordButton.disabled = true;
                        stopButton.disabled = false;
                        toggleDescriptionField(); // Disable description as we are recording
                    } catch (err) {
                        console.error('Error accessing microphone:', err);
                        alert('Impossible d\'accéder au microphone. Assurez-vous que les permissions sont accordées.');
                        recordButton.disabled = false;
                        stopButton.disabled = true;
                        playButton.disabled = true;
                        clearButton.disabled = true;
                        if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = false; // Re-enable checkbox
                        toggleDescriptionField(); // Re-enable description if recording failed
                    }
                });

                // Handle stop button click
                stopButton.addEventListener('click', () => {
                    if (mediaRecorder && mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                        recordButton.disabled = false;
                        stopButton.disabled = true;
                    }
                });

                // Handle play button click
                playButton.addEventListener('click', () => {
                    if (audioPlayback.src) {
                        audioPlayback.play();
                    }
                });

                // Handle clear button click
                clearButton.addEventListener('click', () => {
                    audioChunks = [];
                    recordedAudioBlob = null;
                    audioPlayback.src = '';
                    audioPlayback.style.display = 'none';
                    audioDataInput.value = ''; // Clear hidden input
                    playButton.disabled = true;
                    clearButton.disabled = true;
                    recordButton.disabled = false; // Enable recording again
                    if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = false; // Enable checkbox
                    toggleDescriptionField(); // Re-enable description field
                });

                // Initial state setup on page load
                // (Important for edit page where data already exists)
                if (hasInitialAudio) {
                    audioPlayback.src = "{{ Storage::disk('public')->url($tache->audio_description_path) }}";
                    audioPlayback.style.display = 'block';
                    playButton.disabled = false;
                    clearButton.disabled = false; // Allow clearing existing audio
                    recordButton.disabled = true; // Cannot record new while existing audio is active
                    descriptionField.disabled = true; // Disable description field
                } else if (hasInitialDescription) {
                    // Description is already populated, so disable audio controls initially
                    recordButton.disabled = true;
                    stopButton.disabled = true;
                    playButton.disabled = true;
                    clearButton.disabled = true;
                } else {
                    // No initial audio or description, so allow recording or typing description
                    recordButton.disabled = false;
                    descriptionField.disabled = false;
                }

                // Final check for admin permissions to enable/disable everything
                if (!isAdmin) {
                    descriptionField.disabled = true; // Non-admins cannot change desc or audio
                    recordButton.disabled = true;
                    stopButton.disabled = true;
                    playButton.disabled = true;
                    clearButton.disabled = true;
                    if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = true; // And cannot remove existing audio
                } else {
                    // Admins manage the toggle between text and audio
                    toggleDescriptionField(); // Ensure correct state for description field
                    toggleAudioControls(); // Ensure correct state for audio buttons
                }
            });
        </script>
    </body>
</x-app-layout>