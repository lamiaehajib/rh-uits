<x-app-layout>
    <style>
       
        /* Page title styling */
        h1 {
            color: #343a40;
            font-size: 2rem;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }

        /* Container styling */
        .container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Table styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #343a40;
            color: #ffffff;
        }

        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Button styling */
        .btn-primary, .btn-info, .btn-warning, .btn-danger {
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            margin-right: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            color: #ffffff;
        }

        .btn-info {
            background-color: #17a2b8;
            color: #ffffff;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #343a40;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #ffffff;
        }

        /* Hover effects for buttons */
        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Alert styling */
        .alert {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        </style>
    <h1>Liste des utilisateurs</h1>
    
    <a href="{{ route('users.create') }}" class="btn btn-primary">Ajouter un utilisateur</a>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>telephone</th>
                <th>Poste</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->poste }}</td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info">Voir</a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>