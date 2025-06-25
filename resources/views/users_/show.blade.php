<!-- resources/views/users/show.blade.php -->

<x-app-layout>
    <h1>Détails de l'utilisateur</h1>
    
    <p><strong>ID :</strong> {{ $user->id }}</p>
    <p><strong>Nom :</strong> {{ $user->name }}</p>
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Poste :</strong> {{ $user->poste }}</p>
    <p><strong>Téléphone :</strong> {{ $user->tele }}</p>
    <p><strong>Adresse :</strong> {{ $user->adresse }}</p>
    <p><strong>Repos :</strong> {{ $user->repos }}</p>
    
    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Modifier</a>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Retour</a>
</x-app-layout>