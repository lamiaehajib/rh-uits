<x-app-layout>
    <!-- Enhanced Tailwind CSS with modern styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primaryRed: '#DC2626',
                        lightRed: '#FEF2F2',
                        darkRed: '#991B1B',
                        actionBlue: '#3B82F6',
                        actionGreen: '#10B981',
                        actionRed: '#EF4444',
                        accent: '#F59E0B',
                        darkBg: '#1F2937',
                        lightBg: '#F8FAFC',
                        cardBg: '#FFFFFF',
                        borderColor: '#E5E7EB',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'bounce-soft': 'bounceSoft 0.8s ease-in-out',
                        'pulse-soft': 'pulseSoft 2s infinite',
                        'scale-hover': 'scaleHover 0.2s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        bounceSoft: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' }
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.8' }
                        },
                        scaleHover: {
                            '0%': { transform: 'scale(1)' },
                            '100%': { transform: 'scale(1.05)' }
                        }
                    },
                    boxShadow: {
                        'modern': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'button': '0 2px 4px rgba(0, 0, 0, 0.1)',
                        'glow': '0 0 20px rgba(220, 38, 38, 0.3)',
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .button-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .button-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .table-row:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, rgba(16, 185, 129, 0.05) 100%);
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
        
        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(229, 231, 235, 0.5);
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .floating-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>

    <!-- Main Container with Enhanced Styling -->
    <div class="min-h-screen bg-gradient-to-br from-lightBg via-white to-blue-50 font-inter antialiased">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.15) 1px, transparent 0); background-size: 20px 20px;"></div>
        </div>
        
        <!-- Content Container -->
        <div class="relative z-10 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Modern Card Container -->
                <div class="modern-card p-8 sm:p-10 animate-fade-in">
                    <!-- Header Section with Enhanced Design -->
                    <div class="flex flex-col lg:flex-row justify-between items-center mb-8">
                        <div class="text-center lg:text-left mb-6 lg:mb-0">
                            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-2 header-gradient floating-animation">
                                Role Management
                            </h1>
                            <p class="text-gray-600 text-lg font-medium">Manage user roles and permissions efficiently</p>
                            <div class="w-24 h-1 bg-gradient-to-r from-primaryRed to-accent rounded-full mt-3 mx-auto lg:mx-0"></div>
                        </div>
                        
                        @can('role-create')
                            <a href="{{ route('roles.create') }}" class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-primaryRed to-darkRed text-white font-semibold rounded-xl shadow-button button-hover focus:outline-none focus:ring-4 focus:ring-primaryRed focus:ring-opacity-50 overflow-hidden">
                                <span class="absolute inset-0 bg-gradient-to-r from-darkRed to-primaryRed opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                <i data-lucide="plus" class="w-5 h-5 mr-3 relative z-10"></i>
                                <span class="relative z-10">Create New Role</span>
                                <div class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            </a>
                        @endcan
                    </div>

                    <!-- Success Message with Modern Alert -->
                    @if ($message = Session::get('success'))
                        <div class="mb-8 animate-slide-up">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-green-400 to-emerald-500"></div>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="text-green-800 font-semibold">{{ $message }}</p>
                                    </div>
                                    <button type="button" class="ml-4 text-green-600 hover:text-green-800 focus:outline-none transition-colors duration-200" onclick="this.parentElement.parentElement.parentElement.style.display='none';">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Table Container -->
                    <div class="overflow-hidden rounded-2xl shadow-modern border border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <!-- Modern Table Header -->
                                <thead class="bg-gradient-to-r from-gray-900 to-gray-800">
                                    <tr>
                                        <th scope="col" class="px-8 py-6 text-left text-sm font-bold text-white uppercase tracking-wider">
                                            <div class="flex items-center space-x-2">
                                                <i data-lucide="shield" class="w-5 h-5"></i>
                                                <span>Role Name</span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-8 py-6 text-left text-sm font-bold text-white uppercase tracking-wider">
                                            <div class="flex items-center space-x-2">
                                                <i data-lucide="settings" class="w-5 h-5"></i>
                                                <span>Actions</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                
                                <!-- Table Body with Enhanced Styling -->
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($roles as $key => $role)
                                        <tr class="table-row hover:bg-gray-50 transition-all duration-300">
                                            <td class="px-8 py-6 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-primaryRed to-accent rounded-full flex items-center justify-center">
                                                        <i data-lucide="user-check" class="w-5 h-5 text-white"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-lg font-semibold text-gray-900">{{ $role->name }}</div>
                                                        <div class="text-sm text-gray-500">Role ID: #{{ $role->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6 whitespace-nowrap">
                                                <div class="flex flex-wrap gap-3">
                                                    <!-- Show Button -->
                                                    <a href="{{ route('roles.show', $role->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-actionBlue to-blue-600 text-white text-sm font-semibold rounded-lg shadow-button button-hover focus:outline-none focus:ring-3 focus:ring-blue-300 focus:ring-opacity-50">
                                                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                                        <span>View</span>
                                                    </a>
                                                    
                                                    @can('role-edit')
                                                        <!-- Edit Button -->
                                                        <a href="{{ route('roles.edit', $role->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-actionGreen to-green-600 text-white text-sm font-semibold rounded-lg shadow-button button-hover focus:outline-none focus:ring-3 focus:ring-green-300 focus:ring-opacity-50">
                                                            <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i>
                                                            <span>Edit</span>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('role-delete')
                                                        <!-- Delete Button -->
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                                            {!! Form::button('<i data-lucide="trash-2" class="w-4 h-4 mr-2"></i><span>Delete</span>', [
                                                                'type' => 'submit',
                                                                'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-actionRed to-red-600 text-white text-sm font-semibold rounded-lg shadow-button button-hover focus:outline-none focus:ring-3 focus:ring-red-300 focus:ring-opacity-50',
                                                                'onclick' => 'return confirm("Are you sure you want to delete this role?")'
                                                            ]) !!}
                                                        {!! Form::close() !!}
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Enhanced Pagination -->
                    <div class="mt-8 flex justify-center">
                        <div class="bg-white rounded-xl shadow-card p-4 border border-gray-200">
                            {!! $roles->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Script Section -->
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Add smooth scrolling
        document.documentElement.style.scrollBehavior = 'smooth';
        
        // Add loading animation
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });
        
        // Add interactive hover effects
        document.querySelectorAll('.button-hover').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
        
        // Add ripple effect to buttons
        document.querySelectorAll('button, a').forEach(element => {
            element.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    </script>
    
    <!-- Additional CSS for ripple effect -->
    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        .loaded {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</x-app-layout>