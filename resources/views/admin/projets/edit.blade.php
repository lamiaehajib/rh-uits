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

.container-fluid {
    padding: 2rem;
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
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
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

.breadcrumb {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 12px 20px;
    margin: 1rem 0 0 0;
    position: relative;
    z-index: 2;
}

.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: all 0.3s ease;
}

.breadcrumb-item a:hover {
    color: white;
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
}

.breadcrumb-item.active {
    color: rgba(255, 255, 255, 0.7);
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.6);
}

.btn-back {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: white;
    padding: 14px 28px;
    border-radius: 16px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    position: relative;
    z-index: 2;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-3px);
    color: white;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
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
    padding: 2rem;
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

.card-header-custom h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-header-custom h5 i {
    width: 45px;
    height: 45px;
    background: var(--gradient-bg);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    font-size: 18px;
}

.form-group {
    margin-bottom: 2rem;
    position: relative;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-label i {
    color: var(--primary-color);
    width: 20px;
    text-align: center;
}

.form-control, .form-select {
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 16px 20px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    position: relative;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(194, 24, 91, 0.1);
    background: white;
    outline: none;
}

.form-control.is-invalid, .form-select.is-invalid {
    border-color: var(--accent-color);
    animation: shake 0.5s ease-in-out;
}

.form-select {
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
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

.btn-outline-gradient {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    padding: 14px 28px;
    border-radius: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.btn-outline-gradient:hover {
    background: var(--gradient-bg);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(194, 24, 91, 0.3);
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

.alert-custom {
    border: none;
    border-radius: 16px;
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(211, 47, 47, 0.1) 100%);
    border-left: 4px solid var(--accent-color);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.1);
}

.alert-info-custom {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
    border-left: 4px solid #3b82f6;
    border-radius: 16px;
    padding: 1.2rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-warning-custom {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%);
    border-left: 4px solid #f59e0b;
    border-radius: 16px;
    padding: 1.2rem;
}

.sidebar-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.sidebar-card .card-header {
    background: var(--gradient-light);
    padding: 1.5rem;
    border-bottom: 1px solid rgba(194, 24, 91, 0.1);
    position: relative;
}

.sidebar-card .card-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: var(--gradient-bg);
}

.sidebar-card .card-header h6 {
    margin: 0;
    font-weight: 700;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar-card .card-header h6 i {
    width: 35px;
    height: 35px;
    background: var(--gradient-bg);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 14px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(194, 24, 91, 0.05);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #4b5563;
    font-size: 0.9rem;
}

.info-value {
    color: var(--primary-color);
    font-weight: 500;
    font-size: 0.9rem;
}

.help-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.help-list li {
    padding: 10px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #4b5563;
    font-size: 0.9rem;
}

.help-list li::before {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--gradient-bg);
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

.form-text {
    color: #6b7280;
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.text-danger {
    color: var(--accent-color) !important;
}

.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background: var(--gradient-light);
    border-bottom: 1px solid rgba(194, 24, 91, 0.1);
    border-radius: 20px 20px 0 0;
    padding: 1.5rem;
}

.modal-title {
    color: var(--primary-color);
    font-weight: 700;
}

.preview-item {
    background: rgba(194, 24, 91, 0.02);
    border: 1px solid rgba(194, 24, 91, 0.1);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid var(--primary-color);
}

.preview-label {
    font-size: 0.8rem;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.preview-value {
    color: #1f2937;
    font-weight: 500;
    font-size: 1rem;
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
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%); 
    color: #92400e; 
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.badge-info { 
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(99, 102, 241, 0.2) 100%); 
    color: #1e40af; 
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.badge-success { 
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(22, 163, 74, 0.2) 100%); 
    color: #166534; 
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.badge-danger { 
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.2) 100%); 
    color: #991b1b; 
    border: 1px solid rgba(239, 68, 68, 0.3);
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(10deg); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
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
    
    .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-back {
        align-self: flex-start;
    }
}
</style>

<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center page-header">
        <div>
            <h1 class="mb-0">Modifier le Projet</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.projets.index') }}">Projets</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.projets.show', $projet) }}">{{ Str::limit($projet->titre, 20) }}</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.projets.show', $projet) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
        <div class="alert alert-custom alert-dismissible fade show" role="alert">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <i class="fas fa-exclamation-triangle" style="color: var(--accent-color); font-size: 1.2rem; margin-top: 2px;"></i>
                <div style="flex: 1;">
                    <h6 style="color: var(--accent-color); margin-bottom: 0.8rem; font-weight: 700;">Erreurs de validation :</h6>
                    <ul style="margin: 0; padding-left: 1.2rem;">
                        @foreach($errors->all() as $error)
                            <li style="color: #dc2626; margin-bottom: 0.3rem;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="color: var(--accent-color);"></button>
        </div>
    @endif

    <!-- Formulaire de modification -->
    <div class="row">
        <div class="col-lg-8">
            <div class="glass-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-edit"></i> Informations du Projet</h5>
                </div>
                <div class="card-body" style="padding: 2.5rem;">
                    <form action="{{ route('admin.projets.update', $projet) }}" method="POST" enctype="multipart/form-data" id="projetForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Titre -->
                            <div class="col-md-12 mb-3">
                                <label for="titre" class="form-label">
                                    <i class="fas fa-heading"></i> Titre du projet <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('titre') is-invalid @enderror" 
                                       id="titre" 
                                       name="titre" 
                                       value="{{ old('titre', $projet->titre) }}" 
                                       placeholder="Ex: Création site web e-commerce"
                                       required>
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Client -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-users"></i> Clients <span class="text-danger">*</span>
                                </label>
                                <div class="clients-checkbox-container @error('client_ids') is-invalid @enderror">
                                    @foreach($clients as $client)
                                        <div class="client-checkbox-item">
                                            <input type="checkbox" 
                                                   class="client-checkbox" 
                                                   id="client_{{ $client->id }}" 
                                                   name="client_ids[]" 
                                                   value="{{ $client->id }}"
                                                   {{ in_array($client->id, old('client_ids', $projet->users->pluck('id')->toArray())) ? 'checked' : '' }}>
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

                            <!-- Statut -->
                            <div class="col-md-6 mb-3">
                                <label for="statut_projet" class="form-label">
                                    <i class="fas fa-flag"></i> Statut <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('statut_projet') is-invalid @enderror" 
                                        id="statut_projet" 
                                        name="statut_projet" 
                                        required>
                                    <option value="en attente" {{ old('statut_projet', $projet->statut_projet) == 'en attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="en cours" {{ old('statut_projet', $projet->statut_projet) == 'en cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="terminé" {{ old('statut_projet', $projet->statut_projet) == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                    <option value="annulé" {{ old('statut_projet', $projet->statut_projet) == 'annulé' ? 'selected' : '' }}>Annulé</option>
                                </select>
                                @error('statut_projet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de début -->
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">
                                    <i class="fas fa-calendar-plus"></i> Date de début <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('date_debut') is-invalid @enderror" 
                                       id="date_debut" 
                                       name="date_debut" 
                                       value="{{ old('date_debut', $projet->date_debut) }}" 
                                       required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de fin -->
                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">
                                    <i class="fas fa-calendar-check"></i> Date de fin (optionnelle)
                                </label>
                                <input type="date" 
                                       class="form-control @error('date_fin') is-invalid @enderror" 
                                       id="date_fin" 
                                       name="date_fin" 
                                       value="{{ old('date_fin', $projet->date_fin) }}">
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">La date de fin doit être postérieure à la date de début</div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left"></i> Description
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Description détaillée du projet, objectifs, contraintes particulières..."
                                          style="resize: vertical;">{{ old('description', $projet->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> <span id="charCount">{{ strlen($projet->description ?? '') }}</span> caractères
                                </div>
                            </div>

                            <!-- Fichier -->
                            <div class="col-md-12 mb-3">
                                <label for="fichier" class="form-label">
                                    <i class="fas fa-paperclip"></i> Fichier joint
                                </label>
                                
                                @if($projet->fichier)
                                    <div class="mb-2">
                                        <div class="alert-info-custom">
                                            <i class="fas fa-file text-primary"></i>
                                            <div style="flex: 1;">
                                                <strong>Fichier actuel :</strong> 
                                                <a href="{{ Storage::url($projet->fichier) }}" target="_blank" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                                                    {{ basename($projet->fichier) }}
                                                </a>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="supprimerFichier()" style="border-radius: 8px;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                
                                <input type="file" 
                                       class="form-control @error('fichier') is-invalid @enderror" 
                                       id="fichier" 
                                       name="fichier"
                                       accept=".pdf,.doc,.docx,.jpg,.png">
                                @error('fichier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Formats acceptés : PDF, DOC, DOCX, JPG, PNG. Taille maximale : 5 MB
                                    @if($projet->fichier)
                                        <br><em>Sélectionner un nouveau fichier remplacera l'actuel</em>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Boutons de validation -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr style="border: none; height: 1px; background: rgba(194, 24, 91, 0.1); margin: 2rem 0;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('admin.projets.show', $projet) }}" class="btn-secondary-custom">
                                            <i class="fas fa-times"></i> Annuler
                                        </a>
                                    </div>
                                    <div style="display: flex; gap: 1rem;">
                                        <button type="button" class="btn-outline-gradient" onclick="previewChanges()">
                                            <i class="fas fa-eye"></i> Aperçu
                                        </button>
                                        <button type="submit" class="btn-gradient">
                                            <i class="fas fa-save"></i> Enregistrer les modifications
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar avec informations -->
        <div class="col-lg-4">
            <!-- Aide -->
            <div class="sidebar-card">
                <div class="card-header">
                    <h6><i class="fas fa-question-circle"></i> Aide</h6>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <h6 style="color: var(--primary-color); font-weight: 700; margin-bottom: 1rem;">Conseils pour la modification :</h6>
                    <ul class="help-list">
                        <li>Vérifiez que les dates sont cohérentes</li>
                        <li>Le statut influence l'affichage du projet</li>
                        <li>La description aide à comprendre le projet</li>
                        <li>Un fichier joint peut contenir le cahier des charges</li>
                    </ul>
                </div>
            </div>

            <!-- Informations actuelles -->
            <div class="sidebar-card">
                <div class="card-header">
                    <h6><i class="fas fa-info-circle"></i> Informations actuelles</h6>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="info-row">
                        <span class="info-label">Créé le :</span>
                        <span class="info-value">{{ $projet->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Modifié le :</span>
                        <span class="info-value">{{ $projet->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ID Projet :</span>
                        <span class="info-value">#{{ $projet->id }}</span>
                    </div>
                    @if($projet->avancements->count() > 0)
                        <div class="info-row">
                            <span class="info-label">Avancement :</span>
                            <span class="info-value">{{ number_format($projet->avancements->avg('pourcentage') ?? 0, 1) }}%</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Attention -->
            <div class="sidebar-card">
                <div class="card-header">
                    <h6><i class="fas fa-exclamation-triangle"></i> Attention</h6>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="alert-warning-custom">
                        <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 1.1rem;"></i>
                        <small style="color: #92400e; line-height: 1.4;">
                            Les modifications seront appliquées immédiatement et le client sera potentiellement notifié selon la configuration du système.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aperçu -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-eye" style="color: var(--primary-color);"></i> Aperçu des modifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Le contenu sera généré dynamiquement -->
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(194, 24, 91, 0.1); padding: 1.5rem;">
                <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <button type="button" class="btn-gradient" onclick="document.getElementById('projetForm').submit()">
                    <i class="fas fa-save"></i> Confirmer et enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Compteur de caractères pour la description
document.getElementById('description').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

// Validation des dates
document.getElementById('date_debut').addEventListener('change', validateDates);
document.getElementById('date_fin').addEventListener('change', validateDates);

function validateDates() {
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;
    
    if (dateDebut && dateFin && dateDebut >= dateFin) {
        document.getElementById('date_fin').setCustomValidity('La date de fin doit être postérieure à la date de début');
    } else {
        document.getElementById('date_fin').setCustomValidity('');
    }
}

// Aperçu des modifications
function previewChanges() {
    const form = document.getElementById('projetForm');
    const formData = new FormData(form);
    
    let previewHTML = '<div class="row">';
    
    // Titre
    previewHTML += `
        <div class="col-12 mb-3">
            <div class="preview-item">
                <div class="preview-label">Titre du projet</div>
                <div class="preview-value">${formData.get('titre')}</div>
            </div>
        </div>
    `;
    
    // Client
    const clientSelect = document.getElementById('user_id');
    const clientText = clientSelect.options[clientSelect.selectedIndex].text;
    previewHTML += `
        <div class="col-md-6 mb-3">
            <div class="preview-item">
                <div class="preview-label">Client assigné</div>
                <div class="preview-value">${clientText}</div>
            </div>
        </div>
    `;
    
    // Statut
    const statutSelect = document.getElementById('statut_projet');
    const statutText = statutSelect.options[statutSelect.selectedIndex].text;
    const statutClass = getStatutClass(formData.get('statut_projet'));
    previewHTML += `
        <div class="col-md-6 mb-3">
            <div class="preview-item">
                <div class="preview-label">Statut du projet</div>
                <div class="preview-value">
                    <span class="status-badge badge-${statutClass}">
                        <i class="fas ${getStatutIcon(formData.get('statut_projet'))}"></i>
                        ${statutText}
                    </span>
                </div>
            </div>
        </div>
    `;
    
    // Dates
    previewHTML += `
        <div class="col-md-6 mb-3">
            <div class="preview-item">
                <div class="preview-label">Date de début</div>
                <div class="preview-value">${formatDate(formData.get('date_debut'))}</div>
            </div>
        </div>
    `;
    
    if (formData.get('date_fin')) {
        previewHTML += `
            <div class="col-md-6 mb-3">
                <div class="preview-item">
                    <div class="preview-label">Date de fin</div>
                    <div class="preview-value">${formatDate(formData.get('date_fin'))}</div>
                </div>
            </div>
        `;
    }
    
    // Description
    if (formData.get('description')) {
        previewHTML += `
            <div class="col-12 mb-3">
                <div class="preview-item">
                    <div class="preview-label">Description</div>
                    <div class="preview-value">${formData.get('description')}</div>
                </div>
            </div>
        `;
    }
    
    // Fichier
    const fichierInput = document.getElementById('fichier');
    if (fichierInput.files.length > 0) {
        previewHTML += `
            <div class="col-12 mb-3">
                <div class="preview-item" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(99, 102, 241, 0.05) 100%);">
                    <div class="preview-label">Nouveau fichier</div>
                    <div class="preview-value">
                        <i class="fas fa-file me-2"></i>${fichierInput.files[0].name}
                        <br><small style="color: #f59e0b;"><i class="fas fa-exclamation-triangle"></i> Ce fichier remplacera l'ancien fichier joint</small>
                    </div>
                </div>
            </div>
        `;
    }
    
    previewHTML += '</div>';
    
    document.getElementById('previewContent').innerHTML = previewHTML;
    
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    previewModal.show();
}

function getStatutClass(statut) {
    switch(statut) {
        case 'en cours': return 'warning';
        case 'terminé': return 'success';
        case 'en attente': return 'info';
        case 'annulé': return 'danger';
        default: return 'secondary';
    }
}

function getStatutIcon(statut) {
    switch(statut) {
        case 'en cours': return 'fa-play-circle';
        case 'terminé': return 'fa-check-circle';
        case 'en attente': return 'fa-pause-circle';
        case 'annulé': return 'fa-times-circle';
        default: return 'fa-circle';
    }
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR');
}

function supprimerFichier() {
    if (confirm('Êtes-vous sûr de vouloir supprimer le fichier actuel ?')) {
        // Cette fonction nécessiterait une route AJAX pour supprimer le fichier
        // Pour l'instant, on peut juste cacher l'alerte
        document.querySelector('.alert-info-custom').style.display = 'none';
        alert('Fonctionnalité à implémenter : suppression du fichier via AJAX');
    }
}

// Validation du formulaire avant soumission
document.getElementById('projetForm').addEventListener('submit', function(e) {
    // Validation supplémentaire côté client si nécessaire
    const titre = document.getElementById('titre').value.trim();
    const client = document.getElementById('user_id').value;
    const dateDebut = document.getElementById('date_debut').value;
    
    if (!titre || !client || !dateDebut) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }
    
    // Validation des dates
    validateDates();
    if (!document.getElementById('date_fin').validity.valid) {
        e.preventDefault();
        alert('Erreur de validation des dates. Vérifiez que la date de fin est postérieure à la date de début.');
        return false;
    }
});

// Auto-save en draft (optionnel)
let autoSaveTimeout;
document.querySelectorAll('input, textarea, select').forEach(element => {
    element.addEventListener('change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Ici on pourrait implémenter un auto-save
            console.log('Auto-save déclenché...');
        }, 3000);
    });
});

// Animation des inputs au focus
document.querySelectorAll('.form-control, .form-select').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 10px 25px rgba(194, 24, 91, 0.15)';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
        this.style.boxShadow = '';
    });
});

// Effet de particules dans le header
function createParticle() {
    const particle = document.createElement('div');
    particle.style.cssText = `
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        pointer-events: none;
        z-index: 1;
    `;
    
    const header = document.querySelector('.page-header');
    if (header) {
        header.appendChild(particle);
        
        const startX = Math.random() * header.offsetWidth;
        const startY = header.offsetHeight + 10;
        
        particle.style.left = startX + 'px';
        particle.style.top = startY + 'px';
        
        particle.animate([
            { transform: 'translateY(0px) scale(0)', opacity: 0 },
            { transform: 'translateY(-100px) scale(1)', opacity: 1 },
            { transform: 'translateY(-200px) scale(0)', opacity: 0 }
        ], {
            duration: 3000,
            easing: 'ease-out'
        }).addEventListener('finish', () => {
            particle.remove();
        });
    }
}

// Créer des particules périodiquement
setInterval(createParticle, 2000);

// Smooth scroll pour les ancres
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
</x-app-layout>