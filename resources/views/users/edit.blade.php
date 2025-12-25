<x-app-layout>
    <style>
        /* Custom Styles for the D32F2F color and animations */
        .color-primary {
            color: #D32F2F;
        }

        .bg-primary-custom {
            background-color: #D32F2F;
        }

        .btn-primary-custom {
            background-color: #D32F2F;
            border-color: #D32F2F;
        }

        .btn-primary-custom:hover {
            background-color: #B71C1C; /* A darker shade for hover effect */
            border-color: #B71C1C;
        }

        /* Subtle hover effect for cards */
        .card-hover-scale {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card-hover-scale:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Icon bounce animation on hover */
        .icon-bounce:hover {
            animation: bounce 0.6s ease-in-out;
        }

        @keyframes bounce {
            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        /* Badge pulse animation */
        .badge-pulse {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(211, 47, 47, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(211, 47, 47, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(211, 47, 47, 0);
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0 color-primary">
                        Modifier l'utilisateur : {{ $user->name }}
                    </h2>
                    <a class="btn btn-outline-primary d-inline-flex align-items-center"
                        href="{{ route('users.index') }}">
                        <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Whoops!</strong> Il y a eu quelques problèmes avec votre saisie.<br><br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4 card-hover-scale">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0 color-primary icon-bounce">Modifier les informations de l'utilisateur</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PATCH')
                    {{-- Use PATCH method for updates --}}
                    <div class="row g-3">
                        {{-- Nom --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom Complet <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Entrez le nom complet" value="{{ old('name', $user->name) }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Ex: utilisateur@exemple.com"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Téléphone --}}
                        <div class="col-md-6">
                            <label for="tele" class="form-label">Téléphone <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" name="tele" id="tele"
                                    class="form-control @error('tele') is-invalid @enderror"
                                    placeholder="Ex: +2126XXXXXXXX" value="{{ old('tele', $user->tele) }}"
                                    required>
                                @error('tele')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Code --}}
                        <div class="col-md-6">
                            <label for="code" class="form-label">Code Utilisateur <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                <input type="number" name="code" id="code"
                                    class="form-control @error('code') is-invalid @enderror" placeholder="Ex: 1001"
                                    value="{{ old('code', $user->code) }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Poste --}}
                        <div class="col-md-6">
                            <label for="poste" class="form-label">Poste <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                <input type="text" name="poste" id="poste"
                                    class="form-control @error('poste') is-invalid @enderror"
                                    placeholder="Ex: Développeur Senior" value="{{ old('poste', $user->poste) }}"
                                    required>
                                @error('poste')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        {{-- Salaire --}}
<div class="col-md-6">
    <label for="salaire" class="form-label">Salaire (MAD)</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
        <input type="number" step="0.01" name="salaire" id="salaire" 
               class="form-control @error('salaire') is-invalid @enderror" 
               placeholder="Ex: 5000.00" 
               value="{{ old('salaire', $user->salaire) }}">
        @error('salaire')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
                        ---

                        {{-- Jours de Repos avec des cases à cocher --}}
                        <div class="col-md-6">
                            <label class="form-label">Jours de Repos <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <div class="form-control @error('repos') is-invalid @enderror"
                                    style="height: auto; min-height: 38px;">
                                    @php
                                        $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                                        // Utilise old('repos') en priorité pour les erreurs de validation, sinon $userRepos du contrôleur
                                        $selectedRepos = old('repos', $userRepos);
                                    @endphp
                                    <div class="row g-1">
                                        @foreach ($daysOfWeek as $day)
                                            <div class="col-auto">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input day-checkbox" type="checkbox"
                                                        name="repos[]" id="repos_{{ $day }}"
                                                        value="{{ $day }}"
                                                        {{ in_array($day, $selectedRepos) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="repos_{{ $day }}">{{ $day }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('repos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted mt-2">Veuillez sélectionner un ou deux jours de
                                repos.</small>
                        </div>

                        ---

                        {{-- Adresse --}}
                        <div class="col-12">
                            <label for="adresse" class="form-label">Adresse <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea name="adresse" id="adresse"
                                    class="form-control @error('adresse') is-invalid @enderror"
                                    placeholder="Entrez l'adresse complète" rows="3" required>{{ old('adresse', $user->adresse) }}</textarea>
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Rôles --}}
                        <div class="col-12">
                            <label for="roles" class="form-label">Rôles <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                <select name="roles[]" id="roles"
                                    class="form-select @error('roles') is-invalid @enderror" multiple required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}"
                                            {{ in_array($role, $userRole) ? 'selected' : '' }}>
                                            {{ $role }}</option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted mt-2">Vous pouvez sélectionner plusieurs rôles en
                                maintenant la touche Ctrl (ou Cmd sur Mac) enfoncée.</small>
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Laisser vide pour ne pas changer">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted mt-2">Le mot de passe doit contenir au moins 8
                                caractères, inclure des lettres et des chiffres.</small>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-md-6">
                            <label for="confirm-password" class="form-label">Confirmer le nouveau mot de
                                passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                                <input type="password" name="confirm-password" id="confirm-password"
                                    class="form-control @error('confirm-password') is-invalid @enderror"
                                    placeholder="Confirmer le nouveau mot de passe">
                                <button class="btn btn-outline-secondary" type="button"
                                    id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('confirm-password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit"
                            class="btn btn-success btn-lg d-inline-flex align-items-center btn-primary-custom">
                            <i class="fas fa-sync-alt me-2"></i> Mettre à jour l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                // Script pour basculer la visibilité du mot de passe (garder ceci)
                $('#togglePassword').click(function() {
                    const passwordField = $('#password');
                    const passwordFieldType = passwordField.attr('type');
                    if (passwordFieldType === 'password') {
                        passwordField.attr('type', 'text');
                        $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        passwordField.attr('type', 'password');
                        $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });

                $('#toggleConfirmPassword').click(function() {
                    const confirmPasswordField = $('#confirm-password');
                    const confirmPasswordFieldType = confirmPasswordField.attr('type');
                    if (confirmPasswordFieldType === 'password') {
                        confirmPasswordField.attr('type', 'text');
                        $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        confirmPasswordField.attr('type', 'password');
                        $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });

                // Ensure roles are selected on load (keep this)
                const userRoles = @json($userRole);
                $('#roles option').each(function() {
                    if (userRoles.includes($(this).val())) {
                        $(this).prop('selected', true);
                    }
                });

                // --- Nouveau JavaScript pour limiter la sélection des cases à cocher ---
                const maxSelections = 2; // Limite de 2 jours
                const $checkboxes = $('.day-checkbox'); // Sélectionne toutes les cases à cocher des jours

                $checkboxes.on('change', function() {
                    const checkedCount = $checkboxes.filter(':checked').length;

                    if (checkedCount > maxSelections) {
                        alert('Vous ne pouvez sélectionner qu\'un maximum de ' + maxSelections +
                            ' jours de repos.');
                        // Désélectionne la case qui vient d'être cochée en trop
                        $(this).prop('checked', false);
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>