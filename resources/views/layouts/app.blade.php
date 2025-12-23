<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MANAGEMENT</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" xintegrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+nttiUYTKg0FyILvDFbTvK4iLGHwdCekkYTOV4rFVVzXhGBCcjcWIw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" xintegrity="sha512-1ycn6IcaQQ40jHzj/UDN+a6JoBfMNjytH5zV9xfwDHWUqfPKIB/LExowp/L2P7B7M6t20kX+2/D+4D95K+l8g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">

    <style>
        
          body {
            font-family: 'Ubuntu', sans-serif;
            margin: 0;
            background: #f8f9fa;
        }

        h3 {
            color: #D32F2F;
            font-family: 'Ubuntu', sans-serif;
            font-weight: bold;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .hight {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        path {
            display: none;
        }

        /* Layout principal */
        .app-layout {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Container pour header et contenu avec sidebar */
        .main-container {
            display: flex;
            flex: 1;
            width: 100%;
            overflow: hidden;
        }

        /* La sidebar (navigation) est maintenant incluse via header */
        .sidebar-container {
            flex-shrink: 0;
        }

        /* Content styles */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            background: #f8f9fa;
            min-width: 0;
            margin-left: 0; /* L'espace est géré par le sidebar */
        }

        .bg-primary {
            --bs-bg-opacity: 1;
            background: linear-gradient(135deg, #C2185B, #D32F2F) !important;
            text-align: center;
            text-transform: uppercase;
        }

        tbody, td, tfoot, th, thead, tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .main-content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .main-content {
                padding: 100px 30px 30px 30px !important; 
                padding-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                 padding: 100px 30px 30px 30px !important; 
            }
        }
        .py-4 {
    padding-top: 5.5rem !important;
    padding-bottom: 1.5rem !important;
}
/* Content styles */
.main-content {
    flex: 1;
    /* Zid f padding-top bach l-contenu ihbat. 
       Jarreb 80px awla 100px ila kan l-header kbir 
    */
    padding: 100px 30px 30px 30px !important; 
    overflow-y: auto;
    background: #f8f9fa;
    min-width: 0;
    margin-left: 0; 
}
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen">
    @auth
        @if(auth()->user()->hasRole('Client'))
            @include('layouts.client_header')
        @else
            @include('layouts.header')
            @include('layouts.navigation')
        @endif
    @endauth

<div class="main-content">
                <main>
        {{ $slot }}
    </main>
    </div>
</div>

    <script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" xintegrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtS70P9fajIKg7AuIgBwsdfuXMhGXghg/w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
