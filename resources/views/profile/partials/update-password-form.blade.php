<section>
    <style>
        section {
            background-color: #f9fafb;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 600px;
            margin: 2rem auto;
            font-family: 'Arial', sans-serif;
        }

        header h2 {
            font-size: 1.5rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        header p {
            font-size: 0.875rem;
            color: #4b5563;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        form div {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="email"] {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            color: #111827;
            background-color: #fff;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input:focus {
            border-color: #f16363;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .text-sm {
            font-size: 0.875rem;
            color: #6b7280;
        }

        button {
            background-color: #e54646;
            color: white;
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #4338ca;
        }

        button:active {
            transform: scale(0.98);
        }

        .flex.items-center {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .text-gray-800 {
            color: #374151;
        }

        .text-green-600 {
            color: #10b981;
        }

        .underline {
            text-decoration: underline;
        }

        [x-data] {
            display: block;
        }

        [x-show="false"] {
            display: none;
        }
    </style>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Mettre à jour le mot de passe') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Current Password')" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
