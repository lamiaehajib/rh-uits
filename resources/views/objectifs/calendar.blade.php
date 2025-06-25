<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                    <div class="card-body bg-gradient-primary text-white">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="mb-3 mb-md-0">
                                <h2 class="mb-1 fw-bold">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Calendrier des Objectifs
                                </h2>
                                <p class="mb-0 opacity-75">Visualisez et gérez vos objectifs mensuels et annuels</p>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-light btn-sm" id="todayBtn">
                                    <i class="fas fa-calendar-day me-1"></i>
                                    Aujourd'hui
                                </button>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-light btn-sm" id="prevBtn">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="btn btn-outline-light btn-sm" id="nextBtn">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                {{-- Optionally open the upcoming objectives sidebar --}}
                                <button class="btn btn-info btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#upcomingObjectifs" aria-controls="upcomingObjectifs">
                                    <i class="fas fa-list-alt me-1"></i> Prochains Objectifs
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="stat-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-bullseye text-primary fs-4"></i>
                                </div>
                                <h3 class="mt-3 mb-1 fw-bold" id="totalObjectifs">0</h3> {{-- Default value --}}
                                <p class="text-muted mb-0">Total Objectifs</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="stat-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-check-circle text-success fs-4"></i>
                                </div>
                                <h3 class="mt-3 mb-1 fw-bold text-success" id="completedObjectifs">0</h3> {{-- Default value --}}
                                <p class="text-muted mb-0">Terminés</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="stat-icon bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-clock text-warning fs-4"></i>
                                </div>
                                <h3 class="mt-3 mb-1 fw-bold text-warning" id="inProgressObjectifs">0</h3> {{-- Default value --}}
                                <p class="text-muted mb-0">En cours</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="stat-icon bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                                </div>
                                <h3 class="mt-3 mb-1 fw-bold text-danger" id="overdueObjectifs">0</h3> {{-- Default value --}}
                                <p class="text-muted mb-0">En retard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-12 mb-3 mb-lg-0">
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="filterFormations" checked>
                                        <label class="form-check-label d-flex align-items-center" for="filterFormations">
                                            <span class="badge bg-info me-2">📚</span>
                                            Formations
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="filterProjets" checked>
                                        <label class="form-check-label d-flex align-items-center" for="filterProjets">
                                            <span class="badge bg-success me-2">🚀</span>
                                            Projets
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="filterVente" checked>
                                        <label class="form-check-label d-flex align-items-center" for="filterVente">
                                            <span class="badge bg-danger me-2">💰</span>
                                            Vente
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check" name="viewType" id="monthView" checked>
                                        <label class="btn btn-outline-primary btn-sm" for="monthView">
                                            <i class="fas fa-calendar me-1"></i>
                                            Mois
                                        </label>

                                        <input type="radio" class="btn-check" name="viewType" id="weekView">
                                        <label class="btn btn-outline-primary btn-sm" for="weekView">
                                            <i class="fas fa-calendar-week me-1"></i>
                                            Semaine
                                        </label>

                                        <input type="radio" class="btn-check" name="viewType" id="dayView">
                                        <label class="btn btn-outline-primary btn-sm" for="dayView">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            Jour
                                        </label>
                                    </div>
                                    <button class="btn btn-outline-secondary btn-sm" id="refreshBtn">
                                        <i class="fas fa-sync-alt me-1"></i>
                                        Actualiser
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0 position-relative">
                        <div id="calendar" style="min-height: 600px;"></div>

                        <div id="calendarLoading" class="position-absolute top-0 start-0 w-100 h-100 d-none" style="background: rgba(255,255,255,0.8); z-index: 1000;">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="objectifModal" tabindex="-1" aria-labelledby="objectifModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-gradient-primary text-white border-0">
                    <h5 class="modal-title" id="objectifModalLabel">
                        <i class="fas fa-bullseye me-2"></i>
                        Détails de l'objectif
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="objectifModalBody">
                    </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Fermer
                    </button>
                    <a href="#" class="btn btn-primary" id="viewObjectifBtn">
                        <i class="fas fa-eye me-1"></i>
                        Voir les détails
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="upcomingObjectifs" aria-labelledby="upcomingObjectifsLabel">
        <div class="offcanvas-header bg-gradient-primary text-white">
            <h5 class="offcanvas-title" id="upcomingObjectifsLabel">
                <i class="fas fa-clock me-2"></i>
                Objectifs à venir
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" id="upcomingObjectifsBody">
            {{-- This content is passed from the calendarView method in the controller --}}
            @if ($upcomingObjectifs->isEmpty())
                <p class="text-muted text-center mt-3">Aucun objectif à venir dans les 30 prochains jours.</p>
            @else
                <div class="list-group list-group-flush">
                    @foreach ($upcomingObjectifs as $objectif)
                        <a href="{{ route('objectifs.show', $objectif->id) }}" class="list-group-item list-group-item-action py-3">
                            <div class="d-flex w-100 justify-content-between">
                                {{-- Ensure $objectif->typeIcon is available, either via accessor or by calling getTypeIcon method in PHP controller --}}
                                <h6 class="mb-1 text-primary">
                                    @php
                                        // This assumes getTypeIcon is a public method or accessor in Objectif model
                                        // If getTypeIcon is private in the controller, you need to pass it explicitly.
                                        // A better way for this: Make it an accessor in the Objectif model.
                                        // For now, assuming you have an accessor or you added it to the objectif object in PHP.
                                        $typeIcon = $objectif->typeIcon ?? (method_exists($objectif, 'getTypeIcon') ? $objectif->getTypeIcon($objectif->type) : '');
                                    @endphp
                                    {{ $typeIcon }} {{ Str::limit($objectif->description, 50) }}
                                </h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($objectif->date)->format('d M') }}</small>
                            </div>
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <small class="text-muted">
                                    Assigné à: {{ $objectif->user->name ?? 'N/A' }}
                                </small>
                                @if ($objectif->days_remaining < 0)
                                    <span class="badge bg-danger">En retard</span>
                                @elseif ($objectif->days_remaining <= 7)
                                    <span class="badge bg-warning">Proche échéance</span>
                                @else
                                    <span class="badge bg-info">Dans {{ $objectif->days_remaining }} jours</span>
                                @endif
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar {{ $objectif->calculated_progress >= 100 ? 'bg-success' : ($objectif->calculated_progress >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                     role="progressbar" style="width: {{ $objectif->calculated_progress }}%;"
                                     aria-valuenow="{{ $objectif->calculated_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Calendar Customization */
        .fc {
            font-family: inherit;
            font-size: 14px;
        }

        .fc-header-toolbar {
            padding: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            margin-bottom: 0;
        }

        .fc-toolbar-title {
            font-weight: 700;
            font-size: 1.5rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .fc-button-group .fc-button {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .fc-button-group .fc-button:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .fc-button-group .fc-button-active {
            background: rgba(255,255,255,0.4) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .fc-daygrid-day {
            transition: all 0.2s ease;
            border: 1px solid #e9ecef;
        }

        .fc-daygrid-day:hover {
            background-color: #f8f9ff;
        }

        .fc-daygrid-day-number {
            font-weight: 600;
            padding: 8px;
            color: #495057;
        }

        .fc-day-today {
            background-color: #e3f2fd !important;
        }

        .fc-day-today .fc-daygrid-day-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4px;
        }

        /* Event Styling */
        .fc-event {
            border: none;
            border-radius: 8px;
            padding: 4px 8px;
            margin: 2px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid rgba(255,255,255,0.3);
        }

        .fc-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .fc-event-title {
            font-weight: 600;
            line-height: 1.2;
        }

        /* Event Colors by Type */
        .fc-event[data-type="formations"] {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .fc-event[data-type="projets"] {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
        }

        .fc-event[data-type="vente"] {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        /* Priority indicators */
        .fc-event[data-priority="critique"] {
            animation: pulse 2s infinite;
            border-left-color: #ff0000;
        }

        .fc-event[data-priority="haute"] {
            border-left-color: #ff6b35;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 0, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 0, 0, 0); }
        }

        /* Status Badges */
        .status-badge {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-mois {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .status-annee {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
        }

        /* Progress Indicators */
        .progress-mini {
            height: 3px;
            border-radius: 2px;
            margin-top: 4px;
            background: rgba(255,255,255,0.3);
            overflow: hidden;
        }

        .progress-mini .progress-bar {
            height: 100%;
            background: rgba(255,255,255,0.9);
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        /* Modal Enhancements */
        .modal-content {
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .modal-header {
            border-radius: 16px 16px 0 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .fc-header-toolbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .fc-toolbar-title {
                font-size: 1.25rem;
                order: -1;
                margin-bottom: 0.5rem;
            }

            .fc-button-group .fc-button {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            .fc-event-title {
                font-size: 11px;
            }
        }

        /* Stat Cards */
        .stat-icon {
            transition: transform 0.3s ease;
        }

        .card:hover .stat-icon {
            transform: scale(1.1);
        }

        /* Loading States */
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        /* Accessibility */
        .fc-event:focus {
            outline: 2px solid #007bff;
            outline-offset: 2px;
        }

        /* Print Styles */
        @media print {
            .card-body {
                padding: 0.5rem !important;
            }

            .fc-header-toolbar {
                background: white !important;
                color: black !important;
            }

            .fc-button {
                display: none !important;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/locales/fr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const loadingEl = document.getElementById('calendarLoading');
            let calendar;
            let currentEvents = []; // Store all events to filter locally without refetching

            // Initialize calendar
            initializeCalendar();

            // Load initial statistics (from the calendarView route's passed data, or via AJAX if dynamic)
            // For this example, we'll assume they're passed to the view, or fetched dynamically on first load
            // and then updated by calendar events data.
            updateStatistics(@json($stats ?? [])); // Populate initial stats passed from controller

            // Set up event listeners for filter checkboxes
            document.querySelectorAll('input[type="checkbox'][id^="filter"]').forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    calendar.refetchEvents(); // Refetch/re-render events after filter change
                });
            });

            // Set up event listeners for view type radio buttons
            document.getElementById('monthView').addEventListener('change', () => calendar.changeView('dayGridMonth'));
            document.getElementById('weekView').addEventListener('change', () => calendar.changeView('timeGridWeek'));
            document.getElementById('dayView').addEventListener('change', () => calendar.changeView('timeGridDay'));

            // Set up event listeners for navigation buttons
            document.getElementById('todayBtn').addEventListener('click', () => calendar.today());
            document.getElementById('prevBtn').addEventListener('click', () => calendar.prev());
            document.getElementById('nextBtn').addEventListener('click', () => calendar.next());
            document.getElementById('refreshBtn').addEventListener('click', () => calendar.refetchEvents());


            function initializeCalendar() {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'fr',
                    firstDay: 1, // Monday
                    height: 'auto',
                    aspectRatio: 1.8,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    buttonText: {
                        today: 'Aujourd\'hui',
                        month: 'Mois',
                        week: 'Semaine',
                        day: 'Jour'
                    },
                    // This function will fetch events from your Laravel endpoint
                    events: loadEvents,
                    eventClick: handleEventClick,
                    eventDidMount: handleEventMount,
                    eventContent: renderEventContent,
                    dayCellDidMount: handleDayMount,
                    loading: function(isLoading) {
                        if (isLoading) {
                            showLoading();
                        } else {
                            hideLoading();
                        }
                    },
                    eventMouseEnter: function(info) {
                        info.el.style.transform = 'translateY(-2px)';
                    },
                    eventMouseLeave: function(info) {
                        info.el.style.transform = 'translateY(0)';
                    }
                });

                calendar.render();
            }

            function loadEvents(info, successCallback, failureCallback) {
                showLoading();

                const params = new URLSearchParams({
                    start: info.startStr,
                    end: info.endStr,
                    // Pass current filter states to the backend
                    type: getActiveFilters('filter'),
                    // If you want status filtering as well, add it here and in PHP controller
                    // status: getActiveFilters('status') // Uncomment if you add status filters to JS
                });

                fetch(`{{ route('objectifs.calendar') }}?${params}`)
                    .then(response => {
                        if (!response.ok) {
                            // Log the response text for more details on HTTP errors
                            return response.text().then(text => { throw new Error(`HTTP error! status: ${response.status}, message: ${text}`); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        currentEvents = data; // Store all fetched events
                        const filteredData = filterEvents(data); // Apply local filtering for display
                        successCallback(filteredData);
                        // Update statistics based on ALL events fetched for the current view range
                        updateStatistics(data);
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des objectifs:', error);
                        // showNotification removed as it was not defined
                        // showNotification('Erreur lors du chargement des objectifs', 'error');
                    })
                    .finally(() => {
                        hideLoading();
                    });
            }

            // Helper to get active filter values
            function getActiveFilters(prefix) {
                const activeFilters = [];
                document.querySelectorAll(`input[type="checkbox"][id^="${prefix}"]`).forEach(checkbox => {
                    if (checkbox.checked) {
                        activeFilters.push(checkbox.id.replace(prefix, '').toLowerCase());
                    }
                });
                return activeFilters.join(','); // Or return as an array if your backend expects it
            }


            function filterEvents(events) {
                const showFormations = document.getElementById('filterFormations').checked;
                const showProjets = document.getElementById('filterProjets').checked;
                const showVente = document.getElementById('filterVente').checked;

                return events.filter(event => {
                    switch(event.extendedProps.type) {
                        case 'formations': return showFormations;
                        case 'projets': return showProjets;
                        case 'vente': return showVente;
                        default: return true; // Show events if their type is not one of the filtered ones, or if filter is not present
                    }
                });
            }

            function handleEventClick(info) {
                info.jsEvent.preventDefault();
                showObjectifModal(info.event);
            }

            // Custom JavaScript function for string limiting (replaces PHP's Str::limit)
            function limitString(str, limit, ellipsis = '...') {
                if (!str) return ''; // Return empty string for null/undefined
                str = String(str); // Ensure it's a string
                if (str.length <= limit) return str;
                return str.substring(0, limit) + ellipsis;
            }


            function handleEventMount(info) {
                const event = info.event;
                const el = info.el;

                // Add data attributes
                el.setAttribute('data-type', event.extendedProps.type);
                el.setAttribute('data-status', event.extendedProps.status);
                el.setAttribute('data-priority', event.extendedProps.priority);

                // Add accessibility attributes
                el.setAttribute('tabindex', '0');
                el.setAttribute('role', 'button');
                el.setAttribute('aria-label', `Objectif: ${event.title}`);

                // Add detailed tooltip - USING THE NEW limitString FUNCTION
                const tooltip = `
                    Type: ${event.extendedProps.type ? event.extendedProps.type.toUpperCase() : 'N/A'}
                    Statut: ${event.extendedProps.status ? event.extendedProps.status.toUpperCase() : 'N/A'}
                    Utilisateur: ${event.extendedProps.user ?? 'N/A'}
                    Créateur: ${event.extendedProps.creator ?? 'N/A'}
                    Progrès: ${event.extendedProps.calculated_progress}%
                    Date: ${formatDate(event.start)}
                    Description: ${limitString(event.extendedProps.description, 50)}
                    ${event.extendedProps.is_overdue ? '⚠️ En retard' : ''}
                    Cliquez pour plus de détails
                `.trim();
                el.setAttribute('title', tooltip);

                // Add keyboard support
                el.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        showObjectifModal(event);
                    }
                });
            }

            function renderEventContent(arg) {
                const event = arg.event;
                const props = event.extendedProps;

                return {
                    html: `
                        <div class="fc-event-content">
                            <div class="fc-event-title">${event.title}</div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <span class="status-badge status-${props.status}">
                                    ${props.status}
                                </span>
                                <small class="text-white-50">
                                    ${props.calculated_progress}%
                                </small>
                            </div>
                            <div class="progress-mini">
                                <div class="progress-bar" style="width: ${props.calculated_progress}%"></div>
                            </div>
                        </div>
                    `
                };
            }

            function handleDayMount(info) {
                // Highlight today
                const today = new Date();
                today.setHours(0,0,0,0); // Normalize today's date
                if (info.date.toDateString() === today.toDateString()) {
                    info.el.classList.add('fc-day-today');
                }

                // Add weekend styling
                if (info.date.getDay() === 0 || info.date.getDay() === 6) { // 0 for Sunday, 6 for Saturday
                    info.el.style.backgroundColor = '#f8f9fa';
                }
            }

            function showObjectifModal(event) {
                const modal = new bootstrap.Modal(document.getElementById('objectifModal'));
                const modalBody = document.getElementById('objectifModalBody');
                const viewBtn = document.getElementById('viewObjectifBtn');
                const props = event.extendedProps;

                // Create progress bar classes
                const progressBarClass = props.calculated_progress >= 100 ? 'bg-success' :
                                         props.calculated_progress >= 50 ? 'bg-warning' : 'bg-danger';

                // Create priority badge
                const priorityBadge = {
                    'critique': '<span class="badge bg-danger">🚨 Critique</span>',
                    'haute': '<span class="badge bg-warning">⚡ Haute</span>',
                    'moyenne': '<span class="badge bg-info">📋 Moyenne</span>',
                    'normale': '<span class="badge bg-secondary">📝 Normale</span>'
                }[props.priority] || '<span class="badge bg-secondary">📝 Normale</span>';

                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-tag me-1"></i>
                                    Type d'objectif
                                </h6>
                                <span class="badge bg-primary fs-6">${getTypeIcon(props.type)} ${props.type ? props.type.toUpperCase() : 'N/A'}</span>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Statut
                                </h6>
                                <span class="badge bg-warning text-dark fs-6">${props.status ? props.status.toUpperCase() : 'N/A'}</span>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-flag me-1"></i>
                                    Priorité
                                </h6>
                                ${priorityBadge}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-calendar me-1"></i>
                                    Date
                                </h6>
                                <p class="mb-0">${event.start ? formatDate(event.start) : 'N/A'}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-user me-1"></i>
                                    Assigné à
                                </h6>
                                <p class="mb-0">${props.user ?? 'N/A'}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-user-plus me-1"></i>
                                    Créé par
                                </h6>
                                <p class="mb-0">${props.creator ?? 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-chart-line me-1"></i>
                            Progrès
                        </h6>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar ${progressBarClass}" role="progressbar"
                                 style="width: ${props.calculated_progress}%;"
                                 aria-valuenow="${props.calculated_progress}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                ${props.calculated_progress}%
                            </div>
                        </div>
                        ${props.needs_explanation ? '<p class="text-danger mt-2"><i class="fas fa-exclamation-circle me-1"></i>Nécessite une explication pour le faible progrès.</p>' : ''}
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-align-left me-1"></i>
                            Description
                        </h6>
                        <p class="mb-0">${props.description || 'Aucune description disponible'}</p>
                    </div>

                    ${props.ca ? `
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-euro-sign me-1"></i>
                                Chiffre d'affaires
                            </h6>
                            <p class="mb-0">${formatCurrency(props.ca)}</p>
                        </div>
                    ` : ''}

                    ${props.days_until_deadline !== null ? `
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-clock me-1"></i>
                                Échéance
                            </h6>
                            <p class="mb-0 ${props.is_overdue ? 'text-danger' : 'text-success'}">
                                ${props.is_overdue ?
                                    `<i class="fas fa-exclamation-triangle me-1"></i>En retard de ${Math.abs(props.days_until_deadline)} jours` :
                                    `<i class="fas fa-check me-1"></i>${props.days_until_deadline} jours restants`
                                }
                            </p>
                        </div>
                    ` : ''}

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-plus-circle me-1"></i>
                            Créé le
                        </h6>
                        <p class="mb-0">${props.created_at}</p>
                    </div>
                `;
                viewBtn.href = event.url; // Set the URL for the "View Details" button
                modal.show();
            }

            // Helper functions
            function getTypeIcon(type) {
                const icons = {
                    'formations': '📚',
                    'projets': '🚀',
                    'vente': '💰'
                };
                return icons[type] || '📋';
            }

            function formatDate(dateString) {
                // Ensure dateString is valid before parsing
                if (!dateString) return 'N/A';
                try {
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return new Date(dateString).toLocaleDateString('fr-FR', options);
                } catch (e) {
                    console.error("Error formatting date:", dateString, e);
                    return dateString; // Return original if invalid
                }
            }

            function formatCurrency(amount) {
                if (typeof amount !== 'number' && typeof amount !== 'string' || isNaN(Number(amount))) {
                    return 'N/A';
                }
                return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(Number(amount));
            }

            function showLoading() {
                loadingEl.classList.remove('d-none');
            }

            function hideLoading() {
                loadingEl.classList.add('d-none');
            }

            function showNotification(message, type) {
                // You can integrate a toast notification library (e.g., Bootstrap Toasts, SweetAlert) here
                console.log(`Notification (${type}): ${message}`);
                // Example using basic alert:
                // alert(message);
            }

            function updateStatistics(eventsData) {
                let total = eventsData.length;
                let completed = 0;
                let inProgress = 0;
                let overdue = 0;

                eventsData.forEach(event => {
                    const props = event.extendedProps;
                    // Ensure props and calculated_progress exist
                    if (props && typeof props.calculated_progress !== 'undefined') {
                        if (props.calculated_progress >= 100) {
                            completed++;
                        } else if (props.calculated_progress > 0 && props.calculated_progress < 100) {
                            inProgress++;
                        }
                        // Only count as overdue if not completed
                        if (props.is_overdue && props.calculated_progress < 100) {
                            overdue++;
                        }
                    }
                });

                document.getElementById('totalObjectifs').textContent = total;
                document.getElementById('completedObjectifs').textContent = completed;
                document.getElementById('inProgressObjectifs').textContent = inProgress;
                document.getElementById('overdueObjectifs').textContent = overdue;
            }
        });
    </script>
    @endpush
</x-app-layout>