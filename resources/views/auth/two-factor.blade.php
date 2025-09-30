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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Vérification à Deux Facteurs (2FA)') }}
                </div>

                <div class="card-body">
                    <p class="text-center">
                        Nous avons envoyé un **code de vérification à 6 chiffres** à votre numéro de téléphone ({{ substr($user->tele, -4) }}).
                        <br>
                        Veuillez entrer le code ci-dessous pour finaliser votre connexion.
                    </p>
                    
                    {{-- Formulaire de saisie du code --}}
                    <form method="POST" action="{{ route('verification.verify') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="two_factor_code" class="col-md-4 col-form-label text-md-right">
                                {{ __('Code de Vérification (OTP)') }}
                            </label>

                            <div class="col-md-6">
                                <input id="two_factor_code" type="text" 
                                       class="form-control @error('two_factor_code') is-invalid @enderror" 
                                       name="two_factor_code" required autofocus 
                                       placeholder="Entrez le code à 6 chiffres">

                                {{-- Affichage des messages d'erreur --}}
                                @error('two_factor_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Vérifier et Se Connecter') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    {{-- Option de renvoi du code (Optionnel) --}}
                    <div class="text-center mt-3">
                        <p>
                            Si vous n'avez pas reçu le code, veuillez attendre une minute et réessayer :
                            <a href="#" onclick="event.preventDefault(); document.getElementById('resend-form').submit();">
                                Renvoyer le code
                            </a>
                        </p>
                    </div>

                    {{-- Formulaire de renvoi (Nécessite de créer une nouvelle route) --}}
                    <form id="resend-form" action="#" method="POST" style="display: none;">
                        @csrf
                        {{-- NOTE : Vous devez créer une route POST pour /2fa/resendCode et modifier la fonction dans TwoFactorController --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

