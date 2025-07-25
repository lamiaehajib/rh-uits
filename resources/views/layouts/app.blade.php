<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+nttiUYTKg0FyILvDFbTvK4iLGHwdCekkYTOV4rFVVzXhGBCcjcWIw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40jHzj/UDN+a6JoBfMNjytH5zV9xfwDHWUqfPKIB/LExowp/L2P7B7M6t20kX+2/D+4D95K+l8g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">

    <style>
        :root {
            --primary-color: #C2185B;
            --secondary-color: #D32F2F;
            --accent-color: #D32F2F;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --border-radius: 12px;
            --box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg,rgb(234, 102, 102) 0%, #764ba2 100%);
            min-height: 100vh;
            line-height: 1.6;
        }

        
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        /* Ripple Effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .to-blue-400 {
    --tw-gradient-to: #C2185B;
}
.from-blue-600 {
    --tw-gradient-from: #D32F2F;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(37, 99, 235, 0));
}
.stats-card.fade-in.p-6.text-center.mb-6.visible {
    margin-top: 32px;
}
.text-transparent {
    color: #c12e2e;
}
.space-x-3 > :not([hidden]) ~ :not([hidden]) {
    width: 130px;
    --tw-space-x-reverse: 0;
    margin-right: calc(0.75rem * var(--tw-space-x-reverse));
    margin-left: calc(0.75rem * calc(1 - var(--tw-space-x-reverse)));
    font-size: 10px;
}

button.inline-flex.items-center.px-4.py-2.bg-orange-600.border.border-transparent.rounded-md.font-semibold.text-xs.text-white.uppercase.tracking-widest.hover\:bg-orange-700.active\:bg-orange-900.focus\:outline-none.focus\:border-orange-900.focus\:ring.ring-orange-300.disabled\:opacity-25.transition.ease-in-out.duration-150 {
    background: #d62222;
}
.py-8 {
    padding-top: 2rem;
    padding-bottom: 2rem;
    background: #e6e6e67a;
}
.w-auto {
    width: 137px !important;
}


    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen">
    @include('layouts.header')

    @include('layouts.navigation')

    {{-- Add padding-top to push content below the fixed header --}}
    {{-- py-6 (24px) is already there, but you need more for the header --}}
    {{-- h-16 is 64px, so pt-16 or pt-20 (80px) would work well --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pt-20"> 
        {{ $slot }}
    </main>
</div>
    <script>
        // Ripple effect for buttons
        function createRipple(event) {
            const button = event.currentTarget;
            const circle = document.createElement("span");
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - (button.offsetLeft + radius)}px`;
            circle.style.top = `${event.clientY - (button.offsetTop + radius)}px`;
            circle.classList.add("ripple");

            const ripple = button.getElementsByClassName("ripple")[0];
            if (ripple) {
                ripple.remove();
            }

            button.appendChild(circle);
        }

        document.querySelectorAll('.ripple-btn').forEach(btn => {
            btn.addEventListener('click', createRipple);
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtS70P9fajIKg7AuIgBwsdfuXMhGXghg/w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@yield('scripts')
 @stack('scripts')
</body>
</html>