<x-app-layout>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        // Using 'Inter' for a clean, modern look
                        inter: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primaryRed: '#D32F2F', // Your requested custom red color for primary actions/badges
                        // Additional colors for a harmonious palette
                        grayBack: '#F7F8FA', // Light gray background
                        darkGray: '#333333', // Dark text/header color
                        mediumGray: '#555555', // Medium text color
                        lightGray: '#E5E7EB', // Border color
                    }
                }
            }
        }
    </script>
    <!-- Lucide Icons CDN for modern, customizable icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Google Fonts for 'Inter' -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Main container for the page content, centered and responsive -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-back font-inter antialiased">
        <div class="max-w-2xl w-full bg-white rounded-xl shadow-lg p-6 sm:p-8">

            <!-- Header section with title and back button -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold text-dark-gray mb-4 sm:mb-0">Show Role Details</h2>
                <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Back
                </a>
            </div>

            <!-- Details Section -->
            <div class="p-6 bg-gray-50 rounded-lg shadow-inner border border-gray-200">
                <div class="mb-5">
                    <strong class="block text-base text-medium-gray mb-1">Name:</strong>
                    <span class="text-xl font-bold text-dark-gray">{{ $role->name }}</span>
                </div>

                <div>
                    <strong class="block text-base text-medium-gray mb-2">Permissions:</strong>
                    <div class="flex flex-wrap gap-2">
                        @if(!empty($rolePermissions))
                            @foreach($rolePermissions as $v)
                                <span class="inline-block px-3 py-1.5 text-sm font-medium text-white bg-primaryRed rounded-full shadow-sm hover:bg-darkRed transition duration-200">
                                    {{ $v->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-sm text-gray-500 italic">No permissions assigned to this role.</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script to initialize Lucide icons after the DOM is loaded -->
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
