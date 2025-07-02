<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Modifier l\'Objectif') }}</title>
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
            /* Custom Styles for the D32F2F color and animations */
            body {
                font-family: 'Inter', sans-serif;
            }

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

            /* Input group styling with icons */
            .input-group {
                display: flex;
                align-items: center;
                border: 1px solid #d1d5db; /* gray-300 */
                border-radius: 0.375rem; /* rounded-md */
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
                transition: all 0.2s ease-in-out;
            }

            .input-group:focus-within {
                border-color: #D32F2F; /* primary-red */
                box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.2); /* ring-primary-red with opacity */
            }

            .input-group-text {
                padding: 0.75rem; /* p-3 */
                background-color: #f9fafb; /* gray-50 */
                border-right: 1px solid #d1d5db; /* gray-300 */
                border-top-left-radius: 0.375rem;
                border-bottom-left-radius: 0.375rem;
            }

            .input-group-text i {
                color: #D32F2F; /* primary-red */
                transition: transform 0.3s ease;
            }

            .input-group:focus-within .input-group-text i {
                transform: scale(1.1); /* Slight grow on focus */
            }

            .form-control-custom {
                flex: 1 1 auto;
                width: 1%; /* Occupy remaining width */
                min-width: 0;
                padding: 0.75rem; /* p-3 */
                border: none;
                background-color: transparent;
                outline: none;
                font-size: 0.875rem; /* sm:text-sm */
                line-height: 1.25rem;
                border-radius: 0.375rem; /* rounded-md */
            }

            .form-control-custom:focus {
                outline: none;
                box-shadow: none;
            }

            .form-textarea-custom {
                @apply block w-full px-3 py-2 text-base text-gray-700 placeholder-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm;
            }

            .form-select-custom {
                @apply block w-full px-3 py-2 text-base text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm;
            }

            /* Error message styling */
            .text-danger {
                color: #dc3545; /* Bootstrap's red for danger */
            }
            .invalid-feedback {
                color: #dc3545;
                font-size: 0.75rem;
                margin-top: 0.25rem;
            }

            /* Animations from the original objectif blade (keeping them if desired) */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
            }

            @keyframes bounceIn {
                0% { opacity: 0; transform: scale(0.8); }
                60% { opacity: 1; transform: scale(1.05); }
                80% { transform: scale(0.98); }
                100% { transform: scale(1); }
            }
            .animate-bounce-in {
                animation: bounceIn 0.6s ease-out forwards;
            }

            /* New Animations for Timeline/Calendar */
            @keyframes slideInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-slide-in-up {
                animation: slideInUp 0.6s ease-out forwards;
            }

            @keyframes zoomIn {
                from { opacity: 0; transform: scale(0.95); }
                to { opacity: 1; transform: scale(1); }
            }
            .animate-zoom-in {
                animation: zoomIn 0.7s ease-out forwards;
                animation-delay: 0.2s; /* Delay to appear after main content */
                opacity: 0;
            }

            @keyframes popIn {
                0% { opacity: 0; transform: scale(0.5); }
                70% { opacity: 1; transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            .animate-pop-in-start {
                animation: popIn 0.5s ease-out forwards;
                animation-delay: 0.8s;
                opacity: 0;
            }
            .animate-pop-in-mid {
                animation: popIn 0.5s ease-out forwards;
                animation-delay: 1.1s;
                opacity: 0;
            }
            .animate-pop-in-end {
                animation: popIn 0.5s ease-out forwards;
                animation-delay: 1.4s;
                opacity: 0;
            }

            @keyframes pulseFade {
                0% { opacity: 0.5; transform: scale(0.8); }
                50% { opacity: 1; transform: scale(1); }
                100% { opacity: 0.5; transform: scale(0.8); }
            }
            .animate-pulse-fade {
                animation: pulseFade 2s infinite ease-in-out;
            }

            /* Button styles adapted from create/users and primary-red */
            .btn-custom-primary {
                @apply inline-flex items-center px-4 py-2 bg-primary-red border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-md hover:bg-[#B71C1C] focus:outline-none focus:border-primary-red focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105;
            }

            .btn-custom-secondary {
                @apply inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest shadow-sm hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105;
            }

            /* Form input/textarea styles */
            .form-detail-display {
                @apply block w-full px-3 py-2 text-base text-gray-800 bg-gray-100 border border-gray-200 rounded-md shadow-inner;
            }
        </style>
    </head>
    <body>
        <x-slot name="header">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight border-b-2 border-primary-red pb-3 mb-6 animate-fade-in delay-100">
                <i class="fas fa-bullseye mr-3 text-primary-red"></i> {{ __('Modifier l\'Objectif') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg card-hover-scale animate-fade-in">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-primary-red">Modifier l'Objectif: {{ Str::limit($objectif->description, 30) }}</h3>
                            <a href="{{ route('objectifs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 btn-outline-primary-custom">
                                <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                            </a>
                        </div>

                        <hr class="my-6 border-gray-200">

                        <form action="{{ route('objectifs.update', $objectif->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Date --}}
                                <div class="col-span-1">
                                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" name="date" id="date" class="form-control-custom @error('date') border-red-500 @enderror" value="{{ old('date', $objectif->date) }}" required>
                                    </div>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Type --}}
                                <div class="col-span-1">
                                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        <select name="type" id="type" class="form-select-custom @error('type') border-red-500 @enderror" required>
                                            <option value="">Sélectionner un type</option>
                                            <option value="formations" {{ old('type', $objectif->type) == 'formations' ? 'selected' : '' }}>Formations</option>
                                            <option value="projets" {{ old('type', $objectif->type) == 'projets' ? 'selected' : '' }}>Projets</option>
                                            <option value="vente" {{ old('type', $objectif->type) == 'vente' ? 'selected' : '' }}>Vente</option>
                                        </select>
                                    </div>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="col-span-1 md:col-span-2">
                                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                        <textarea name="description" id="description" rows="4" class="form-control-custom form-textarea-custom @error('description') border-red-500 @enderror" required>{{ old('description', $objectif->description) }}</textarea>
                                    </div>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- CA --}}
                                <div class="col-span-1">
                                    <label for="ca" class="block text-gray-700 text-sm font-bold mb-2">CA: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        <input type="text" name="ca" id="ca" class="form-control-custom @error('ca') border-red-500 @enderror" value="{{ old('ca', $objectif->ca) }}" required>
                                    </div>
                                    @error('ca')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Durée (combined input) --}}
                                <div class="col-span-1">
                                    <label for="duree_value" class="block text-gray-700 text-sm font-bold mb-2">Durée:</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                            <i class="fas fa-calendar-days"></i>
                                        </span>
                                        <input type="number" name="duree_value" id="duree_value" value="{{ old('duree_value', $objectif->duree_value) }}" min="1"
                                            class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none @error('duree_value') border-red-500 @enderror focus:outline-none focus:ring-primary-red focus:border-primary-red">

                                        <select name="duree_type" id="duree_type"
                                            class="block px-3 py-2 border border-gray-300 rounded-r-md shadow-sm @error('duree_type') border-red-500 @enderror focus:outline-none focus:ring-primary-red focus:border-primary-red">
                                            <option value="">Unité</option>
                                            <option value="jours" {{ old('duree_type', $objectif->duree_type) == 'jours' ? 'selected' : '' }}>Jours</option>
                                            <option value="semaines" {{ old('duree_type', $objectif->duree_type) == 'semaines' ? 'selected' : '' }}>Semaines</option>
                                            <option value="mois" {{ old('duree_type', $objectif->duree_type) == 'mois' ? 'selected' : '' }}>Mois</option>
                                            <option value="annee" {{ old('duree_type', $objectif->duree_type) == 'annee' ? 'selected' : '' }}>Année</option>
                                        </select>
                                    </div>
                                    @error('duree_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('duree_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- À Faire --}}
                                <div class="col-span-1 md:col-span-2">
                                    <label for="afaire" class="block text-gray-700 text-sm font-bold mb-2">À Faire: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                        <textarea name="afaire" id="afaire" rows="4" class="form-control-custom form-textarea-custom @error('afaire') border-red-500 @enderror" required>{{ old('afaire', $objectif->afaire) }}</textarea>
                                    </div>
                                    @error('afaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Assigner à l'utilisateur --}}
                                <div class="col-span-1">
                                    <label for="iduser" class="block text-gray-700 text-sm font-bold mb-2">Assigner à l'utilisateur: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <select name="iduser" id="iduser" class="form-select-custom @error('iduser') border-red-500 @enderror" required>
                                            <option value="">Sélectionner un utilisateur</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('iduser', $objectif->iduser) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('iduser')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Progression (manuelle, 0-100%) --}}
                                <div class="col-span-1">
                                    <label for="progress" class="block text-gray-700 text-sm font-bold mb-2">Progression (manuelle, 0-100%):</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                        <input type="number" name="progress" id="progress" min="0" max="100" class="form-control-custom @error('progress') border-red-500 @enderror" value="{{ old('progress', $objectif->progress) }}">
                                    </div>
                                    @error('progress')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Explication pour incomplet (si nécessaire) --}}
                                <div class="col-span-1 md:col-span-2">
                                    <label for="explanation_for_incomplete" class="block text-gray-700 text-sm font-bold mb-2">Explication si incomplet:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
                                        <textarea name="explanation_for_incomplete" id="explanation_for_incomplete" rows="3" class="form-control-custom form-textarea-custom @error('explanation_for_incomplete') border-red-500 @enderror">{{ old('explanation_for_incomplete', $objectif->explanation_for_incomplete ?? '') }}</textarea>
                                    </div>
                                    @error('explanation_for_incomplete')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6 space-x-3">
                                <button type="submit" class="inline-flex items-center px-6 py-3 btn-primary-custom rounded-md font-bold text-sm text-white uppercase tracking-wider shadow-lg">
                                    <i class="fas fa-save mr-2"></i> Mettre à jour l'Objectif
                                </button>
                                <a href="{{ route('objectifs.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 border border-gray-300 rounded-md font-bold text-sm text-gray-700 uppercase tracking-wider shadow-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 btn-outline-primary-custom">
                                    <i class="fas fa-times-circle mr-2"></i> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>
