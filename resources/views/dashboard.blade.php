<x-app-layout>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .header {
            background-color: #1a202c;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-bottom: 4px solid #2d3748;
        }

        .header h2 {
            margin: 0;
            font-size: 2em;
            font-weight: bold;
            text-transform: capitalize;
        }

        .header h3 {
            margin: 5px 0 0;
            font-size: 1.2em;
            font-weight: normal;
            color: #a0aec0;
        }

        .div-tout {
            padding: 20px;
        }

        .max-w-7xl {
            max-width: 1200px;
            margin: 0 auto;
        }

        .project-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .create-project {
            background-color: #38a169;
            color: #ffffff;
            padding: 15px 25px;
            border-radius: 8px;
            text-align: center;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .create-project:hover {
            background-color: #2f855a;
        }

        

        .project-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .project-card h3 {
            margin: 0 0 10px;
            font-size: 1.5em;
            color: #2b6cb0;
            text-transform: capitalize;
        }

        .project-card p {
            margin: 0;
            color: #718096;
            font-size: 1em;
        }
        .div-style{
            margin-left: -1200px;
        }
    </style>
<div class="div-style">
    <div class="header">
        <h2 class="font-semibold">
            <?php
                $hour = \Carbon\Carbon::now()->format('H');
                $greeting = ($hour >= 6 && $hour < 19) ? 'Bonjour' : 'Bonsoir';
            ?>
            {{ $greeting }}, {{ Auth::user()->name }}
        </h2>
        @php
        \Carbon\Carbon::setLocale('fr');
        @endphp
        <h3 class="date-jour">{{ \Carbon\Carbon::now()->translatedFormat('l, d/m/Y') }}</h3>
    </div>

    <div class="div-tout">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="project-list">
                <a href="{{ route('projects.create') }}">+Créer un projet</a>

                @foreach($projects as $project)
                    <div class="project-card">
                        <h3 class="name projet">{{ $project->name }}</h3>
                        
                    </div>
                @endforeach
            </div>
            @foreach($taches as $tache)
            <div class="project-card">
                <h3 class="name projet">{{ $tache->description }}</h3>
                
            </div>
        @endforeach
        </div>
    </div>
</div>
</x-app-layout>
