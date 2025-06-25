<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Créer un Nouveau Projet') }}</title>
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

            /* Subtle hover effect for cards */
            .card-hover-scale {
                transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            }

            .card-hover-scale:hover {
                transform: translateY(-5px) scale(1.005); /* Slightly less scale for form */
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            /* Input group styling with icons */
            .input-group-custom {
                display: flex;
                align-items: center;
                border: 1px solid #d1d5db; /* gray-300 */
                border-radius: 0.375rem; /* rounded-md */
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
                transition: all 0.2s ease-in-out;
                width: 100%; /* Ensure it takes full width of parent */
            }

            .input-group-custom:focus-within {
                border-color: #D32F2F; /* primary-red */
                box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.2); /* ring-primary-red with opacity */
            }

            .input-group-text-custom {
                padding: 0.75rem; /* p-3 */
                background-color: #f9fafb; /* gray-50 */
                border-right: 1px solid #d1d5db; /* gray-300 */
                border-top-left-radius: 0.375rem;
                border-bottom-left-radius: 0.375rem;
            }

            .input-group-text-custom i {
                color: #D32F2F; /* primary-red */
                transition: transform 0.3s ease;
            }

            .input-group-custom:focus-within .input-group-text-custom i {
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
                @apply block w-full px-3 py-2 text-base text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-red focus:border-primary-red sm:text-sm;
            }

            /* Error message styling */
            .text-danger-custom {
                color: #dc3545; /* Bootstrap's red for danger */
            }
            .invalid-feedback-custom {
                color: #dc3545;
                font-size: 0.75rem;
                margin-top: 0.25rem;
            }

            /* General animations */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
            }

            /* Custom styles for checkbox buttons */
            .checkbox-button-group {
                display: flex;
                flex-wrap: wrap; /* Allow items to wrap to the next line */
                gap: 0.5rem; /* Space between buttons */
            }

            .checkbox-button {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem 1rem;
                border-radius: 9999px; /* Fully rounded */
                border: 1px solid #d1d5db; /* gray-300 */
                background-color: #f9fafb; /* gray-50 */
                color: #4b5563; /* gray-700 */
                font-weight: 600; /* semi-bold */
                cursor: pointer;
                transition: all 0.2s ease-in-out;
            }

            .checkbox-button:hover {
                background-color: #e5e7eb; /* gray-100 */
                border-color: #9ca3af; /* gray-400 */
            }

            .checkbox-button input[type="checkbox"] {
                display: none; /* Hide the actual checkbox */
            }

            .checkbox-button input[type="checkbox"]:checked + span {
                background-color: #C2185B; /* secondary-purple */
                color: white;
                border-color: #C2185B;
                box-shadow: 0 2px 5px rgba(194, 24, 91, 0.2);
            }

            .checkbox-button span {
                /* This span acts as the visual part of the button */
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                border-radius: 9999px; /* Make the span rounded as well */
                padding: 0.25rem 0.75rem; /* Adjust padding for the inner span */
                transition: all 0.2s ease-in-out;
            }

            .checkbox-button span i {
                color: #9ca3af; /* gray-400 for unselected icon */
            }

            .checkbox-button input[type="checkbox"]:checked + span i {
                color: white; /* White icon when checked */
            }
        </style>
    </head>
    <body>
        @can("project-create")
       
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight border-b-2 border-secondary-purple pb-3 mb-6 animate-fade-in delay-100">
                <i class="fas fa-project-diagram mr-3 text-secondary-purple"></i> {{ __('Créer un Nouveau Projet') }}
            </h2>
        

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg card-hover-scale animate-fade-in">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-secondary-purple">Ajouter un Projet</h3>
                            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 btn-outline-primary-custom">
                                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                            </a>
                        </div>

                        <hr class="my-6 border-gray-200">

                        <form action="{{ route('projects.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Titre --}}
                                <div class="col-span-1">
                                    <label for="titre" class="block text-gray-700 text-sm font-bold mb-2">Titre: <span class="text-danger-custom">*</span></label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text-custom"><i class="fas fa-heading"></i></span>
                                        <input type="text" name="titre" id="titre" class="form-control-custom @error('titre') border-primary-red @enderror" value="{{ old('titre') }}" required>
                                    </div>
                                    @error('titre')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nom du Client --}}
                                <div class="col-span-1">
                                    <label for="nomclient" class="block text-gray-700 text-sm font-bold mb-2">Nom du Client: <span class="text-danger-custom">*</span></label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text-custom"><i class="fas fa-user-tie"></i></span>
                                        <input type="text" name="nomclient" id="nomclient" class="form-control-custom @error('nomclient') border-primary-red @enderror" value="{{ old('nomclient') }}" required>
                                    </div>
                                    @error('nomclient')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Ville --}}
                                <div class="col-span-1">
                                    <label for="ville" class="block text-gray-700 text-sm font-bold mb-2">Ville: <span class="text-danger-custom">*</span></label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text-custom"><i class="fas fa-city"></i></span>
                                        <input type="text" name="ville" id="ville" class="form-control-custom @error('ville') border-primary-red @enderror" value="{{ old('ville') }}" required>
                                    </div>
                                    @error('ville')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Utilisateur (Checkbox buttons) --}}
                                <div class="col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Assigner à l'utilisateur: <span class="text-danger-custom">*</span></label>
                                    <div class="checkbox-button-group">
                                        @foreach($users as $user)
                                            <label class="checkbox-button">
                                                <input type="checkbox" name="iduser[]" value="{{ $user->id }}" {{ in_array($user->id, old('iduser', [])) ? 'checked' : '' }}>
                                                <span>
                                                    <i class="fas fa-user-circle"></i>
                                                    {{ $user->name }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('iduser')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Date du Projet --}}
                                <div class="col-span-1">
                                    <label for="date_project" class="block text-gray-700 text-sm font-bold mb-2">Date du Projet: <span class="text-danger-custom">*</span></label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text-custom"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" name="date_project" id="date_project" class="form-control-custom @error('date_project') border-primary-red @enderror" value="{{ old('date_project') }}" required>
                                    </div>
                                    @error('date_project')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Besoins --}}
                                <div class="col-span-full">
                                    <label for="bessoins" class="block text-gray-700 text-sm font-bold mb-2">Besoins: <span class="text-danger-custom">*</span></label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text-custom"><i class="fas fa-clipboard-list"></i></span>
                                        <textarea name="bessoins" id="bessoins" rows="4" class="form-control-custom form-textarea-custom @error('bessoins') border-primary-red @enderror" required>{{ old('bessoins') }}</textarea>
                                    </div>
                                    @error('bessoins')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6 space-x-3">
                                <button type="submit" class="inline-flex items-center px-6 py-3 btn-project-create rounded-md font-bold text-sm text-white uppercase tracking-wider shadow-lg">
                                    <i class="fas fa-plus-circle mr-2"></i> Créer le Projet
                                </button>
                                <a href="{{ route('projects.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 border border-gray-300 rounded-md font-bold text-sm text-gray-700 uppercase tracking-wider shadow-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 btn-outline-primary-custom">
                                    <i class="fas fa-times-circle mr-2"></i> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                // You can add JavaScript here if you need more complex interactions for the checkboxes
                // For example, limiting the number of selections or adding dynamic behavior.
                // For a simple checkbox, no specific JS is required beyond what's already done by the browser.
            </script>
        @endpush
        @endcan
    </body>
</x-app-layout>