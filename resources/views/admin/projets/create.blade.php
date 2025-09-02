<x-app-layout>
    <style>
        :root {
            --primary-color: #C2185B;
            --secondary-color: #D32F2F;
            --accent-color: #ef4444;
            --gradient-bg: linear-gradient(135deg, #C2185B 0%, #D32F2F 50%, #ef4444 100%);
            --gradient-light: linear-gradient(135deg, rgba(194, 24, 91, 0.1) 0%, rgba(211, 47, 47, 0.1) 100%);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        .page-header {
            background: var(--gradient-bg);
            padding: 2.5rem;
            border-radius: 24px;
            margin-bottom: 2rem;
            box-shadow: 0 25px 60px rgba(194, 24, 91, 0.25);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .page-header h1 {
            color: white;
            font-weight: 700;
            font-size: 2.2rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .btn-back {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 12px 24px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            color: white;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        .card-header-custom {
            background: var(--gradient-light);
            padding: 1.8rem;
            border-bottom: 1px solid rgba(194, 24, 91, 0.1);
            position: relative;
        }

        .card-header-custom::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--gradient-bg);
        }

        .card-header-custom h6 {
            margin: 0;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header-custom h6::before {
            content: '\f1c0';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            width: 40px;
            height: 40px;
            background: var(--gradient-bg);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 2rem;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label::before {
            content: '';
            width: 3px;
            height: 18px;
            background: var(--gradient-bg);
            border-radius: 2px;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 14px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(194, 24, 91, 0.1);
            background: white;
        }

        .form-control.is-invalid {
            border-color: var(--accent-color);
            animation: shake 0.5s ease-in-out;
        }

        /* Styles pour les checkboxes clients */
        .clients-checkbox-container {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .clients-checkbox-container:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(194, 24, 91, 0.1);
        }

        .clients-checkbox-container.is-invalid {
            border-color: var(--accent-color);
            animation: shake 0.5s ease-in-out;
        }

        .client-checkbox-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 8px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .client-checkbox-item:hover {
            background: rgba(194, 24, 91, 0.05);
            border-color: var(--primary-color);
            transform: translateX(4px);
        }

        .client-checkbox-item:last-child {
            margin-bottom: 0;
        }

        .client-checkbox {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }

        .client-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .client-name {
            font-weight: 600;
            color: #374151;
            font-size: 0.95rem;
        }

        .client-email {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .custom-file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            opacity: 0;
            position: absolute;
            z-index: -1;
        }

        .file-label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border: 2px dashed #d1d5db;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafafa;
            color: #6b7280;
            font-weight: 500;
        }

        .file-label:hover,
        .file-input:focus+.file-label {
            border-color: var(--primary-color);
            background: rgba(194, 24, 91, 0.05);
            color: var(--primary-color);
        }

        .file-label i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .btn-gradient {
            background: var(--gradient-bg);
            border: none;
            color: white;
            padding: 16px 32px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-gradient:hover::before {
            left: 100%;
        }

        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(194, 24, 91, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            color: #374151;
            padding: 14px 28px;
            border-radius: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary-custom:hover {
            background: #e5e7eb;
            border-color: #d1d5db;
            transform: translateY(-2px);
            color: #374151;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 4px 0;
        }

        .badge-warning {
            background: rgba(251, 191, 36, 0.2);
            color: #92400e;
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.2);
            color: #1e40af;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.2);
            color: #166534;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #991b1b;
        }

        .help-card {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05) 0%, rgba(239, 68, 68, 0.05) 100%);
            border: 1px solid rgba(194, 24, 91, 0.1);
        }

        .help-card .card-header-custom h6::before {
            content: '\f059';
        }

        .help-list {
            list-style: none;
            padding: 0;
        }

        .help-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #4b5563;
        }

        .help-list li::before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--primary-color);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(194, 24, 91, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            flex-shrink: 0;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--accent-color);
            font-weight: 500;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .row {
            margin: 0 -15px;
        }

        [class*="col-"] {
            padding: 0 15px;
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.8rem;
                margin-bottom: 1rem;
            }

            .d-sm-flex {
                flex-direction: column;
            }

            .btn-back {
                align-self: flex-start;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between page-header">
            <h1 class="mb-0">Créer un Nouveau Projet</h1>
            <a href="{{ route('admin.projets.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="glass-card mb-4">
                    <div class="card-header-custom">
                        <h6>Informations du Projet</h6>
                    </div>
                    <div class="card-body" style="padding: 2.5rem;">
                        <form action="{{ route('admin.projets.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titre" class="form-label">Titre du Projet <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('titre') is-invalid @enderror"
                                            id="titre" name="titre" value="{{ old('titre') }}"
                                            placeholder="Ex: Développement Site Web" required>
                                        @error('titre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Clients <span class="text-danger">*</span></label>
                                        <div class="clients-checkbox-container @error('client_ids') is-invalid @enderror">
                                            @foreach($clients as $client)
                                                <div class="client-checkbox-item">
                                                    <input type="checkbox" 
                                                           class="client-checkbox" 
                                                           id="client_{{ $client->id }}" 
                                                           name="client_ids[]" 
                                                           value="{{ $client->id }}"
                                                           {{ in_array($client->id, old('client_ids', [])) ? 'checked' : '' }}>
                                                    <label for="client_{{ $client->id }}" class="client-info">
                                                        <div class="client-name">{{ $client->name }}</div>
                                                        <div class="client-email">{{ $client->email }}</div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('client_ids')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="4"
                                    placeholder="Décrivez les détails et objectifs du projet..."
                                    style="resize: vertical;">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_debut" class="form-label">Date de Début <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('date_debut') is-invalid @enderror"
                                            id="date_debut" name="date_debut" value="{{ old('date_debut') }}" required>
                                        @error('date_debut')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_fin" class="form-label">Date de Fin Prévue</label>
                                        <input type="date" class="form-control @error('date_fin') is-invalid @enderror"
                                            id="date_fin" name="date_fin" value="{{ old('date_fin') }}">
                                        @error('date_fin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="statut_projet" class="form-label">Statut <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control form-select @error('statut_projet') is-invalid @enderror"
                                            id="statut_projet" name="statut_projet" required>
                                            <option value="en cours" {{ old('statut_projet') == 'en cours' ? 'selected' : '' }}>En Cours</option>
                                            <option value="en attente" {{ old('statut_projet') == 'en attente' ? 'selected' : '' }}>En Attente</option>
                                            <option value="terminé" {{ old('statut_projet') == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                            <option value="annulé" {{ old('statut_projet') == 'annulé' ? 'selected' : '' }}>Annulé</option>
                                        </select>
                                        @error('statut_projet')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Fichier Joint</label>
                                <div class="custom-file-upload">
                                    <input type="file" class="file-input @error('fichier') is-invalid @enderror"
                                        id="fichier" name="fichier" accept=".pdf,.doc,.docx,.jpg,.png">
                                    <label for="fichier" class="file-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span class="file-text">Choisir un fichier ou glisser ici</span>
                                    </label>
                                </div>
                                <small class="form-text text-muted" style="margin-top: 0.8rem; color: #6b7280;">
                                    <i class="fas fa-info-circle"></i> Formats acceptés: PDF, DOC, DOCX, JPG, PNG (Max:
                                    5MB)
                                </small>
                                @error('fichier')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr style="border: none; height: 1px; background: rgba(194, 24, 91, 0.1); margin: 3rem 0;">

                            <div class="form-group mb-0">
                                <button type="submit" class="btn-gradient">
                                    <i class="fas fa-save"></i>
                                    <span>Créer le Projet</span>
                                </button>
                                <a href="{{ route('admin.projets.index') }}" class="btn-secondary-custom"
                                    style="margin-left: 1rem;">
                                    <i class="fas fa-times"></i>
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card help-card mb-4">
                    <div class="card-header-custom">
                        <h6>Guide d'aide</h6>
                    </div>
                    <div class="card-body" style="padding: 2rem;">
                        <h6 style="font-weight: 700; color: var(--primary-color); margin-bottom: 1.2rem;">Conseils pour
                            créer un projet:</h6>
                        <ul class="help-list">
                            <li>Choisissez un titre descriptif et clair</li>
                            <li>Sélectionnez un ou plusieurs clients via les cases à cocher</li>
                            <li>La description aide à comprendre les objectifs</li>
                            <li>Définissez des dates réalistes</li>
                            <li>Vous pourrez ajouter des étapes après création</li>
                        </ul>

                        <hr style="border: none; height: 1px; background: rgba(194, 24, 91, 0.1); margin: 2rem 0;">

                        <h6 style="font-weight: 700; color: var(--primary-color); margin-bottom: 1.2rem;">Statuts
                            disponibles:</h6>
                        <div>
                            <div class="status-badge badge-warning">
                                <i class="fas fa-play-circle"></i>
                                En Cours
                            </div>
                            <small style="display: block; color: #6b7280; margin-bottom: 0.5rem;">Projet actif en
                                développement</small>

                            <div class="status-badge badge-info">
                                <i class="fas fa-pause-circle"></i>
                                En Attente
                            </div>
                            <small style="display: block; color: #6b7280; margin-bottom: 0.5rem;">Projet temporairement
                                suspendu</small>

                            <div class="status-badge badge-success">
                                <i class="fas fa-check-circle"></i>
                                Terminé
                            </div>
                            <small style="display: block; color: #6b7280; margin-bottom: 0.5rem;">Projet livré avec
                                succès</small>

                            <div class="status-badge badge-danger">
                                <i class="fas fa-times-circle"></i>
                                Annulé
                            </div>
                            <small style="display: block; color: #6b7280;">Projet arrêté définitivement</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gestion du nom de fichier dans l'input
        document.getElementById('fichier').addEventListener('change', function (e) {
            const fileLabel = document.querySelector('.file-text');
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choisir un fichier ou glisser ici';
            fileLabel.textContent = fileName;

            if (e.target.files[0]) {
                document.querySelector('.file-label').style.borderColor = 'var(--primary-color)';
                document.querySelector('.file-label').style.background = 'rgba(194, 24, 91, 0.05)';
            }
        });

        // Animation des inputs au focus
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function () {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Animation des checkboxes clients
        document.querySelectorAll('.client-checkbox-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('.client-checkbox');
                    checkbox.checked = !checkbox.checked;
                }
            });
        });
    </script>
</x-app-layout>