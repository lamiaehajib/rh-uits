<x-app-layout>
<div class="container-fluid" style="max-width:900px;">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('ordre-missions.show', $ordreMission) }}"
           class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
           style="width:36px;height:36px;">
            <i class="fas fa-arrow-left" style="font-size:13px;"></i>
        </a>
        <div>
            <h3 class="mb-0">
                <i class="fas fa-edit me-2"></i>Modifier la demande #{{ $ordreMission->id }}
            </h3>
            <p class="text-muted mb-0 small">
                Seules les demandes <strong>en attente</strong> peuvent être modifiées.
            </p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger shadow-sm border-0 rounded-3">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Veuillez corriger les erreurs :</strong>
        <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('ordre-missions.update', $ordreMission) }}" method="POST">
        @csrf @method('PUT')

        {{-- ══ Section 1 : Mission ══════════════════════════════ --}}
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
                        <label class="form-label fw-semibold small">
                            Destination <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-map-marker-alt" style="color:#D32F2F;"></i>
                            </span>
                            <input type="text" name="destination"
                                   value="{{ old('destination', $ordreMission->destination) }}"
                                   class="form-control @error('destination') is-invalid @enderror">
                            @error('destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">
                            Objet / But de la mission <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-bullseye" style="color:#D32F2F;"></i>
                            </span>
                            <input type="text" name="objet"
                                   value="{{ old('objet', $ordreMission->objet) }}"
                                   class="form-control @error('objet') is-invalid @enderror">
                            @error('objet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Date de départ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-alt" style="color:#C2185B;"></i>
                            </span>
                            <input type="date" name="date_depart" id="date_depart"
                                   value="{{ old('date_depart', $ordreMission->date_depart->format('Y-m-d')) }}"
                                   class="form-control @error('date_depart') is-invalid @enderror">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Date de retour <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-check" style="color:#C2185B;"></i>
                            </span>
                            <input type="date" name="date_retour" id="date_retour"
                                   value="{{ old('date_retour', $ordreMission->date_retour->format('Y-m-d')) }}"
                                   class="form-control @error('date_retour') is-invalid @enderror">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Durée estimée</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-clock text-muted"></i></span>
                            <input type="text" id="duree_display" class="form-control bg-light fw-semibold" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Moyen de transport <span class="text-danger">*</span></label>
                        <select name="moyen_transport" id="moyen_transport"
                                class="form-select @error('moyen_transport') is-invalid @enderror">
                            <option value="">-- Sélectionner --</option>
                            <option value="voiture_personnelle" @selected(old('moyen_transport', $ordreMission->moyen_transport)=='voiture_personnelle')>🚗 Voiture personnelle</option>
                            <option value="train"              @selected(old('moyen_transport', $ordreMission->moyen_transport)=='train')>🚂 Train</option>
                            <option value="avion"              @selected(old('moyen_transport', $ordreMission->moyen_transport)=='avion')>✈️ Avion</option>
                            <option value="bus"                @selected(old('moyen_transport', $ordreMission->moyen_transport)=='bus')>🚌 Bus</option>
                            <option value="autre"              @selected(old('moyen_transport', $ordreMission->moyen_transport)=='autre')>🔧 Autre</option>
                        </select>
                    </div>

                    <div class="col-md-6" id="transport_autre_div"
                         style="display:{{ old('moyen_transport', $ordreMission->moyen_transport) === 'autre' ? '' : 'none' }};">
                        <label class="form-label fw-semibold small">Préciser le transport</label>
                        <input type="text" name="moyen_transport_autre"
                               value="{{ old('moyen_transport_autre', $ordreMission->moyen_transport_autre) }}"
                               class="form-control" placeholder="Précisez...">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold small">Notes / Remarques</label>
                        <textarea name="notes_employe" rows="2" class="form-control"
                                  placeholder="Informations complémentaires...">{{ old('notes_employe', $ordreMission->notes_employe) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══ Section 2 : Budget ═══════════════════════════════ --}}
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
                    $fraisFields = [
                        ['name'=>'frais_transport',   'label'=>'Frais transport',   'icon'=>'fa-car'],
                        ['name'=>'frais_hebergement', 'label'=>'Frais hébergement', 'icon'=>'fa-bed'],
                        ['name'=>'frais_repas',       'label'=>'Frais repas',       'icon'=>'fa-utensils'],
                        ['name'=>'frais_divers',      'label'=>'Frais divers',      'icon'=>'fa-ellipsis-h'],
                    ];
                    @endphp

                    @foreach($fraisFields as $fi)
                    <div class="col-md-3 col-6">
                        <label class="form-label fw-semibold small">{{ $fi['label'] }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas {{ $fi['icon'] }}" style="color:#C2185B;font-size:13px;"></i>
                            </span>
                            <input type="number" name="{{ $fi['name'] }}"
                                   value="{{ old($fi['name'], $ordreMission->{$fi['name']}) }}"
                                   step="0.01" min="0" class="form-control budget-input" placeholder="0.00">
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
                                   value="{{ old('avance_demandee', $ordreMission->avance_demandee) }}"
                                   step="0.01" min="0" class="form-control fw-semibold">
                            <span class="input-group-text bg-white text-muted small">MAD</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-3 mb-4">
            <a href="{{ route('ordre-missions.show', $ordreMission) }}"
               class="btn btn-outline-secondary px-4 rounded-pill">
                <i class="fas fa-times me-2"></i>Annuler
            </a>
            <button type="submit"
                    class="btn px-5 text-white fw-semibold rounded-pill ripple-btn"
                    style="background:linear-gradient(135deg,#C2185B,#D32F2F);border:none;box-shadow:0 4px 15px rgba(194,24,91,.3);">
                <i class="fas fa-save me-2"></i>Enregistrer les modifications
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
document.getElementById('moyen_transport').addEventListener('change', function() {
    document.getElementById('transport_autre_div').style.display = this.value === 'autre' ? '' : 'none';
});
function calcDuree() {
    const d = document.getElementById('date_depart').value;
    const r = document.getElementById('date_retour').value;
    if (d && r) {
        const diff = Math.round((new Date(r) - new Date(d)) / 86400000) + 1;
        document.getElementById('duree_display').value = diff > 0 ? diff + ' jour(s)' : '⚠ Invalide';
    }
}
document.getElementById('date_depart').addEventListener('change', calcDuree);
document.getElementById('date_retour').addEventListener('change', calcDuree);
calcDuree();

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
</script>
@endpush

</x-app-layout>