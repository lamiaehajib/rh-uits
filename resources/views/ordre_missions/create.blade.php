<x-app-layout>
<div class="container-fluid" style="max-width:900px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('ordre-missions.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
           style="width:36px;height:36px;">
            <i class="fas fa-arrow-left" style="font-size:13px;"></i>
        </a>
        <div>
            <h3 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Nouvelle Demande d'Ordre de Mission</h3>
            <p class="text-muted mb-0 small">Champs marqués <span class="text-danger fw-bold">*</span> obligatoires</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger shadow-sm border-0 rounded-3">
        <i class="fas fa-exclamation-triangle me-2"></i><strong>Erreurs :</strong>
        <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('ordre-missions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ══ SECTION 1 : Mission ══════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header border-0 rounded-top-3 py-3 px-4"
                 style="background:linear-gradient(135deg,#C2185B,#D32F2F);">
                <h6 class="text-white fw-bold mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>Informations de la Mission
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Destination <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-map-marker-alt" style="color:#D32F2F;"></i>
                            </span>
                            <input type="text" name="destination" value="{{ old('destination') }}"
                                   class="form-control @error('destination') is-invalid @enderror"
                                   placeholder="Ex: Casablanca, Rabat, Paris...">
                            @error('destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Objet / But de la mission <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-bullseye" style="color:#D32F2F;"></i>
                            </span>
                            <input type="text" name="objet" value="{{ old('objet') }}"
                                   class="form-control @error('objet') is-invalid @enderror"
                                   placeholder="Ex: Réunion client, Formation, Audit...">
                            @error('objet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ── Date + Heure départ ─────────────────── --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">
                            Date & Heure de départ <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-alt" style="color:#C2185B;"></i>
                            </span>
                            <input type="datetime-local" name="date_depart" id="date_depart"
                                   value="{{ old('date_depart') }}"
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="form-control @error('date_depart') is-invalid @enderror">
                            @error('date_depart')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ── Date + Heure retour ─────────────────── --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">
                            Date & Heure de retour <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-check" style="color:#C2185B;"></i>
                            </span>
                            <input type="datetime-local" name="date_retour" id="date_retour"
                                   value="{{ old('date_retour') }}"
                                   class="form-control @error('date_retour') is-invalid @enderror">
                            @error('date_retour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ── Durée calculée ──────────────────────── --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Durée estimée</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-clock text-muted"></i>
                            </span>
                            <input type="text" id="duree_display" class="form-control bg-light fw-semibold"
                                   readonly placeholder="Calculée automatiquement...">
                        </div>
                        {{-- Alerte mission courte --}}
                        <div id="alerte_courte" class="mt-1 small text-warning fw-semibold" style="display:none;">
                            <i class="fas fa-exclamation-triangle me-1"></i>Mission de moins de 24h
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Moyen de transport <span class="text-danger">*</span></label>
                        <select name="moyen_transport" id="moyen_transport"
                                class="form-select @error('moyen_transport') is-invalid @enderror">
                            <option value="">-- Sélectionner --</option>
                            <option value="voiture_personnelle" @selected(old('moyen_transport')=='voiture_personnelle')>🚗 Voiture personnelle</option>
                            <option value="train"              @selected(old('moyen_transport')=='train')>🚂 Train</option>
                            <option value="avion"              @selected(old('moyen_transport')=='avion')>✈️ Avion</option>
                            <option value="bus"                @selected(old('moyen_transport')=='bus')>🚌 Bus</option>
                            <option value="autre"              @selected(old('moyen_transport')=='autre')>🔧 Autre</option>
                        </select>
                        @error('moyen_transport')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6" id="transport_autre_div" style="display:none;">
                        <label class="form-label fw-semibold small">Préciser le transport</label>
                        <input type="text" name="moyen_transport_autre" value="{{ old('moyen_transport_autre') }}"
                               class="form-control" placeholder="Précisez...">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold small">Notes / Remarques</label>
                        <textarea name="notes_employe" rows="2" class="form-control"
                                  placeholder="Informations complémentaires, besoins spécifiques...">{{ old('notes_employe') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══ SECTION 2 : Budget ═══════════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header border-0 rounded-top-3 py-3 px-4"
                 style="background:linear-gradient(135deg,#C2185B,#D32F2F);">
                <h6 class="text-white fw-bold mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>Budget Prévisionnel
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">

                    @php
                    $fraisItems = [
                        ['name'=>'frais_transport',   'label'=>'Transport',   'icon'=>'fa-car'],
                        ['name'=>'frais_hebergement', 'label'=>'Hébergement', 'icon'=>'fa-bed'],
                        ['name'=>'frais_repas',       'label'=>'Repas',       'icon'=>'fa-utensils'],
                        ['name'=>'frais_divers',      'label'=>'Divers',      'icon'=>'fa-ellipsis-h'],
                    ];
                    @endphp

                    @foreach($fraisItems as $fi)
                    <div class="col-md-3 col-6">
                        <label class="form-label fw-semibold small">{{ $fi['label'] }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas {{ $fi['icon'] }}" style="color:#C2185B;font-size:13px;"></i>
                            </span>
                            <input type="number" name="{{ $fi['name'] }}"
                                   value="{{ old($fi['name'], 0) }}" step="0.01" min="0"
                                   class="form-control budget-input @error($fi['name']) is-invalid @enderror">
                            <span class="input-group-text bg-white text-muted small">MAD</span>
                        </div>
                    </div>
                    @endforeach

                    <div class="col-12 mt-2">
                        <div class="rounded-3 p-3 d-flex justify-content-between align-items-center"
                             style="background:linear-gradient(135deg,#FFF0F3,#FFF5F5);border:1px solid #FFCDD2;">
                            <span class="fw-bold">Budget total prévisionnel</span>
                            <span class="fw-bold fs-4" style="color:#D32F2F;" id="budget_total">0.00 MAD</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">
                            Avance demandée <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-hand-holding-usd" style="color:#f59e0b;"></i>
                            </span>
                            <input type="number" name="avance_demandee" id="avance_demandee"
                                   value="{{ old('avance_demandee', 0) }}" step="0.01" min="0"
                                   class="form-control fw-semibold @error('avance_demandee') is-invalid @enderror">
                            <span class="input-group-text bg-white text-muted small">MAD</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══ SECTION 3 : Justificatifs ════════════════════════ --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header border-0 rounded-top-3 py-3 px-4"
                 style="background:linear-gradient(135deg,#C2185B,#D32F2F);">
                <h6 class="text-white fw-bold mb-0">
                    <i class="fas fa-paperclip me-2"></i>Justificatifs
                    <span class="badge bg-white text-secondary ms-2" style="font-size:11px;">Optionnel</span>
                </h6>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    Vous pouvez joindre vos justificatifs maintenant ou après soumission.
                    Formats acceptés : <strong>JPG, PNG, PDF, DOC</strong> — Max 5 Mo par fichier.
                </p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Type de document</label>
                        <select name="type_doc" class="form-select">
                            <option value="autre">📎 Autre</option>
                            <option value="bon_transport">🚌 Bon transport</option>
                            <option value="facture_hotel">🏨 Facture hôtel</option>
                            <option value="facture_repas">🍽️ Facture repas</option>
                            <option value="ticket">🎫 Ticket</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Description (optionnel)</label>
                        <input type="text" name="description" class="form-control"
                               placeholder="Ex: Billet ONCF aller-retour...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Fichiers</label>
                        <input type="file" name="fichiers[]" id="fichiers_input"
                               class="form-control" multiple
                               accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx">
                    </div>

                    {{-- Prévisualisation --}}
                    <div class="col-12" id="preview_zone" style="display:none;">
                        <div id="preview_list" class="d-flex flex-wrap gap-2 mt-1"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-3 mb-4">
            <a href="{{ route('ordre-missions.index') }}"
               class="btn btn-outline-secondary px-4 rounded-pill">
                <i class="fas fa-times me-2"></i>Annuler
            </a>
            <button type="submit"
                    class="btn px-5 text-white fw-semibold rounded-pill ripple-btn"
                    style="background:linear-gradient(135deg,#C2185B,#D32F2F);border:none;box-shadow:0 4px 15px rgba(194,24,91,.3);">
                <i class="fas fa-paper-plane me-2"></i>Soumettre la demande
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
// ── Transport autre
document.getElementById('moyen_transport').addEventListener('change', function() {
    document.getElementById('transport_autre_div').style.display = this.value === 'autre' ? '' : 'none';
});
@if(old('moyen_transport') === 'autre')
    document.getElementById('transport_autre_div').style.display = '';
@endif

// ── Durée auto avec datetime
function calcDuree() {
    const d = document.getElementById('date_depart').value;
    const r = document.getElementById('date_retour').value;
    if (!d || !r) return;

    const diffMs = new Date(r) - new Date(d);
    if (diffMs < 0) {
        document.getElementById('duree_display').value = '⚠ Dates invalides';
        document.getElementById('duree_display').style.color = '#D32F2F';
        return;
    }

    const totalMinutes = Math.round(diffMs / 60000);
    const heures = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;

    let texte = '';
    if (heures < 24) {
        texte = heures + 'h' + (minutes > 0 ? minutes + 'min' : '');
        document.getElementById('alerte_courte').style.display = '';
    } else {
        const jours = Math.floor(heures / 24);
        const heuresReste = heures % 24;
        texte = jours + 'j' + (heuresReste > 0 ? ' ' + heuresReste + 'h' : '');
        document.getElementById('alerte_courte').style.display = 'none';
    }

    document.getElementById('duree_display').value = texte;
    document.getElementById('duree_display').style.color = '#C2185B';
}
document.getElementById('date_depart').addEventListener('change', calcDuree);
document.getElementById('date_retour').addEventListener('change', calcDuree);

// ── Budget auto
let avanceModified = false;
function calcBudget() {
    let total = 0;
    document.querySelectorAll('.budget-input').forEach(el => total += parseFloat(el.value) || 0);
    document.getElementById('budget_total').textContent = total.toFixed(2) + ' MAD';
    if (!avanceModified) document.getElementById('avance_demandee').value = total.toFixed(2);
}
document.querySelectorAll('.budget-input').forEach(el => el.addEventListener('input', calcBudget));
document.getElementById('avance_demandee').addEventListener('input', () => { avanceModified = true; });
calcBudget();

// ── Prévisualisation fichiers
document.getElementById('fichiers_input').addEventListener('change', function() {
    const zone = document.getElementById('preview_zone');
    const list = document.getElementById('preview_list');
    list.innerHTML = '';

    if (this.files.length === 0) { zone.style.display = 'none'; return; }
    zone.style.display = '';

    Array.from(this.files).forEach(file => {
        const isImage = file.type.startsWith('image/');
        const item = document.createElement('div');
        item.className = 'd-flex align-items-center gap-2 rounded-3 px-3 py-2';
        item.style.cssText = 'background:#f8f9fa;border:1px solid #e5e7eb;font-size:13px;';

        if (isImage) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width:40px;height:40px;object-fit:cover;border-radius:6px;';
                item.prepend(img);
            };
            reader.readAsDataURL(file);
        } else {
            const icon = document.createElement('i');
            icon.className = 'fas fa-file-pdf fa-lg text-danger';
            item.prepend(icon);
        }

        const info = document.createElement('div');
        info.innerHTML = `<div class="fw-semibold" style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${file.name}</div>
                          <div class="text-muted" style="font-size:11px;">${(file.size/1024).toFixed(1)} Ko</div>`;
        item.appendChild(info);
        list.appendChild(item);
    });
});
</script>
@endpush

</x-app-layout>