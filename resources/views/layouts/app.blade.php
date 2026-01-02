<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MANAGEMENT</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
  

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
            padding: 100px 30px 30px 30px !important; 
            overflow-y: auto;
            background: #f8f9fa;
            min-width: 0;
            margin-left: 0;
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

        .py-4 {
            padding-top: 5.5rem !important;
            padding-bottom: 1.5rem !important;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .main-content {
                padding: 80px 20px 20px 20px !important;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .main-content {
                padding: 100px 30px 30px 30px !important; 
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 100px 15px 20px 15px !important; 
            }
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Ripple Effect
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

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.ripple-btn').forEach(btn => {
                btn.addEventListener('click', createRipple);
            });
        });
    </script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>