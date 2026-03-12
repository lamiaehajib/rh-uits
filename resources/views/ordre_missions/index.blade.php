<x-app-layout>

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&family=Ubuntu+Mono&display=swap" rel="stylesheet">
<style>
    :root {
        --crimson:    #D32F2F;
        --magenta:    #C2185B;
        --grad:       linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);
        --grad-soft:  linear-gradient(135deg, rgba(194,24,91,.08) 0%, rgba(211,47,47,.08) 100%);
        --dark:       #1a0a0f;
        --text:       #2d1a20;
        --muted:      #7a5560;
        --border:     rgba(194,24,91,.15);
        --surface:    #ffffff;
        --bg:         #fdf6f7;
        --shadow-sm:  0 2px 12px rgba(194,24,91,.08);
        --shadow-md:  0 8px 32px rgba(194,24,91,.14);
        --shadow-lg:  0 20px 60px rgba(194,24,91,.18);
        --radius:     16px;
        --radius-sm:  10px;
    }

    /* ── Page Shell ─────────────────────────────────────── */
    .om-page {
        font-family: 'Ubuntu', sans-serif;
        color: var(--text);
        animation: pageIn .45s cubic-bezier(.22,1,.36,1) both;
    }

    @keyframes pageIn {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Hero Header ──────────────────────────────────────── */
    .om-hero {
        background: var(--grad);
        border-radius: var(--radius);
        padding: 36px 40px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .om-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 85% 20%, rgba(255,255,255,.12) 0%, transparent 55%),
            radial-gradient(circle at 10% 90%, rgba(0,0,0,.12) 0%, transparent 50%);
        pointer-events: none;
    }

    .om-hero-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    .om-hero-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: rgba(255,255,255,.65);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .om-hero-label::before {
        content: '';
        display: inline-block;
        width: 24px;
        height: 2px;
        background: rgba(255,255,255,.5);
        border-radius: 2px;
    }

    .om-hero-title {
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        font-weight: 700;
        color: #fff;
        margin: 0 0 6px;
        line-height: 1.15;
        letter-spacing: -.3px;
        position: relative;
    }

    .om-hero-sub {
        color: rgba(255,255,255,.72);
        font-size: .92rem;
        font-weight: 400;
        margin: 0;
        position: relative;
    }

    .om-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        flex-shrink: 0;
    }

    /* ── Stat Cards ────────────────────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 22px 24px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: var(--radius) var(--radius) 0 0;
    }

    .stat-card.c-all::after     { background: var(--grad); }
    .stat-card.c-wait::after    { background: linear-gradient(90deg, #F59E0B, #FBBF24); }
    .stat-card.c-ok::after      { background: linear-gradient(90deg, #10B981, #34D399); }
    .stat-card.c-no::after      { background: linear-gradient(90deg, #EF4444, #F87171); }

    .stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        margin-bottom: 14px;
    }

    .stat-card.c-all  .stat-icon { background: var(--grad-soft); color: var(--magenta); }
    .stat-card.c-wait .stat-icon { background: rgba(245,158,11,.1); color: #D97706; }
    .stat-card.c-ok   .stat-icon { background: rgba(16,185,129,.1); color: #059669; }
    .stat-card.c-no   .stat-icon { background: rgba(239,68,68,.1);  color: #DC2626; }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 4px;
        color: var(--text);
        font-family: 'Ubuntu Mono', monospace;
    }

    .stat-label {
        font-size: .78rem;
        color: var(--muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .8px;
    }

    /* ── Toolbar ──────────────────────────────────────────── */
    .toolbar {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 20px 24px;
        margin-bottom: 20px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .toolbar-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        flex: 1;
        min-width: 160px;
    }

    .toolbar-group label {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: var(--muted);
    }

    .om-input {
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 9px 14px;
        font-family: 'Ubuntu', sans-serif;
        font-size: .875rem;
        color: var(--text);
        background: var(--bg);
        transition: border-color .2s, box-shadow .2s;
        outline: none;
        width: 100%;
    }

    .om-input:focus {
        border-color: var(--magenta);
        box-shadow: 0 0 0 3px rgba(194,24,91,.1);
        background: #fff;
    }

    .om-select { appearance: none; cursor: pointer; }

    /* ── Buttons ──────────────────────────────────────────── */
    .btn-primary-om {
        background: var(--grad);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        padding: 10px 22px;
        font-family: 'Ubuntu', sans-serif;
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: opacity .2s, transform .15s, box-shadow .2s;
        box-shadow: 0 4px 14px rgba(211,47,47,.35);
        white-space: nowrap;
    }

    .btn-primary-om:hover {
        opacity: .9;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(211,47,47,.45);
        color: #fff;
        text-decoration: none;
    }

    .btn-outline-om {
        background: transparent;
        color: var(--magenta);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 9px 18px;
        font-family: 'Ubuntu', sans-serif;
        font-size: .875rem;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all .2s;
        white-space: nowrap;
    }

    .btn-outline-om:hover {
        border-color: var(--magenta);
        background: var(--grad-soft);
        color: var(--crimson);
        text-decoration: none;
    }

    .btn-sm-om {
        padding: 6px 14px;
        font-size: .8rem;
        border-radius: 8px;
    }

    /* ── Table Panel ──────────────────────────────────────── */
    .table-panel {
        background: var(--surface);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .table-panel-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .table-panel-title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .table-panel-title .dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--grad);
        display: inline-block;
    }

    .count-badge {
        background: var(--grad-soft);
        color: var(--crimson);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2px 10px;
        font-size: .75rem;
        font-weight: 700;
        font-family: 'Ubuntu Mono', monospace;
    }

    /* ── Data Table ───────────────────────────────────────── */
    .om-table {
        width: 100%;
        border-collapse: collapse;
    }

    .om-table thead tr {
        background: var(--bg);
    }

    .om-table thead th {
        padding: 12px 16px;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: var(--muted);
        white-space: nowrap;
        border-bottom: 1px solid var(--border);
        text-align: left;
    }

    .om-table tbody tr {
        border-bottom: 1px solid rgba(194,24,91,.06);
        transition: background .15s;
    }

    .om-table tbody tr:last-child { border-bottom: none; }
    .om-table tbody tr:hover { background: var(--grad-soft); }

    .om-table tbody td {
        padding: 14px 16px;
        font-size: .875rem;
        color: var(--text);
        vertical-align: middle;
    }

    .om-table tbody td:first-child { font-weight: 600; color: var(--magenta); }

    /* ── Status Badges ────────────────────────────────────── */
    .badge-om {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .4px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .badge-om .dot-s {
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .badge-en_attente { background: rgba(245,158,11,.12); color: #92400E; border: 1px solid rgba(245,158,11,.25); }
    .badge-en_attente .dot-s { background: #F59E0B; }

    .badge-approuve   { background: rgba(16,185,129,.12); color: #064E3B; border: 1px solid rgba(16,185,129,.25); }
    .badge-approuve .dot-s   { background: #10B981; }

    .badge-refuse     { background: rgba(239,68,68,.1);  color: #7F1D1D; border: 1px solid rgba(239,68,68,.2); }
    .badge-refuse .dot-s     { background: #EF4444; }

    .badge-cloture    { background: rgba(107,114,128,.1); color: #374151; border: 1px solid rgba(107,114,128,.2); }
    .badge-cloture .dot-s    { background: #9CA3AF; }

    .badge-annule     { background: rgba(107,114,128,.08); color: #6B7280; border: 1px solid rgba(107,114,128,.15); }
    .badge-annule .dot-s     { background: #D1D5DB; }

    /* ── Employee Avatar ──────────────────────────────────── */
    .emp-cell { display: flex; align-items: center; gap: 10px; }

    .emp-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: var(--grad);
        color: #fff;
        font-size: .72rem;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    .emp-name  { font-weight: 600; font-size: .875rem; color: var(--text); }
    .emp-email { font-size: .75rem; color: var(--muted); }

    /* ── Amount Cell ──────────────────────────────────────── */
    .amount-cell {
        font-family: 'Ubuntu Mono', monospace;
        font-weight: 600;
        font-size: .875rem;
    }

    /* ── Destination Cell ─────────────────────────────────── */
    .dest-cell { max-width: 180px; }
    .dest-name { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dest-objet { font-size: .75rem; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }

    /* ── Transport Badge ──────────────────────────────────── */
    .transport-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: var(--grad-soft);
        color: var(--crimson);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 3px 9px;
        font-size: .72rem;
        font-weight: 600;
    }

    /* ── Empty State ──────────────────────────────────────── */
    .empty-state {
        padding: 80px 40px;
        text-align: center;
    }

    .empty-state-icon {
        width: 72px; height: 72px;
        border-radius: 50%;
        background: var(--grad-soft);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px;
        margin: 0 auto 20px;
        border: 2px dashed var(--border);
    }

    .empty-state h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }

    .empty-state p {
        color: var(--muted);
        font-size: .875rem;
        margin-bottom: 24px;
    }

    /* ── Pagination ───────────────────────────────────────── */
    .om-pagination {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info {
        font-size: .8rem;
        color: var(--muted);
        font-weight: 500;
    }

    .om-pagination .pagination {
        margin: 0;
        gap: 4px;
        display: flex;
        flex-wrap: wrap;
    }

    .om-pagination .page-link {
        border: 1.5px solid var(--border);
        color: var(--muted);
        border-radius: 8px !important;
        padding: 6px 12px;
        font-size: .8rem;
        font-weight: 600;
        font-family: 'Ubuntu', sans-serif;
        transition: all .2s;
    }

    .om-pagination .page-link:hover {
        border-color: var(--magenta);
        color: var(--magenta);
        background: var(--grad-soft);
        z-index: auto;
    }

    .om-pagination .page-item.active .page-link {
        background: var(--grad);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 3px 10px rgba(211,47,47,.35);
    }

    .om-pagination .page-item.disabled .page-link {
        opacity: .4;
        pointer-events: none;
    }

    /* ── Alert Flash ──────────────────────────────────────── */
    .om-alert {
        border-radius: var(--radius-sm);
        padding: 14px 18px;
        margin-bottom: 20px;
        font-size: .875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid transparent;
        animation: slideDown .3s ease both;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .om-alert-success { background: rgba(16,185,129,.1); border-color: rgba(16,185,129,.25); color: #064E3B; }
    .om-alert-error   { background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.2); color: #7F1D1D; }

    /* ── Responsive ───────────────────────────────────────── */
    @media (max-width: 1200px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .om-hero { padding: 24px 20px; }
        .stats-row { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .om-hero-actions { flex-direction: column; width: 100%; }
        .om-table { display: block; overflow-x: auto; }
        .toolbar { gap: 10px; }
        .toolbar-group { min-width: 140px; }
    }

    @media (max-width: 480px) {
        .stats-row { grid-template-columns: 1fr 1fr; }
        .stat-value { font-size: 1.6rem; }
    }
</style>
@endpush


<div class="om-page">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="om-alert om-alert-success">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="om-alert om-alert-error">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Hero Header ─────────────────────────────────────── --}}
    <div class="om-hero">
        <div class="om-hero-grid"></div>
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <div class="om-hero-label">Gestion RH</div>
                <h1 class="om-hero-title">
                    @if($isAdmin) Ordres de Mission @else Mes Demandes @endif
                </h1>
                <p class="om-hero-sub">
                    @if($isAdmin)
                        Tableau de bord administrateur — suivi et validation des missions
                    @else
                        Soumettez et suivez l'avancement de vos demandes de déplacement
                    @endif
                </p>
            </div>
            <div class="om-hero-actions">
                @if($isAdmin)
                    <a href="{{ route('ordre-missions.dashboard') }}" class="btn-outline-om" style="border-color:rgba(255,255,255,.4);color:#fff;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('ordre-missions.create') }}" class="btn-primary-om" style="background:rgba(255,255,255,.2);box-shadow:none;border:1.5px solid rgba(255,255,255,.4);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Nouvelle demande
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Stats Row ────────────────────────────────────────── --}}
    <div class="stats-row">
        <div class="stat-card c-all">
            <div class="stat-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17H7A5 5 0 017 7h2M15 7h2a5 5 0 010 10h-2M11 12h2"/></svg>
            </div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total missions</div>
        </div>
        <div class="stat-card c-wait">
            <div class="stat-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="stat-value">{{ $stats['en_attente'] }}</div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="stat-card c-ok">
            <div class="stat-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="stat-value">{{ $stats['approuve'] }}</div>
            <div class="stat-label">Approuvées</div>
        </div>
        <div class="stat-card c-no">
            <div class="stat-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div class="stat-value">{{ $stats['refuse'] }}</div>
            <div class="stat-label">Refusées</div>
        </div>
    </div>

    {{-- ── Toolbar / Filters ────────────────────────────────── --}}
    <form method="GET" action="{{ route('ordre-missions.index') }}" class="toolbar">
        <div class="toolbar-group" style="max-width:280px;">
            <label>Recherche</label>
            <div style="position:relative;">
                <svg style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="om-input" style="padding-left:34px;" placeholder="Destination, objet…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="toolbar-group" style="max-width:160px;">
            <label>Statut</label>
            <select name="statut" class="om-input om-select">
                <option value="">Tous</option>
                <option value="en_attente" @selected(request('statut')=='en_attente')>En attente</option>
                <option value="approuve"   @selected(request('statut')=='approuve')>Approuvé</option>
                <option value="refuse"     @selected(request('statut')=='refuse')>Refusé</option>
                <option value="cloture"    @selected(request('statut')=='cloture')>Clôturé</option>
                <option value="annule"     @selected(request('statut')=='annule')>Annulé</option>
            </select>
        </div>
        <div class="toolbar-group" style="max-width:160px;">
            <label>Date départ (de)</label>
            <input type="date" name="date_from" class="om-input" value="{{ request('date_from') }}">
        </div>
        <div class="toolbar-group" style="max-width:160px;">
            <label>Date départ (à)</label>
            <input type="date" name="date_to" class="om-input" value="{{ request('date_to') }}">
        </div>
        <div style="display:flex;gap:8px;align-items:flex-end;padding-bottom:1px;">
            <button type="submit" class="btn-primary-om btn-sm-om">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filtrer
            </button>
            @if(request()->hasAny(['search','statut','date_from','date_to']))
                <a href="{{ route('ordre-missions.index') }}" class="btn-outline-om btn-sm-om">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Réinitialiser
                </a>
            @endif
        </div>
    </form>

    {{-- ── Table Panel ──────────────────────────────────────── --}}
    <div class="table-panel">
        <div class="table-panel-header">
            <p class="table-panel-title">
                <span class="dot"></span>
                Liste des missions
                <span class="count-badge">{{ $missions->total() }}</span>
            </p>
            @if(!$isAdmin)
                <a href="{{ route('ordre-missions.create') }}" class="btn-primary-om btn-sm-om">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Nouvelle demande
                </a>
            @endif
        </div>

        @if($missions->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">✈️</div>
                <h5>Aucune mission trouvée</h5>
                <p>{{ request()->hasAny(['search','statut','date_from','date_to']) ? 'Essayez de modifier vos critères de recherche.' : 'Aucune demande pour le moment.' }}</p>
                @if(!$isAdmin)
                    <a href="{{ route('ordre-missions.create') }}" class="btn-primary-om">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Créer ma première demande
                    </a>
                @endif
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="om-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if($isAdmin)<th>Employé</th>@endif
                            <th>Destination / Objet</th>
                            <th>Départ</th>
                            <th>Retour</th>
                            <th>Transport</th>
                            <th>Avance</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($missions as $mission)
                        @php
                            $transportIcons = [
                                'voiture_personnelle' => '🚗',
                                'train'  => '🚆',
                                'avion'  => '✈️',
                                'bus'    => '🚌',
                                'autre'  => '🔄',
                            ];
                            $transportLabels = [
                                'voiture_personnelle' => 'Voiture',
                                'train'  => 'Train',
                                'avion'  => 'Avion',
                                'bus'    => 'Bus',
                                'autre'  => $mission->moyen_transport_autre ?? 'Autre',
                            ];
                        @endphp
                        <tr>
                            <td>
                                <span style="font-family:'Ubuntu Mono',monospace;font-size:.8rem;color:var(--muted);">
                                    #{{ str_pad($mission->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            @if($isAdmin)
                            <td>
                                <div class="emp-cell">
                                    <div class="emp-avatar">
                                        {{ strtoupper(substr($mission->employe->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(strstr($mission->employe->name ?? '', ' ') ?: '', 1, 1)) }}
                                    </div>
                                    <div>
                                        <div class="emp-name">{{ $mission->employe->name ?? '—' }}</div>
                                        <div class="emp-email">{{ $mission->employe->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            @endif

                            <td>
                                <div class="dest-cell">
                                    <div class="dest-name">📍 {{ $mission->destination }}</div>
                                    <div class="dest-objet">{{ $mission->objet }}</div>
                                </div>
                            </td>

                            <td style="white-space:nowrap;font-size:.82rem;color:var(--muted);">
                                {{ \Carbon\Carbon::parse($mission->date_depart)->format('d M Y') }}
                            </td>
                            <td style="white-space:nowrap;font-size:.82rem;color:var(--muted);">
                                {{ \Carbon\Carbon::parse($mission->date_retour)->format('d M Y') }}
                            </td>

                            <td>
                                <span class="transport-pill">
                                    {{ $transportIcons[$mission->moyen_transport] ?? '🔄' }}
                                    {{ $transportLabels[$mission->moyen_transport] ?? $mission->moyen_transport }}
                                </span>
                            </td>

                            <td>
                                <span class="amount-cell">
                                    {{ number_format($mission->avance_demandee, 2) }}
                                    <span style="font-size:.7rem;font-weight:400;color:var(--muted);"> MAD</span>
                                </span>
                            </td>

                            <td>
                                @php
                                    $statusLabels = [
                                        'en_attente' => 'En attente',
                                        'approuve'   => 'Approuvée',
                                        'refuse'     => 'Refusée',
                                        'cloture'    => 'Clôturée',
                                        'annule'     => 'Annulée',
                                    ];
                                @endphp
                                <span class="badge-om badge-{{ $mission->statut }}">
                                    <span class="dot-s"></span>
                                    {{ $statusLabels[$mission->statut] ?? $mission->statut }}
                                </span>
                            </td>

                            <td>
                                <div style="display:flex;gap:6px;align-items:center;justify-content:center;">
                                    <a href="{{ route('ordre-missions.show', $mission) }}"
                                       class="btn-outline-om btn-sm-om"
                                       title="Voir les détails"
                                       style="padding:5px 10px;">
                                         <i class="fas fa-eye me-1"></i>Voir
                                    </a>

                                    @if(!$isAdmin && $mission->statut === 'en_attente')
                                        <a href="{{ route('ordre-missions.edit', $mission) }}"
                                           class="btn-outline-om btn-sm-om"
                                           title="Modifier"
                                           style="padding:5px 10px;">
                                             <i class="fas fa-pen"></i>
                                        </a>
                                    @endif

                                    @if(!$isAdmin && in_array($mission->statut, ['en_attente','approuve']))
                                        <form method="POST"
                                              action="{{ route('ordre-missions.annuler', $mission) }}"
                                              style="display:inline;"
                                              onsubmit="return confirm('Annuler cette demande ?')">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="btn-outline-om btn-sm-om"
                                                    title="Annuler"
                                                    style="padding:5px 10px;color:#DC2626;border-color:rgba(239,68,68,.3);">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($missions->hasPages())
            <div class="om-pagination">
                <span class="pagination-info">
                    Affichage de
                    <strong>{{ $missions->firstItem() }}</strong>
                    à
                    <strong>{{ $missions->lastItem() }}</strong>
                    sur
                    <strong>{{ $missions->total() }}</strong>
                    résultats
                </span>
                <div>{{ $missions->links() }}</div>
            </div>
            @endif
        @endif
    </div>

</div>


</x-app-layout>