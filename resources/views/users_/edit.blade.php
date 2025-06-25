<!-- resources/views/users/edit.blade.php -->

<x-app-layout>
    <h1>Modifier l'utilisateur</h1>
    
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="tele">Téléphone</label>
            <input type="text" id="tele" name="tele" value="{{ $user->tele }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="poste">Poste</label>
            <input type="text" id="poste" name="poste" value="{{ $user->poste }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="adresse">Adresse</label>
            <input type="text" id="adresse" name="adresse" value="{{ $user->adresse }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="repos">Repos</label>
            <input type="text" id="repos" name="repos" value="{{ $user->repos }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</x-app-layout>
