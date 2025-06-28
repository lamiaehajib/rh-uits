```blade
{{-- resources/views/reclamations/edit.blade.php --}}

<x-app-layout>
    
        <div class="flex justify-between items-center px-4 sm:px-0">
            <h2 class="font-bold text-2xl text-gray-900 animate-slide-in-left">
                {{ __('Modifier la réclamation') }} #{{ e($reclamation->reference) }}
            </h2>
            <a href="{{ route('reclamations.show', $reclamation) }}"
               class="inline-flex items-center px-5 py-2.5 bg-gray-600 text-white rounded-full font-semibold text-sm uppercase tracking-wide hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 focus:ring-opacity-50 transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>


    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden transform transition-all duration-500 hover:shadow-3xl animate-fade-in">
                <div class="p-8">
                    <form action="{{ route('reclamations.update', $reclamation) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        @if(auth()->user()->hasRole('Sup_Admin'))
                            <!-- Admin-only form: Only status and admin_notes are editable -->
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-clipboard-check text-red-600 mr-2"></i> Statut *
                                    </label>
                                    <select name="status"
                                            id="status"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-red-600 focus:ring-4 focus:ring-red-100 text-gray-900 transition-all duration-300 @error('status') border-red-500 @enderror"
                                            required>
                                        <option value="pending" {{ old('status', $reclamation->status) === 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="in_progress" {{ old('status', $reclamation->status) === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                        <option value="resolved" {{ old('status', $reclamation->status) === 'resolved' ? 'selected' : '' }}>Résolu</option>
                                        <option value="closed" {{ old('status', $reclamation->status) === 'closed' ? 'selected' : '' }}>Fermé</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-2 text-sm text-red-600 animate-pulse">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="admin_notes" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-sticky-note text-red-600 mr-2"></i> Notes administrateur
                                    </label>
                                    <textarea name="admin_notes"
                                              id="admin_notes"
                                              rows="4"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-red-600 focus:ring-4 focus:ring-red-100 text-gray-900 transition-all duration-300 @error('admin_notes') border-red-500 @enderror"
                                              placeholder="Notes internes pour le suivi...">{{ old('admin_notes', e($reclamation->admin_notes)) }}</textarea>
                                    @error('admin_notes')
                                        <p class="mt-2 text-sm text-red-600 animate-pulse">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Display other fields as read-only for admins -->
                            <div class="mt-8 p-6 bg-gray-50 rounded-xl shadow-inner">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-info-circle text-red-600 mr-2"></i> Détails de la réclamation
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm text-gray-700">
                                    <div>
                                        <span class="font-semibold"><i class="fas fa-heading text-red-600 mr-2"></i>Titre :</span>
                                        {{ e($reclamation->titre) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold"><i class="fas fa-calendar-alt text-red-600 mr-2"></i>Date de l'incident :</span>
                                        {{ \Carbon\Carbon::parse($reclamation->date)->format('d/m/Y') }}
                                    </div>
                                    <div>
                                        <span class="font-semibold"><i class="fas fa-exclamation-circle text-red-600 mr-2"></i>Priorité :</span>
                                        {{ ucfirst($reclamation->priority) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold"><i class="fas fa-tags text-red-600 mr-2"></i>Catégorie :</span>
                                        {{ e($reclamation->category) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold"><i class="fas fa-user text-red-600 mr-2"></i>Utilisateur :</span>
                                        {{ e($reclamation->user->name) }} ({{ e($reclamation->user->email) }})
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="font-semibold"><i class="fas fa-file-alt text-red-600 mr-2"></i>Description :</span>
                                        {{ e($reclamation->description) }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Non-admin form: All fields editable except status and admin_notes -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="titre" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-heading text-red-600 mr-2"></i> Titre de la réclamation *
                                    </label>
                                    <input type="text"
                                           name="titre"
                                           id="titre"
                                           value="{{ old('titre', e($reclamation->titre)) }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-red-600 focus:ring-4 focus:ring-red-100 text-gray-900 transition-all duration-300 @error('titre') border-red-500 @enderror"
                                           required>
                                    @error('titre')
                                        <p class="mt-2 text-sm text-red-600 animate-pulse">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-calendar-alt text-red-600 mr-2"></i> Date de l'incident *
                                    </label>
                                    <input type="date"
                                           name="date"
                                           id="date"
                                           value="{{ old('date', \Carbon\Carbon::parse($reclamation->date)->format('Y-m-d')) }}"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-300 @error('date') border-red-500 @enderror"
                                           required>
                                    @error('date')
                                        <p class="mt-2 text-sm text-red-600 animate-pulse">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="priority" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-exclamation-circle text-red-600 mr-2"></i> Priorité *
                                    </label>
                                    <select name="priority"
                                            id="priority"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-red-600 focus:ring-4 focus:ring-red-100 text-gray-900 transition-all duration-300 @error('priority') border-red-500 @enderror"
                                            required>
                                        <option value="">Sélectionner une priorité</option>
                                        <option value="low" {{ old('priority', $reclamation->priority) === 'low' ? 'selected' : '' }}>Faible</option>
                                        <option value="medium" {{ old('priority', $reclamation->priority) === 'medium' ? 'selected' : '' }}>Moyenne</option>
                                        <option value="high" {{ old('priority', $reclamation->priority) === 'high' ? 'selected' : '' }}>Haute</option>
                                    </select>
                                    @error('priority')
                                        <p class="mt-2 text-sm text-red-600 animate-pulse">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="category" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-tags text-red-600 mr-2"></i> Catégorie *
                                    </label>
                                    <select name="category"
                                            id="category"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-red-600 focus:ring-4 focus:ring-red-100 text-gray-900 transition-all duration-300 @error('category') border-red-500 @enderror"
                                            required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="Service Client" {{ old('category', $reclamation->category) === 'Service Client' ? 'selected' : '' }}>Service Client</option>
                                        <option value="Technique" {{ old('category', $reclamation->category) === 'Technique' ? 'selected' : '' }}>Technique</option>
                                        <option value="Facturation" {{ old('category', $reclamation->category) === 'Facturation' ? 'selected' : '' }}>Facturation</option>
                                        <option value="Livraison" {{ old('category', $reclamation->category) === 'Livraison' ? 'selected' : '' }}>Livraison</option>
                                        <option value="Qualité" {{ old('category', $reclamation->category) === 'Qualité' ? 'selected' : '' }}>Qualité</option>
                                        <option value="Autre" {{ old('category', $reclamation->category) === 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('category')
                                        <p class="mt-2 text-sm text-red-600 animate-pulse">{{ $message }}</p>
                                    @enderror
                                </div>

                                <input type="hidden" name="iduser" value="{{ $reclamation->iduser }}">
                                <input type="hidden" name="status" value="{{ $reclamation->status }}">

                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-file-alt text-red-600 mr-2"></i> Description détaillée *
                                    </label>
                                    <textarea name="description"
                                              id="description"
                                              rows="6"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-red-600 focus:ring-4 focus:ring-red-100 text-gray-900 transition-all duration-300 @error('description') border-red-500 @enderror"
                                              placeholder="Décrivez votre réclamation en détail..."
                                              required>{{ old('description', e($reclamation->description)) }}</textarea>
                                    @error('description')
                                        <p class="mt-2 text-sm text-red-600 animate-pulse">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        @if($reclamation->attachments)
                            @php
                                $attachments = json_decode($reclamation->attachments, true);
                                $attachments = is_array($attachments) ? $attachments : [];
                            @endphp
                            @if(!empty($attachments))
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <i class="fas fa-paperclip text-red-600 mr-2"></i> Pièces jointes existantes
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($attachments as $index => $attachment)
                                            <div class="flex items-center p-4 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                                                <div class="flex-shrink-0 text-2xl">
                                                    @if(in_array(strtolower(pathinfo($attachment['name'] ?? '', PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                                        <i class="fas fa-image text-red-600"></i>
                                                    @elseif(in_array(strtolower(pathinfo($attachment['name'] ?? '', PATHINFO_EXTENSION)), ['pdf']))
                                                        <i class="fas fa-file-pdf text-gray-600"></i>
                                                    @elseif(in_array(strtolower(pathinfo($attachment['name'] ?? '', PATHINFO_EXTENSION)), ['doc', 'docx']))
                                                        <i class="fas fa-file-word text-gray-600"></i>
                                                    @else
                                                        <i class="fas fa-file text-gray-600"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-4 flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ e($attachment['name'] ?? 'Unknown') }}</p>
                                                    <p class="text-xs text-gray-500">{{ number_format(($attachment['size'] ?? 0) / 1024, 2) }} KB</p>
                                                </div>
                                                <a href="{{ route('reclamations.downloadAttachment', [$reclamation, $index]) }}"
                                                   class="ml-4 inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-full text-sm font-medium hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition-all duration-300 transform hover:scale-105">
                                                    <i class="fas fa-download mr-2"></i> Télécharger
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="mt-3 text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                                        La modification des pièces jointes existantes n'est pas supportée. Pour ajouter de nouveaux fichiers, créez une nouvelle réclamation.
                                    </p>
                                </div>
                            @endif
                        @endif

                        <div class="mt-8 p-6 bg-gray-50 rounded-xl shadow-inner">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-red-600 mr-2"></i> Informations système
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-sm text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag text-red-600 mr-2"></i>
                                    <div>
                                        <span class="font-semibold">Référence :</span> {{ e($reclamation->reference) }}
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus text-red-600 mr-2"></i>
                                    <div>
                                        <span class="font-semibold">Créé le :</span> {{ $reclamation->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-check text-red-600 mr-2"></i>
                                    <div>
                                        <span class="font-semibold">Modifié le :</span> {{ $reclamation->updated_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                                @if($reclamation->resolved_at)
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-red-600 mr-2"></i>
                                        <div>
                                            <span class="font-semibold">Résolu le :</span>
                                            {{ \Carbon\Carbon::parse($reclamation->resolved_at)->format('d/m/Y à H:i') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold text-sm rounded-full uppercase tracking-wide hover:bg-red-700 focus:ring-4 focus:ring-red-300 disabled:opacity-50 transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes slide-in-left {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .animate-slide-in-left {
            animation: slide-in-left 0.5s ease-out;
        }

        .animate-fade-in {
            animation: fade-in 0.5s ease-out forwards;
        }

        .animate-pulse {
            animation: pulse 1.5s infinite;
        }
    </style>
</x-app-layout>
```