<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier la Tâche</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: { 'primary-red': '#D32F2F' },
                        fontFamily: { sans: ['Inter', 'sans-serif'] }
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <style>
            body { font-family: 'Inter', sans-serif; }
            .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; opacity: 0; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
            .animate-pulse-subtle { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
            .btn-primary-red { background: linear-gradient(to right, #D32F2F, #B71C1C); box-shadow: 0 4px 10px rgba(211,47,47,0.3); transition: all 0.3s ease; }
            .btn-primary-red:hover { background: linear-gradient(to right, #B71C1C, #D32F2F); box-shadow: 0 6px 15px rgba(211,47,47,0.4); transform: translateY(-2px); }
            .btn-secondary { background-color: #e5e7eb; color: #4b5563; transition: all 0.3s ease; }
            .btn-secondary:hover { background-color: #d1d5db; transform: translateY(-2px); }
            input:focus, textarea:focus, select:focus { transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out; border-color: #D32F2F; box-shadow: 0 0 0 3px rgba(211,47,47,0.2); }
            button:disabled { opacity: 0.5; cursor: not-allowed; }
            /* ✅ Bannière tâche annulée */
            .annule-banner { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; padding: 16px 24px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-weight: 600; }
        </style>
    </head>
    <body>
        <x-slot name="header">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center border-b-2 border-primary-red pb-3 animate-fade-in delay-100">
                <i class="fas fa-edit text-primary-red mr-3"></i>
                {{ __('Modifier la Tâche') }}
                <span class="ml-3 px-4 py-1 bg-primary-red text-white rounded-full text-base font-bold shadow-md">#{{ $tache->id }}</span>
                {{-- ✅ Badge annulé dans le header --}}
                @if($tache->status == 'annulé')
                    <span class="ml-3 px-4 py-1 bg-gray-500 text-white rounded-full text-base font-bold shadow-md"><i class="fas fa-ban mr-1"></i> Annulée</span>
                @endif
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
                                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';"><svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg></span>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 shadow-md animate-fade-in animate-pulse-subtle" role="alert">
                                <strong class="font-bold">Erreur!</strong>
                                <span class="block sm:inline">{{ session('error') }}</span>
                                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';"><svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.65a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg></span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 p-6 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md animate-fade-in animate-pulse-subtle">
                                <strong class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i> {{ __('Oups !') }}</strong>
                                <ul class="mt-3 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        @php
                            $user = auth()->user();
                            $isAdminOrAdmin1 = $user->hasAnyRole(['Sup_Admin', 'Custom_Admin']);
                            $canModifyRetourRoles = ['USER_MULTIMEDIA', 'USER_TRAINING', 'Sales_Admin', 'USER_TECH', 'Custom_Admin'];
                            $canEditRetour = $user->hasAnyRole($canModifyRetourRoles);
                            // ✅ Tâche annulée : non-admin ne peut rien modifier
                            $tacheAnnulee = $tache->status == 'annulé';
                            $blockedByAnnulation = $tacheAnnulee && !$isAdminOrAdmin1;
                        @endphp

                        {{-- ✅ Bannière si tâche annulée --}}
                        @if($tacheAnnulee)
                            <div class="annule-banner">
                                <i class="fas fa-ban text-2xl"></i>
                                <div>
                                    <span class="text-lg">Cette tâche est annulée.</span>
                                    @if(!$isAdminOrAdmin1)
                                        <span class="block text-sm font-normal opacity-80">Vous ne pouvez pas modifier une tâche annulée.</span>
                                    @else
                                        <span class="block text-sm font-normal opacity-80">En tant qu'administrateur, vous pouvez modifier ou réactiver cette tâche.</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('taches.update', $tache->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            @foreach ($filterParams as $key => $value)
                                @if ($key === 'status')
                                    <input type="hidden" name="original_status_filter" value="{{ $value }}">
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label for="titre" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-heading mr-2 text-indigo-500"></i> {{ __('Titre de la Tâche') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <input type="text" name="titre" id="titre"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300 focus:ring-primary-red focus:border-primary-red @error('titre') border-primary-red ring-red-200 @enderror {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        value="{{ old('titre', $tache->titre) }}"
                                        {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'readonly' : '' }} required>
                                    @error('titre')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-file-alt mr-2 text-blue-500"></i> {{ __('Description (Texte)') }}
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300 focus:ring-primary-red focus:border-primary-red @error('description') border-primary-red ring-red-200 @enderror {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'readonly' : '' }}
                                    >{{ old('description', ($tache->description === '-' && $tache->audio_description_path) ? '' : $tache->description) }}</textarea>
                                    @error('description')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-microphone mr-2 text-purple-500"></i> {{ __('Ou Enregistrer une Description Audio') }}
                                    </label>
                                    <div class="flex flex-wrap items-center space-x-2 mt-1">
                                        <button type="button" id="recordButton" class="px-4 py-2 rounded-lg text-sm font-semibold btn-primary-red text-white"><i class="fas fa-microphone mr-1"></i> Démarrer</button>
                                        <button type="button" id="stopButton" class="px-4 py-2 rounded-lg text-sm font-semibold bg-red-500 text-white"><i class="fas fa-stop mr-1"></i> Arrêter</button>
                                        <button type="button" id="playButton" class="px-4 py-2 rounded-lg text-sm font-semibold bg-green-500 text-white"><i class="fas fa-play mr-1"></i> Écouter</button>
                                        <button type="button" id="clearButton" class="px-4 py-2 rounded-lg text-sm font-semibold bg-yellow-500 text-white"><i class="fas fa-trash-alt mr-1"></i> Effacer</button>
                                    </div>
                                    <audio id="audioPlayback" controls class="w-full mt-4" style="display: none;"></audio>
                                    <input type="hidden" name="audio_data" id="audioDataInput" value="{{ old('audio_data') }}">
                                    @error('audio_data')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror

                                    @if($tache->audio_description_path)
                                        <p class="mt-4 text-gray-700">Fichier audio actuel:</p>
                                        <audio src="{{ Storage::disk('public')->url($tache->audio_description_path) }}" controls class="w-full mt-2"></audio>
                                        <div class="flex items-center mt-2 text-gray-600">
                                            <input type="checkbox" id="remove_existing_audio" name="remove_existing_audio" value="1" class="rounded border-gray-300 text-primary-red shadow-sm">
                                            <label for="remove_existing_audio" class="ml-2 text-sm">{{ __('Supprimer l\'audio actuel') }}</label>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label for="duree" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-hourglass-half mr-2 text-green-500"></i> {{ __('Durée Estimée') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <input type="text" name="duree" id="duree"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300 focus:ring-primary-red focus:border-primary-red @error('duree') border-primary-red ring-red-200 @enderror {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        value="{{ old('duree', $tache->duree) }}"
                                        {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'readonly' : '' }} required>
                                    @error('duree')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror
                                    @if (!$isAdminOrAdmin1)
                                        <input type="hidden" name="duree" value="{{ old('duree', $tache->duree) }}">
                                    @endif
                                </div>

                                <div>
                                    <label for="datedebut" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i> {{ __('Date de Début') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <input type="date" name="datedebut" id="datedebut"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300 focus:ring-primary-red focus:border-primary-red @error('datedebut') border-primary-red ring-red-200 @enderror {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        value="{{ old('datedebut', \Carbon\Carbon::parse($tache->datedebut)->format('Y-m-d')) }}"
                                        {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'readonly' : '' }} required>
                                    @error('datedebut')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div id="heuredebutContainer" style="display: none;">
                                    <label for="heuredebut" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-clock mr-2 text-blue-500"></i> {{ __('Heure de Début') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <input type="time" name="heuredebut" id="heuredebut"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300 focus:ring-primary-red focus:border-primary-red @error('heuredebut') border-primary-red ring-red-200 @enderror {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        value="{{ old('heuredebut', $tache->heuredebut) }}"
                                        {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'readonly' : '' }}>
                                    @error('heuredebut')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror
                                    @if (!$isAdminOrAdmin1)
                                        <input type="hidden" name="heuredebut" value="{{ old('heuredebut', $tache->heuredebut) }}">
                                    @endif
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-info-circle mr-2 text-yellow-500"></i> {{ __('Statut de la Tâche') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <select name="status" id="status"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300 focus:ring-primary-red focus:border-primary-red @error('status') border-primary-red ring-red-200 @enderror {{ $blockedByAnnulation ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ $blockedByAnnulation ? 'disabled' : '' }} required>
                                        <option value="nouveau" {{ old('status', $tache->status) == 'nouveau' ? 'selected' : '' }}>{{ __('Nouveau') }}</option>
                                        <option value="en cours" {{ old('status', $tache->status) == 'en cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                        <option value="termine" {{ old('status', $tache->status) == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                                        {{-- ✅ AJOUT : Annulé visible aux admins + affiché si la tâche est déjà annulée --}}
                                        @if($isAdminOrAdmin1 || $tache->status == 'annulé')
                                            <option value="annulé" {{ old('status', $tache->status) == 'annulé' ? 'selected' : '' }}>{{ __('Annulé') }}</option>
                                        @endif
                                    </select>
                                    @error('status')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror
                                    {{-- Si désactivé, envoyer valeur via hidden --}}
                                    @if($blockedByAnnulation)
                                        <input type="hidden" name="status" value="{{ $tache->status }}">
                                    @endif
                                </div>

                                <div>
                                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-clock mr-2 text-orange-500"></i> {{ __('Type de Planification') }} <span class="text-primary-red text-lg">*</span>
                                    </label>
                                    <select name="date" id="date"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300 focus:ring-primary-red focus:border-primary-red @error('date') border-primary-red ring-red-200 @enderror {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'disabled' : '' }} required>
                                        <option value="minute" {{ old('date', $tache->date) == 'minute' ? 'selected' : '' }}>{{ __('Minute') }}</option>
                                        <option value="heure" {{ old('date', $tache->date) == 'heure' ? 'selected' : '' }}>{{ __('Heure') }}</option>
                                        <option value="jour" {{ old('date', $tache->date) == 'jour' ? 'selected' : '' }}>{{ __('Journalier') }}</option>
                                        <option value="semaine" {{ old('date', $tache->date) == 'semaine' ? 'selected' : '' }}>{{ __('Hebdomadaire') }}</option>
                                        <option value="mois" {{ old('date', $tache->date) == 'mois' ? 'selected' : '' }}>{{ __('Mensuel') }}</option>
                                    </select>
                                    @error('date')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror
                                    @if (!$isAdminOrAdmin1)
                                        <input type="hidden" name="date" value="{{ old('date', $tache->date) }}">
                                    @endif
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label" for="user_ids">Assigné(e) <span class="text-danger">*</span></label>
                                    <div class="border rounded p-3">
                                        @foreach ($users as $userItem)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $userItem->id }}" id="user_{{ $userItem->id }}"
                                                    {{ in_array($userItem->id, old('user_ids', $tache->users->pluck('id')->toArray())) ? 'checked' : '' }}
                                                    {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="user_{{ $userItem->id }}">{{ $userItem->name }} ({{ $userItem->email }})</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('user_ids')<div class="text-danger mt-2">{{ $message }}</div>@enderror
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
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300 focus:ring-primary-red focus:border-primary-red @error('priorite') border-primary-red ring-red-200 @enderror {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ (!$isAdminOrAdmin1 || $blockedByAnnulation) ? 'disabled' : '' }} required>
                                        <option value="faible" {{ old('priorite', $tache->priorite) == 'faible' ? 'selected' : '' }}>{{ __('Faible') }}</option>
                                        <option value="moyen" {{ old('priorite', $tache->priorite) == 'moyen' ? 'selected' : '' }}>{{ __('Moyen') }}</option>
                                        <option value="élevé" {{ old('priorite', $tache->priorite) == 'élevé' ? 'selected' : '' }}>{{ __('Élevé') }}</option>
                                    </select>
                                    @error('priorite')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror
                                    @if (!$isAdminOrAdmin1)
                                        <input type="hidden" name="priorite" value="{{ old('priorite', $tache->priorite) }}">
                                    @endif
                                </div>

                                <div class="md:col-span-2">
                                    <label for="retour" class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-comment-dots mr-2 text-gray-500"></i> {{ __('Retour/Notes (Optionnel)') }}
                                    </label>
                                    <textarea name="retour" id="retour" rows="3"
                                        class="mt-1 block w-full px-4 py-2 text-gray-800 rounded-lg shadow-sm border-gray-300 focus:ring-primary-red focus:border-primary-red @error('retour') border-primary-red ring-red-200 @enderror {{ (!$isAdminOrAdmin1 && !$canEditRetour || $blockedByAnnulation) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ (!$isAdminOrAdmin1 && !$canEditRetour || $blockedByAnnulation) ? 'readonly' : '' }}
                                        placeholder="{{ __('Ajoutez des notes ou un retour...') }}"
                                    >{{ old('retour', $tache->retour) }}</textarea>
                                    @error('retour')<p class="text-primary-red text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="mt-8 flex items-center justify-end space-x-4">
                                <a href="{{ route('taches.index', $filterParams) }}" class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-full font-bold text-sm text-gray-700 uppercase tracking-wider shadow-md btn-secondary transform hover:scale-105">
                                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Annuler') }}
                                </a>
                                {{-- ✅ Bouton save désactivé si non-admin et tâche annulée --}}
                                @if(!$blockedByAnnulation)
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary-red border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-wider shadow-lg btn-primary-red transform hover:scale-105">
                                        <i class="fas fa-save mr-2"></i> {{ __('Mettre à Jour la Tâche') }}
                                    </button>
                                @else
                                    <span class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-full font-bold text-sm text-gray-500 uppercase tracking-wider cursor-not-allowed">
                                        <i class="fas fa-ban mr-2"></i> {{ __('Tâche Annulée') }}
                                    </span>
                                @endif
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
                const audioDataInput = document.getElementById('audioDataInput');
                const removeExistingAudioCheckbox = document.getElementById('remove_existing_audio');
                let mediaRecorder, audioChunks = [], recordedAudioBlob = null;

                const hasInitialAudio = {{ $tache->audio_description_path ? 'true' : 'false' }};
                const hasInitialDescription = {{ ($tache->description && $tache->description !== '-') ? 'true' : 'false' }};
                const isAdmin = {{ $isAdminOrAdmin1 ? 'true' : 'false' }};
                // ✅ Bloquer tout si tâche annulée et non-admin
                const blockedByAnnulation = {{ $blockedByAnnulation ? 'true' : 'false' }};

                function toggleDescriptionField() {
                    if (!isAdmin || blockedByAnnulation) { descriptionField.disabled = true; return; }
                    if (audioDataInput.value || (removeExistingAudioCheckbox && removeExistingAudioCheckbox.checked) || (hasInitialAudio && !(removeExistingAudioCheckbox && removeExistingAudioCheckbox.checked))) {
                        descriptionField.disabled = true; descriptionField.value = '';
                    } else {
                        descriptionField.disabled = false;
                        if (hasInitialDescription && !audioDataInput.value) descriptionField.value = "{{ old('description', ($tache->description === '-' && $tache->audio_description_path) ? '' : $tache->description) }}";
                    }
                }

                function toggleAudioControls() {
                    if (!isAdmin || blockedByAnnulation) {
                        recordButton.disabled = true; stopButton.disabled = true; playButton.disabled = true; clearButton.disabled = true;
                        if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = true;
                        return;
                    }
                    if (descriptionField.value.trim() !== '' && descriptionField.value.trim() !== '-') {
                        recordButton.disabled = true; stopButton.disabled = true; playButton.disabled = true; clearButton.disabled = true;
                        if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = true;
                        audioChunks = []; recordedAudioBlob = null; audioPlayback.src = ''; audioPlayback.style.display = 'none'; audioDataInput.value = '';
                    } else {
                        recordButton.disabled = false;
                        if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = false;
                        if (!recordedAudioBlob && !audioDataInput.value && !hasInitialAudio) { playButton.disabled = true; clearButton.disabled = true; }
                        else { playButton.disabled = false; clearButton.disabled = false; }
                    }
                }

                descriptionField.addEventListener('input', toggleAudioControls);

                if (removeExistingAudioCheckbox) {
                    removeExistingAudioCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            descriptionField.disabled = true; descriptionField.value = ''; recordButton.disabled = true;
                            audioChunks = []; recordedAudioBlob = null; audioPlayback.src = ''; audioPlayback.style.display = 'none'; audioDataInput.value = '';
                            playButton.disabled = true; clearButton.disabled = true;
                        } else { toggleDescriptionField(); toggleAudioControls(); }
                    });
                }

                recordButton.addEventListener('click', async () => {
                    recordedAudioBlob = null; audioChunks = []; audioPlayback.style.display = 'none'; audioPlayback.src = ''; audioDataInput.value = '';
                    playButton.disabled = true; clearButton.disabled = true; stopButton.disabled = true;
                    if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = true;
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm;codecs=opus' });
                        mediaRecorder.ondataavailable = e => { if (e.data.size > 0) audioChunks.push(e.data); };
                        mediaRecorder.onstop = () => {
                            recordedAudioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                            audioPlayback.src = URL.createObjectURL(recordedAudioBlob);
                            audioPlayback.style.display = 'block'; playButton.disabled = false; clearButton.disabled = false;
                            const reader = new FileReader();
                            reader.readAsDataURL(recordedAudioBlob);
                            reader.onloadend = () => { audioDataInput.value = reader.result; toggleDescriptionField(); };
                            stream.getTracks().forEach(t => t.stop());
                        };
                        mediaRecorder.start(); recordButton.disabled = true; stopButton.disabled = false; toggleDescriptionField();
                    } catch (err) {
                        alert('Impossible d\'accéder au microphone.');
                        recordButton.disabled = false; stopButton.disabled = true;
                        if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = false;
                        toggleDescriptionField();
                    }
                });

                stopButton.addEventListener('click', () => { if (mediaRecorder && mediaRecorder.state === 'recording') { mediaRecorder.stop(); recordButton.disabled = false; stopButton.disabled = true; } });
                playButton.addEventListener('click', () => { if (audioPlayback.src) audioPlayback.play(); });
                clearButton.addEventListener('click', () => {
                    audioChunks = []; recordedAudioBlob = null; audioPlayback.src = ''; audioPlayback.style.display = 'none'; audioDataInput.value = '';
                    playButton.disabled = true; clearButton.disabled = true; recordButton.disabled = false;
                    if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = false;
                    toggleDescriptionField();
                });

                if (hasInitialAudio) {
                    audioPlayback.src = "{{ Storage::disk('public')->url($tache->audio_description_path ?? '') }}";
                    audioPlayback.style.display = 'block'; playButton.disabled = false; clearButton.disabled = false;
                    recordButton.disabled = true; descriptionField.disabled = true;
                } else if (hasInitialDescription) {
                    recordButton.disabled = true; stopButton.disabled = true; playButton.disabled = true; clearButton.disabled = true;
                } else {
                    recordButton.disabled = false; descriptionField.disabled = false;
                }

                if (!isAdmin || blockedByAnnulation) {
                    descriptionField.disabled = true; recordButton.disabled = true; stopButton.disabled = true; playButton.disabled = true; clearButton.disabled = true;
                    if (removeExistingAudioCheckbox) removeExistingAudioCheckbox.disabled = true;
                } else {
                    toggleDescriptionField(); toggleAudioControls();
                }

                // Toggle heure début
                const dateSelect = document.getElementById('date');
                const heuredebutContainer = document.getElementById('heuredebutContainer');
                const heuredebutInput = document.getElementById('heuredebut');
               function toggleHeureDebut() {
    const val = dateSelect.value;
    if (val === 'heure' || val === 'minute') {
        heuredebutContainer.style.display = 'block';
        heuredebutInput.required = true;
        heuredebutInput.disabled = false; 
    } else {
        heuredebutContainer.style.display = 'none';
        heuredebutInput.required = false;
        heuredebutInput.value = '';
        heuredebutInput.disabled = true; // ✅ désactiver = ne sera pas envoyé
    }
}
                dateSelect.addEventListener('change', toggleHeureDebut);
                toggleHeureDebut();
            });
        </script>
    </body>
</x-app-layout>