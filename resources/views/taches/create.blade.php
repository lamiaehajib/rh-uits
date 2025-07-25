<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Créer une Nouvelle Tâche</title>
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
                <i class="fas fa-plus-circle text-primary-red mr-3"></i>
                {{ __('Créer une Nouvelle Tâche') }}
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

                        <form action="{{ route('taches.store') }}" method="POST" class="space-y-6">
                            @csrf

                            @php
                                $user = auth()->user();
                                $isAdmin = $user->hasAnyRole(['Sup_Admin', 'Custom_Admin']);
                                $canCreateRetour = $user->hasAnyRole(['USER_MULTIMEDIA', 'USER_TRAINING', 'Sales_Admin', 'USER_TECH']);
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label for="titre" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-heading mr-2 text-indigo-500"></i> {{ __('Titre de la Tâche') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <input type="text" name="titre" id="titre"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('titre') border-primary-red ring-red-200 @enderror"
                                        value="{{ old('titre') }}" placeholder="{{ __('Entrez un titre concis pour la tâche...') }}" required>
                                    @error('titre')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-file-alt mr-2 text-blue-500"></i> {{ __('Description de la Tâche (Texte)') }}
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('description') border-primary-red ring-red-200 @enderror"
                                        placeholder="{{ __('Décrivez la tâche en détail ou utilisez l\'enregistrement audio...') }}"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

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
                                    <input type="hidden" name="audio_data" id="audioDataInput" value="{{ old('audio_data') }}">
                                    @error('audio_data')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                    @if(isset($tache) && $tache->audio_description_path)
                                        <p class="mt-4 text-gray-700">Fichier audio actuel:</p>
                                        <audio src="{{ Storage::url($tache->audio_description_path) }}" controls class="w-full mt-2"></audio>
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
                                        @error('duree') border-primary-red ring-red-200 @enderror"
                                        value="{{ old('duree') }}" 
                                        placeholder="{{ __('Ex: 1 jour, 3 jours, 2 semaines, 1 mois') }}"
                                        required>
                                    @error('duree')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="datedebut" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i> {{ __('Date de Début') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <input type="date" name="datedebut" id="datedebut"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('datedebut') border-primary-red ring-red-200 @enderror"
                                        value="{{ old('datedebut') }}" required>
                                    @error('datedebut')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-info-circle mr-2 text-yellow-500"></i> {{ __('Statut Initial') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <select name="status" id="status"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('status') border-primary-red ring-red-200 @enderror" required>
                                        <option value="nouveau" {{ old('status') == 'nouveau' ? 'selected' : '' }}>{{ __('Nouveau') }}</option>
                                        <option value="en cours" {{ old('status') == 'en cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                        <option value="termine" {{ old('status') == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
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
                                        @error('date') border-primary-red ring-red-200 @enderror" required>
                                        <option value="jour" {{ old('date') == 'jour' ? 'selected' : '' }}>{{ __('Journalier') }}</option>
                                        <option value="semaine" {{ old('date') == 'semaine' ? 'selected' : '' }}>{{ __('Hebdomadaire') }}</option>
                                        <option value="mois" {{ old('date') == 'mois' ? 'selected' : '' }}>{{ __('Mensuel') }}</option>
                                    </select>
                                    @error('date')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2"> <label class="block text-sm font-semibold text-gray-700 mb-1" for="user_ids">
                                        <i class="fas fa-users mr-2 text-cyan-600"></i> {{ __('Assigné(e) à') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <div class="border border-gray-300 rounded-lg p-3 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto"> @foreach ($users as $userItem) {{-- Changed $user to $userItem to avoid conflict with $user in @php --}}
                                            <div class="flex items-center">
                                                <input class="rounded border-gray-300 text-primary-red shadow-sm focus:border-primary-red focus:ring focus:ring-primary-red focus:ring-opacity-50" type="checkbox" name="user_ids[]" value="{{ $userItem->id }}" id="user_{{ $userItem->id }}"
                                                    {{ in_array($userItem->id, old('user_ids', [])) ? 'checked' : '' }}>
                                                <label class="ml-2 text-sm text-gray-800" for="user_{{ $userItem->id }}">
                                                    {{ $userItem->name }} ({{ $userItem->email }})
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('user_ids')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="priorite" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i> {{ __('Priorité') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <select name="priorite" id="priorite"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('priorite') border-primary-red ring-red-200 @enderror" required>
                                        <option value="faible" {{ old('priorite') == 'faible' ? 'selected' : '' }}>{{ __('Information ') }}</option>
                                        <option value="moyen" {{ old('priorite') == 'moyen' ? 'selected' : '' }}>{{ __('Important') }}</option>
                                        <option value="élevé" {{ old('priorite') == 'élevé' ? 'selected' : '' }}>{{ __('Urgent') }}</option>
                                    </select>
                                    @error('priorite')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if ($canCreateRetour && !$isAdmin)
                                <div class="md:col-span-2"> <label for="retour" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-comment-dots mr-2 text-gray-500"></i> {{ __('Retour/Notes (Optionnel)') }}
                                    </label>
                                    <textarea name="retour" id="retour" rows="3"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300
                                        focus:ring-primary-red focus:border-primary-red
                                        @error('retour') border-primary-red ring-red-200 @enderror"
                                        placeholder="{{ __('Ajoutez des notes ou un retour sur la tâche...') }}">{{ old('retour') }}</textarea>
                                    @error('retour')
                                        <p class="text-primary-red text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif
                            </div>

                            <div class="mt-8 flex items-center justify-end space-x-4">
                                <a href="{{ route('taches.index') }}"
                                    class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-full font-bold text-sm text-gray-700 uppercase tracking-wider shadow-md btn-secondary transform hover:scale-105">
                                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Annuler') }}
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red transform hover:scale-105">
                                    <i class="fas fa-save mr-2"></i> {{ __('Enregistrer la Tâche') }}
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
                const removeExistingAudioCheckbox = document.getElementById('remove_existing_audio'); // Checkbox for existing audio (if on edit page)

                let mediaRecorder;
                let audioChunks = [];
                let recordedAudioBlob = null;

                // Function to enable/disable description field based on audio input/state
                function toggleDescriptionField() {
                    if (audioDataInput.value || (audioPlayback.src && audioPlayback.style.display !== 'none')) {
                        descriptionField.disabled = true;
                        descriptionField.value = ''; // Clear text description if audio is present
                    } else {
                        descriptionField.disabled = false;
                        // Restore old description if available and no audio
                        if ("{{ old('description', '') }}" && !audioDataInput.value) {
                             descriptionField.value = "{{ old('description', '') }}";
                        }
                    }
                }

                // Function to enable/disable audio controls based on description field
                function toggleAudioControls() {
                    if (descriptionField.value.trim() !== '') {
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
                    } else {
                        recordButton.disabled = false;
                        if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = false; // Enable if description is empty
                        // Stop/Play/Clear buttons will be handled by recording state below
                        if (!recordedAudioBlob && !audioDataInput.value) { // Only if no current recorded audio
                            playButton.disabled = true;
                            clearButton.disabled = true;
                        }
                    }
                }

                descriptionField.addEventListener('input', toggleAudioControls);

                if (removeExistingAudioCheckbox) {
                    removeExistingAudioCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            descriptionField.disabled = true;
                            descriptionField.value = '';
                            recordButton.disabled = true;
                            // Clear any new recording if old one is being removed
                            audioChunks = [];
                            recordedAudioBlob = null;
                            audioPlayback.src = '';
                            audioPlayback.style.display = 'none';
                            audioDataInput.value = '';
                        } else {
                            toggleDescriptionField(); // Re-evaluate if description should be enabled
                            recordButton.disabled = false; // Enable record if checkbox is unchecked
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

                    try {
                        // Request access to microphone
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        // Prefer opus in webm for better quality and smaller size
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

                            // Convert blob to Base64 to send via hidden input
                            const reader = new FileReader();
                            reader.readAsDataURL(recordedAudioBlob);
                            reader.onloadend = () => {
                                audioDataInput.value = reader.result; // Base64 string
                                toggleDescriptionField(); // Update field states after recording
                            };

                            // Stop microphone stream (important!)
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
                    toggleDescriptionField(); // Re-enable description field
                });

                // Initial state update on page load (for 'create' page, `old` values)
                toggleDescriptionField();
                toggleAudioControls(); // This will disable recording buttons if description is already filled

                // If old audio_data is present (e.g., after validation error)
                if (audioDataInput.value) {
                    // Re-create Blob from Base64 to allow playback
                    const base64_audio = audioDataInput.value;
                    const parts = base64_audio.split(';base64,');
                    const mimeType = parts[0].split(':')[1];
                    const decodedData = atob(parts[1]);
                    const arrayBuffer = new ArrayBuffer(decodedData.length);
                    const uint8Array = new Uint8Array(arrayBuffer);
                    for (let i = 0; i < decodedData.length; i++) {
                        uint8Array[i] = decodedData.charCodeAt(i);
                    }
                    recordedAudioBlob = new Blob([uint8Array], { type: mimeType });
                    audioPlayback.src = URL.createObjectURL(recordedAudioBlob);
                    audioPlayback.style.display = 'block';
                    playButton.disabled = false;
                    clearButton.disabled = false;
                    recordButton.disabled = true; // Disable new recording
                }
            });
        </script>
    </body>
</x-app-layout>