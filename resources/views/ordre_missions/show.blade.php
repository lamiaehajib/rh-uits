<x-app-layout>
<div class="container-fluid" style="max-width:980px;">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('ordre-missions.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
           style="width:36px;height:36px;">
            <i class="fas fa-arrow-left" style="font-size:13px;"></i>
        </a>
        <div class="flex-grow-1">
            <h3 class="mb-0">
                <i class="fas fa-file-alt me-2"></i>Ordre de Mission #{{ $ordreMission->id }}
            </h3>
            <small class="text-muted">
                Soumis le {{ $ordreMission->created_at->format('d/m/Y à H:i') }}
                par <strong>{{ $ordreMission->employe->name }}</strong>
            </small>
        </div>
        @php
        $sc = [
            'en_attente' => ['#FFF8E1','#F59E0B','⏳ En attente'],
            'approuve'   => ['#E8F5E9','#10B981','✅ Approuvé'],
            'refuse'     => ['#FFEBEE','#D32F2F','❌ Refusé'],
            'annule'     => ['#F3F4F6','#6B7280','🚫 Annulé'],
            'cloture'    => ['#E3F2FD','#1E88E5','🏁 Clôturé'],
        ][$ordreMission->statut] ?? ['#F3F4F6','#6B7280','?'];
        @endphp
        <span class="badge rounded-pill px-4 py-2 fs-6"
              style="background:{{ $sc[0] }};color:{{ $sc[1] }};font-weight:700;">
            {{ $sc[2] }}
        </span>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">

        {{-- ══ COLONNE PRINCIPALE ═══════════════════════════════ --}}
        <div class="col-lg-8">

            {{-- Détails mission --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-0 rounded-top-3 py-3 px-4"
                     style="background:linear-gradient(135deg,#C2185B,#D32F2F);">
                    <h6 class="text-white fw-bold mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Détails de la Mission
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background:#FFF0F3;">
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-user me-1" style="color:#C2185B;"></i>Employé
                                </div>
                                <div class="fw-bold">{{ $ordreMission->employe->name }}</div>
                                <div class="text-muted small">{{ $ordreMission->employe->poste }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background:#FFF0F3;">
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-map-marker-alt me-1" style="color:#C2185B;"></i>Destination
                                </div>
                                <div class="fw-bold fs-5">{{ $ordreMission->destination }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded-3" style="background:#f8f9fa;">
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-bullseye me-1" style="color:#C2185B;"></i>Objet
                                </div>
                                <div class="fw-semibold">{{ $ordreMission->objet }}</div>
                            </div>
                        </div>

                        {{-- Dates avec heure --}}
                        <div class="col-md-4">
                            <div class="text-center p-3 rounded-3" style="background:#f8f9fa;">
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-calendar-alt me-1"></i>Départ
                                </div>
                                <div class="fw-bold" style="color:#C2185B;">
                                    {{ $ordreMission->date_depart->format('d/m/Y') }}
                                </div>
                                <div class="badge rounded-pill mt-1"
                                     style="background:#FFF0F3;color:#C2185B;font-size:12px;">
                                    <i class="fas fa-clock me-1"></i>{{ $ordreMission->date_depart->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 rounded-3" style="background:#f8f9fa;">
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-calendar-check me-1"></i>Retour
                                </div>
                                <div class="fw-bold" style="color:#C2185B;">
                                    {{ $ordreMission->date_retour->format('d/m/Y') }}
                                </div>
                                <div class="badge rounded-pill mt-1"
                                     style="background:#FFF0F3;color:#C2185B;font-size:12px;">
                                    <i class="fas fa-clock me-1"></i>{{ $ordreMission->date_retour->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 rounded-3"
                                 style="background:{{ $ordreMission->is_mission_courte ? '#FFF8E1' : '#FFF0F3' }};">
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-clock me-1"></i>Durée
                                </div>
                                <div class="fw-bold fs-5"
                                     style="color:{{ $ordreMission->is_mission_courte ? '#F59E0B' : '#D32F2F' }};">
                                    {{ $ordreMission->duree_formattee }}
                                </div>
                                @if($ordreMission->is_mission_courte)
                                <div class="badge rounded-pill mt-1"
                                     style="background:#FFF8E1;color:#F59E0B;font-size:11px;">
                                    Mission courte
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background:#f8f9fa;">
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-car me-1" style="color:#C2185B;"></i>Transport
                                </div>
                                <div class="fw-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $ordreMission->moyen_transport)) }}
                                    @if($ordreMission->moyen_transport_autre)
                                        — {{ $ordreMission->moyen_transport_autre }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($ordreMission->notes_employe)
                        <div class="col-12">
                            <div class="p-3 rounded-3" style="background:#FFFDE7;border-left:3px solid #f59e0b;">
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-sticky-note me-1" style="color:#f59e0b;"></i>Notes
                                </div>
                                <div class="fst-italic">{{ $ordreMission->notes_employe }}</div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Budget --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-0 rounded-top-3 py-3 px-4"
                     style="background:linear-gradient(135deg,#C2185B,#D32F2F);">
                    <h6 class="text-white fw-bold mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>Budget
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-3">
                        @php
                        $frais = [
                            ['label'=>'Transport',   'val'=>$ordreMission->frais_transport,   'icon'=>'fa-car',       'color'=>'#C2185B'],
                            ['label'=>'Hébergement', 'val'=>$ordreMission->frais_hebergement, 'icon'=>'fa-bed',       'color'=>'#8B5CF6'],
                            ['label'=>'Repas',       'val'=>$ordreMission->frais_repas,       'icon'=>'fa-utensils',  'color'=>'#10B981'],
                            ['label'=>'Divers',      'val'=>$ordreMission->frais_divers,      'icon'=>'fa-ellipsis-h','color'=>'#6B7280'],
                        ];
                        @endphp
                        @foreach($frais as $f)
                        <div class="col-6 col-md-3 text-center">
                            <div class="p-3 rounded-3 h-100" style="background:#f8f9fa;">
                                <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                                     style="width:38px;height:38px;background:{{ $f['color'] }}18;">
                                    <i class="fas {{ $f['icon'] }}" style="color:{{ $f['color'] }};"></i>
                                </div>
                                <div class="fw-bold small" style="color:{{ $f['color'] }};">{{ number_format($f['val'], 2) }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $f['label'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="p-3 rounded-3 d-flex justify-content-between align-items-center mb-2"
                         style="background:linear-gradient(135deg,#FFF0F3,#FFF5F5);border:1px solid #FFCDD2;">
                        <span class="fw-bold">Budget total</span>
                        <span class="fw-bold fs-5" style="color:#D32F2F;">{{ number_format($ordreMission->budget_total, 2) }} MAD</span>
                    </div>
                    <div class="p-3 rounded-3 d-flex justify-content-between align-items-center"
                         style="background:#FFFDE7;border:1px solid #FDE68A;">
                        <span class="fw-semibold">Avance demandée</span>
                        <span class="fw-bold fs-5" style="color:#D97706;">{{ number_format($ordreMission->avance_demandee, 2) }} MAD</span>
                    </div>

                    @if($ordreMission->frais_reels !== null)
                    <hr class="my-3">
                    <h6 class="fw-bold mb-3"><i class="fas fa-flag-checkered me-2" style="color:#1E88E5;"></i>Clôture</h6>
                    <div class="row g-2">
                        <div class="col-md-4 text-center">
                            <div class="p-3 rounded-3" style="background:#f8f9fa;">
                                <div class="text-muted small">Avance versée</div>
                                <div class="fw-bold" style="color:#1E88E5;">{{ number_format($ordreMission->avance_versee, 2) }} MAD</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 rounded-3" style="background:#f8f9fa;">
                                <div class="text-muted small">Frais réels</div>
                                <div class="fw-bold">{{ number_format($ordreMission->frais_reels, 2) }} MAD</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            @php $solde = $ordreMission->solde_rembourse; @endphp
                            <div class="p-3 rounded-3" style="background:{{ $solde >= 0 ? '#E8F5E9' : '#FFEBEE' }};">
                                <div class="text-muted small">{{ $solde >= 0 ? 'Remboursé' : 'Dû' }}</div>
                                <div class="fw-bold fs-5" style="color:{{ $solde >= 0 ? '#10B981' : '#D32F2F' }};">
                                    {{ number_format(abs($solde), 2) }} MAD
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ══ JUSTIFICATIFS ════════════════════════════════ --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-0 rounded-top-3 py-3 px-4"
                     style="background:linear-gradient(135deg,#C2185B,#D32F2F);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-white fw-bold mb-0">
                            <i class="fas fa-paperclip me-2"></i>Justificatifs
                        </h6>
                        <span class="badge bg-white text-dark fw-bold">
                            {{ $ordreMission->justificatifs->count() }} fichier(s)
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">

                    {{-- Liste des justificatifs existants --}}
                    @if($ordreMission->justificatifs->count() > 0)
                    <div class="row g-3 mb-4">
                        @foreach($ordreMission->justificatifs as $justif)
                        <div class="col-md-6" id="justif_{{ $justif->id }}">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 border"
                                 style="background:#fafafa;">
                                {{-- Miniature si image --}}
                                @if($justif->is_image)
                                <a href="{{ route('justificatifs.show', $justif) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $justif->chemin) }}"
                                         style="width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;"
                                         alt="{{ $justif->nom_fichier }}">
                                </a>
                                @else
                                <div class="d-flex align-items-center justify-content-center rounded-3"
                                     style="width:52px;height:52px;background:#FFEBEE;flex-shrink:0;">
                                    <i class="fas {{ $justif->icon }} fa-xl"></i>
                                </div>
                                @endif

                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold small text-truncate">{{ $justif->nom_fichier }}</div>
                                    <div class="text-muted" style="font-size:11px;">
                                        {{ $justif->label_type }} · {{ $justif->taille_format }}
                                    </div>
                                    @if($justif->description)
                                    <div class="text-muted fst-italic" style="font-size:11px;">{{ $justif->description }}</div>
                                    @endif
                                </div>

                                <div class="d-flex flex-column gap-1 flex-shrink-0">
                                    <a href="{{ route('justificatifs.show', $justif) }}" target="_blank"
                                       class="btn btn-sm rounded-pill px-2 py-1"
                                       style="background:#E3F2FD;color:#1E88E5;border:none;font-size:11px;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->id() === $justif->user_id || $isAdmin)
                                    <form action="{{ route('justificatifs.destroy', $justif) }}" method="POST"
                                          onsubmit="return confirm('Supprimer ce fichier ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm rounded-pill px-2 py-1 w-100"
                                                style="background:#FFEBEE;color:#D32F2F;border:none;font-size:11px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-3 text-muted mb-4">
                        <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                        Aucun justificatif joint pour l'instant.
                    </div>
                    @endif

                    {{-- Formulaire d'ajout --}}
                    @if(auth()->id() === $ordreMission->user_id || $isAdmin)
                    <div class="border-top pt-4">
                        <p class="fw-semibold small mb-3">
                            <i class="fas fa-plus-circle me-1" style="color:#C2185B;"></i>
                            Ajouter un justificatif
                        </p>
                        <form action="{{ route('justificatifs.store', $ordreMission) }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <select name="type_doc" class="form-select form-select-sm">
                                        <option value="autre">📎 Autre</option>
                                        <option value="bon_transport">🚌 Bon transport</option>
                                        <option value="facture_hotel">🏨 Facture hôtel</option>
                                        <option value="facture_repas">🍽️ Facture repas</option>
                                        <option value="ticket">🎫 Ticket</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="description" class="form-control form-control-sm"
                                           placeholder="Description (optionnel)">
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="fichiers[]" class="form-control form-control-sm"
                                           multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx"
                                           id="fichiers_add">
                                    <div class="form-text" style="font-size:10px;">
                                        JPG, PNG, PDF, DOC — Max 5 Mo/fichier
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit"
                                            class="btn btn-sm w-100 text-white fw-semibold rounded-pill"
                                            style="background:linear-gradient(135deg,#C2185B,#D32F2F);border:none;">
                                        <i class="fas fa-upload me-1"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- ══ COLONNE LATÉRALE ════════════════════════════════ --}}
        <div class="col-lg-4">

            {{-- Suivi --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-0 py-3 px-4" style="background:#f8f9fa;">
                    <h6 class="fw-bold mb-0"><i class="fas fa-history me-2" style="color:#C2185B;"></i>Suivi</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3 align-items-start">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                 style="width:34px;height:34px;background:linear-gradient(135deg,#C2185B,#D32F2F);">
                                <i class="fas fa-paper-plane" style="font-size:13px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Demande soumise</div>
                                <div class="text-muted" style="font-size:11px;">{{ $ordreMission->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>
                        @if($ordreMission->traite_par)
                        <div class="d-flex gap-3 align-items-start">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                 style="width:34px;height:34px;background:{{ $ordreMission->statut === 'refuse' ? '#D32F2F' : '#10B981' }};">
                                <i class="fas {{ $ordreMission->statut === 'refuse' ? 'fa-times' : 'fa-check' }}" style="font-size:13px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">
                                    {{ $ordreMission->statut === 'refuse' ? 'Refusée' : 'Approuvée' }}
                                    par {{ $ordreMission->admin->name }}
                                </div>
                                <div class="text-muted" style="font-size:11px;">{{ $ordreMission->date_traitement?->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        @endif
                        @if($ordreMission->statut === 'cloture')
                        <div class="d-flex gap-3 align-items-start">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                 style="width:34px;height:34px;background:#1E88E5;">
                                <i class="fas fa-flag-checkered" style="font-size:13px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Mission clôturée</div>
                                <div class="text-muted" style="font-size:11px;">{{ $ordreMission->date_cloture?->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($ordreMission->commentaire_admin)
                    <div class="mt-3 p-3 rounded-3" style="background:#E8F5E9;border-left:3px solid #10B981;">
                        <div class="text-muted small fw-semibold mb-1"><i class="fas fa-comment-alt me-1 text-success"></i>Commentaire</div>
                        <div style="font-size:13px;">{{ $ordreMission->commentaire_admin }}</div>
                    </div>
                    @endif
                    @if($ordreMission->motif_refus)
                    <div class="mt-3 p-3 rounded-3" style="background:#FFEBEE;border-left:3px solid #D32F2F;">
                        <div class="text-muted small fw-semibold mb-1"><i class="fas fa-times-circle me-1 text-danger"></i>Motif refus</div>
                        <div style="font-size:13px;color:#7f1d1d;">{{ $ordreMission->motif_refus }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Admin : Approuver / Refuser --}}
            @if($isAdmin && $ordreMission->statut === 'en_attente')
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-0 py-3 px-4" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                    <h6 class="fw-bold mb-0" style="color:#065f46;">
                        <i class="fas fa-gavel me-2" style="color:#10B981;"></i>Action Admin
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ordre-missions.approuver', $ordreMission) }}" method="POST" class="mb-4">
                        @csrf @method('PATCH')
                        <p class="fw-semibold small text-success mb-3"><i class="fas fa-check-circle me-1"></i>Approuver</p>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-muted">Avance à verser (MAD)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="fas fa-coins" style="color:#f59e0b;"></i></span>
                                <input type="number" name="avance_versee" step="0.01" min="0"
                                       value="{{ $ordreMission->avance_demandee }}" class="form-control">
                                <span class="input-group-text bg-white small text-muted">MAD</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Commentaire</label>
                            <textarea name="commentaire_admin" rows="2" class="form-control form-control-sm"
                                      placeholder="Optionnel..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-semibold rounded-pill"
                                onclick="return confirm('Confirmer l\'approbation ?')">
                            <i class="fas fa-check me-2"></i>Approuver
                        </button>
                    </form>

                    <div class="d-flex align-items-center gap-2 my-3">
                        <hr class="flex-grow-1"><span class="text-muted small">ou</span><hr class="flex-grow-1">
                    </div>

                    <form action="{{ route('ordre-missions.refuser', $ordreMission) }}" method="POST">
                        @csrf @method('PATCH')
                        <p class="fw-semibold small text-danger mb-3"><i class="fas fa-times-circle me-1"></i>Refuser</p>
                        <div class="mb-3">
                            <textarea name="motif_refus" rows="3" required class="form-control form-control-sm"
                                      style="border-color:#FFCDD2;"
                                      placeholder="Motif de refus obligatoire..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 fw-semibold rounded-pill"
                                onclick="return confirm('Confirmer le refus ?')">
                            <i class="fas fa-times me-2"></i>Refuser
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Admin : Clôturer --}}
            @if($isAdmin && $ordreMission->statut === 'approuve')
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-0 py-3 px-4" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                    <h6 class="fw-bold mb-0" style="color:#1e3a8a;">
                        <i class="fas fa-flag-checkered me-2" style="color:#1E88E5;"></i>Clôturer
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ordre-missions.cloturer', $ordreMission) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">
                                Frais réels (MAD) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-receipt" style="color:#1E88E5;"></i>
                                </span>
                                <input type="number" name="frais_reels" step="0.01" min="0" required
                                       class="form-control" placeholder="0.00">
                                <span class="input-group-text bg-white small text-muted">MAD</span>
                            </div>
                            <div class="form-text" style="font-size:11px;">
                                Avance versée : <strong>{{ number_format($ordreMission->avance_versee ?? 0, 2) }} MAD</strong>
                            </div>
                        </div>
                        <button type="submit" class="btn w-100 fw-semibold rounded-pill text-white"
                                style="background:#1E88E5;border:none;"
                                onclick="return confirm('Clôturer cette mission ?')">
                            <i class="fas fa-flag-checkered me-2"></i>Clôturer
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Employé actions --}}
            @if(!$isAdmin)
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-grid gap-2">
                    @if($ordreMission->statut === 'en_attente')
                        <a href="{{ route('ordre-missions.edit', $ordreMission) }}"
                           class="btn fw-semibold rounded-pill"
                           style="background:#FFFDE7;color:#D97706;border:1px solid #FDE68A;">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <form action="{{ route('ordre-missions.annuler', $ordreMission) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-semibold"
                                    onclick="return confirm('Annuler cette demande ?')">
                                <i class="fas fa-ban me-2"></i>Annuler la demande
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('ordre-missions.index') }}"
                       class="btn btn-outline-secondary rounded-pill fw-semibold">
                        <i class="fas fa-list me-2"></i>Mes demandes
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
</x-app-layout>