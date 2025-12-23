<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Modern Sidebar Navigation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
           
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            border: none;
            border-radius: 15px;
            color: white;
            font-size: 22px;
            cursor: pointer;
            z-index: 1001;
            box-shadow: 0 8px 16px rgba(211, 47, 47, 0.4);
            transition: all 0.3s ease;
        }

        .mobile-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(211, 47, 47, 0.5);
        }

        .mobile-toggle:active {
            transform: translateY(0);
        }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .overlay.active {
            display: block;
            opacity: 1;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #D32F2F 0%, #C2185B 50%, #ef4444 100%);
            overflow: hidden;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 20px rgba(211, 47, 47, 0.3);
            z-index: 999;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        /* Sidebar Header */
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            min-height: 80px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 1;
            transition: opacity 0.3s ease 0.1s;
        }

        .sidebar.collapsed .sidebar-logo {
            opacity: 0;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: white;
            letter-spacing: 0.5px;
        }

        .sidebar-burger {
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar-burger:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: rotate(90deg);
        }

        .sidebar-burger i {
            font-size: 24px;
            color: white;
        }

        /* Sidebar Menu */
        .sidebar-menu {
            padding: 20px 0;
            overflow-y: auto;
            height: calc(100vh - 80px);
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .menu-item {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: white;
            background: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            gap: 16px;
            margin: 4px 0;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: white;
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }

        .menu-item:hover::before {
            height: 70%;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.15);
            padding-left: 28px;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .menu-item.active::before {
            height: 70%;
        }

        .menu-icon {
            min-width: 28px;
            font-size: 22px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-text {
            opacity: 1;
            white-space: nowrap;
            font-size: 15px;
            font-weight: 500;
            transition: opacity 0.3s ease 0.1s;
            text-transform: capitalize;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.15);
            margin: 16px 20px;
        }

        /* Dropdown */
        .dropdown-container {
            position: relative;
        }

        .dropdown-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            color: white;
            background: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            gap: 16px;
        }

        .dropdown-btn:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .dropdown-content {
            display: none;
            padding-left: 20px;
        }

        .dropdown-content.active {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 12px;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            padding-left: 28px;
            color: white;
        }

        .dropdown-arrow {
            transition: transform 0.3s ease;
            opacity: 1;
            font-size: 14px;
        }

        .sidebar.collapsed .dropdown-arrow {
            opacity: 0;
        }

        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }

        /* Badge */
        .menu-badge {
            position: absolute;
            top: 8px;
            left: 40px;
            background: #ef4444;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                transition: transform 0.3s ease;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar.mobile-open .sidebar-logo,
            .sidebar.mobile-open .menu-text,
            .sidebar.mobile-open .dropdown-arrow {
                opacity: 1;
            }

            .sidebar:hover {
                width: 280px;
            }
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 40px;
            transition: margin-left 0.4s ease;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 80px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 100px 20px 20px;
            }
        }

        /* Demo Content Card */
        .demo-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .demo-card h1 {
            color: #D32F2F;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .demo-card p {
            color: #666;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    
    <!-- Overlay -->
    <div class="overlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            
            
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item active">
                <i class='bx bx-home menu-icon'></i>
                <span class="menu-text">Accueil</span>
            </a>

            @can("user-list")
            <a href="{{ route('users.index') }}" class="menu-item">
                <i class='bx bx-group menu-icon'></i>
                <span class="menu-text">Équipe</span>
                <span class="menu-badge">5</span>
            </a>
            <div class="menu-divider"></div>
            @endcan

            <a href="{{ route('login.history') }}" class="menu-item">
                <i class="fas fa-history menu-icon"></i>
                <span class="menu-text">Historique Session</span>
            </a>

            @can("pointage-list")
            <a href="{{ route('pointage.index') }}" class="menu-item">
                <i class='bx bx-time menu-icon'></i>
                <span class="menu-text">Pointages</span>
            </a>
            @endcan

            @can("tache-list")
            <a href="{{ route('taches.index') }}" class="menu-item">
                <i class='bx bx-task menu-icon'></i>
                <span class="menu-text">Les Tâches</span>
            </a>
            @endcan

            @can("reclamation-list")
            <a href="{{ route('reclamations.index') }}" class="menu-item">
                <i class='bx bx-comment-detail menu-icon'></i>
                <span class="menu-text">Réclamations</span>
            </a>
            @endcan

            @can("formation-list")
            <a href="{{ route('conges.index') }}" class="menu-item">
                <i class='bx bx-chalkboard menu-icon'></i>
                <span class="menu-text">Congés</span>
            </a>
            @endcan

            @can("objectif-list")
            <a href="{{ route('objectifs.index') }}" class="menu-item">
                <i class='bx bx-target-lock menu-icon'></i>
                <span class="menu-text">Objectifs</span>
            </a>
            @endcan

            <div class="menu-divider"></div>

            @can("image_preuve-list")
            <a href="{{ route('image_preuve.index') }}" class="menu-item">
                <i class="fas fa-image menu-icon"></i>
                <span class="menu-text">Image Preuve</span>
            </a>
            @endcan

            @can("project-list")
            <a href="{{ route('admin.projets.index') }}" class="menu-item">
                <i class='bx bx-briefcase menu-icon'></i>
                <span class="menu-text">Projets</span>
            </a>

            <a href="{{ route('admin.rendez-vous.index') }}" class="menu-item">
                <i class='bx bx-calendar menu-icon'></i>
                <span class="menu-text">L'intervention</span>
            </a>
            @endcan

            @can("role-list")
            <a href="{{ route('roles.index') }}" class="menu-item">
                <i class="fas fa-user-shield menu-icon"></i>
                <span class="menu-text">Rôles</span>
            </a>

            <a href="{{ route('download.backup') }}" class="menu-item">
                <i class="fas fa-database menu-icon"></i>
                <span class="menu-text">Backup</span>
            </a>
            @endcan

            <div class="menu-divider"></div>

            <!-- Dropdown Gestion Dépenses -->
            <div class="dropdown-container">
                <button class="dropdown-btn" onclick="toggleDropdown(this)">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <i class="fas fa-wallet menu-icon"></i>
                        <span class="menu-text">Gestion Dépenses</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('depenses.index') }}" class="dropdown-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('depenses.fixes.index') }}" class="dropdown-item">
                        <i class="fas fa-lock"></i>
                        <span>Dépenses Fixes</span>
                    </a>
                    <a href="{{ route('depenses.variables.index') }}" class="dropdown-item">
                        <i class="fas fa-chart-bar"></i>
                        <span>Dépenses Variables</span>
                    </a>
                    <a href="{{ route('depenses.rapport') }}" class="dropdown-item">
                        <i class="fas fa-file-invoice"></i>
                        <span>Rapport Mensuel</span>
                    </a>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown-container">
                <button class="dropdown-btn" onclick="toggleDropdown(this)">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <i class='bx bx-user menu-icon'></i>
                        <span class="menu-text">Profile</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class='bx bx-user-circle'></i>
                        <span>Mon Profile</span>
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                        <i class='bx bx-log-out'></i>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </div>

            <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </div>
    </nav>

    

    <script>
        let isMobile = window.innerWidth <= 768;

        window.addEventListener('resize', function() {
            isMobile = window.innerWidth <= 768;
            if (!isMobile) {
                closeMobileSidebar();
            }
        });

        function toggleSidebar() {
            if (!isMobile) {
                document.getElementById('sidebar').classList.toggle('collapsed');
            }
        }

        function toggleMobileSidebar() {
            if (isMobile) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.querySelector('.overlay');
                
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            }
        }

        function closeMobileSidebar() {
            if (isMobile) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.querySelector('.overlay');
                
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
        }

        function toggleDropdown(btn) {
            const dropdown = btn.nextElementSibling;
            const arrow = btn.querySelector('.dropdown-arrow');
            
            dropdown.classList.toggle('active');
            arrow.classList.toggle('rotated');
        }

        // Close mobile sidebar when clicking on menu items
        document.querySelectorAll('.menu-item, .dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                if (isMobile) {
                    setTimeout(closeMobileSidebar, 200);
                }
            });
        });
    </script>
</body>
</html>