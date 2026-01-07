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
            width: 20%;
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

        .retard-details {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .retard-details h3 {
            color: #856404;
            font-size: 14px;
            margin: 0 0 10px 0;
            text-align: center;
        }

        .retard-info {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .retard-info-row {
            display: table-row;
        }

        .retard-info-cell {
            display: table-cell;
            padding: 8px;
            text-align: center;
            font-size: 11px;
        }

        .retard-info-cell strong {
            display: block;
            font-size: 16px;
            color: #D32F2F;
            margin-bottom: 3px;
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
                <div class="value">{{ $stats['absences'] }}</div>
                <div class="label">Absences</div>
            </div>
            <div class="stat-box">
                <div class="value">{{ $stats['temps_total'] }}</div>
                <div class="label">Temps Total</div>
            </div>
        </div>
    </div>

    @php
        // Calcul détaillé des retards
        $totalRetardMinutes = 0;
        $retardsDetail = [];
        
        foreach($pointages as $pointage) {
            if ($pointage->type === 'presence' && $pointage->heure_arrivee) {
                $arriveeTime = \Carbon\Carbon::parse($pointage->heure_arrivee);
                $expectedArrivee = \Carbon\Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                
                if ($arriveeTime->greaterThan($expectedArrivee)) {
                    // Vérifier si le retard n'est PAS justifié
                    $isJustified = $pointage->retard_justifie ?? false;
                    
                    if (!$isJustified) {
                        $retardMinutes = $arriveeTime->diffInMinutes($expectedArrivee);
                        $totalRetardMinutes += $retardMinutes;
                        $retardsDetail[] = [
                            'user' => $pointage->user->name,
                            'date' => $pointage->date_pointage->format('d/m/Y'),
                            'heure_arrivee' => $arriveeTime->format('H:i'),
                            'retard' => $retardMinutes
                        ];
                    }
                }
            }
        }
        
        $heuresRetard = floor($totalRetardMinutes / 60);
        $minutesRetard = $totalRetardMinutes % 60;
    @endphp

    @if($stats['retards'] > 0)
    <div class="retard-details">
        <h3>⚠️ DÉTAILS DES RETARDS NON JUSTIFIÉS</h3>
        <div class="retard-info">
            <div class="retard-info-row">
                <div class="retard-info-cell">
                    <strong>{{ $stats['retards'] }}</strong>
                    <span>Nombre de retards</span>
                </div>
                <div class="retard-info-cell">
                    <strong>{{ $totalRetardMinutes }} min</strong>
                    <span>Total en minutes</span>
                </div>
                <div class="retard-info-cell">
                    <strong>{{ $heuresRetard }}h {{ str_pad($minutesRetard, 2, '0', STR_PAD_LEFT) }}min</strong>
                    <span>Total en heures</span>
                </div>
                <div class="retard-info-cell">
                    <strong>{{ $stats['retards'] > 0 ? round($totalRetardMinutes / $stats['retards'], 0) : 0 }} min</strong>
                    <span>Moyenne par retard</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Date</th>
                <th>Type</th>
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
                    $retardMinutes = 0;
                    $duree = '';

                    if ($pointage->type === 'presence' && $pointage->heure_arrivee) {
                        $arriveeTime = \Carbon\Carbon::parse($pointage->heure_arrivee);
                        $expectedArrivee = \Carbon\Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                        if ($arriveeTime->greaterThan($expectedArrivee)) {
                            $isJustified = $pointage->retard_justifie ?? false;
                            if (!$isJustified) {
                                $isLateForArrival = true;
                                $retardMinutes = $arriveeTime->diffInMinutes($expectedArrivee);
                            }
                        }
                    }

                    if ($pointage->type === 'presence' && $pointage->heure_arrivee && $pointage->heure_depart) {
                        $arrivee = \Carbon\Carbon::parse($pointage->heure_arrivee);
                        $depart = \Carbon\Carbon::parse($pointage->heure_depart);
                        $dureeMinutes = $arrivee->diffInMinutes($depart);
                        $heures = floor($dureeMinutes / 60);
                        $minutes = $dureeMinutes % 60;
                        $duree = sprintf('%dh %02dmin', $heures, $minutes);
                    }

                    $typeLabel = match($pointage->type) {
                        'presence' => 'Présence',
                        'absence' => 'Absence',
                        'conge' => 'Congé',
                        default => $pointage->type
                    };
                @endphp
                <tr class="{{ $isLateForArrival ? 'retard' : '' }}">
                    <td>{{ $pointage->user->name }}</td>
                    <td>{{ $pointage->date_pointage ? $pointage->date_pointage->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $typeLabel }}</td>
                    <td>
                        @if($pointage->type === 'presence' && $pointage->heure_arrivee)
                            {{ \Carbon\Carbon::parse($pointage->heure_arrivee)->format('H:i') }}
                            @if($isLateForArrival)
                                <span class="badge badge-danger">Retard {{ $retardMinutes }}min</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($pointage->type === 'presence')
                            {{ $pointage->heure_depart ? \Carbon\Carbon::parse($pointage->heure_depart)->format('H:i') : '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $duree ?: '-' }}</td>
                    <td>
                        @if($pointage->type === 'absence')
                            <span class="badge badge-danger">Absent</span>
                        @elseif($pointage->type === 'conge')
                            <span class="badge badge-warning">Congé</span>
                        @elseif($pointage->heure_depart)
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