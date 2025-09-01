<x-app-layout>
<style>
    :root {
        --primary-color: #C2185B;
        --secondary-color: #D32F2F;
        --accent-color: #ef4444;
        --gradient-bg: linear-gradient(135deg, #C2185B 0%, #D32F2F 50%, #ef4444 100%);
        --card-shadow: 0 10px 30px rgba(194, 24, 91, 0.15);
        --hover-shadow: 0 15px 40px rgba(194, 24, 91, 0.25);
    }

    body {
        background: linear-gradient(135deg, #fef7ff 0%, #fff1f2 50%, #fef2f2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container-fluid {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header Styling */
    .page-header {
        background: var(--gradient-bg);
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="60" r="1" fill="white" opacity="0.1"/><circle cx="90" cy="80" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }

    .page-header h2 {
        color: white;
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1;
    }

    .page-header .text-muted {
        color: rgba(255,255,255,0.9) !important;
        font-size: 1.1rem;
        position: relative;
        z-index: 1;
    }

    .page-header a {
        color: white !important;
        text-decoration: none;
        border-bottom: 2px solid rgba(255,255,255,0.5);
        transition: all 0.3s ease;
    }

    .page-header a:hover {
        border-bottom-color: white;
        transform: translateY(-2px);
    }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: var(--hover-shadow);
        transform: translateY(-5px);
    }

    .card-header {
        background: var(--gradient-bg);
        border: none;
        padding: 1.5rem;
        position: relative;
    }

    .card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    }

    .card-header h5, .card-header h6 {
        color: white;
        font-weight: 600;
        margin: 0;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    .card-body {
        padding: 2rem;
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.25);
        background: white;
        transform: scale(1.02);
    }

    .form-range {
        height: 8px;
        background: linear-gradient(to right, #e9ecef, #e9ecef);
        border-radius: 10px;
        outline: none;
        margin: 1rem 0;
    }

    .form-range::-webkit-slider-thumb {
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--gradient-bg);
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(194, 24, 91, 0.3);
        transition: all 0.3s ease;
    }

    .form-range::-webkit-slider-thumb:hover {
        transform: scale(1.2);
        box-shadow: 0 4px 12px rgba(194, 24, 91, 0.4);
    }

    .input-group {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .input-group .form-control {
        border-radius: 0;
        border-right: none;
    }

    .input-group-text {
        background: var(--gradient-bg);
        color: white;
        border: none;
        font-weight: 600;
        padding: 0.75rem 1rem;
    }

    /* Buttons */
    .btn {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
    }

    .btn-primary {
        background: var(--gradient-bg);
        color: white;
        box-shadow: 0 4px 15px rgba(194, 24, 91, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(194, 24, 91, 0.4);
        background: linear-gradient(135deg, #a91349 0%, #b71c1c 50%, #dc2626 100%);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
    }

    /* Progress Bar */
    .progress {
        border-radius: 15px;
        background: rgba(233, 236, 239, 0.8);
        backdrop-filter: blur(10px);
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 15px;
        transition: all 0.6s ease;
        position: relative;
        overflow: hidden;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: shine 2s infinite;
    }

    @keyframes shine {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .bg-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    }

    .bg-info {
        background: var(--gradient-bg) !important;
    }

    .bg-warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
    }

    /* Alert Styling */
    .alert-info {
        background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
        border: 2px solid var(--primary-color);
        border-radius: 15px;
        color: var(--secondary-color);
    }

    .alert-info a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        border-bottom: 1px solid var(--primary-color);
        transition: all 0.3s ease;
    }

    .alert-info a:hover {
        color: var(--secondary-color);
        border-bottom-color: var(--secondary-color);
    }

    /* Required asterisk */
    .text-danger {
        color: var(--accent-color) !important;
        font-weight: bold;
    }

    /* Form text */
    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Invalid feedback */
    .invalid-feedback {
        color: var(--accent-color);
        font-weight: 500;
    }

    .is-invalid {
        border-color: var(--accent-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.25) !important;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        animation: fadeInUp 0.6s ease-out;
    }

    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--gradient-bg);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #a91349 0%, #b71c1c 50%, #dc2626 100%);
    }

    /* Responsive enhancements */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.8rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
    }

    /* Hover effects for form elements */
    .form-control:hover:not(:focus), .form-select:hover:not(:focus) {
        border-color: var(--primary-color);
        box-shadow: 0 2px 8px rgba(194, 24, 91, 0.1);
    }

    /* File input styling */
    input[type="file"] {
        background: rgba(255, 255, 255, 0.9);
        border: 2px dashed var(--primary-color);
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    input[type="file"]:hover {
        background: rgba(194, 24, 91, 0.05);
        border-style: solid;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h2 class="h4 mb-1">
                    <i class="fas fa-tasks me-3"></i>
                    Modifier l'étape d'avancement
                </h2>
                <p class="text-muted mb-0">
                    <i class="fas fa-project-diagram me-2"></i>
                    Projet: <a href="{{ route('admin.projets.show', $projet) }}" class="text-decoration-none">{{ $projet->titre }}</a>
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Informations de l'étape
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.avancements.update', [$projet, $avancement]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nom de l'étape -->
                        <div class="mb-4">
                            <label for="etape" class="form-label">
                                <i class="fas fa-tag me-2"></i>
                                Nom de l'étape <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('etape') is-invalid @enderror" 
                                   id="etape" 
                                   name="etape" 
                                   value="{{ old('etape', $avancement->etape) }}" 
                                   required
                                   placeholder="Saisir le nom de l'étape...">
                            @error('etape')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required
                                      placeholder="Décrire les détails de cette étape...">{{ old('description', $avancement->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Row for Pourcentage and Statut -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="pourcentage" class="form-label">
                                        <i class="fas fa-percentage me-2"></i>
                                        Pourcentage d'achèvement <span class="text-danger">*</span>
                                    </label>
                                    <div class="mb-3">
                                        <input type="range" 
                                               class="form-range" 
                                               id="pourcentageRange" 
                                               min="0" 
                                               max="100" 
                                               value="{{ old('pourcentage', $avancement->pourcentage) }}"
                                               oninput="document.getElementById('pourcentage').value = this.value; document.getElementById('pourcentageDisplay').textContent = this.value + '%'">
                                    </div>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('pourcentage') is-invalid @enderror" 
                                               id="pourcentage" 
                                               name="pourcentage" 
                                               value="{{ old('pourcentage', $avancement->pourcentage) }}" 
                                               min="0" 
                                               max="100"
                                               oninput="document.getElementById('pourcentageRange').value = this.value; document.getElementById('pourcentageDisplay').textContent = this.value + '%'"
                                               required>
                                        <span class="input-group-text" id="pourcentageDisplay">{{ old('pourcentage', $avancement->pourcentage) }}%</span>
                                    </div>
                                    @error('pourcentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="statut" class="form-label">
                                        <i class="fas fa-flag me-2"></i>
                                        Statut <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('statut') is-invalid @enderror" 
                                            id="statut" 
                                            name="statut" 
                                            required>
                                        <option value="">
                                            <i class="fas fa-hand-pointer"></i> 
                                            Choisir un statut
                                        </option>
                                        <option value="en cours" {{ old('statut', $avancement->statut) == 'en cours' ? 'selected' : '' }}>
                                            🔄 En cours
                                        </option>
                                        <option value="terminé" {{ old('statut', $avancement->statut) == 'terminé' ? 'selected' : '' }}>
                                            ✅ Terminé
                                        </option>
                                        <option value="bloqué" {{ old('statut', $avancement->statut) == 'bloqué' ? 'selected' : '' }}>
                                            ⚠️ Bloqué
                                        </option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Row for Dates -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="date_prevue" class="form-label">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Date prévue
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('date_prevue') is-invalid @enderror" 
                                           id="date_prevue" 
                                           name="date_prevue" 
                                           value="{{ old('date_prevue', $avancement->date_prevue?->format('Y-m-d')) }}">
                                    @error('date_prevue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="date_realisee" class="form-label">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Date réalisée
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('date_realisee') is-invalid @enderror" 
                                           id="date_realisee" 
                                           name="date_realisee" 
                                           value="{{ old('date_realisee', $avancement->date_realisee?->format('Y-m-d')) }}">
                                    @error('date_realisee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Commentaires -->
                        {{-- <div class="mb-4">
                            <label for="commentaires" class="form-label">
                                <i class="fas fa-comments me-2"></i>
                                Commentaires
                            </label>
                            <textarea class="form-control @error('commentaires') is-invalid @enderror" 
                                      id="commentaires" 
                                      name="commentaires" 
                                      rows="3" 
                                      placeholder="Ajouter des commentaires additionnels...">{{ old('commentaires', $avancement->commentaires) }}</textarea>
                            @error('commentaires')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <!-- Upload de fichier -->
                        <div class="mb-4">
                            <label for="fichiers" class="form-label">
                                <i class="fas fa-paperclip me-2"></i>
                                Nouveau fichier joint
                            </label>
                            @if($avancement->fichiers)
                                <div class="mb-3">
                                    <div class="alert alert-info py-3">
                                        <i class="fas fa-info-circle me-2"></i> 
                                        <strong>Fichier actuel:</strong>
                                        <a href="{{ Storage::url($avancement->fichiers) }}" target="_blank" class="ms-2">
                                            <i class="fas fa-download me-1"></i>
                                            {{ basename($avancement->fichiers) }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('fichiers') is-invalid @enderror" 
                                   id="fichiers" 
                                   name="fichiers" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                            <div class="form-text">
                                <i class="fas fa-file-upload me-1"></i>
                                <strong>Formats acceptés:</strong> PDF, DOC, DOCX, JPG, PNG, ZIP (Max: 10MB)
                                @if($avancement->fichiers)
                                    <br><i class="fas fa-exclamation-triangle me-1" style="color: var(--accent-color);"></i>
                                    <strong>Note:</strong> Uploader un nouveau fichier remplacera le fichier actuel.
                                @endif
                            </div>
                            @error('fichiers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.avancements.show', [$projet, $avancement]) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Progression actuelle -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Progression actuelle
                    </h6>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 30px;">
                        <div class="progress-bar 
                            @if($avancement->pourcentage == 100) bg-success
                            @elseif($avancement->pourcentage >= 50) bg-info
                            @else bg-warning
                            @endif" 
                             role="progressbar" 
                             style="width: {{ $avancement->pourcentage }}%">
                            <strong>{{ $avancement->pourcentage }}%</strong>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info me-1"></i>
                        Progression de cette étape
                    </small>
                </div>
            </div>

            <!-- Historique -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historique
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-plus-circle me-2" style="color: var(--primary-color);"></i>
                            <small class="text-muted">Créé le</small>
                        </div>
                        <p class="mb-0 fw-semibold">{{ $avancement->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-edit me-2" style="color: var(--secondary-color);"></i>
                            <small class="text-muted">Dernière modification</small>
                        </div>
                        <p class="mb-0 fw-semibold">{{ $avancement->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-update date_realisee when status is set to 'terminé'
document.getElementById('statut').addEventListener('change', function() {
    const dateRealiseInput = document.getElementById('date_realisee');
    const pourcentageInput = document.getElementById('pourcentage');
    const pourcentageRange = document.getElementById('pourcentageRange');
    const pourcentageDisplay = document.getElementById('pourcentageDisplay');
    
    if (this.value === 'terminé') {
        if (!dateRealiseInput.value) {
            dateRealiseInput.value = new Date().toISOString().split('T')[0];
        }
        pourcentageInput.value = 100;
        pourcentageRange.value = 100;
        pourcentageDisplay.textContent = '100%';
        
        // Add success animation
        pourcentageInput.style.transform = 'scale(1.05)';
        setTimeout(() => {
            pourcentageInput.style.transform = 'scale(1)';
        }, 200);
    }
});

// Auto-update status when percentage reaches 100%
document.getElementById('pourcentage').addEventListener('input', function() {
    const statutSelect = document.getElementById('statut');
    const dateRealiseInput = document.getElementById('date_realisee');
    
    if (this.value == 100 && statutSelect.value !== 'terminé') {
        if (confirm('Marquer automatiquement le statut comme "terminé" ?')) {
            statutSelect.value = 'terminé';
            if (!dateRealiseInput.value) {
                dateRealiseInput.value = new Date().toISOString().split('T')[0];
            }
            
            // Add celebration effect
            this.style.borderColor = '#28a745';
            this.style.boxShadow = '0 0 20px rgba(40, 167, 69, 0.3)';
            setTimeout(() => {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }, 1000);
        }
    }
});

// Add smooth transitions on load
window.addEventListener('load', function() {
    document.querySelectorAll('.card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// File input enhancement
document.getElementById('fichiers').addEventListener('change', function() {
    if (this.files[0]) {
        this.style.borderColor = 'var(--primary-color)';
        this.style.background = 'rgba(194, 24, 91, 0.1)';
    }
});

// Form validation enhancement
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mise à jour...';
    submitBtn.disabled = true;
});
</script>
</x-app-layout>