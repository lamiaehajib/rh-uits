<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Header</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Your existing CSS code here */
        :root {
            --primary-pink: #C2185B;
            --primary-red: #D32F2F;
            --accent-red: #ef4444;
            --border-radius: 12px;
        }

        body {
            padding-top: 30px;
        }

        .client-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: linear-gradient(135deg,
                var(--primary-pink) 0%,
                var(--primary-red) 50%,
                var(--accent-red) 100%) !important;
            backdrop-filter: blur(20px);
            border-bottom: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 10px 30px rgba(194, 24, 91, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            animation: slideDown 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            /* --- The fix is here! --- */
            flex-wrap: wrap; 
        }

        .client-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent);
            animation: sweep 3s infinite;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes sweep {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .text-3xl {
            background: linear-gradient(45deg,
                #ffffff 0%,
                #ffeaa7 25%,
                #ffffff 50%,
                #fab1a0 75%,
                #ffffff 100%) !important;
            background-size: 300% 300%;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            animation: gradientShift 4s ease-in-out infinite;
            font-weight: 900;
            text-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
            position: relative;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .flex.items-center.space-x-2 {
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }

        .flex.items-center.space-x-2::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(45deg,
                var(--primary-pink),
                var(--accent-red),
                var(--primary-red),
                var(--primary-pink));
            background-size: 400% 400%;
            border-radius: 15px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
            animation: gradientRotate 3s ease infinite;
        }

        @keyframes gradientRotate {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .flex.items-center.space-x-2:hover::before {
            opacity: 1;
        }

        .flex.items-center.space-x-2:hover {
            transform: scale(1.05) rotate(2deg);
        }

        .h-10 {
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(194, 24, 91, 0.3));
        }

        .flex.items-center.space-x-2:hover .h-10 {
            transform: rotate(-2deg) scale(1.1);
            filter: drop-shadow(0 8px 16px rgba(194, 24, 91, 0.5));
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 12px 20px !important;
            border-radius: 25px;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
            text-decoration: none !important;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            /* Make text stay on one line */
            white-space: nowrap;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent);
            transition: left 0.6s ease;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.25) !important;
            transform: translateY(-3px) scale(1.05);
            box-shadow:
                0 10px 25px rgba(194, 24, 91, 0.4),
                0 0 20px rgba(255, 255, 255, 0.2);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.3) !important;
            color: white !important;
            font-weight: 700;
            border-bottom: 3px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }

        .nav-link i {
            margin-right: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.3) rotate(15deg);
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.6));
        }

        @keyframes iconBounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0) scale(1.3); }
            40% { transform: translateY(-5px) scale(1.4); }
            60% { transform: translateY(-2px) scale(1.35); }
        }

        .nav-link:hover i {
            animation: iconBounce 0.8s ease infinite;
        }

        #theme-toggle {
            background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.2),
                rgba(255, 255, 255, 0.1));
            border: 2px solid rgba(255, 255, 255, 0.4);
            color: white;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(194, 24, 91, 0.3);
        }

        #theme-toggle::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle,
                rgba(255, 255, 255, 0.3),
                rgba(255, 255, 255, 0.1));
            border-radius: 50%;
            transition: all 0.5s ease;
            transform: translate(-50%, -50%);
        }

        #theme-toggle:hover::before {
            width: 120%;
            height: 120%;
        }

        #theme-toggle:hover {
            transform: rotate(360deg) scale(1.15);
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow:
                0 0 25px rgba(255, 255, 255, 0.4),
                0 10px 30px rgba(194, 24, 91, 0.6);
        }

        #theme-toggle i {
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        #theme-toggle:hover i {
            transform: scale(1.2);
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.8));
        }

        .user-avatar {
            background: linear-gradient(135deg, var(--primary-pink), var(--primary-red));
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 12px;
            box-shadow: 0 5px 15px rgba(194, 24, 91, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .user-avatar::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--accent-red), var(--primary-pink), var(--primary-red));
            border-radius: 50%;
            z-index: -1;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .dropdown-toggle {
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .user-info {
            color: rgba(255, 255, 255, 0.9);
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .user-email {
            font-size: 12px;
            opacity: 0.8;
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            animation: dropIn 0.3s ease;
            /* La solution est ici: bach l-menu yban*/
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.125rem;
            z-index: 1050;
        }

        /* Had l-class 3andha d'orore fiha l-jawb */
        .dropdown-menu.show {
            display: block;
        }

        @keyframes dropIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .dropdown-header {
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-red));
            color: white;
            font-weight: bold;
            padding: 12px 20px;
            margin: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dropdown-item {
            padding: 12px 20px;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 4px 8px;
        }

        .dropdown-item:hover {
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-red));
            color: white !important;
            transform: translateX(10px);
        }

        .dropdown-item i {
            transition: all 0.3s ease;
        }

        .dropdown-item:hover i {
            transform: scale(1.2);
        }

        .dropdown-divider {
            border-top: 1px solid rgba(194, 24, 91, 0.3);
            margin: 8px 0;
        }

        .client-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(2px 2px at 20px 30px, rgba(255, 255, 255, 0.3), transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255, 255, 255, 0.2), transparent),
                radial-gradient(1px 1px at 90px 40px, rgba(255, 255, 255, 0.3), transparent),
                radial-gradient(1px 1px at 130px 80px, rgba(255, 255, 255, 0.2), transparent);
            background-repeat: repeat;
            background-size: 200px 100px;
            animation: float 20s infinite linear;
            pointer-events: none;
        }

        @keyframes float {
            0% { transform: translateX(-200px); }
            100% { transform: translateX(100vw); }
        }

        @media (max-width: 768px) {
            .client-header .d-flex {
                flex-wrap: wrap;
            }

            .greeting-text {
                font-size: 1.5rem !important;
            }

            .nav {
                display: none;
            }

            .nav.mobile-show {
                display: flex;
                flex-direction: column;
                width: 100%;
                margin-top: 10px;
            }

            .mobile-menu-toggle {
                display: block;
                background: none;
                border: none;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-toggle {
                display: none;
            }
        }

        .nav-item {
            position: relative;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, var(--accent-red), transparent);
            border-radius: 50%;
            transition: all 0.4s ease;
            transform: translate(-50%, -50%);
            z-index: -1;
            opacity: 0;
        }

        .nav-item:hover::before {
            width: 100px;
            height: 100px;
            opacity: 0.3;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-5px); }
            60% { transform: translateY(-3px); }
        }

        .nav-link:hover i {
            animation: bounce 0.6s ease;
        }

        .theme-toggle-dark {
            background: linear-gradient(135deg, #1a1a3a, var(--dark-bg));
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .theme-toggle-light {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-red));
            box-shadow: 0 0 20px rgba(194, 24, 91, 0.5);
        }

        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        span.d-none.d-lg-block.text-sm.text-gray-800 {
            color: white;
            font-weight: 700;
            font-size: 19px;
        }

        span.d-none.d-lg-block.text-xs.text-gray-500 {
            color: #fffdf0;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-between align-items-center client-header px-4 py-3 shadow-sm">
        <div class="d-flex align-items-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-red-600 to-red-400 bg-clip-text text-transparent mb-2">
                <?php
                    $hour = \Carbon\Carbon::now()->format('H');
                    $greeting = ($hour >= 6 && $hour < 19) ? 'Bonjour' : 'Bonsoir';
                ?>
                {{ $greeting }}, {{ Auth::user()->name }}
            </h1>
        </div>
        <a href="{{ route('client.dashboard') }}" class="flex items-center space-x-2">
           <img class="h-14 w-auto rounded-md" src="{{ asset('photos/Asset.png') }}" alt="Your Company Logo">
        </a>

        <div class="d-flex align-items-center">
            <nav class="me-3">
                <ul class="nav">
                    <li class="nav-item me-3">
                        <a class="nav-link text-decoration-none text-gray-700 hover:text-primary-red transition" href="{{ route('client.dashboard') }}">
                            <i class="fas fa-chart-line me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link text-decoration-none text-gray-700 hover:text-primary-red transition" href="{{ route('client.projets.index') }}">
                            <i class="fas fa-folder me-2"></i>Mes Projets
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link text-decoration-none text-gray-700 hover:text-primary-red transition" href="{{ route('client.client.planning') }}">
                            <i class="fas fa-calendar-check me-2"></i> intervention sur site
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-decoration-none text-gray-700 hover:text-primary-red transition" href="{{ route('client.reclamations.index') }}">
                            <i class="fas fa-exclamation-circle me-2"></i>Réclamations
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="dropdown">
                <a class="dropdown-toggle d-flex align-items-center text-decoration-none" href="#" role="button" onclick="toggleDropdown()">
                    <div class="h-10 w-10 bg-red-600 rounded-full d-flex align-items-center justify-content-center text-white font-bold me-2">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <span class="d-none d-lg-block text-sm text-gray-800">{{ Auth::user()->name }}</span>
                        <span class="d-none d-lg-block text-xs text-gray-500">{{ Auth::user()->email }}</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow" id="profileDropdown">
                    <h6 class="dropdown-header">Mon Compte</h6>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                        Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
            </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdownMenu = document.getElementById('profileDropdown');
            dropdownMenu.classList.toggle('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const body = document.body;
            const clientHeader = document.querySelector('.client-header');
            let isDarkTheme = localStorage.getItem('theme') === 'dark';

            function applyTheme(isDark) {
                if (isDark) {
                    body.style.filter = 'invert(1) hue-rotate(180deg)';
                    clientHeader.style.filter = 'invert(1) hue-rotate(180deg)';
                    themeToggleBtn.querySelector('i').className = 'fas fa-sun';
                    themeToggleBtn.style.boxShadow = '0 0 25px rgba(255, 255, 255, 0.3), 0 10px 30px rgba(194, 24, 91, 0.6)';
                } else {
                    body.style.filter = 'none';
                    clientHeader.style.filter = 'none';
                    themeToggleBtn.querySelector('i').className = 'fas fa-moon';
                    themeToggleBtn.style.boxShadow = '0 5px 15px rgba(194, 24, 91, 0.3)';
                }
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            }

            if (themeToggleBtn) {
                applyTheme(isDarkTheme);
                themeToggleBtn.addEventListener('click', function() {
                    isDarkTheme = !isDarkTheme;
                    applyTheme(isDarkTheme);
                    this.style.transform = 'rotate(360deg) scale(0.9)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 300);
                });
            }

            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPath ||
                    (currentPath.includes('dashboard') && link.textContent.includes('Dashboard')) ||
                    (currentPath.includes('projets') && link.textContent.includes('Projets')) ||
                    (currentPath.includes('rendez-vous') && link.textContent.includes('Rendez-vous')) ||
                    (currentPath.includes('reclamations') && link.textContent.includes('Réclamations'))) {
                    link.classList.add('active');
                }
            });

            const userAvatar = document.querySelector('.user-avatar');
            if (userAvatar) {
                userAvatar.style.animation = 'avatarFloat 3s ease-in-out infinite';
            }

            const dropdownToggle = document.querySelector('.dropdown-toggle');
            if (dropdownToggle) {
                 dropdownToggle.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.02)';
                    this.style.boxShadow = '0 8px 25px rgba(194, 24, 91, 0.3)';
                });
                dropdownToggle.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                });
            }
        });

        const style = document.createElement('style');
        style.textContent = `
            @keyframes avatarFloat {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                33% { transform: translateY(-3px) rotate(1deg); }
                66% { transform: translateY(3px) rotate(-1deg); }
            }

            .dropdown-item {
                transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
            }

            .dropdown-item:hover {
                background: linear-gradient(45deg, var(--primary-pink), var(--primary-red)) !important;
                color: white !important;
                transform: translateX(10px) scale(1.02) !important;
                border-radius: 8px !important;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>