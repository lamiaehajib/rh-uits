<x-app-layout>
    <style>
        /* Global styles */
        
        body {
            background-color: #f8f9fa; /* Couleur de fond douce */
            font-family: Arial, sans-serif; /* Police moderne */
        }
    
        h1 {
            text-align: center; /* Centrer le titre */
            color: #343a40; /* Couleur du titre */
            margin-bottom: 30px; /* Espacement en bas du titre */
        }
    
        .alert {
            margin: 20px auto; /* Espacement autour de l'alerte */
            max-width: 600px; /* Largeur maximale de l'alerte */
        }
    
        form {
            max-width: 600px; /* Largeur maximale du formulaire */
            margin: auto; /* Centrer le formulaire */
            padding: 20px; /* Espacement interne */
            background-color: #ffffff; /* Couleur de fond blanche pour le formulaire */
            border-radius: 8px; /* Coins arrondis */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Ombre légère */
        }
    
        .form-group {
            margin-bottom: 15px; /* Espacement entre les groupes de formulaire */
        }
    
        label {
            font-weight: bold; /* Gras pour les étiquettes */
            color: #495057; /* Couleur des étiquettes */
        }
    
        .form-control {
            border: 1px solid #ced4da; /* Bordure standard */
            border-radius: 5px; /* Coins arrondis pour les champs de saisie */
            padding: 10px; /* Espacement interne */
            width: 100%; /* Largeur complète */
            transition: border-color 0.2s; /* Transition pour la couleur de bordure */
        }
    
        .form-control:focus {
            border-color: #007bff; /* Couleur de la bordure au focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Ombre au focus */
        }
    
        .btn {
            display: block; /* Affichage en bloc pour centrer le bouton */
            width: 100%; /* Largeur complète */
            padding: 10px; /* Espacement interne */
            border-radius: 5px; /* Coins arrondis pour le bouton */
            font-weight: bold; /* Gras pour le texte du bouton */
            margin-top: 20px; /* Espacement en haut du bouton */
        }
    
        .btn-primary {
            background-color: #007bff; /* Couleur de fond du bouton */
            border: none; /* Pas de bordure */
            color: white; /* Couleur du texte du bouton */
            transition: background-color 0.2s; /* Transition pour la couleur de fond */
        }
    
        .btn-primary:hover {
            background-color: #0056b3; /* Couleur de fond au survol */
        }
    
      
        /* Label styles */
        x-input-label {
          display: block;
          margin-bottom: 10px;
          font-size: 16px;
          font-weight: bold;
          color: #333;
        }
      
        /* Error message styles */
        x-input-error {
          color: red;
          font-size: 14px;
          margin-top: 10px;
        }
      
        /* Button styles */
       
      </style>
       <nav class="nav-tach">
        <!-- Content de navigation -->
    </nav>
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <img width="200px" style="margin-left:100px" src="{{ asset('photos/uits-lo.png') }}" alt="Logo"/>
        
        <!-- Name -->
        <div class="input-name">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Telephone -->
        <div class="input-tele">
            <x-input-label for="tele" :value="__('Telephone')" />
            <x-text-input id="tele" class="block mt-1 w-full" type="text" name="tele" :value="old('tele')" required autocomplete="tele" />
            <x-input-error :messages="$errors->get('tele')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="input-email">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Poste -->
        <div class="input-poste">
            <x-input-label for="poste" :value="__('Poste')" />
            <x-text-input id="poste" class="block mt-1 w-full" type="text" name="poste" :value="old('poste')" required autocomplete="poste" />
            <x-input-error :messages="$errors->get('poste')" class="mt-2" />
        </div>

        <!-- Adresse -->
        <div class="input-adresse">
            <x-input-label for="adresse" :value="__('Adresse')" />
            <x-text-input id="adresse" class="block mt-1 w-full" type="text" name="adresse" :value="old('adresse')" required autocomplete="adresse" />
            <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
        </div>

        <!-- Repos -->
        <div class="input-repos">
            <x-input-label for="repos" :value="__('Repos')" />
            <x-text-input id="repos" class="block mt-1 w-full" type="text" name="repos" :value="old('repos')" required autocomplete="repos" />
            <x-input-error :messages="$errors->get('repos')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="name-password">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="cla-log">
            
            <x-primary-button class="ml-ml">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
  </x-app-layout>
