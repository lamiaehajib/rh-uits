<x-app-layout>
    <style>
        :root {
            --primary-color: #C2185B;
            --danger-color: #D32F2F;
            --accent-color: #ef4444;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(194, 24, 91, 0.3);
        }
        
        .solde-card {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05) 0%, rgba(211, 47, 47, 0.05) 100%);
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .solde-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--danger-color));
        }
        
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            padding: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
        }
        
        .input-group-text {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            border: none;
            border-radius: 10px 0 0 10px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .btn-custom-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(194, 24, 91, 0.3);
        }
        
        .btn-custom-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(194, 24, 91, 0.4);
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: white;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-custom:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .progress-custom {
            height: 30px;
            border-radius: 15px;
            background: #f3f4f6;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .progress-bar-custom {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--danger-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            transition: width 0.6s ease;
        }
        
        .solde-stat {
            text-align: center;
        }
        
        .solde-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .preview-box {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05) 0%, rgba(211, 47, 47, 0.05) 100%);
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .preview-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .stat-box {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }
        
        .badge-stat-primary { color: var(--primary-color); }
        .badge-stat-success { color: #10b981; }
        .badge-stat-warning { color: #f59e0b; }
        .badge-stat-secondary { color: #6b7280; }
        
        .alert-info-custom {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            border: none;
            border-left: 4px solid #3b82f6;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .required-star {
            color: var(--danger-color);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="page-header fade-in-up">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2 fw-bold">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Nouvelle Demande de Congé
                    </h2>
                    <p class="mb-0 opacity-75">Remplissez le formulaire pour soumettre votre demande</p>
                </div>
                <a class="btn btn-outline-custom bg-white" href="{{ route('conges.index') }}">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 4px solid var(--danger-color);">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Erreur!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Solde Card -->
        <div class="solde-card fade-in-up" style="animation-delay: 0.1s;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-3 fw-bold" style="color: var(--primary-color);">
                        <i class="fas fa-wallet me-2"></i>
                        Votre solde de congés pour {{ date('Y') }}
                    </h5>
                    <div class="progress-custom">
                        <div class="progress-bar-custom" style="width: {{ ($solde->jours_restants / $solde->total_jours) * 100 }}%">
                            {{ $solde->jours_restants }} / {{ $solde->total_jours }} jours restants
                        </div>
                    </div>
                    <div class="mt-2 text-muted">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            {{ round(($solde->jours_restants / $solde->total_jours) * 100, 1) }}% de votre solde disponible
                        </small>
                    </div>
                </div>
                <div class="col-md-4 solde-stat">
                    <div class="solde-number">{{ $solde->jours_restants }}</div>
                    <p class="text-muted mb-0 fw-semibold">jours disponibles</p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="form-card fade-in-up" style="animation-delay: 0.2s;">
            <div class="form-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-file-alt me-2"></i>
                    Formulaire de demande
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('conges.store') }}" id="congeForm">
                    @csrf
                    <div class="row g-4">
                        <!-- Date début -->
                        <div class="col-md-6">
                            <label for="date_debut" class="form-label">
                                <i class="fas fa-calendar-day text-muted"></i>
                                Date de début 
                                <span class="required-star">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-play"></i>
                                </span>
                                <input type="date" name="date_debut" id="date_debut" 
                                       class="form-control @error('date_debut') is-invalid @enderror" 
                                       value="{{ old('date_debut') }}" 
                                       min="{{ date('Y-m-d') }}" 
                                       required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Date fin -->
                        <div class="col-md-6">
                            <label for="date_fin" class="form-label">
                                <i class="fas fa-calendar-check text-muted"></i>
                                Date de fin 
                                <span class="required-star">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-stop"></i>
                                </span>
                                <input type="date" name="date_fin" id="date_fin" 
                                       class="form-control @error('date_fin') is-invalid @enderror" 
                                       value="{{ old('date_fin') }}" 
                                       min="{{ date('Y-m-d') }}" 
                                       required>
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Motif -->
                        <div class="col-12">
                            <label for="motif" class="form-label">
                                <i class="fas fa-comment-alt text-muted"></i>
                                Motif de la demande
                                <span class="required-star">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <textarea name="motif" id="motif" 
                                          class="form-control @error('motif') is-invalid @enderror" 
                                          rows="4" 
                                          placeholder="Décrivez le motif de votre demande de congé..."
                                          required>{{ old('motif') }}</textarea>
                                @error('motif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Prévisualisation -->
                    <div id="preview" style="display: none;">
                        <div class="preview-box">
                            <h6 class="preview-title">
                                <i class="fas fa-calculator"></i>
                                Aperçu du calcul
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="stat-box text-center">
                                        <i class="fas fa-calendar fa-2x badge-stat-primary mb-2"></i>
                                        <div class="stat-value badge-stat-primary" id="preview_total">-</div>
                                        <small class="text-muted fw-semibold">Total jours</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-box text-center">
                                        <i class="fas fa-briefcase fa-2x badge-stat-success mb-2"></i>
                                        <div class="stat-value badge-stat-success" id="preview_ouvrables">-</div>
                                        <small class="text-muted fw-semibold">Jours ouvrables</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-box text-center">
                                        <i class="fas fa-bed fa-2x badge-stat-warning mb-2"></i>
                                        <div class="stat-value badge-stat-warning" id="preview_repos">-</div>
                                        <small class="text-muted fw-semibold">Jours repos</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-box text-center">
                                        <i class="fas fa-star fa-2x badge-stat-secondary mb-2"></i>
                                        <div class="stat-value badge-stat-secondary" id="preview_feries">-</div>
                                        <small class="text-muted fw-semibold">Jours fériés</small>
                                    </div>
                                </div>
                            </div>
                            <div class="alert-info-custom">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Important:</strong> Tous les jours (y compris week-ends et jours fériés) sont comptabilisés dans votre solde de congés.
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-custom" onclick="window.history.back()">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-custom-primary">
                            <i class="fas fa-paper-plane me-2"></i>Soumettre la demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        $(document).ready(function() {
            $('#date_debut, #date_fin').on('change', function() {
                const dateDebut = $('#date_debut').val();
                const dateFin = $('#date_fin').val();
                
                if (dateDebut && dateFin) {
                    if (new Date(dateFin) < new Date(dateDebut)) {
                        alert('⚠️ La date de fin doit être après la date de début');
                        $('#date_fin').val('');
                        return;
                    }
                    
                    $.ajax({
                        url: '{{ route("conges.preview") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            date_debut: dateDebut,
                            date_fin: dateFin
                        },
                        success: function(response) {
                            $('#preview').fadeIn();
                            $('#preview_total').text(response.total);
                            $('#preview_ouvrables').text(response.ouvrables);
                            $('#preview_repos').text(response.repos);
                            $('#preview_feries').text(response.feries);
                            
                            if (response.total > {{ $solde->jours_restants }}) {
                                alert('⚠️ Attention: Vous n\'avez pas assez de jours disponibles ({{ $solde->jours_restants }} jours restants)');
                            }
                        },
                        error: function(xhr) {
                            console.error('Erreur lors du calcul:', xhr);
                        }
                    });
                }
            });
        });
    </script>
    @endsection
</x-app-layout>