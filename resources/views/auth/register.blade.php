<div>
    <style>
        /* Global styles */
        * {
          box-sizing: border-box;
          margin: 0;
          padding: 0;
        }
      
        body {
          font-family: Arial, sans-serif;
          background-color: #f7f7f7;
          background-color: rgb(214, 212, 212);
          
        }
      
        /* Form styles */
        form {
          max-width: 400px;
          margin: 40px auto;
          padding: 20px;
          background-color: rgb(214, 212, 212);
          border: 1px solid #ddd;
          box-shadow: 0 0 30px rgba(12, 20, 0, 20.1);
          border-radius: 10px;
          margin-top: 70px;
        }
      
        /* Input styles */
        .input-name, .input-email, .name-password {
          margin-bottom: 20px;
        }
      
        .block {
          width: 100%;
          padding: 10px;
        
          border-radius: 5px;
          font-size: 16px;
        }
      
        .block:focus {
          border-color: #aaa;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

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
          color: #red;
          font-size: 14px;
          margin-top: 10px;
        }
      
        /* Button styles */
       .ml-ml {
          background-color: rgb(146, 146, 146);
          color: #fff;
          padding: 10px 20px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          font-size: 16px;
          font-weight: bold;
        }
      
        .ml-ml:hover {
          background-color: rgb(107, 107, 107);
        }
      
        /* Link styles */
        .underline {
          text-decoration: underline;
        }
      
        .text-sm {
          font-size: 14px;
        }
      
        .text-gray-600 {
          color: #666;
        }
      
        .hover:text-gray-900:hover {
          color: #333;
        }
      
        .rounded-md {
          border-radius: 5px;
        }
      
        .focus:outline-none:focus {
          outline: none;
        }
      
        .focus:ring-2:focus {
          box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
      
        .focus:ring-offset-2:focus {
          box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
      
        .focus:ring-indigo-500:focus {
          box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
        }
      
        /* Flexbox styles */
        .flex {
          display: flex;
          justify-content: space-between;
          align-items: center;
        }
      
        .items-center {
          align-items: center;
        }
      
        .justify-end {
          justify-content: flex-end;
        }
      
        .ml-ml {
          margin-top: 20px;
        }
      
        .ml-ml {
          margin-left: 20px;
        }
        .mt-2{
            color: red
        }
        .nav-tach {
            display: flex;
            align-items: center;
            background-color: rgb(211, 207, 207);
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-tach img {
            width: 92px;
            height: auto;
            margin-left: 300px;
         
        }

       
        .nav-tach .nav-p {
            margin-left: 3px;
            font-size: 24px;
            font-weight: bold;
            
            
        }
        .nav-p{
            font-family: hiun;
            color: gray
        }
        .cla-log{
            color: rgb(83, 83, 195);
            
            
        }
      </style>
       <nav class="nav-tach">
        <!-- Content de navigation -->
    </nav>
    <form method="POST" action="{{ route('register') }}">
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
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ml-ml">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
