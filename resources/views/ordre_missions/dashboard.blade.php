<x-app-layout>

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&display=swap');

/* ── Reset table alignment (override layout global) ── */
.dash-wrap tbody, .dash-wrap td,
.dash-wrap tfoot, .dash-wrap th,
.dash-wrap thead, .dash-wrap tr {
    text-align: left !important;
}

/* ── Wrapper ─────────────────────────────────────────── */
.dash-wrap {
    font-family: 'Ubuntu', sans-serif;
}

/* ── Page Header ─────────────────────────────────────── */
.dash-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 1.75rem;
    animation: fadeUp .4s ease both;
}
.dash-page-title {
    font-family: 'Syne', sans-serif;
    font-size: 1.85rem;
    font-weight: 800;
    letter-spacing: -.03em;
    line-height: 1.1;
    background: linear-gradient(135deg, #C2185B, #D32F2F);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-transform: uppercase;
}
.dash-page-sub {
    font-size: .82rem;
    color: #9ca3af;
    margin-top: .25rem;
}

/* ── KPI Cards ───────────────────────────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
    animation: fadeUp .4s .08s ease both;
}
@media(max-width:900px) { .kpi-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:480px) { .kpi-grid { grid-template-columns: 1fr; } }

.kpi-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.2rem 1.3rem 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
    border: 1px solid #f3f4f6;
    position: relative;
    overflow: hidden;
    transition: transform .22s, box-shadow .22s;
}
.kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(194,24,91,.12);
}
.kpi-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--kpi-accent);
    border-radius: 14px 14px 0 0;
}
.kpi-icon-wrap {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    background: var(--kpi-bg);
    margin-bottom: .85rem;
    font-size: 1.1rem;
}
.kpi-value {
    font-family: 'Syne', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    color: #111827;
    line-height: 1;
}
.kpi-label {
    font-size: .75rem;
    color: #9ca3af;
    margin-top: .3rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    font-weight: 500;
}
.kpi-badge {
    position: absolute;
    top: .85rem; right: .85rem;
    font-size: .68rem;
    padding: .18rem .5rem;
    border-radius: 99px;
    font-weight: 600;
    background: var(--kpi-bg);
    color: var(--kpi-color);
}

/* ── Pulse dot ───────────────────────────────────────── */
.dot-pulse {
    display: inline-block;
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--kpi-color, #f59e0b);
    animation: dotPulse 1.8s ease-in-out infinite;
    vertical-align: middle;
    margin-right: 4px;
}
@keyframes dotPulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(var(--kpi-rgb),.5); }
    50%      { box-shadow: 0 0 0 4px rgba(var(--kpi-rgb),0); }
}

/* ── Section layout ──────────────────────────────────── */
.main-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
    animation: fadeUp .4s .16s ease both;
}
@media(max-width:960px) { .main-grid { grid-template-columns: 1fr; } }

.bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    animation: fadeUp .4s .24s ease both;
}
@media(max-width:760px) { .bottom-grid { grid-template-columns: 1fr; } }

