<x-app-layout>
    <style>
        :root {
            --primary-dark: #C2185B;
            --primary-medium: #D32F2F;
            --primary-light: #ef4444;
            --bg-color: #f0f2f5;
            --card-bg: #ffffff;
            --text-color: #495057;
            --light-text-color: #888;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Poppins', sans-serif;
        }

        .page-title {
            color: var(--primary-dark);
            font-weight: 700;
            letter-spacing: -1px;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background-color: var(--card-bg);
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .btn-custom-primary {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #ffffff;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-custom-primary:hover {
            background-color: #a4154b;
            border-color: #a4154b;
            transform: translateY(-2px);
        }

        .btn-custom-outline-danger {
            color: var(--primary-medium);
            border-color: var(--primary-medium);
            background-color: transparent;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-custom-outline-danger:hover {
            background-color: var(--primary-medium);
            color: #ffffff;
            border-color: var(--primary-medium);
        }

        .progress {
            height: 25px;
            border-radius: 12px;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 12px;
            transition: width 0.6s ease;
            font-weight: bold;
            color: white;
        }

        .badge-status {
            font-size: 0.9rem;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
        }
        
        .alert-info {
            background-color: #e3f2fd;
            color: #014387;
            border-left: 5px solid #0d6efd;
            border-radius: 0.75rem;
        }

        .quick-actions .btn {
            border-radius: 0.75rem;
        }
    </style>

    <div class="container-fluid py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="page-title">{{ $avancement->etape }}</h1>
                <p class="text-muted lead">
                    Projet: <a href="{{ route('admin.projets.show', $projet) }}" class="text-decoration-none fw-bold" style="color: var(--primary-medium);">{{ $projet->titre }}</a>
                </p>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('admin.avancements.edit', [$projet, $avancement]) }}" class="btn btn-custom-primary">
                    <i class="fas fa-edit me-2"></i> Modifier l'étape
                </a>
                <form action="{{ route('admin.avancements.destroy', [$projet, $avancement]) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette étape ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-custom-outline-danger">
                        <i class="fas fa-trash-alt me-2"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">Détails de l'avancement</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">Description</h6>
                            <p class="text-color">{!! nl2br(e($avancement->description)) !!}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">Progression</h6>
                            <div class="progress mb-2">
                                <div class="progress-bar 
                                     @if($avancement->pourcentage == 100) bg-success
                                     @elseif($avancement->pourcentage >= 50) bg-info
                                     @else bg-warning
                                     @endif"
                                     role="progressbar" style="width: {{ $avancement->pourcentage }}%"
                                     aria-valuenow="{{ $avancement->pourcentage }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $avancement->pourcentage }}%
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h6 class="fw-bold text-muted">Statut</h6>
                                @switch($avancement->statut)
                                    @case('en cours')
                                        <span class="badge bg-warning text-dark badge-status">
                                            <i class="fas fa-clock me-1"></i> En cours
                                        </span>
                                        @break
                                    @case('terminé')
                                        <span class="badge bg-success badge-status">
                                            <i class="fas fa-check-circle me-1"></i> Terminé
                                        </span>
                                        @break
                                    @case('bloqué')
                                        <span class="badge bg-danger badge-status">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Bloqué
                                        </span>
                                        @break
                                @endswitch
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h6 class="fw-bold text-muted">Date prévue</h6>
                                <p class="mb-0">{{ $avancement->date_prevue ? $avancement->date_prevue->format('d/m/Y') : 'Non définie' }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted">Date réalisée</h6>
                                <p class="mb-0">{{ $avancement->date_realisee ? $avancement->date_realisee->format('d/m/Y') : 'Non réalisée' }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-muted"><i class="fas fa-comment-dots me-2"></i>Commentaires du client</h6>
                            @if($avancement->commentaires)
                                <div class="alert alert-info" role="alert">
                                    <p class="mb-0">{!! nl2br(e($avancement->commentaires)) !!}</p>
                                </div>
                            @else
                                <p class="text-light-text-color">Aucun commentaire n'a été laissé pour le moment.</p>
                            @endif
                        </div>

                        @if($avancement->fichiers)
                            <div class="mt-4 pt-4 border-top">
                                <h6 class="fw-bold text-muted"><i class="fas fa-paperclip me-2"></i>Fichier joint</h6>
                                <a href="{{ route('client.avancements.download', $avancement) }}" target="_blank" class="btn btn-custom-primary">
                                    <i class="fas fa-download me-2"></i> Télécharger le fichier
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0">Informations rapides</h6>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <small class="text-muted d-block">Créé le</small>
                                <p class="mb-0 fw-bold">{{ $avancement->created_at->format('d/m/Y à H:i') }}</p>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Dernière modification</small>
                                <p class="mb-0 fw-bold">{{ $avancement->updated_at->format('d/m/Y à H:i') }}</p>
                            </li>
                            @if($avancement->date_prevue)
                                <li>
                                    <small class="text-muted d-block">Délai</small>
                                    <p class="mb-0 fw-bold">
                                        @if($avancement->date_prevue->isPast() && $avancement->statut !== 'terminé')
                                            <span class="text-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i> En retard de {{ $avancement->date_prevue->diffInDays(now()) }} jour(s)
                                            </span>
                                        @elseif($avancement->date_prevue->isFuture())
                                            <span class="text-info">
                                                <i class="fas fa-calendar-alt me-1"></i> Dans {{ now()->diffInDays($avancement->date_prevue) }} jour(s)
                                            </span>
                                        @else
                                            <span class="text-success">
                                                <i class="fas fa-check-circle me-1"></i> À temps
                                            </span>
                                        @endif
                                    </p>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="card quick-actions">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0">Actions rapides</h6>
                    </div>
                    <div class="card-body p-4">
                        @if($avancement->statut !== 'terminé')
                            <button class="btn btn-success w-100 mb-3" onclick="markAsCompleted()">
                                <i class="fas fa-check-double me-2"></i> Marquer comme terminé
                            </button>
                        @endif

                        <div class="mb-3">
                            <label for="quickPourcentage" class="form-label small text-muted fw-bold">Mise à jour rapide du pourcentage:</label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-sm" id="quickPourcentage" value="{{ $avancement->pourcentage }}" min="0" max="100">
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuickPourcentage()">
                                    Mettre à jour
                                </button>
                            </div>
                        </div>

                        <a href="{{ route('admin.avancements.index', $projet) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-list-ul me-2"></i> Voir toutes les étapes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function markAsCompleted() {
            if (confirm('Marquer cette étape comme terminée (100%) ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.avancements.update", [$projet, $avancement]) }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);

                const values = {
                    'etape': '{{ $avancement->etape }}',
                    'description': '{{ $avancement->description }}',
                    'pourcentage': '100',
                    'statut': 'terminé',
                    'date_prevue': '{{ $avancement->date_prevue ? $avancement->date_prevue->format("Y-m-d") : "" }}',
                    'date_realisee': '{{ now()->format("Y-m-d") }}',
                    'commentaires': '{{ $avancement->commentaires }}'
                };

                Object.entries(values).forEach(([name, value]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        }

        function updateQuickPourcentage() {
            const newValue = document.getElementById('quickPourcentage').value;
            
            if (newValue < 0 || newValue > 100) {
                alert('Le pourcentage doit être entre 0 et 100.');
                return;
            }
            
            fetch('{{ route("admin.avancements.update-pourcentage", [$projet, $avancement]) }}', {
                method: 'PATCH',
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
                } else {
                    alert('Erreur lors de la mise à jour');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour');
            });
        }
    </script>
    @endpush
</x-app-layout>