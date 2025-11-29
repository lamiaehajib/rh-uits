<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYbYMpwVNrGj39HPPcodSyE7KPLB7UqM1Ny6WFAQx1Q3pld0TUf9xj6am2DYspgZPXQ58&usqp=CAU" type="image/png">
  <title>MANAGEMENT</title>
</head>
<body>
  <div>
    <style>
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
  @if (Session::has('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ Session::get('error') }}</span>
       <span class="absolute top-0 bottom-0 right-0 px-4 py-3 alert-close-button cursor-pointer">
    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
</span>
    </div>
@endif
    <form method="POST" action="{{ route('login') }}">
      @csrf
  
      <div class="container">
        <!-- Email Address -->
        <div class="form-group">
          <img width="200px" style="margin-left:100px" src="{{ asset('photos/uits-lo.png') }}" alt="Logo"/>
          <x-input-label for="email" :value="('Email')" class="form-label" />
          <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
          <x-input-error :messages="$errors->get('email')" class="form-error" />
        </div>
  
        <!-- Password -->
        <div class="form-group">
          <x-input-label for="password" :value="('mot de passe')" class="form-label" />
          <x-text-input id="password" class="form-input"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
          <x-input-error :messages="$errors->get('password')" class="form-error" />
        </div>
  
        <!-- Remember Me -->
        <div class="remember-me">
          <label for="remember_me" class="flex items-center justify-end mt-4">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <span class="ml-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
          </label>
        </div>
  
        <div class="flex items-center justify-end mt-4">
          @if (Route::has('password.request'))
            <a class="forgot-password" href="{{ route('password.request') }}">
              {{ __('Mot de passe oublié ?') }}
            </a>
          @endif
  
          <x-primary-button class="primary-button">
            {{ __('Log in') }}
          </x-primary-button>
        </div>
      </div>
    </form>
  
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const closeButton = document.querySelector('.alert-close-button'); // Add this class to your SVG's parent span
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                this.closest('[role="alert"]').remove();
            });
        }
    });
</script>
</body>
</html>