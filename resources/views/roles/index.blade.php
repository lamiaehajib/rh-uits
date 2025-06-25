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
                        primaryRed: '#D32F2F', // Your requested custom red color
                        lightRed: '#FEE2E2', // Lighter shade for alert backgrounds
                        darkRed: '#991B1B',  // Darker shade for hover states and alert text
                        // Standard Tailwind colors for action buttons to provide clear visual distinction
                        actionBlue: '#3B82F6', // For 'Show' button
                        actionGreen: '#22C55E', // For 'Edit' button
                        actionRed: '#EF4444', // For 'Delete' button
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
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-100 font-inter antialiased">
        <div class="max-w-4xl w-full bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <!-- Header section with title and create button -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4 sm:mb-0">Role Management</h2>
                <!-- Blade directive for conditional display of 'Create New Role' button -->
                @can('role-create')
                    <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 bg-primaryRed text-white font-semibold rounded-md shadow-md hover:bg-darkRed transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primaryRed">
                        <i data-lucide="plus" class="w-5 h-5 mr-2"></i> Create New Role
                    </a>
                @endcan
            </div>

            <!-- Success Message Alert -->
            <!-- Blade directive for conditional display of success message -->
            @if ($message = Session::get('success'))
                <div class="bg-lightRed border border-primaryRed text-darkRed px-4 py-3 rounded-md relative mb-6" role="alert">
                    <p class="pr-8">{{ $message }}</p>
                    <button type="button" class="absolute top-3 right-3 text-darkRed hover:text-primaryRed focus:outline-none" onclick="this.parentElement.style.display='none';">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            @endif

            <!-- Table for displaying roles, made responsive with overflow-x-auto -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow-sm">
                    <thead class="bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider rounded-tl-lg">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider rounded-tr-lg">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Blade directive to loop through roles -->
                        @foreach ($roles as $key => $role)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $role->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                                    <a class="inline-flex items-center px-3 py-1.5 bg-actionBlue text-white text-xs font-semibold rounded-md hover:bg-blue-600 transition duration-300" href="{{ route('roles.show', $role->id) }}">
                                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Show
                                    </a>
                                    <!-- Blade directive for conditional display of 'Edit' button -->
                                    @can('role-edit')
                                        <a class="inline-flex items-center px-3 py-1.5 bg-actionGreen text-white text-xs font-semibold rounded-md hover:bg-green-600 transition duration-300" href="{{ route('roles.edit', $role->id) }}">
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit
                                        </a>
                                    @endcan
                                    <!-- Blade directive for conditional display of 'Delete' button -->
                                    @can('role-delete')
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                            {!! Form::button('<i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete', ['type' => 'submit', 'class' => 'inline-flex items-center px-3 py-1.5 bg-actionRed text-white text-xs font-semibold rounded-md hover:bg-red-600 transition duration-300']) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination section, centered -->
            <div class="mt-6 flex justify-center">
                <div class="flex space-x-2">
                    {!! $roles->render() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Script to initialize Lucide icons after the DOM is loaded -->
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
