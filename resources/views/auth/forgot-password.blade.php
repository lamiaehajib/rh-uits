<style>
    body {
        font-family: Arial, sans-serif;
  background-image: url('photos/Untitled-2ccc.jpg'); /* Assurez-vous que le chemin d'accès est correct */
  background-size: cover; /* Cela permet à l'image de couvrir toute la surface de l'écran */
  background-position: center; /* Cela centre l'image de fond */
  background-attachment: fixed; /* L'image reste fixe lorsqu'on défile la page */
  margin: 0; /* Supprime les marges par défaut de la page */
  height: 90vh; 
    }

    .container {
        background-color: white;
      max-width: 600px;
      margin: 131px auto;
      padding: 20px;
     
      border: 1px solid #ddd;
      border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        font-size: 24px;
        font-weight: bold;
        color: #D32F2F;
        text-align: center;
    }

    .mb-4 {
        margin-bottom: 16px;
    }

    .text-gray-600 {
        color: #555;
    }

    .text-sm {
        font-size: 14px;
    }

    .x-input-label {
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
        color: #333;
    }

    .x-text-input {
        width: 100%;
        padding: 12px;
        border-radius: 4px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
        font-size: 16px;
        color: #333;
        
    }

    .x-text-input:focus {
        border-color: #C2185B;
        outline: none;
        box-shadow: 0 0 5px rgba(194, 24, 91, 0.5);
    }

    .x-primary-button {
        background-color: #D32F2F;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    .x-primary-button:hover {
        background-color: #C2185B;
    }

    .x-input-error {
        color: #ff0000 !important;
        font-size: 12px;
        margin-top: 4px;
    }

    .x-auth-session-status {
        font-size: 14px;
        color: #4caf50;
        margin-bottom: 12px;
    }
    .flex{
        margin-top: 20px;
    }
</style>

<div class="container">
    <div class="header">
        {{ __('Mot de passe oublié ?') }}
    </div>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Pas de problème. Indiquez simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation de mot de passe qui vous permettra de choisir un nouveau mot de passe.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" onsubmit="return validateForm()">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex">
            <x-primary-button>
                {{ __('Envoyer le lien de réinitialisation du mot de passe par e-mail') }}
            </x-primary-button>
        </div>
    </form>
</div>

<script>
    function validateForm() {
        const email = document.getElementById('email').value;
        if (!email) {
            alert("L'adresse e-mail est requise.");
            return false;
        }
        // Validating email format
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if (!emailPattern.test(email)) {
            alert("Veuillez entrer une adresse e-mail valide.");
            return false;
        }
        return true;
    }
</script>
