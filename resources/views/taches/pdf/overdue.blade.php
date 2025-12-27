<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Tâches en Retard</title>
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
            width: 33.33%;
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
            font-size: 9px;
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
        
        .badge-priority-faible {
            background-color: #3498db;
            color: white;
        }
        
        .badge-priority-moyen {
            background-color: #f39c12;
            color: white;
        }
        
        .badge-priority-eleve {
            background-color: #e74c3c;
            color: white;
        }
        
        .badge-status-nouveau {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-status-en-cours {
            background-color: #9b59b6;
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
        
        .retard-highlight {
            background-color: #ffebee !important;
        }
        
        .retard-days {
            color: #D32F2F;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚠️ Rapport des Tâches en Retard</h1>
        <p><strong>Date d'export :</strong> {{ \Carbon\Carbon::now('Africa/Casablanca')->format('d/m/Y à H:i') }}</p>
        <p><strong>Période :</strong> {{ $monthName }} {{ $year }}</p>
    </div>

    <div class="stats-grid">
        <div class="stats-row">
            <div class="stat-box">
                <div class="value">{{ $stats['total'] }}</div>
                <div class="label">Total Tâches en Retard</div>
            </div>
            <div class="stat-box">
                <div class="value">{{ $stats['total_jours_retard'] }}</div>
                <div class="label">Total Jours de Retard</div>
            </div>
            <div class="stat-box">
                <div class="value">{{ $stats['retard_moyen'] }}</div>
                <div class="label">Retard Moyen (jours)</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 20%;">Titre</th>
                <th style="width: 10%;">Date Début</th>
                <th style="width: 10%;">Date Fin Prévue</th>
                <th style="width: 8%;">Retard</th>
                <th style="width: 8%;">Priorité</th>
                <th style="width: 8%;">Statut</th>
                <th style="width: 20%;">Utilisateurs</th>
                <th style="width: 11%;">Durée</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taches as $tache)
                @php
                    $daysLate = 0;
                    if ($tache->date_fin_prevue) {
                        $dateFinPrevue = \Carbon\Carbon::parse($tache->date_fin_prevue);
                        $daysLate = $dateFinPrevue->diffInDays(\Carbon\Carbon::now(), false);
                        if ($daysLate < 0) $daysLate = 0;
                    }
                @endphp
                <tr class="{{ $daysLate > 7 ? 'retard-highlight' : '' }}">
                    <td>{{ $tache->id }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($tache->titre, 40) }}</td>
                    <td>{{ \Carbon\Carbon::parse($tache->datedebut)->format('d/m/Y') }}</td>
                    <td>{{ $tache->date_fin_prevue ? \Carbon\Carbon::parse($tache->date_fin_prevue)->format('d/m/Y') : '-' }}</td>
                    <td class="retard-days">{{ $daysLate }} jour(s)</td>
                    <td>
                        @if($tache->priorite == 'faible')
                            <span class="badge badge-priority-faible">Faible</span>
                        @elseif($tache->priorite == 'moyen')
                            <span class="badge badge-priority-moyen">Moyen</span>
                        @else
                            <span class="badge badge-priority-eleve">Élevé</span>
                        @endif
                    </td>
                    <td>
                        @if($tache->status == 'nouveau')
                            <span class="badge badge-status-nouveau">Nouveau</span>
                        @else
                            <span class="badge badge-status-en-cours">En cours</span>
                        @endif
                    </td>
                    <td>
                        @foreach($tache->users as $user)
                            {{ $user->name }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </td>
                    <td>{{ $tache->duree }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px; color: #999;">
                        Aucune tâche en retard trouvée pour cette période.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Généré automatiquement par le système de gestion des tâches</p>
        <p>Document confidentiel - Usage interne uniquement</p>
    </div>
</body>
</html>