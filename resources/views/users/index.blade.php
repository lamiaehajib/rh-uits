<x-app-layout>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                   <h2 class="font-semibold text-2xl text-gray-800 leading-tight border-b-2 border-primary-red pb-3 mb-6 animate-fade-in delay-100">
    <i class="fas fa-user mr-3 text-primary-red"></i> {{ __('Gestion des utilisateures') }}
</h2>
            </div>
        </div>

        {{-- Alerts (kept as is, you mentioned keeping them) --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Total Users Card --}}
            <div class="bg-blue-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-300 transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div>
                    <h3 class="text-lg font-semibold text-primary-red" style="color: #D32F2F;">{{ __('Total Utilisateurs') }}</h3>
                    <p class="text-4xl font-extrabold text-blue-900 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-16 h-16 text-white rounded-full flex items-center justify-center shadow-md" style="background-color: #D32F2F;">
                    <i class="fas fa-users text-4xl opacity-85"></i>
                </div>
            </div>
            {{-- Active Users Card --}}
            <div class="bg-green-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-400 transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div>
                    <h3 class="text-lg font-semibold text-green-700">{{ __('Utilisateurs Actifs') }}</h3>
                    <p class="text-4xl font-extrabold text-green-900 mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="w-16 h-16 bg-green-500 text-white rounded-full flex items-center justify-center shadow-md">
                    <i class="fas fa-user-check text-4xl opacity-85"></i>
                </div>
            </div>
            {{-- Inactive Users Card --}}
            <div class="bg-yellow-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-500 transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div>
                    <h3 class="text-lg font-semibold text-yellow-700">{{ __('Utilisateurs Inactifs') }}</h3>
                    <p class="text-4xl font-extrabold text-yellow-900 mt-1">{{ $stats['inactive'] }}</p>
                </div>
                <div class="w-16 h-16 bg-yellow-500 text-white rounded-full flex items-center justify-center shadow-md">
                    <i class="fas fa-user-times text-4xl opacity-85"></i>
                </div>
            </div>
            {{-- New Users Card (7 days) --}}
            <div class="bg-blue-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-600 transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div>
                    <h3 class="text-lg font-semibold text-blue-700">{{ __('Nouveaux (7 jours)') }}</h3>
                    <p class="text-4xl font-extrabold text-blue-900 mt-1">{{ $stats['recent'] }}</p>
                </div>
                <div class="w-16 h-16 bg-blue-500 text-white rounded-full flex items-center justify-center shadow-md">
                    <i class="fas fa-user-plus text-4xl opacity-85"></i>
                </div>
            </div>
        </div>

        {{-- Actions and Filters Card --}}
        <div class="card shadow-md mb-4 rounded-lg animate-slide-up">
            <div class="card-header bg-gray-100 d-flex flex-wrap justify-content-between align-items-center py-3 px-4 border-b border-gray-200">
                <h5 class="mb-2 mb-md-0 text-lg font-semibold">Actions et Filtres</h5>
                @can('user-create')
                    <a class="btn d-inline-flex align-items-center text-white custom-btn-primary transition duration-300 hover:opacity-90" style="background-color: #D32F2F; border-color: #D32F2F;" href="{{ route('users.create') }}">
                        <i class="fas fa-plus me-2"></i> Nouveau Utilisateur
                    </a>
                @endcan
            </div>
            <div class="card-body p-4">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-6 order-lg-1 order-2">
                        <div class="btn-group w-100 w-md-auto" role="group">
                            <button type="button" class="btn btn-outline-danger transition duration-300 hover:bg-red-50" id="bulkDeleteBtn" disabled>
                                <i class="fas fa-trash me-2"></i> Supprimer Sélectionnés
                            </button>

                            <button type="button" class="btn btn-outline-success transition duration-300 hover:bg-green-50" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fas fa-upload me-2"></i> Importer
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-6 order-lg-2 order-1">
                        <form method="GET" action="{{ route('users.index') }}" class="row g-2 justify-content-lg-end">
                            <div class="col-md-5 col-lg-4">
                                <input type="text" class="form-control focus:ring-2 focus:ring-primary-red focus:border-transparent transition duration-200" name="search"
                                       placeholder="Rechercher..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <select name="role" class="form-select focus:ring-2 focus:ring-primary-red focus:border-transparent transition duration-200">
                                    <option value="">Tous les rôles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}"
                                                {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-lg-3">
                                <select name="status" class="form-select focus:ring-2 focus:ring-primary-red focus:border-transparent transition duration-200">
                                    <option value="">Tous les statuts</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <button type="submit" class="btn btn-dark w-100 transition duration-300 hover:bg-gray-700">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="card shadow-md rounded-lg animate-fade-in">
            <div class="card-body p-4">
                {{-- Added overflow-x-auto to this div for horizontal scroll --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" id="selectAll" class="form-check-input accent-primary-red">
                                </th>
                                <th scope="col">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_direction' => request('sort_direction') == 'ASC' ? 'DESC' : 'ASC']) }}"
                                       class="text-white text-decoration-none d-flex align-items-center hover:text-gray-300 transition duration-200">
                                        #ID
                                        @if(request('sort_by') == 'id')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'ASC' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_direction' => request('sort_direction') == 'ASC' ? 'DESC' : 'ASC']) }}"
                                       class="text-white text-decoration-none d-flex align-items-center hover:text-gray-300 transition duration-200">
                                        Nom
                                        @if(request('sort_by') == 'name')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'ASC' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col">Email</th>
                                <th scope="col">Code</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Poste</th>
                                <th scope="col">Rôle</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Date Création</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $user)
                                <tr id="user-row-{{ $user->id }}" class="hover:bg-gray-50 transition duration-150">
                                    <td>
                                        <input type="checkbox" class="form-check-input user-checkbox accent-primary-red" value="{{ $user->id }}">
                                    </td>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle-sm text-white me-2 shadow-sm" style="background-color: #D32F2F;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <strong class="text-dark">{{ $user->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-secondary text-white px-2 py-1 rounded-md">{{ $user->code }}</span></td>
                                    <td>{{ $user->tele }}</td>
                                    <td>{{ $user->poste }}</td>
                                    <td>
                                        @if(!empty($user->getRoleNames()))
                                            @foreach($user->getRoleNames() as $rolename)
                                                <span class="badge text-white me-1 px-2 py-1 rounded-md" style="background-color: #D32F2F;">{{ $rolename }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input status-toggle me-2 cursor-pointer" type="checkbox" role="switch"
                                                    data-user-id="{{ $user->id }}"
                                                    {{ $user->is_active ? 'checked' : '' }}>
                                            <span class="badge text-white px-2 py-1 rounded-md transition duration-200 {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex flex-nowrap gap-2">
                                            <a class="btn btn-info btn-sm text-white rounded-md transition duration-200 hover:scale-110" href="{{ route('users.show', $user->id) }}" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('user-edit')
                                                <a class="btn btn-sm text-white rounded-md transition duration-200 hover:scale-110" style="background-color: #D32F2F;" href="{{ route('users.edit', $user->id) }}" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button type="button" class="btn btn-secondary btn-sm rounded-md transition duration-200 hover:scale-110" onclick="duplicateUser({{ $user->id }})" title="Dupliquer">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            @endcan
                                            @can('user-delete')
                                                <button type="button" class="btn btn-danger btn-sm rounded-md transition duration-200 hover:scale-110" onclick="deleteUser({{ $user->id }})" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-gray-500">Aucun utilisateur trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted text-sm">
                        Affichage de {{ $data->firstItem() }} à {{ $data->lastItem() }} sur {{ $data->total() }} utilisateurs
                    </div>
                    <div>
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-lg shadow-xl animate-scale-in">
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header text-white rounded-t-lg" style="background-color: #D32F2F;">
                        <h5 class="modal-title font-bold" id="importModalLabel">Importer des utilisateurs</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="file" class="form-label text-gray-700 font-medium">Fichier CSV/Excel</label>
                            <input type="file" class="form-control focus:ring-2 focus:ring-primary-red focus:border-transparent transition duration-200" id="file" name="file" accept=".csv,.xlsx,.xls" required>
                            <div class="form-text text-gray-500 text-sm mt-1">
                                Format attendu: name, email, code, tele, poste, adresse, repos, role
                            </div>
                        </div>
                        <div class="alert alert-info small bg-blue-50 border border-blue-200 text-blue-800 rounded-md p-3">
                            <strong>Note:</strong> Le mot de passe par défaut sera "123456" pour tous les utilisateurs importés.
                        </div>
                    </div>
                    <div class="modal-footer border-t border-gray-200 p-3 flex justify-end">
                        <button type="button" class="btn btn-secondary me-2 transition duration-200 hover:bg-gray-200" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn text-white custom-btn-primary transition duration-300 hover:opacity-90" style="background-color: #D32F2F;">Importer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        $(document).ready(function() {
            // Gestion sélection multiple
            $('#selectAll').change(function() {
                $('.user-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkActions();
            });

            $('.user-checkbox').change(function() {
                // If any checkbox is unchecked, uncheck the "select all" checkbox
                if (!$(this).prop('checked')) {
                    $('#selectAll').prop('checked', false);
                } else {
                    // If all checkboxes are checked, check the "select all" checkbox
                    if ($('.user-checkbox:checked').length === $('.user-checkbox').length) {
                        $('#selectAll').prop('checked', true);
                    }
                }
                toggleBulkActions();
            });

            function toggleBulkActions() {
                const checkedCount = $('.user-checkbox:checked').length;
                $('#bulkDeleteBtn').prop('disabled', checkedCount === 0);
            }

            // Toggle statut utilisateur
            $('.status-toggle').change(function() {
                const userId = $(this).data('user-id');
                const isActive = $(this).prop('checked');
                const $badge = $(this).closest('div').find('.badge'); // Find the badge relative to the toggle

                $.ajax({
                    url: `/users/${userId}/toggle-status`,
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            // Update the badge text and class
                            $badge.removeClass('bg-success bg-danger')
                                .addClass(response.status ? 'bg-success' : 'bg-danger')
                                .text(response.status ? 'Actif' : 'Inactif');
                        }
                    },
                    error: function() {
                        toastr.error('Erreur lors de la modification du statut');
                        // Revert the toggle state on error
                        $(this).prop('checked', !isActive);
                    }
                });
            });

            // Suppression en masse
            $('#bulkDeleteBtn').click(function() {
                const selectedIds = $('.user-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    toastr.warning("Veuillez sélectionner des utilisateurs à supprimer.");
                    return;
                }

                // Replace confirm() with a custom modal for better UX
                showCustomConfirmModal(
                    `Êtes-vous sûr de vouloir supprimer ${selectedIds.length} utilisateur(s) ? Cette action est irréversible.`,
                    function() { // On confirm
                        $.ajax({
                            url: '{{ route("users.bulk-delete") }}',
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                user_ids: selectedIds
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    location.reload(); // Reload to reflect changes
                                }
                            },
                            error: function(xhr) {
                                toastr.error('Erreur lors de la suppression en masse. Détails: ' + (xhr.responseJSON ? xhr.responseJSON.message : ''));
                            }
                        });
                    }
                );
            });

            // Export (You had a commented out section, assuming you want to keep this functionality)
            $('#bulkExportBtn').click(function() {
                const selectedIds = $('.user-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                let exportUrl = '{{ route("users.export") }}?format=csv';
                if (selectedIds.length > 0) {
                    exportUrl += '&user_ids=' + selectedIds.join(',');
                }
                window.location.href = exportUrl;
            });

            // Custom Confirm Modal Functions
            function showCustomConfirmModal(message, onConfirmCallback) {
                // Remove any existing modal to prevent duplicates
                $('#customConfirmModal').remove();

                const modalHtml = `
                    <div class="modal fade" id="customConfirmModal" tabindex="-1" aria-labelledby="customConfirmModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-lg shadow-xl animate-scale-in">
                                <div class="modal-header text-white rounded-t-lg bg-red-600" style="background-color: #D32F2F;">
                                    <h5 class="modal-title font-bold" id="customConfirmModalLabel">Confirmation</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 text-gray-800">
                                    <p>${message}</p>
                                </div>
                                <div class="modal-footer border-t border-gray-200 p-3 flex justify-end">
                                    <button type="button" class="btn btn-secondary me-2 transition duration-200 hover:bg-gray-200" data-bs-dismiss="modal">Annuler</button>
                                    <button type="button" class="btn text-white custom-btn-primary transition duration-300 hover:opacity-90" style="background-color: #D32F2F;" id="confirmActionBtn">Confirmer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('body').append(modalHtml);
                const customConfirmModal = new bootstrap.Modal(document.getElementById('customConfirmModal'));
                customConfirmModal.show();

                $('#confirmActionBtn').off('click').on('click', function() {
                    if (onConfirmCallback) {
                        onConfirmCallback();
                    }
                    customConfirmModal.hide();
                });

                // Clean up modal from DOM after it's hidden
                $('#customConfirmModal').on('hidden.bs.modal', function () {
                    $(this).remove();
                });
            }
        });

        // Fonctions globales pour les actions individuelles
        function deleteUser(userId) {
            // Using a custom modal instead of confirm()
            showCustomConfirmModal(
                'Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.',
                function() { // On confirm
                    // Using AJAX for smoother deletion feedback without full page reload
                    $.ajax({
                        url: `/users/${userId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $(`#user-row-${userId}`).fadeOut(300, function() {
                                    $(this).remove(); // Remove the row after fading out
                                });
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Erreur lors de la suppression de l\'utilisateur. Détails: ' + (xhr.responseJSON ? xhr.responseJSON.message : ''));
                        }
                    });
                }
            );
        }

        function resetPassword(userId) {
            showCustomConfirmModal(
                'Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ? Un mot de passe temporaire sera généré.',
                function() { // On confirm
                    $.ajax({
                        url: `/users/${userId}/reset-password`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Erreur lors de la réinitialisation du mot de passe. Détails: ' + (xhr.responseJSON ? xhr.responseJSON.message : ''));
                        }
                    });
                }
            );
        }

        function duplicateUser(userId) {
            showCustomConfirmModal(
                'Êtes-vous sûr de vouloir dupliquer cet utilisateur ? Une nouvelle entrée sera créée avec les mêmes informations, mais un email et un code différents.',
                function() { // On confirm
                    $.ajax({
                        url: `/users/${userId}/duplicate`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            toastr.success('Utilisateur dupliqué avec succès ! Un nouvel utilisateur a été créé.');
                            location.reload(); // Reload to show the new duplicated user
                        },
                        error: function(xhr) {
                            toastr.error('Erreur lors de la duplication de l\'utilisateur. Détails: ' + (xhr.responseJSON ? xhr.responseJSON.message : ''));
                        }
                    });
                }
            );
        }

        // --- Custom Modal Helper Functions (moved outside $(document).ready for global access if needed) ---
        // You'll need to include Bootstrap's JS and CSS for this modal to work.
        // Make sure Bootstrap 5 JS is loaded before this script.
        // Example: <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </script>
    @endsection
