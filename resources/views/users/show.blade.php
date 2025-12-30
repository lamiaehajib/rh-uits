<x-app-layout>
    <style>
        /* Custom Styles for the D32F2F color and animations */
        .color-primary {
            color: #D32F2F;
        }

        .bg-primary-custom {
            background-color: #D32F2F;
        }

        .hover-bg-primary-darker:hover {
            background-color: #B71C1C; /* A darker shade for hover effect */
        }

        .border-primary-custom {
            border-color: #D32F2F;
        }

        /* Subtle hover effect for cards */
        .card-hover-scale {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card-hover-scale:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Icon bounce animation on hover */
        .icon-bounce:hover {
            animation: bounce 0.6s ease-in-out;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        /* Badge pulse animation */
        .badge-pulse {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(211, 47, 47, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(211, 47, 47, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(211, 47, 47, 0);
            }
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-user-circle mr-3 color-primary"></i> User Details: <span class="color-primary">{{ $user->name }}</span>
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-primary-custom border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover-bg-primary-darker focus:outline-none focus:border-primary-custom focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                    <i class="fas fa-edit mr-2"></i> Edit User
                </a>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            {{-- User Information Card --}}
            <div class="md:col-span-2 bg-white rounded-lg shadow-lg p-6 card-hover-scale">
                <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                    <i class="fas fa-info-circle mr-3 color-primary icon-bounce"></i> General Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-gray-700">
                    <p><strong><i class="fas fa-id-badge mr-2 text-gray-500"></i> Name:</strong> {{ $user->name }}</p>
                    <p><strong><i class="fas fa-at mr-2 text-gray-500"></i> Email:</strong> {{ $user->email }}</p>
                    <p><strong><i class="fas fa-barcode mr-2 text-gray-500"></i> Code:</strong> {{ $user->code }}</p>
                    <p><strong><i class="fas fa-phone mr-2 text-gray-500"></i> Phone:</strong> {{ $user->tele }}</p>
                    <p><strong><i class="fas fa-briefcase mr-2 text-gray-500"></i> Post:</strong> {{ $user->poste }}</p>
                    <p><strong><i class="fas fa-map-marker-alt mr-2 text-gray-500"></i> Address:</strong> {{ $user->adresse }}</p>
<p>
    <strong><i class="fas fa-couch mr-2 text-gray-500"></i> Day Off:</strong> 
    {{ is_array($user->repos) ? implode(', ', $user->repos) : $user->repos }}
</p><p>
    <strong><i class="fas fa-couch mr-2 text-gray-500"></i> Day Off:</strong> 
    {{ is_array($user->repos) ? implode(', ', $user->repos) : $user->repos }}
</p>                    <p>
                        <strong><i class="fas fa-toggle-on mr-2 text-gray-500"></i> Status:</strong>
                        @if($user->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- User Statistics Card --}}
            <div class="md:col-span-1 bg-white rounded-lg shadow-lg p-6 card-hover-scale">
                <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                    <i class="fas fa-chart-line mr-3 color-primary icon-bounce"></i> User Statistics
                </h3>
                <div class="space-y-4 text-gray-700">
                    <p><strong><i class="fas fa-sign-in-alt mr-2 text-gray-500"></i> Last Login:</strong> {{ $userStats['last_login'] ? \Carbon\Carbon::parse($userStats['last_login'])->diffForHumans() : 'Never' }}</p>
                    <p><strong><i class="fas fa-users-rectangle mr-2 text-gray-500"></i> Login Count:</strong> {{ $userStats['login_count'] }}</p>
                    <p><strong><i class="fas fa-calendar-alt mr-2 text-gray-500"></i> Member Since:</strong> {{ $userStats['created_ago'] }}</p>
                    <p><strong><i class="fas fa-user-tag mr-2 text-gray-500"></i> Assigned Roles:</strong> {{ $userStats['role_count'] }}</p>
                </div>
            </div>
        </div>

        ---

        {{-- User Roles & Permissions Card --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 card-hover-scale">
            <h3 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-3 flex items-center">
                <i class="fas fa-user-shield mr-3 color-primary icon-bounce"></i> Roles & Permissions
            </h3>
            <div class="mb-4">
                <p class="text-gray-700">
                    <strong><i class="fas fa-users mr-2 text-gray-500"></i> Roles:</strong>
                    @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-primary-custom text-white mr-2 mb-2 inline-block badge-pulse">
                                {{ $v }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-gray-500">No roles assigned.</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-gray-700">
                    <strong><i class="fas fa-key mr-2 text-gray-500"></i> Direct Permissions:</strong>
                    @if($user->permissions->isNotEmpty())
                        @foreach($user->permissions as $permission)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-300 text-gray-800 mr-2 mb-2 inline-block">
                                {{ $permission->name }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-gray-500">No direct permissions. Permissions are typically granted via roles.</span>
                    @endif
                </p>
            </div>
        </div>

        ---

       
</x-app-layout>