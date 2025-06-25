<x-app-layout>
  <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  </head>
  <style>
  /* Définition des variables CSS pour un thème cohérent */
  :root {
  --primary-color: #D32F2F; /* Rouge doux */
  --accent-color: #C2185B; /* Rose clair */
  --header-color: #f04242; /* Rouge vibrant pour le titre */
  --table-header-bg: #d82e34; /* Rouge foncé pour l'en-tête du tableau */
  --success-text: #10b981; /* Vert pour 'Connecté' */
  --danger-text: #ef4444; /* Rouge pour les erreurs/déconnexions */
  --bg-light: #f4f7fc; /* Arrière-plan général */
  --card-bg: #ffffff; /* Fond des cartes */
  --border-color: #e5e7eb; /* Bordures subtiles */
  --box-shadow-light: 0 4px 12px rgba(0, 0, 0, 0.06);
  --box-shadow-medium: 0 8px 24px rgba(0, 0, 0, 0.1);
  --border-radius-lg: 1rem;
  --border-radius-md: 0.75rem;
  --transition-ease: all 0.3s ease-in-out;
  }

  body {
  font-family: 'Inter', sans-serif; /* Police moderne */
  background-color: var(--bg-light);
  color: #333;
  }

  /* Styles globaux pour les sections */
  .section-card {
  background-color: var(--card-bg);
  border-radius: var(--border-radius-lg);
  box-shadow: var(--box-shadow-medium);
  padding: 2rem;
  margin-bottom: 2rem;
  transition: var(--transition-ease);
  animation: fadeIn 0.5s ease-out; /* Animation d'apparition */
  }

  .section-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
  }

  /* Titre de section */
  .section-title-main {
  font-size: 2.5rem; /* Plus grand pour l'impact */
  color: var(--header-color);
  font-weight: 800; /* Extra bold */
  text-align: center;
  letter-spacing: 0.1rem;
  margin-bottom: 2.5rem;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.05); /* Ombre légère pour le texte */
  animation: slideInDown 0.5s ease-out; /* Animation de glissement vers le bas */
  }

  /* Formulaire de recherche */
  .search-form-card {
  background-color: var(--card-bg);
  padding: 1.5rem;
  border-radius: var(--border-radius-md);
  box-shadow: var(--box-shadow-light);
  flex-shrink: 0; /* Ne pas rétrécir sur les petits écrans */
  max-width: 300px; /* Taille plus raisonnable */
  margin-top: 0; /* Supprime le margin-top qui le décalait */
  animation: slideInLeft 0.5s ease-out; /* Animation de glissement depuis la gauche */
  }

  .form-input-group {
  margin-bottom: 1rem;
  }

  .form-label {
  display: block;
  font-weight: 600; /* Semi-bold */
  margin-bottom: 0.4rem;
  color: #4a5568; /* Gris plus doux */
  font-size: 0.95rem;
  }

  .form-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid var(--border-color);
  border-radius: 0.5rem;
  font-size: 1rem;
  box-sizing: border-box;
  transition: border-color 0.3s, box-shadow 0.3s;
  }

  .form-input:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); /* Anneau de focus bleu */
  outline: none;
  }

  .form-button {
  display: block;
  width: 100%;
  padding: 0.75rem;
  background: linear-gradient(135deg, #f43c3c, #d82e34); /* Dégradé rouge */
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-size: 1.1rem;
  font-weight: 700;
  cursor: pointer;
  transition: var(--transition-ease);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  animation: pulse 2s infinite alternate; /* Animation de pulsation */
  }

  .form-button:hover {
  background: linear-gradient(135deg, #d82e34, #f43c3c); /* Inverser le dégradé */
  box-shadow: 0 5px 15px rgba(244, 60, 60, 0.3);
  }

  /* Tableau */
  .modern-table-container {
  overflow-x: auto; /* Permet le défilement horizontal sur petits écrans */
  background-color: var(--card-bg);
  border-radius: var(--border-radius-md);
  box-shadow: var(--box-shadow-light);
  padding: 1rem;
  animation: slideInRight 0.5s ease-out; /* Animation de glissement depuis la droite */
  }

  .modern-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  min-width: 600px; /* Minimum width pour les colonnes */
  }

  .modern-table thead {
  background: var(--table-header-bg);
  }

  .modern-table th {
  padding: 1rem 1.5rem;
  text-align: left;
  color: white;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 0.9rem;
  letter-spacing: 0.05em;
  }

  .modern-table th:first-child {
  border-top-left-radius: var(--border-radius-md);
  }

  .modern-table th:last-child {
  border-top-right-radius: var(--border-radius-md);
  }

  .modern-table td {
  padding: 1rem 1.5rem;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
  background-color: var(--card-bg);
  font-size: 0.95rem;
  color: #374151; /* Gris foncé */
  transition: background-color 0.3s ease; /* Transition de couleur de fond */
  }

  .modern-table tbody tr:last-child td {
  border-bottom: none;
  }

  .modern-table tbody tr:hover {
  background-color: #fbfbfc; /* Gris très clair au survol */
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); /* Légère ombre au survol */
  transform: translateY(-2px); /* Légère translation vers le haut au survol */
  }

  .status-connected {
  font-weight: 600;
  color: var(--success-text);
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  animation: fadeIn 1s ease-in-out; /* Animation d'apparition */
  }

  .status-disconnected {
  font-weight: 600;
  color: var(--danger-text);
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both; /* Animation de secousse */
  }

  /* Pagination */
  .pagination-container {
  display: flex;
  justify-content: center;
  margin-top: 1.5rem;
  margin-bottom: 2rem;
  animation: fadeInUp 0.5s ease-out; /* Animation de glissement vers le haut */
  }

  .pagination-container nav ul {
  display: flex;
  list-style: none;
  padding: 0;
  margin: 0;
  gap: 0.5rem;
  }

  .pagination-container nav ul li a,
  .pagination-container nav ul li span {
  padding: 0.6rem 1rem;
  border-radius: 0.5rem;
  font-weight: 600;
  transition: var(--transition-ease);
  border: 1px solid var(--border-color);
  color: var(--primary-color);
  text-decoration: none;
  }

  .pagination-container nav ul li a:hover:not(.active),
  .pagination-container nav ul li span:hover:not(.active) {
  background-color: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
  transform: translateY(-2px);
  box-shadow: 0 3px 10px rgba(59, 130, 246, 0.2);
  }

  .pagination-container nav ul li.active span {
  background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
  color: white;
  border-color: var(--primary-color);
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  }

  /* Responsive adjustments */
  @media (min-width: 768px) {
  .div-flex {
  display: flex;
  gap: 2rem; /* Espace plus grand entre le formulaire et le tableau */
  align-items: flex-start; /* Alignement en haut */
  }
  .search-form-card {
  max-width: 250px; /* Ajuste pour les écrans moyens */
  }
  }

  @media (max-width: 767px) {
  .div-flex {
  flex-direction: column; /* Empile le formulaire et le tableau */
  }
  .search-form-card {
  width: 100%;
  max-width: none; /* Occupe toute la largeur disponible */
  margin-bottom: 2rem; /* Espace sous le formulaire */
  }
  .modern-table-container {
  padding: 0.5rem; /* Moins de padding sur les petits écrans */
  }
  .modern-table th, .modern-table td {
  padding: 0.8rem 1rem; /* Moins de padding dans les cellules */
  font-size: 0.85rem;
  }
  .section-title-main {
  font-size: 1.8rem;
  margin-bottom: 1.5rem;
  }
  }

  @media (max-width: 480px) {
  .modern-table th, .modern-table td {
  font-size: 0.8rem;
  }
  }

  /* Animations */
  @keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
  }

  @keyframes slideInDown {
  from { transform: translateY(-50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
  }

  @keyframes slideInLeft {
  from { transform: translateX(-50px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
  }

  @keyframes slideInRight {
  from { transform: translateX(50px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
  }

  @keyframes fadeInUp {
  from { transform: translateY(50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
  }

  @keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
  }

  @keyframes shake {
  0% { transform: translateX(0); }
  10% { transform: translateX(-10px); }
  20% { transform: translateX(10px); }
  30% { transform: translateX(-10px); }
  40% { transform: translateX(10px); }
  50% { transform: translateX(-10px); }
  60% { transform: translateX(10px); }
  70% { transform: translateX(-10px); }
  80% { transform: translateX(10px); }
  90% { transform: translateX(-10px); }
  100% { transform: translateX(0); }
  }
  </style>

  <div class="py-12 px-4 sm:px-6 lg:px-8">
  <h3 class="section-title-main">Historique des connexions</h3>

  <div class="div-flex">
  <div class="search-form-card">
  <form method="GET" action="{{ route('login.history') }}">
  <div class="form-input-group">
  <label for="username" class="form-label">Nom d'utilisateur :</label>
  <input type="text" name="username" id="username" class="form-input" placeholder="Ex: Jean Dupont" value="{{ request('username') }}">
  </div>
  <div class="form-input-group">
  <label for="date" class="form-label">Date :</label>
  <input type="date" name="date" id="date" class="form-input" value="{{ request('date') }}">
  </div>
  <button type="submit" class="form-button">
  <i class="fas fa-search mr-2"></i> Rechercher
  </button>
  </form>
  </div>

  <div class="modern-table-container flex-1">
  <table class="modern-table">
  <thead>
  <tr>
  <th>Jour & Date</th>
  <th>Utilisateur</th>
  <th>Heure de Connexion</th>
  </tr>
  </thead>
  <tbody>
  @forelse ($logs as $log)
  @php
  // Assurez-vous que Carbon est bien importé dans votre contrôleur
  // et que la colonne 'logged_in_at' existe et est de type datetime.
  $logTime = \Carbon\Carbon::parse($log->logged_in_at)->timezone('Africa/Casablanca');
  $dayOfWeek = $logTime->locale('fr')->dayName;
  @endphp
  <tr class="hover:shadow-md">
  <td>{{ ucfirst($dayOfWeek) }} ({{ $logTime->format('d/m/Y') }})</td>
  <td>{{ $log->user->name ?? 'Utilisateur inconnu' }}</td> {{-- Ajout de ?? 'Utilisateur inconnu' --}}
  <td>
  <span class="status-connected">
  <i class="fas fa-circle-check"></i> Connecté à {{ $logTime->format('H:i') }}
  </span>
  {{-- Si tu avais un champ logout_at, tu pourrais l'ajouter ici --}}
  {{-- @if($log->logged_out_at)
  <br>
  <span class="status-disconnected">
  <i class="fas fa-circle-xmark"></i> Déconnecté à {{ \Carbon\Carbon::parse($log->logged_out_at)->timezone('Africa/Casablanca')->format('H:i') }}
  </span>
  @endif --}}
  </td>
  </tr>
  @empty
  <tr>
  <td colspan="3" class="text-center text-gray-500 py-4">Aucun résultat trouvé pour votre recherche.</td>
  </tr>
  @endforelse
  </tbody>
  </table>
  </div>
  </div>

  {{-- Pagination --}}
  <div class="pagination-container">
  {{ $logs->links('pagination::tailwind') }} {{-- Utilise la pagination Tailwind par défaut --}}
  </div>
  </div>
 </x-app-layout>