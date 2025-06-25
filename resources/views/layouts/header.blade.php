{{-- resources/views/layouts/header.blade.php --}}

<header class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Mobile Menu Button (Left for Mobile) --}}
           

            {{-- Logo Section (Centered on Mobile, Left on Desktop) --}}
            <div class="flex-1 flex justify-center md:justify-start"> {{-- Added flex-1 and justify-center --}}
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <img class="h-10 w-auto rounded-md" src="{{ asset('photos/logo.png') }}" alt="Your Company Logo">
                    {{-- <span class="text-xl font-bold text-gray-900">Your Company</span> --}}
                </a>
            </div>

            {{-- Desktop Navigation Links --}}
           

            {{-- User/Auth Section (Right) --}}
            <div class="flex items-center">
                <div class="hidden md:flex items-center ml-6">
                 @auth
    {{-- User Dropdown (requires Alpine.js) --}}
    <div x-data="{ open: false }" @click.away="open = false" class="relative">
        <button @click="open = !open" type="button" class="flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-full" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
            <span class="sr-only">Open user menu</span>
            {{-- This is the element you click: The user's initials avatar --}}
            <span class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center text-white text-base font-semibold border-2 border-indigo-500">
                {{ Str::substr(Auth::user()->name, 0, 1) }}{{ Str::substr(Auth::user()->name, strrpos(Auth::user()->name, ' ') + 1, 1) }}
            </span>
        </button>

        {{-- This is the dropdown content that appears/disappears --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
             role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">

           
        </div>
    </div>
@endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile menu content (requires Alpine.js) --}}
    
</header>