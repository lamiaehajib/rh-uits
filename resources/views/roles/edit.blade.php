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
                        // Standard Tailwind colors for buttons
                        primaryButton: '#10B981', // Green for Submit
                        primaryButtonHover: '#059669', // Darker green on hover
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
        <div class="max-w-2xl w-full bg-white rounded-xl shadow-lg p-6 sm:p-8">

            <!-- Header section with title and back button -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4 sm:mb-0">Edit Role</h2>
                <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Back
                </a>
            </div>

            <!-- Error Messages Alert -->
            @if (count($errors) > 0)
                <div class="bg-lightRed border border-primaryRed text-darkRed px-4 py-3 rounded-md relative mb-6" role="alert">
                    <strong class="font-bold">Whoops!</strong> There were some problems with your input.<br><br>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Role Edit Form -->
            {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
                <div class="space-y-6">
                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <strong>Name:</strong>
                        </label>
                        {!! Form::text('name', null, array('placeholder' => 'Role Name','class' => 'mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primaryRed focus:border-primaryRed sm:text-sm')) !!}
                    </div>

                    <!-- Permissions Checkboxes -->
                    <div>
                        <strong class="block text-sm font-medium text-gray-700 mb-2">Permission:</strong>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-2">
                            @foreach($permission as $value)
                                <label class="inline-flex items-center text-gray-800 cursor-pointer hover:text-primaryRed transition duration-200">
                                    {!! Form::checkbox('permission[]', $value->name, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'form-checkbox h-4 w-4 text-primaryRed rounded border-gray-300 focus:ring-primaryRed name mr-2')) !!}
                                    {{ $value->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primaryButton hover:bg-primaryButtonHover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primaryButton transition duration-300">
                            <i data-lucide="save" class="w-5 h-5 mr-2"></i> Update Role
                        </button>
                    </div>
                </div>
            {!! Form::close() !!}

        </div>
    </div>

    <!-- Script to initialize Lucide icons after the DOM is loaded -->
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
