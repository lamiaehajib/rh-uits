<x-app-layout>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      
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

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-text h2 {
        color: #C2185B;
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-text p {
        color: #666;
        font-size: 1.1rem;
        font-weight: 500;
    }

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
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(194, 24, 91, 0.4);
        text-decoration: none;
        color: white;
    }

    /* Progress Global Card */
    .progress-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: fadeInUp 0.6s ease 0.1s both;
    }

    .progress-card h5 {
        color: #333;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    .progress-container {
        position: relative;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        height: 35px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .progress-bar {
        background: linear-gradient(90deg, #C2185B, #D32F2F, #ef4444);
        height: 100%;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        transition: width 1s ease-in-out;
        position: relative;
        overflow: hidden;
    }

    .progress-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Main Card */
    .main-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        animation: fadeInUp 0.6s ease 0.2s both;
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
        padding: 0;
    }

    /* Table Styles */
    .table-responsive {
        border-radius: 0 0 24px 24px;
        overflow: hidden;
    }

    .modern-table {
        margin: 0;
        background: white;
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
        color: #333;
        font-weight: 700;
        padding: 1.2rem;
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody td {
        padding: 1.5rem 1.2rem;
        border: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        vertical-align: middle;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: rgba(194, 24, 91, 0.03);
        transform: translateY(-1px);
    }

    /* Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .status-en-cours {
        background: linear-gradient(135deg, #ffa726, #ff9800);
        color: white;
    }

    .status-termine {
        background: linear-gradient(135deg, #66bb6a, #4caf50);
        color: white;
    }

    .status-bloque {
        background: linear-gradient(135deg, #ef4444, #D32F2F);
        color: white;
    }

    /* Progress in Table */
    .progress-mini {
        width: 120px;
        height: 12px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin-right: 0.5rem;
    }

    .progress-mini .progress-bar-mini {
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(90deg, #C2185B, #D32F2F);
        transition: width 0.6s ease;
    }

    .progress-row {
        display: flex;
        align-items: center;
    }

    /* Action Buttons */
    .btn-group {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn-view {
        background: linear-gradient(135deg, #42a5f5, #1e88e5);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444, #D32F2F);
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        color: #C2185B;
        margin-bottom: 1.5rem;
        opacity: 0.7;
    }

    .empty-state p {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    /* Back Button */
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
        margin-top: 1rem;
    }

    .btn-secondary:hover {
        background: rgba(194, 24, 91, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(194, 24, 91, 0.2);
        color: #C2185B;
        text-decoration: none;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-text h2 {
            font-size: 1.8rem;
        }

        .modern-table {
            font-size: 0.85rem;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.8rem 0.5rem;
        }

        .btn-group {
            flex-direction: column;
        }
    }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #C2185B, #D32F2F);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(90deg, #D32F2F, #ef4444);
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section">
        <div class="header-content">
            <div class="header-text">
                <h2>Avancements du projet</h2>
                <p>{{ $projet->nom }}</p>
            </div>
            <a href="{{ route('admin.avancements.create', $projet) }}" class="btn-primary">
                <i class="fas fa-plus"></i> Nouvelle étape
            </a>
        </div>
    </div>

    <!-- Progress Global -->
    <div class="progress-card">
        <h5>Progression globale</h5>
        <div class="progress-container">
            <div class="progress-bar" style="width: {{ $pourcentageGlobal }}%">
                {{ round($pourcentageGlobal, 1) }}%
            </div>
        </div>
        <small style="color: #666;">Basé sur la moyenne de toutes les étapes</small>
    </div>

    <!-- Liste des avancements -->
    <div class="main-card">
        <div class="card-header">
            <h5>Étapes d'avancement</h5>
        </div>
        <div class="card-body">
            @if($avancements->count() > 0)
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Étape</th>
                                <th>Statut</th>
                                <th>Progression</th>
                                <th>Date prévue</th>
                                <th>Date réalisée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($avancements as $avancement)
                                <tr>
                                    <td>
                                        <strong>{{ $avancement->etape }}</strong>
                                        @if($avancement->description)
                                            <br>
                                            <small style="color: #666;">{{ Str::limit($avancement->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($avancement->statut)
                                            @case('en cours')
                                                <span class="status-badge status-en-cours">En cours</span>
                                                @break
                                            @case('terminé')
                                                <span class="status-badge status-termine">Terminé</span>
                                                @break
                                            @case('bloqué')
                                                <span class="status-badge status-bloque">Bloqué</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="progress-row">
                                            <div class="progress-mini">
                                                <div class="progress-bar-mini" style="width: {{ $avancement->pourcentage }}%"></div>
                                            </div>
                                            <span style="font-size: 0.9rem; font-weight: 600;">{{ $avancement->pourcentage }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $avancement->date_prevue ? $avancement->date_prevue->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        {{ $avancement->date_realisee ? $avancement->date_realisee->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.avancements.show', [$projet, $avancement]) }}" 
                                               class="btn-action btn-view">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.avancements.edit', [$projet, $avancement]) }}" 
                                               class="btn-action btn-edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.avancements.destroy', [$projet, $avancement]) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette étape ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-tasks fa-3x"></i>
                    <p>Aucune étape d'avancement créée pour ce projet.</p>
                    <a href="{{ route('admin.avancements.create', $projet) }}" class="btn-primary">
                        Créer la première étape
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Retour au projet -->
    <div style="margin-top: 2rem;">
        <a href="{{ route('admin.projets.show', $projet) }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au projet
        </a>
    </div>
</div>

@push('scripts')
<script>
// Animation fadeIn
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

// Animation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Animation des barres de progression
    const progressBars = document.querySelectorAll('.progress-bar-mini');
    progressBars.forEach((bar, index) => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 300 + (index * 100));
    });
    
    // Animation de la barre principale
    const mainProgressBar = document.querySelector('.progress-bar');
    if (mainProgressBar) {
        const width = mainProgressBar.style.width;
        mainProgressBar.style.width = '0%';
        setTimeout(() => {
            mainProgressBar.style.width = width;
        }, 500);
    }
});
</script>
@endpush
</x-app-layout>