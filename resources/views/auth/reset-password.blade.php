<style>
    /* Styles généraux pour le formulaire */
    body {
        font-family: Arial, sans-serif;
        background-image: url('photos/Untitled-2ccc.jpg'); /* Assurez-vous que le chemin d'accès est correct */
        background-size: cover; /* Cela permet à l'image de couvrir toute la surface de l'écran */
        background-position: center; /* Cela centre l'image de fond */
        background-attachment: fixed; /* L'image reste fixe lorsqu'on défile la page */
        margin: 0; /* Supprime les marges par défaut de la page */
        height: auto; /* Assure que le body prend toute la hauteur de la fenêtre */
    }

    .container {
        background-color: white;
        max-width: 400px;
        margin: 131px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Logo */
    img {
        display: block;
        margin: 0 auto 30px;
    }

    /* Titres */
    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    /* Champs de saisie */
    .form-input {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px dashed #ccc;
        border-radius: 4px;
        font-size: 14px;
        color: #333;
        transition: border-color 0.3s ease;
    }

    .form-input:focus {
        border-color: #D32F2F;
        outline: none;
        box-shadow: 0 4px 10px rgba(213, 59, 59, 0.424);
    }

    /* Erreur */
    .form-error {
        color: #D32F2F;
        font-size: 12px;
    }

    /* Checkbox Remember Me */
    .remember-me {
        margin-bottom: 20px;
    }

    .remember-me input {
        width: 16px;
        height: 16px;
    }

    .remember-me span {
        font-size: 12px;
        color: #333;
    }

    /* Boutons */
    .primary-button {
        background-color: #D32F2F;
        color: white;
        padding: 12px 20px;
        width: 100%;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .primary-button:hover {
        background-color: #C2185B;
    }

    .forgot-password {
        font-size: 12px;
        color: #D32F2F;
        text-decoration: none;
        display: inline-block;  /* Cela permet de gérer l'alignement horizontal */
        margin-bottom: 10px;  /* Si tu veux un petit espace au-dessus */
        transition: color 0.3s ease;
    }

    /* Si tu veux centrer le lien dans son conteneur parent */
    .flex.items-center {
        justify-content: center;
    }

    .forgot-password:hover {
        color: #C2185B;
    }
</style>

<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <div class="container">
    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email Address -->
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full form-input" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2 form-error" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Mot de passe')" />
        <x-text-input id="password" class="block mt-1 w-full form-input" type="password" name="password" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2 form-error" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
        <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 form-error" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button class="primary-button">
            {{ __('Réinitialiser le mot de passe') }}
        </x-primary-button>
    </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  @if ($errors->any())
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: '{{ implode(' ', $errors->all()) }}', // This will display all error messages
    });
  @endif
</script>
