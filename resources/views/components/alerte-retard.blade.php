{{-- resources/views/components/alerte-retard.blade.php --}}

@if($alerte['doit_alerter'])
<div id="alerte-retard" class="fixed bottom-4 right-4 max-w-md z-50 animate-slide-up">
    <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-lg shadow-2xl p-4 border-l-4 border-yellow-300">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-3xl animate-pulse"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-bold mb-1">
                    ⚠️ Attention aux Retards !
                </h3>
                <p class="text-xs mb-2 opacity-90">
                    {{ $alerte['message'] }}
                </p>
                <div class="bg-white bg-opacity-20 rounded p-2 text-xs">
                    <div class="flex justify-between items-center mb-1">
                        <span>Progression vers déduction:</span>
                        <span class="font-bold">{{ $alerte['jours_potentiels'] }} jour(s)</span>
                    </div>
                    <div class="w-full bg-white bg-opacity-30 rounded-full h-2">
                        @php
                            $pourcentage = (30 - $alerte['minutes_restantes']) / 30 * 100;
                        @endphp
                        <div class="bg-yellow-300 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ $pourcentage }}%"></div>
                    </div>
                    <p class="text-right mt-1 font-semibold">
                        {{ $alerte['minutes_restantes'] }} min restantes
                    </p>
                </div>
                <div class="mt-3 flex space-x-2">
                    <a href="{{ route('retards.mon-rapport') }}" 
                       class="flex-1 bg-white text-orange-600 text-center text-xs font-bold py-2 px-3 rounded hover:bg-opacity-90 transition">
                        Voir Détails
                    </a>
                    <button onclick="fermerAlerte()" 
                            class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-xs font-bold py-2 px-3 rounded transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slide-up {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out forwards;
}
</style>

<script>
function fermerAlerte() {
    document.getElementById('alerte-retard').style.display = 'none';
    // Enregistrer dans localStorage pour ne pas afficher pendant 24h
    localStorage.setItem('alerte-retard-fermee', Date.now());
}

// Vérifier si l'alerte a été fermée récemment
window.addEventListener('DOMContentLoaded', () => {
    const alerteFermee = localStorage.getItem('alerte-retard-fermee');
    if (alerteFermee) {
        const heuresEcoulees = (Date.now() - parseInt(alerteFermee)) / 1000 / 60 / 60;
        if (heuresEcoulees < 24) {
            document.getElementById('alerte-retard').style.display = 'none';
        }
    }
});
</script>
@endif

{{-- INCLURE DANS LE LAYOUT PRINCIPAL (ex: app.blade.php) --}}
{{-- @include('components.alerte-retard', ['alerte' => app(\App\Services\RetardCongeService::class)->verifierAlerteRetard(auth()->id())]) --}}