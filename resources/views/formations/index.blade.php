<x-app-layout>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Gestion des Formations') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            // Defined new primary and accent colors based on your request
                            'primary-red': '#DC2626',
                            'primary-pink': '#C2185B',
                            'primary': '#6366f1', // Keeping the original primary for general use
                            'primary-dark': '#4f46e5',
                            'accent': '#f59e0b',
                            'success': '#10b981',
                            'danger': '#ef4444',
                            'glass': 'rgba(255, 255, 255, 0.1)',
                        },
                        fontFamily: {
                            sans: ['Inter', 'system-ui', 'sans-serif'],
                        },
                        backdropBlur: {
                            'xs': '2px',
                        },
                        animation: {
                            'fade-in': 'fadeIn 0.6s ease-out forwards',
                            'slide-up': 'slideUp 0.5s ease-out forwards',
                            'float': 'float 3s ease-in-out infinite',
                            'glow': 'glow 2s ease-in-out infinite alternate',
                            'shimmer': 'shimmer 2s linear infinite',
                        },
                        keyframes: {
                            fadeIn: {
                                '0%': { opacity: '0', transform: 'translateY(20px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' }
                            },
                            slideUp: {
                                '0%': { opacity: '0', transform: 'translateY(30px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' }
                            },
                            float: {
                                '0%, 100%': { transform: 'translateY(0px)' },
                                '50%': { transform: 'translateY(-10px)' }
                            },
                            glow: {
                                '0%': { boxShadow: '0 0 20px rgba(99, 102, 241, 0.5)' },
                                '100%': { boxShadow: '0 0 30px rgba(99, 102, 241, 0.8)' }
                            },
                            shimmer: {
                                '0%': { backgroundPosition: '-200% 0' },
                                '100%': { backgroundPosition: '200% 0' }
                            }
                        }
                    }
                }
            }
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            /* Removed background from body */
            body {
                font-family: 'Inter', sans-serif;
                /* background property removed */
                min-height: 100vh;
                background-color: #f0f2f5; /* A light, neutral background for the body */
            }

            .glass-card {
                /* New background for cards */
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                color: #333; /* Darker text color for better readability on light background */
            }

            .glass-card:hover {
                background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.95) 100%);
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }

            .stat-card {
                /* New background for stat cards */
                background: linear-gradient(135deg, #DC2626 0%, #C2185B 100%); /* Using your specified colors */
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                color: white; /* Text color for stat cards */
            }

            .stat-card:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2); /* Slightly stronger shadow on hover */
                background: linear-gradient(135deg, #FF3333 0%, #E02070 100%); /* Slightly brighter on hover */
            }

            .btn-modern {
                background: linear-gradient(135deg, #DC2626 0%, #C2185B 100%); /* Using your specified colors */
                border: none;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-modern::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .btn-modern:hover::before {
                left: 100%;
            }

            .btn-modern:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(220, 38, 38, 0.4); /* Shadow color based on primary-red */
            }

            .search-container {
                background: rgba(255, 255, 255, 0.95); /* Slightly less transparent for better readability */
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 10px 20px rgba(0,0,0,0.05); /* Added subtle shadow */
            }

            .table-modern {
                background: rgba(255, 255, 255, 0.98); /* Almost opaque for table for clarity */
                backdrop-filter: blur(10px);
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }

            .table-row:hover {
                background: rgba(220, 38, 38, 0.05); /* Hover based on primary-red */
                transform: scale(1.01);
            }

            .status-badge {
                backdrop-filter: blur(5px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                /* Ensure text is visible for different status colors */
            }

            .floating-action {
                position: fixed;
                bottom: 2rem;
                right: 2rem;
                z-index: 1000;
                background: linear-gradient(135deg, #DC2626 0%, #C2185B 100%); /* Using your specified colors */
                border-radius: 50%;
                width: 60px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 30px rgba(220, 38, 38, 0.4); /* Shadow based on primary-red */
                transition: all 0.3s ease;
            }

            .floating-action:hover {
                transform: scale(1.1);
                box-shadow: 0 15px 40px rgba(220, 38, 38, 0.6); /* Stronger shadow on hover */
            }

            .modal-glass {
                background: rgba(255, 255, 255, 0.98); /* Less transparent for modal content */
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                color: #333; /* Darker text for modal */
            }

            /* Adjust text color for specific elements for better readability */
            .text-white {
                color: #333; /* Changed to dark for readability on light cards */
            }
           .text-white\/80 {
    color: rgb(255 255 255 / 80%);
}
            .text-white\/70 {
                color: rgba(51, 51, 51, 0.7); /* Darker text for readability */
            }

            /* Adjust header text color to be visible against its own background */
            .header-text-white {
                color: white; /* Keep white for header elements */
            }

            /* Specific color for icons based on your request */
            .text-primary-icon {
                color: #DC2626; /* Your requested primary icon color */
            }

            /* Ensure the header gradient icon remains white */
            .header-icon-white i {
                color: white !important;
            }

        </style>
    </head>
    <body class="font-sans antialiased">
        <div id="custom-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 invisible transition-all duration-300">
            <div class="modal-glass rounded-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-all duration-300">
                <div id="modal-icon" class="text-center text-5xl mb-4"></div>
                <div id="modal-message" class="text-center text-gray-800 text-lg mb-6"></div>
                <div id="modal-buttons" class="flex justify-center gap-3"></div>
            </div>
        </div>

        <div class="bg-primary-red/80 backdrop-blur-sm border-b border-white/20 mb-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-4xl font-bold header-text-white flex items-center animate-fade-in">
                    <div class="bg-gradient-to-r from-primary-red to-primary-pink p-3 rounded-2xl mr-4 animate-float header-icon-white">
                        <i class="fas fa-graduation-cap text-2xl text-white"></i>
                    </div>
                    {{ __('Gestion des Formations') }}
                </h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            @if (session('success'))
                <div class="bg-green-500/20 backdrop-blur-sm border border-green-500/30 text-green-800 px-6 py-4 rounded-2xl mb-6 animate-slide-up">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-2xl mr-3 text-green-600"></i>
                        <div>
                            <strong class="font-semibold">{{ __('Succès!') }}</strong>
                            <p class="mt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500/20 backdrop-blur-sm border border-red-500/30 text-red-800 px-6 py-4 rounded-2xl mb-6 animate-slide-up">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-2xl mr-3 text-red-600"></i>
                        <div>
                            <strong class="font-semibold">{{ __('Erreur!') }}</strong>
                            <p class="mt-1">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Dashboard Statistics Section (refactored to Tailwind) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-blue-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-300">
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-700">{{ __('Total Formations') }}</h3>
                                    <p class="text-4xl font-extrabold text-blue-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-layer-group text-blue-500 text-5xl opacity-75"></i>
                            </div>
                            <div class="bg-yellow-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-400">
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-700">{{ __('Formations En Cours') }}</h3>
                                    <p class="text-4xl font-extrabold text-yellow-900 mt-1">{{ $stats['en_cours'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-hourglass-half text-yellow-500 text-5xl opacity-75 animate-spin-slow"></i>
                            </div>
                            <div class="bg-green-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-500">
                                <div>
                                    <h3 class="text-lg font-semibold text-green-700">{{ __('Formations Terminées') }}</h3>
                                    <p class="text-4xl font-extrabold text-green-900 mt-1">{{ $stats['terminées'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-check-double text-green-500 text-5xl opacity-75"></i>
                            </div>
                            <div class="bg-indigo-50 p-6 rounded-xl shadow-lg flex items-center justify-between card-hover-effect animate-fade-in delay-600">
                                <div>
                                    <h3 class="text-lg font-semibold text-indigo-700">{{ __('Nouvelles Formations') }}</h3>
                                    <p class="text-4xl font-extrabold text-indigo-900 mt-1">{{ $stats['nouvelles'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-star text-indigo-500 text-5xl opacity-75"></i>
                            </div>
                        </div>


            <div class="search-container rounded-2xl p-6 mb-8 animate-fade-in">
                <form action="{{ route('formations.index') }}" method="GET">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-transparent transition-all duration-200"
                                        placeholder="Rechercher une formation..."
                                        value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <select name="status" class="px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-red focus:border-transparent">
                                <option value="">{{ __('Tous les statuts') }}</option>
                                <option value="nouveu" {{ request('status') == 'nouveu' ? 'selected' : '' }}>{{ __('Nouveau') }}</option>
                                <option value="encour" {{ request('status') == 'encour' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                                <option value="fini" {{ request('status') == 'fini' ? 'selected' : '' }}>{{ __('Terminée') }}</option>
                            </select>
                            <button type="submit" class="btn-modern px-6 py-3 text-white font-semibold rounded-xl flex items-center gap-2">
                                <i class="fas fa-filter"></i>
                                {{ __('Filtrer') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($formations ?? [] as $formation)
                    <div class="glass-card rounded-2xl p-6 animate-fade-in">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $formation->name ?? 'Formation Laravel Avancé' }}</h3>
                                <p class="text-gray-700 text-sm">{{ $formation->nomformateur ?? 'Dr. Ahmed Bennani' }}</p>
                            </div>
                            <div class="status-badge px-3 py-1 rounded-full text-xs font-semibold
                                @if(($formation->statut ?? 'nouveu') == 'nouveu') bg-indigo-500/20 text-indigo-700
                                @elseif(($formation->statut ?? 'nouveu') == 'encour') bg-yellow-500/20 text-yellow-700
                                @else bg-green-500/20 text-green-700
                                @endif">
                                {{ ucfirst($formation->statut ?? 'Nouveau') }}
                            </div>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-calendar-alt mr-3 text-primary-icon"></i>
                                <span class="text-sm">{{ \Carbon\Carbon::parse($formation->date ?? '2024-01-15')->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-users mr-3 text-primary-icon"></i>
                                <span class="text-sm">{{ $formation->users->count() ?? 25 }} participants</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-{{ ($formation->status ?? 'lieu') == 'en ligne' ? 'globe' : 'map-marker-alt' }} mr-3 text-primary-icon"></i>
                                <span class="text-sm">{{ ($formation->status ?? 'lieu') == 'en ligne' ? 'En ligne' : 'Présentiel' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2">
                                @can('formation-show')
                                    <button class="p-2 bg-blue-500/20 text-blue-700 rounded-lg hover:bg-blue-500/30 transition-all">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @endcan
                                @can('formation-edit')
                                    <button class="p-2 bg-indigo-500/20 text-indigo-700 rounded-lg hover:bg-indigo-500/30 transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endcan
                                @can('formation-delete')
                                    <button class="p-2 bg-red-500/20 text-red-700 rounded-lg hover:bg-red-500/30 transition-all"
                                            onclick="showCustomConfirm('{{ __('Êtes-vous sûr de vouloir supprimer cette formation ?') }}', function() { console.log('Delete formation'); });">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endcan
                            </div>
                            @if($formation->file_path ?? false)
                                <button class="p-2 bg-green-500/20 text-green-700 rounded-lg hover:bg-green-500/30 transition-all">
                                    <i class="fas fa-download"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="glass-card rounded-2xl p-12 text-center">
                            <i class="fas fa-graduation-cap text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ __('Aucune formation trouvée') }}</h3>
                            <p class="text-gray-600">{{ __('Commencez par créer votre première formation') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 flex justify-center">
                <div class="glass-card rounded-2xl px-6 py-3">
                    <div class="flex items-center space-x-2 text-gray-700">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-all">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="px-3 py-1 bg-primary-red text-white rounded-lg">1</span>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-all">2</button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-all">3</button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-all">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @can('formation-create')
            <a href="{{ route('formations.create') }}" class="floating-action animate-bounce">
                <i class="fas fa-plus text-white text-xl"></i>
            </a>
        @endcan

        <script>
            // Custom Modal Logic
            const customModal = document.getElementById('custom-modal');
            const modalMessage = document.getElementById('modal-message');
            const modalButtons = document.getElementById('modal-buttons');
            const modalIcon = document.getElementById('modal-icon');
            let resolveModalPromise;

            function showCustomModal(message, type = 'alert', onConfirm = null) {
                modalMessage.textContent = message;
                modalButtons.innerHTML = '';
                modalIcon.innerHTML = '';

                if (type === 'confirm') {
                    modalIcon.innerHTML = '<i class="fas fa-question-circle text-primary-red"></i>'; // Icon with primary-red
                    const confirmBtn = document.createElement('button');
                    confirmBtn.textContent = 'Confirmer';
                    confirmBtn.className = 'btn-modern px-6 py-3 text-white font-semibold rounded-xl';
                    confirmBtn.onclick = () => {
                        hideModal();
                        if (onConfirm) onConfirm();
                        resolveModalPromise(true);
                    };
                    modalButtons.appendChild(confirmBtn);

                    const cancelBtn = document.createElement('button');
                    cancelBtn.textContent = 'Annuler';
                    cancelBtn.className = 'px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-xl hover:bg-gray-300 transition-all';
                    cancelBtn.onclick = () => {
                        hideModal();
                        resolveModalPromise(false);
                    };
                    modalButtons.appendChild(cancelBtn);
                } else {
                    modalIcon.innerHTML = '<i class="fas fa-info-circle text-primary-pink"></i>'; // Icon with primary-pink
                    const okBtn = document.createElement('button');
                    okBtn.textContent = 'OK';
                    okBtn.className = 'btn-modern px-6 py-3 text-white font-semibold rounded-xl';
                    okBtn.onclick = () => {
                        hideModal();
                        if (onConfirm) onConfirm();
                        resolveModalPromise(true);
                    };
                    modalButtons.appendChild(okBtn);
                }

                showModal();
                return new Promise(resolve => {
                    resolveModalPromise = resolve;
                });
            }

            function showModal() {
                customModal.classList.remove('opacity-0', 'invisible');
                customModal.querySelector('.modal-glass').classList.remove('scale-95');
                customModal.querySelector('.modal-glass').classList.add('scale-100');
            }

            function hideModal() {
                customModal.classList.add('opacity-0', 'invisible');
                customModal.querySelector('.modal-glass').classList.add('scale-95');
                customModal.querySelector('.modal-glass').classList.remove('scale-100');
            }

            function showCustomConfirm(message, callback = null) {
                return showCustomModal(message, 'confirm', callback);
            }

            // Animation on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.glass-card').forEach(card => {
                observer.observe(card);
            });
        </script>
    </body>
</x-app-layout>