/* ── Panel card ──────────────────────────────────────── */
.panel {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f3f4f6;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    overflow: hidden;
}
.panel-head {
    padding: .9rem 1.25rem;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.panel-title {
    font-family: 'Syne', sans-serif;
    font-size: .88rem;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.panel-title i { color: #C2185B; }
.panel-body { padding: 1.1rem 1.25rem; }

/* ── Table ───────────────────────────────────────────── */
.dm-table { width: 100%; border-collapse: collapse; }
.dm-table th {
    font-size: .68rem;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #9ca3af;
    padding: .55rem .75rem;
    border-bottom: 1px solid #f3f4f6;
    font-weight: 600;
    white-space: nowrap;
}
.dm-table td {
    padding: .7rem .75rem;
    border-bottom: 1px solid #f9fafb;
    font-size: .82rem;
    color: #374151;
    vertical-align: middle;
}
.dm-table tr:last-child td { border-bottom: none; }
.dm-table tbody tr { transition: background .12s; }
.dm-table tbody tr:hover td { background: #fdf2f8; }

/* ── Avatar ──────────────────────────────────────────── */
.av {
    width: 32px; height: 32px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #C2185B, #D32F2F);
    flex-shrink: 0;
}

/* ── Pill ────────────────────────────────────────────── */
.pill {
    display: inline-block;
    padding: .2rem .6rem;
    border-radius: 99px;
    font-size: .7rem;
    font-weight: 600;
    white-space: nowrap;
}

/* ── Action buttons ──────────────────────────────────── */
.act-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px; height: 28px;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    font-size: .75rem;
    transition: transform .15s, opacity .15s;
    text-decoration: none;
}
.act-btn:hover { transform: translateY(-1px); opacity: .85; }
.act-view    { background: #f3f4f6;           color: #6b7280; }
.act-approve { background: rgba(16,185,129,.1); color: #059669; border: 1px solid rgba(16,185,129,.2) !important; }
.act-refuse  { background: rgba(239,68,68,.08); color: #dc2626; border: 1px solid rgba(239,68,68,.15) !important; }

/* ── Top Destinations ────────────────────────────────── */
.dest-row { display: flex; align-items: center; gap: .75rem; margin-bottom: .8rem; }
.dest-row:last-child { margin-bottom: 0; }
.dest-rank { font-family:'Syne',sans-serif; font-size:.72rem; font-weight:800; color:#d1d5db; width:18px; flex-shrink:0; }
.dest-name { font-size:.8rem; color:#374151; flex-shrink:0; width:85px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.dest-bar-bg { flex:1; background:#f3f4f6; border-radius:99px; height:5px; overflow:hidden; }
.dest-bar-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#C2185B,#f43f5e); transition: width 1.1s cubic-bezier(.4,0,.2,1); }
.dest-n { font-family:'Syne',sans-serif; font-size:.75rem; font-weight:700; color:#C2185B; flex-shrink:0; min-width:18px; text-align:right; }

/* ── Budget block ────────────────────────────────────── */
.budget-big {
    text-align: center;
    padding: .75rem 0 .5rem;
}
.budget-num {
    font-family: 'Syne', sans-serif;
    font-size: 2.2rem;
    font-weight: 800;
    background: linear-gradient(135deg,#C2185B,#D32F2F);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1;
}
.budget-unit { font-size: .85rem; color: #9ca3af; }
.budget-sub  { font-size: .75rem; color: #9ca3af; margin-top: .25rem; }

/* ── Progress bar generic ────────────────────────────── */
.prog-wrap { background:#f3f4f6; border-radius:99px; height:5px; overflow:hidden; }
.prog-fill  { height:100%; border-radius:99px; transition: width 1.1s cubic-bezier(.4,0,.2,1); }

/* ── Activity item ───────────────────────────────────── */
.act-item { display:flex; align-items:center; gap:.75rem; margin-bottom:.85rem; }
.act-item:last-child { margin-bottom:0; }
.act-dot {
    width:30px; height:30px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    font-size:.72rem; flex-shrink:0;
}
.act-text { flex:1; min-width:0; }
.act-text strong { font-size:.8rem; color:#111827; }
.act-text .act-dest { font-size:.8rem; color:#374151; }
.act-text .act-time { font-size:.7rem; color:#9ca3af; margin-top:.1rem; }

/* ── Divider ─────────────────────────────────────────── */
.soft-divider { border:none; border-top:1px solid #f3f4f6; margin:1rem 0; }

/* ── Scrollable zone ─────────────────────────────────── */
.scroll-zone { overflow-y:auto; max-height:340px; }
.scroll-zone::-webkit-scrollbar { width:3px; }
.scroll-zone::-webkit-scrollbar-thumb { background:#e5e7eb; border-radius:99px; }

/* ── Gradient header stripe ──────────────────────────── */
.stripe-header {
    background: linear-gradient(135deg,#C2185B,#D32F2F);
    padding: .85rem 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.stripe-title { font-family:'Syne',sans-serif; font-size:.88rem; font-weight:700; color:#fff; }

/* ── Empty ───────────────────────────────────────────── */
.empty-box { text-align:center; padding:2.5rem 1rem; color:#d1d5db; }
.empty-box i { font-size:2rem; margin-bottom:.6rem; display:block; }
.empty-box p { font-size:.8rem; color:#9ca3af; margin:0; }

/* ── Animation ───────────────────────────────────────── */
@keyframes fadeUp {
    from { opacity:0; transform:translateY(18px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>

<div class="dash-wrap">

    {{-- ── Header ─────────────────────────────────────────── --}}
    <div class="dash-page-header">
        <div>
            <div class="dash-page-title">
                <i class="fas fa-plane-departure me-2" style="-webkit-text-fill-color:#C2185B;"></i>
                Dashboard Missions
            </div>
            <div class="dash-page-sub">
                Vue d'ensemble — {{ now()->isoFormat('MMMM YYYY') }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('ordre-missions.index') }}"
               class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fas fa-list-ul me-1"></i> Toutes les demandes
            </a>
            <a href="{{ route('ordre-missions.create') }}"
               class="btn btn-sm text-white rounded-pill px-3 fw-semibold"
               style="background:linear-gradient(135deg,#C2185B,#D32F2F);border:none;">
                <i class="fas fa-plus me-1"></i> Nouvelle
            </a>
        </div>
    </div>

    {{-- ── KPI ─────────────────────────────────────────────── --}}
    <div class="kpi-grid">

        <div class="kpi-card"
             style="--kpi-accent:linear-gradient(90deg,#f59e0b,#fbbf24);--kpi-bg:rgba(245,158,11,.08);--kpi-color:#d97706;--kpi-rgb:245,158,11;">
            <div class="kpi-icon-wrap"><i class="fas fa-hourglass-half" style="color:#f59e0b;"></i></div>
            <div class="kpi-value">{{ $stats['en_attente'] }}</div>
            <div class="kpi-label">En attente</div>
            @if($stats['en_attente'] > 0)
            <div class="kpi-badge">
                <span class="dot-pulse" style="--kpi-color:#d97706;--kpi-rgb:245,158,11;"></span>À traiter
            </div>
            @endif
        </div>

        <div class="kpi-card"
             style="--kpi-accent:linear-gradient(90deg,#10b981,#34d399);--kpi-bg:rgba(16,185,129,.08);--kpi-color:#059669;--kpi-rgb:16,185,129;">
            <div class="kpi-icon-wrap"><i class="fas fa-check-circle" style="color:#10b981;"></i></div>
            <div class="kpi-value">{{ $stats['approuve_ce_mois'] }}</div>
            <div class="kpi-label">Approuvées ce mois</div>
            <div class="kpi-badge">{{ now()->format('M Y') }}</div>
        </div>

        <div class="kpi-card"
             style="--kpi-accent:linear-gradient(90deg,#C2185B,#f43f5e);--kpi-bg:rgba(194,24,91,.08);--kpi-color:#C2185B;--kpi-rgb:194,24,91;">
            <div class="kpi-icon-wrap"><i class="fas fa-money-bill-wave" style="color:#C2185B;"></i></div>
            <div class="kpi-value" style="font-size:1.45rem;">
                {{ number_format($stats['budget_total_mois'], 0, ',', ' ') }}
            </div>
            <div class="kpi-label">Budget versé (MAD)</div>
            <div class="kpi-badge">Ce mois</div>
        </div>

        <div class="kpi-card"
             style="--kpi-accent:linear-gradient(90deg,#6366f1,#818cf8);--kpi-bg:rgba(99,102,241,.08);--kpi-color:#6366f1;--kpi-rgb:99,102,241;">
            <div class="kpi-icon-wrap"><i class="fas fa-plane" style="color:#6366f1;"></i></div>
            <div class="kpi-value">{{ $missionsEnCours->count() }}</div>
            <div class="kpi-label">Demandes en cours</div>
        </div>

    </div>

    {{-- ── Main Grid ────────────────────────────────────────── --}}
    <div class="main-grid">

        {{-- Table demandes en attente --}}
        <div class="panel">
            <div class="stripe-header">
                <div class="stripe-title">
                    <i class="fas fa-inbox me-2"></i>Demandes en attente
                </div>
                <span class="pill" style="background:rgba(255,255,255,.2);color:#fff;">
                    {{ $missionsEnCours->count() }} demande(s)
                </span>
            </div>
            <div class="scroll-zone">
                @if($missionsEnCours->count() > 0)
                <table class="dm-table">
                    <thead>
                        <tr>
                            <th>Employé</th>
                            <th>Destination</th>
                            <th>Départ</th>
                            <th>Durée</th>
                            <th>Avance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($missionsEnCours as $m)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="av">{{ strtoupper(substr($m->employe->name,0,2)) }}</div>
                                    <div>
                                        <div style="font-weight:600;font-size:.82rem;color:#111827;line-height:1.2;">{{ $m->employe->name }}</div>
                                        <div style="font-size:.7rem;color:#9ca3af;">{{ $m->employe->poste }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt me-1" style="color:#C2185B;font-size:.72rem;"></i>
                                <span style="font-weight:500;">{{ $m->destination }}</span>
                            </td>
                            <td>
                                <div style="font-weight:500;font-size:.82rem;">{{ $m->date_depart->format('d/m/Y') }}</div>
                                <div style="font-size:.7rem;color:#9ca3af;">{{ $m->date_depart->format('H:i') }}</div>
                            </td>
                            <td>
                                <span class="pill" style="background:rgba(99,102,241,.08);color:#6366f1;">
                                    {{ $m->duree_formattee }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight:700;color:#C2185B;font-size:.82rem;">{{ number_format($m->avance_demandee,2) }}</span>
                                <span style="font-size:.7rem;color:#9ca3af;"> MAD</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('ordre-missions.show', $m) }}" class="act-btn act-view" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('ordre-missions.approuver', $m) }}" method="POST" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="avance_versee" value="{{ $m->avance_demandee }}">
                                        <button type="submit" class="act-btn act-approve" title="Approuver"
                                                onclick="return confirm('Approuver cette mission ?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="act-btn act-refuse" title="Refuser"
                                            data-bs-toggle="modal" data-bs-target="#refusModal"
                                            data-id="{{ $m->id }}"
                                            data-dest="{{ $m->destination }}"
                                            data-employe="{{ $m->employe->name }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-box">
                    <i class="fas fa-check-double" style="color:#d1fae5;"></i>
                    <p>Aucune demande en attente 🎉</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="d-flex flex-column gap-3">

            {{-- Budget mois --}}
            <div class="panel">
                <div class="stripe-header">
                    <div class="stripe-title"><i class="fas fa-chart-pie me-2"></i>Budget {{ now()->format('M Y') }}</div>
                </div>
                <div class="panel-body">
                    @php
                        $totalGlobal = \App\Models\OrdreMission::approuve()->sum('avance_versee');
                        $pct = $totalGlobal > 0 ? round(($stats['budget_total_mois'] / $totalGlobal) * 100) : 0;
                    @endphp
                    <div class="budget-big">
                        <div class="budget-num">
                            {{ number_format($stats['budget_total_mois'], 0, ',', ' ') }}
                            <span class="budget-unit">MAD</span>
                        </div>
                        <div class="budget-sub">Total avances versées ce mois</div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.73rem;">
                            <span style="color:#9ca3af;">Part du total global</span>
                            <span style="color:#C2185B;font-weight:700;">{{ $pct }}%</span>
                        </div>
                        <div class="prog-wrap">
                            <div class="prog-fill" style="width:{{ $pct }}%;background:linear-gradient(90deg,#C2185B,#f43f5e);"></div>
                        </div>
                        <div style="font-size:.7rem;color:#d1d5db;margin-top:.4rem;">
                            Global : {{ number_format($totalGlobal,0,',',' ') }} MAD
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top destinations --}}
            <div class="panel" style="flex:1;">
                <div class="stripe-header">
                    <div class="stripe-title"><i class="fas fa-map-marked-alt me-2"></i>Top destinations</div>
                </div>
                <div class="panel-body">
                    @php $maxDest = $stats['top_destinations']->first()?->total ?? 1; @endphp
                    @forelse($stats['top_destinations'] as $i => $dest)
                    <div class="dest-row">
                        <div class="dest-rank">#{{ $i+1 }}</div>
                        <div class="dest-name" title="{{ $dest->destination }}">{{ $dest->destination }}</div>
                        <div class="dest-bar-bg">
                            <div class="dest-bar-fill" style="width:{{ ($dest->total/$maxDest)*100 }}%;"></div>
                        </div>
                        <div class="dest-n">{{ $dest->total }}</div>
                    </div>
                    @empty
                    <div class="empty-box" style="padding:1.25rem 0;">
                        <i class="fas fa-map" style="font-size:1.4rem;"></i>
                        <p>Aucune donnée</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- ── Bottom Grid ──────────────────────────────────────── --}}
    <div class="bottom-grid">

        {{-- Répartition globale --}}
        <div class="panel">
            <div class="stripe-header">
                <div class="stripe-title"><i class="fas fa-layer-group me-2"></i>Répartition globale</div>
            </div>
            <div class="panel-body">
                @php
                $rep = [
                    ['label'=>'En attente', 'count'=>\App\Models\OrdreMission::enAttente()->count(), 'color'=>'#f59e0b','bar'=>'#f59e0b'],
                    ['label'=>'Approuvées', 'count'=>\App\Models\OrdreMission::approuve()->count(),  'color'=>'#10b981','bar'=>'#10b981'],
                    ['label'=>'Refusées',   'count'=>\App\Models\OrdreMission::refuse()->count(),    'color'=>'#ef4444','bar'=>'#ef4444'],
                    ['label'=>'Clôturées',  'count'=>\App\Models\OrdreMission::where('statut','cloture')->count(),'color'=>'#6366f1','bar'=>'#6366f1'],
                    ['label'=>'Annulées',   'count'=>\App\Models\OrdreMission::where('statut','annule')->count(), 'color'=>'#9ca3af','bar'=>'#9ca3af'],
                ];
                $tot = array_sum(array_column($rep,'count')) ?: 1;
                @endphp

                <div class="d-flex flex-column gap-3">
                    @foreach($rep as $r)
                    <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size:.79rem;">
                            <span style="color:#374151;">{{ $r['label'] }}</span>
                            <span style="font-weight:700;color:{{ $r['color'] }};">
                                {{ $r['count'] }}
                                <span style="color:#d1d5db;font-weight:400;font-size:.7rem;">&nbsp;({{ round(($r['count']/$tot)*100) }}%)</span>
                            </span>
                        </div>
                        <div class="prog-wrap">
                            <div class="prog-fill" style="width:{{ ($r['count']/$tot)*100 }}%;background:{{ $r['bar'] }};"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr class="soft-divider">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:.75rem;color:#9ca3af;">Total demandes</span>
                    <span style="font-family:'Syne',sans-serif;font-size:1.3rem;font-weight:800;color:#111827;">{{ $tot }}</span>
                </div>
            </div>
        </div>

        {{-- Activité récente --}}
        <div class="panel">
            <div class="stripe-header">
                <div class="stripe-title"><i class="fas fa-bolt me-2"></i>Activité récente</div>
                <a href="{{ route('ordre-missions.index') }}"
                   style="font-size:.75rem;color:rgba(255,255,255,.7);text-decoration:none;">
                    Voir tout →
                </a>
            </div>
            <div class="panel-body scroll-zone" style="max-height:260px;">
                @php
                $recent = \App\Models\OrdreMission::with('employe')
                    ->whereIn('statut',['approuve','refuse','cloture'])
                    ->latest('updated_at')->limit(8)->get();
                @endphp
                @forelse($recent as $m)
                @php
                $ac = match($m->statut) {
                    'approuve' => ['bg'=>'rgba(16,185,129,.1)',  'color'=>'#059669','icon'=>'fa-check'],
                    'refuse'   => ['bg'=>'rgba(239,68,68,.08)',  'color'=>'#dc2626','icon'=>'fa-times'],
                    'cloture'  => ['bg'=>'rgba(99,102,241,.1)',  'color'=>'#6366f1','icon'=>'fa-flag-checkered'],
                    default    => ['bg'=>'rgba(107,114,128,.08)','color'=>'#6b7280','icon'=>'fa-circle'],
                };
                @endphp
                <div class="act-item">
                    <div class="act-dot" style="background:{{ $ac['bg'] }};">
                        <i class="fas {{ $ac['icon'] }}" style="color:{{ $ac['color'] }};font-size:.72rem;"></i>
                    </div>
                    <div class="act-text">
                        <div class="act-dest">
                            <strong>{{ $m->employe->name }}</strong>
                            <span style="color:#9ca3af;"> → </span>
                            {{ $m->destination }}
                        </div>
                        <div class="act-time">{{ $m->updated_at->diffForHumans() }}</div>
                    </div>
                    <span class="pill flex-shrink-0"
                          style="background:{{ $ac['bg'] }};color:{{ $ac['color'] }};font-size:.68rem;">
                        {{ ucfirst($m->statut) }}
                    </span>
                </div>
                @empty
                <div class="empty-box" style="padding:1.5rem 0;">
                    <i class="fas fa-history"></i>
                    <p>Aucune activité récente</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

{{-- ── Modal Refus ──────────────────────────────────────── --}}
<div class="modal fade" id="refusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            {{-- Header gradient --}}
            <div class="modal-header border-0 py-3 px-4"
                 style="background:linear-gradient(135deg,#C2185B,#D32F2F);">
                <h6 class="modal-title fw-bold text-white mb-0"
                    style="font-family:'Syne',sans-serif;">
                    <i class="fas fa-times-circle me-2"></i>Refuser la mission
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="refusForm" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body px-4 pt-3 pb-2">
                    <p id="refusDesc" class="text-muted small mb-3 p-2 rounded-3"
                       style="background:#fff5f7;border-left:3px solid #C2185B;"></p>
                    <label class="form-label fw-semibold small text-muted">
                        Motif de refus <span class="text-danger">*</span>
                    </label>
                    <textarea name="motif_refus" rows="3" required
                              class="form-control border"
                              style="border-color:#f1c1ce;border-radius:10px;font-size:.85rem;resize:none;"
                              placeholder="Expliquez la raison du refus..."></textarea>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                            data-bs-dismiss="modal">Annuler</button>
                    <button type="submit"
                            class="btn btn-sm rounded-pill px-4 fw-semibold text-white"
                            style="background:linear-gradient(135deg,#C2185B,#D32F2F);border:none;">
                        <i class="fas fa-times me-1"></i>Confirmer le refus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('refusModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('refusForm').action = `/ordre-missions/${btn.dataset.id}/refuser`;
    document.getElementById('refusDesc').textContent =
        `Mission de ${btn.dataset.employe} → ${btn.dataset.dest}`;
});
</script>
@endpush

</x-app-layout>