<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Pointages</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #D32F2F;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #D32F2F;
            font-size: 24px;
            margin: 0 0 5px 0;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .stat-box .value {
            font-size: 20px;
            font-weight: bold;
            color: #D32F2F;
            margin-bottom: 5px;
        }
        
        .stat-box .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        thead {
            background-color: #D32F2F;
            color: white;
        }
        
        th {
            padding: 10px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 9px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tbody tr:hover {
            background-color: #e9ecef;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .retard {
            color: #D32F2F;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Rapport des Pointages</h1>
        <p><strong>Date d'export :</strong> {{ \Carbon\Carbon::now('Africa/Casablanca')->format('d/m/Y à H:i') }}</p>
        <p><strong>Période :</strong> 
            @if(request('periode'))
                {{ ucfirst(str_replace('_', ' ', request('periode'))) }}
            @elseif(request('date_debut') || request('date_fin'))
                Du {{ request('date_debut') ? \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') : '...' }} 
                au {{ request('date_fin') ? \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') : '...' }}
            @else
                Tous les pointages
            @endif
        </p>
    </div>

    <div class="stats-grid">
        <div class="stats-row">
            <div class="stat-box">
                <div class="value">{{ $stats['total_pointages'] }}</div>
                <div class="label">Total Pointages</div>
            </div>
            <div class="stat-box">
                <div class="value">{{ $stats['pointages_complets'] }}</div>
                <div class="label">Terminés</div>
            </div>
            <div class="stat-box">
                <div class="value">{{ $stats['retards'] }}</div>
                <div class="label">Retards</div>
            </div>
            <div class="stat-box">
                <div class="value">{{ $stats['temps_total'] }}</div>
                <div class="label">Temps Total</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Date</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Durée</th>
                <th>Statut</th>
                
            </tr>
        </thead>
        <tbody>
            @forelse($pointages as $pointage)
                @php
                    $isLateForArrival = false;
                    $duree = '';

                    if ($pointage->heure_arrivee) {
                        $arriveeTime = \Carbon\Carbon::parse($pointage->heure_arrivee);
                        $expectedArrivee = \Carbon\Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                        if ($arriveeTime->greaterThan($expectedArrivee)) {
                            $isLateForArrival = true;
                        }
                    }

                    if ($pointage->heure_arrivee && $pointage->heure_depart) {
                        $arrivee = \Carbon\Carbon::parse($pointage->heure_arrivee);
                        $depart = \Carbon\Carbon::parse($pointage->heure_depart);
                        $dureeMinutes = $arrivee->diffInMinutes($depart);
                        $heures = floor($dureeMinutes / 60);
                        $minutes = $dureeMinutes % 60;
                        $duree = sprintf('%dh %02dmin', $heures, $minutes);
                    }
                @endphp
                <tr class="{{ $isLateForArrival ? 'retard' : '' }}">
                    <td>{{ $pointage->user->name }}</td>
                    <td>{{ $pointage->date_pointage ? $pointage->date_pointage->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                        {{ $pointage->heure_arrivee ? \Carbon\Carbon::parse($pointage->heure_arrivee)->format('H:i') : '-' }}
                        @if($isLateForArrival)
                            <span class="badge badge-danger">Retard</span>
                        @endif
                    </td>
                    <td>{{ $pointage->heure_depart ? \Carbon\Carbon::parse($pointage->heure_depart)->format('H:i') : '-' }}</td>
                    <td>{{ $duree ?: '-' }}</td>
                    <td>
                        @if($pointage->heure_depart)
                            <span class="badge badge-success">Terminé</span>
                        @else
                            <span class="badge badge-warning">En cours</span>
                        @endif
                    </td>
                    
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #999;">
                        Aucun pointage trouvé pour cette période.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Généré automatiquement par le système de gestion des pointages</p>
        <p>Document confidentiel - Usage interne uniquement</p>
    </div>
</body>
</html>