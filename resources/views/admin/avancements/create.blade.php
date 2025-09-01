<x-app-layout>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
       
        min-height: 100vh;
        line-height: 1.6;
    }

    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Header Styles */
    .header-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: fadeInUp 0.6s ease;
    }

    .header-section h2 {
        color: #C2185B;
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-section p {
        color: #666;
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Form Card Styles */
    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        animation: fadeInUp 0.6s ease 0.1s both;
        margin-bottom: 2rem;
    }

    .card-header {
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        color: white;
        padding: 1.5rem 2rem;
        border: none;
    }

    .card-header h5 {
        font-weight: 700;
        font-size: 1.3rem;
        margin: 0;
    }

    .card-body {
        padding: 2rem;
    }

    /* Form Styles */
    .form-label {
        color: #333;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        border: 2px solid rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 0.8rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: #C2185B;
        box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
        background: white;
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .form-select {
        border: 2px solid rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 0.8rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
    }

    .form-select:focus {
        border-color: #C2185B;
        box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
        background: white;
    }

    .form-range {
        height: 8px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        outline: none;
        margin: 1rem 0;
    }

    .form-range::-webkit-slider-thumb {
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(194, 24, 91, 0.3);
        transition: all 0.3s ease;
    }

    .form-range::-webkit-slider-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(194, 24, 91, 0.4);
    }

    .form-range::-webkit-slider-track {
        height: 8px;
        background: linear-gradient(90deg, #C2185B, #D32F2F, #ef4444);
        border-radius: 4px;
    }

    .input-group {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .input-group .form-control {
        flex: 0 0 100px;
    }

    .input-group-text {
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8rem 1rem;
        font-weight: 600;
        min-width: 60px;
        text-align: center;
    }

    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 16px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(194, 24, 91, 0.3);
        cursor: pointer;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(194, 24, 91, 0.4);
        color: white;
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(194, 24, 91, 0.2);
        color: #C2185B;
        padding: 0.8rem 2rem;
        border-radius: 16px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: rgba(194, 24, 91, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(194, 24, 91, 0.2);
        color: #C2185B;
        text-decoration: none;
    }

    /* Sidebar Card */
    .sidebar-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        animation: fadeInUp 0.6s ease 0.2s both;
        margin-bottom: 2rem;
    }

    .sidebar-card .card-header {
        background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
        color: #333;
        padding: 1.5rem 2rem;
        font-weight: 700;
    }

    .sidebar-card .card-header h6 {
        margin: 0;
        font-size: 1.1rem;
        color: #C2185B;
    }

    .sidebar-card .card-body {
        padding: 2rem;
    }

    .sidebar-card .card-body p {
        margin-bottom: 1rem;
        color: #555;
    }

    .sidebar-card .card-body strong {
        color: #C2185B;
        font-weight: 600;
    }

    /* Form Validation */
    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .form-text {
        color: #666;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    /* File Upload Styling */
    .form-control[type="file"] {
        padding: 0.6rem;
        background: rgba(255, 255, 255, 0.9);
    }

    .form-control[type="file"]::-webkit-file-upload-button {
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        margin-right: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-control[type="file"]::-webkit-file-upload-button:hover {
        background: linear-gradient(135deg, #D32F2F, #ef4444);
        transform: translateY(-1px);
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

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Form Group Animations */
    .mb-3 {
        animation: slideIn 0.4s ease forwards;
        animation-delay: calc(var(--delay, 0) * 0.1s);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .header-section h2 {
            font-size: 1.8rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .input-group {
            flex-direction: column;
            align-items: stretch;
        }

        .input-group .form-control {
            flex: 1;
        }
    }

    /* Custom Focus States */
    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: #C2185B;
        box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
    }

    /* Progress Display Enhancement */
    #pourcentageDisplay {
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        color: white;
        font-weight: 700;
        min-width: 70px;
        text-align: center;
        border-radius: 12px;
    }

    /* Form Row Enhancement */
    .row .col-md-6 {
        padding: 0 0.75rem;
    }

    /* Floating Labels Effect */
    .form-floating {
        position: relative;
    }

    .form-floating .form-control:focus ~ label,
    .form-floating .form-control:not(:placeholder-shown) ~ label {
        transform: scale(0.85) translateY(-0.5rem);
        color: #C2185B;
    }

    /* Enhanced Required Field Indicator */
    .text-danger {
        color: #ef4444 !important;
        font-weight: 700;
    }

    /* Button Container */
    .button-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* Success/Error Message Styles */
    .alert {
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: none;
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(102, 187, 106, 0.1));
        color: #2e7d32;
        border-left: 4px solid #4caf50;
    }

    .alert-danger {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(211, 47, 47, 0.1));
        color: #c62828;
        border-left: 4px solid #ef4444;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="header-section">
        <h2>Nouvelle étape d'avancement</h2>
        <p>Projet: {{ $projet->titre }}</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="card-header">
                    <h5>Informations de l'étape</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.avancements.store', $projet) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Nom de l'étape -->
                        <div class="mb-3" style="--delay: 1">
                            <label for="etape" class="form-label">Nom de l'étape <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('etape') is-invalid @enderror" 
                                   id="etape" 
                                   name="etape" 
                                   value="{{ old('etape') }}" 
                                   placeholder="Ex: Conception des maquettes"
                                   required>
                            @error('etape')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3" style="--delay: 2">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Décrivez en détail cette étape d'avancement..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Row for Pourcentage and Statut -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3" style="--delay: 3">
                                    <label for="pourcentage" class="form-label">Pourcentage d'achèvement <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="range" 
                                               class="form-range" 
                                               id="pourcentageRange" 
                                               min="0" 
                                               max="100" 
                                               value="{{ old('pourcentage', 0) }}"
                                               oninput="document.getElementById('pourcentage').value = this.value; document.getElementById('pourcentageDisplay').textContent = this.value + '%'">
                                        <input type="number" 
                                               class="form-control @error('pourcentage') is-invalid @enderror" 
                                               id="pourcentage" 
                                               name="pourcentage" 
                                               value="{{ old('pourcentage', 0) }}" 
                                               min="0" 
                                               max="100"
                                               oninput="document.getElementById('pourcentageRange').value = this.value; document.getElementById('pourcentageDisplay').textContent = this.value + '%'"
                                               required>
                                        <span class="input-group-text" id="pourcentageDisplay">{{ old('pourcentage', 0) }}%</span>
                                    </div>
                                    @error('pourcentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" style="--delay: 4">
                                    <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select @error('statut') is-invalid @enderror" 
                                            id="statut" 
                                            name="statut" 
                                            required>
                                        <option value="">Choisir un statut</option>
                                        <option value="en cours" {{ old('statut') == 'en cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="terminé" {{ old('statut') == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                        <option value="bloqué" {{ old('statut') == 'bloqué' ? 'selected' : '' }}>Bloqué</option>
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
                                <div class="mb-3" style="--delay: 5">
                                    <label for="date_prevue" class="form-label">Date prévue</label>
                                    <input type="date" 
                                           class="form-control @error('date_prevue') is-invalid @enderror" 
                                           id="date_prevue" 
                                           name="date_prevue" 
                                           value="{{ old('date_prevue') }}">
                                    @error('date_prevue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" style="--delay: 6">
                                    <label for="date_realisee" class="form-label">Date réalisée</label>
                                    <input type="date" 
                                           class="form-control @error('date_realisee') is-invalid @enderror" 
                                           id="date_realisee" 
                                           name="date_realisee" 
                                           value="{{ old('date_realisee') }}">
                                    @error('date_realisee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Commentaires -->
                        {{-- <div class="mb-3" style="--delay: 7">
                            <label for="commentaires" class="form-label">Commentaires</label>
                            <textarea class="form-control @error('commentaires') is-invalid @enderror" 
                                      id="commentaires" 
                                      name="commentaires" 
                                      rows="3" 
                                      placeholder="Commentaires additionnels...">{{ old('commentaires') }}</textarea>
                            @error('commentaires')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <!-- Upload de fichier -->
                        <div class="mb-3" style="--delay: 8">
                            <label for="fichiers" class="form-label">Fichier joint</label>
                            <input type="file" 
                                   class="form-control @error('fichiers') is-invalid @enderror" 
                                   id="fichiers" 
                                   name="fichiers" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                            <div class="form-text">
                                Formats acceptés: PDF, DOC, DOCX, JPG, PNG, ZIP (Max: 10MB)
                            </div>
                            @error('fichiers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="button-container" style="--delay: 9">
                            <a href="{{ route('admin.avancements.index', $projet) }}" class="btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Créer l'étape
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar with project info -->
        <div class="col-lg-4">
            <div class="sidebar-card">
                <div class="card-header">
                    <h6>Informations du projet</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> {{ $projet->titre }}</p>
                    @if($projet->description)
                        <p><strong>Description:</strong></p>
                        <p style="color: #666;">{{ Str::limit($projet->description, 100) }}</p>
                    @endif
                    @if($projet->date_debut)
                        <p><strong>Date de début:</strong> {{ $projet->date_debut->format('d/m/Y') }}</p>
                    @endif
                    @if($projet->date_fin)
                        <p><strong>Date de fin:</strong> {{ $projet->date_fin->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Animation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Animation séquentielle des éléments de formulaire
    const formGroups = document.querySelectorAll('.mb-3');
    formGroups.forEach((group, index) => {
        group.style.setProperty('--delay', index);
    });
    
    // Amélioration de l'interaction avec le range slider
    const rangeInput = document.getElementById('pourcentageRange');
    const numberInput = document.getElementById('pourcentage');
    const display = document.getElementById('pourcentageDisplay');
    
    function updateProgress() {
        const value = this.value;
        rangeInput.value = value;
        numberInput.value = value;
        display.textContent = value + '%';
        
        // Animation de couleur basée sur la valeur
        const hue = (value / 100) * 120; // De rouge à vert
        rangeInput.style.background = `linear-gradient(90deg, #C2185B ${value}%, rgba(0,0,0,0.1) ${value}%)`;
    }
    
    rangeInput.addEventListener('input', updateProgress);
    numberInput.addEventListener('input', updateProgress);
    
    // Animation des champs au focus
    const formControls = document.querySelectorAll('.form-control, .form-select');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px rgba(194, 24, 91, 0.15)';
        });
        
        control.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Validation en temps réel
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('.btn-primary[type="submit"]');
    
    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
            }
        });
        
        if (isValid) {
            submitBtn.style.background = 'linear-gradient(135deg, #66bb6a, #4caf50)';
        } else {
            submitBtn.style.background = 'linear-gradient(135deg, #C2185B, #D32F2F)';
        }
    }
    
    form.addEventListener('input', validateForm);
    form.addEventListener('change', validateForm);
    
    // Animation au submit
    form.addEventListener('submit', function() {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création...';
        submitBtn.style.background = 'linear-gradient(135deg, #42a5f5, #1e88e5)';
    });
});

// Script pour mise à jour rapide du pourcentage (optionnel)
function updatePourcentage(avancementId, newValue) {
    fetch(`/admin/projets/{{ $projet->id }}/avancements/${avancementId}/pourcentage`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            pourcentage: newValue
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
</x-app-layout>