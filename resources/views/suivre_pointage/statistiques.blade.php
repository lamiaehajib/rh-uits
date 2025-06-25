<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Statistiques de Pointage') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Statistiques Mensuelles</h3>

                    <div class="mb-6 flex items-center space-x-4">
                        <label for="month-selector" class="block text-sm font-medium text-gray-700">Sélectionner un mois:</label>
                        <input type="month" id="month-selector" value="{{ now()->format('Y-m') }}"
                               class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div id="statistics-display" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-blue-100 p-6 rounded-lg shadow-md text-center">
                            <h4 class="text-lg font-semibold text-blue-800 mb-2">Total Pointages</h4>
                            <p id="total_pointages" class="text-4xl font-bold text-blue-600">--</p>
                        </div>
                        <div class="bg-green-100 p-6 rounded-lg shadow-md text-center">
                            <h4 class="text-lg font-semibold text-green-800 mb-2">Pointages Complets</h4>
                            <p id="pointages_complets" class="text-4xl font-bold text-green-600">--</p>
                        </div>
                        <div class="bg-yellow-100 p-6 rounded-lg shadow-md text-center">
                            <h4 class="text-lg font-semibold text-yellow-800 mb-2">Pointages en Cours</h4>
                            <p id="pointages_en_cours" class="text-4xl font-bold text-yellow-600">--</p>
                        </div>
                        <div class="bg-purple-100 p-6 rounded-lg shadow-md text-center col-span-full">
                            <h4 class="text-lg font-semibold text-purple-800 mb-2">Temps Total Travaillé (mois sélectionné)</h4>
                            <p id="temps_total_travaille" class="text-5xl font-bold text-purple-600">-- h -- min</p>
                        </div>
                    </div>

                    <div id="loading-spinner" class="hidden text-center mt-8">
                        <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-indigo-500 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Chargement des statistiques...
                    </div>
                    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-8" role="alert">
                        Une erreur est survenue lors du chargement des statistiques.
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthSelector = document.getElementById('month-selector');
            const totalPointages = document.getElementById('total_pointages');
            const pointagesComplets = document.getElementById('pointages_complets');
            const pointagesEnCours = document.getElementById('pointages_en_cours');
            const tempsTotalTravaille = document.getElementById('temps_total_travaille');
            const loadingSpinner = document.getElementById('loading-spinner');
            const errorMessage = document.getElementById('error-message');
            const statisticsDisplay = document.getElementById('statistics-display');

           async function fetchStatistics(month) {
    loadingSpinner.classList.remove('hidden');
    errorMessage.classList.add('hidden');
    statisticsDisplay.classList.add('hidden');
    try {
        // UPDATED: Call the API endpoint route
        const response = await fetch(`{{ route('api.suivre_pointage.statistiques') }}?mois=${month}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();

        totalPointages.textContent = data.total_pointages;
        pointagesComplets.textContent = data.pointages_complets;
        pointagesEnCours.textContent = data.pointages_en_cours;
        tempsTotalTravaille.textContent = data.temps_total_travaille;

        statisticsDisplay.classList.remove('hidden');
    } catch (error) {
        console.error('Error fetching statistics:', error);
        errorMessage.classList.remove('hidden');
    } finally {
        loadingSpinner.classList.add('hidden');
    }
}

            // Initial fetch on page load
            fetchStatistics(monthSelector.value);

            // Fetch when month changes
            monthSelector.addEventListener('change', (event) => {
                fetchStatistics(event.target.value);
            });
        });
    </script>
</x-app-layout>