</x-app-layout>

{{-- Custom CSS for animations and refined styles (add this in your main CSS file or a <style> block) --}}
<style>
    /* Primary Red for consistent branding */
    .text-primary-red {
        color: #D32F2F;
    }

    .bg-primary-red {
        background-color: #D32F2F;
    }

    .accent-primary-red:checked {
        accent-color: #D32F2F;
    }

    .custom-btn-primary {
        background-color: #D32F2F !important;
        border-color: #D32F2F !important;
    }

    .custom-btn-primary:hover {
        background-color: #c02a2a !important; /* Slightly darker on hover */
        border-color: #c02a2a !important;
    }

    /* Card Hover Effect */
    .card-hover-effect {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .card-hover-effect:hover {
        transform: translateY(-5px); /* Lifts the card slightly */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); /* More pronounced shadow */
    }

    /* Avatar Circle */
    .avatar-circle-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: bold;
        flex-shrink: 0; /* Prevent shrinking in flex container */
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    .delay-600 { animation-delay: 0.6s; }

    .animate-slide-up {
        animation: slideUp 0.7s ease-out forwards;
    }

    .animate-scale-in {
        animation: scaleIn 0.3s ease-out forwards;
    }

    /* Table specific styles */
    .table-responsive {
        border-radius: 0.5rem; /* Match card border-radius */
        overflow-x: auto; /* THIS IS THE KEY FOR HORIZONTAL SCROLL */
        overflow-y: hidden; /* Hide vertical scroll if not needed */
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
    }

    .table-responsive table {
        min-width: 900px; /* Adjust this value as needed based on your content */
        /* This ensures the table will overflow and trigger the scrollbar */
    }

    .table-dark th {
        background-color: #343a40; /* Darker header */
        color: #fff;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.03); /* Lighter stripe */
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.07); /* Clearer hover effect */
    }

    /* Form select and input focus */
    .form-control:focus, .form-select:focus {
        border-color: #D32F2F;
        box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.25);
    }

    /* Custom switch for status toggle */
    .form-switch .form-check-input {
        background-color: #e2e8f0; /* Default grey for unchecked */
        border-color: #cbd5e0;
    }

    .form-switch .form-check-input:checked {
        background-color: #198754; /* Green for checked */
        border-color: #198754;
    }

    /* Pagination Styling (if Bootstrap 5 default is not enough) */
    .pagination .page-item .page-link {
        color: #D32F2F;
        border-color: #dee2e6;
        transition: all 0.2s ease-in-out;
    }

    .pagination .page-item.active .page-link {
        background-color: #D32F2F;
        border-color: #D32F2F;
        color: #fff;
    }

    .pagination .page-item .page-link:hover {
        background-color: #f8d7da; /* Light red on hover */
        border-color: #f5c6cb;
    }
</style>
