<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYbYMpwVNrGj39HPPcodSyE7KPLB7UqM1Ny6WFAQx1Q3pld0TUf9xj6am2DYspgZPXQ58&usqp=CAU" type="image/png">
    <title>Professional Sidebar</title>
    <style>
        body {
            margin: 0;
            font-family: 'Ubuntu', sans-serif;
            background: linear-gradient(120deg, #f3f4f6, #e9ecef);
            transition: all 0.3s ease-in-out;
        }

        .a {
            color: rgb(255 255 255) !important;
            text-decoration: none !important;
            text-transform: uppercase !important;
        }

        /* Mobile Toggle Button - Only visible on mobile */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: linear-gradient(180deg, #D32F2F, #C2185B);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            z-index: 1001;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .mobile-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Overlay for mobile */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .overlay.active {
            display: block;
            opacity: 1;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 75px;
            height: 100%;
            background: linear-gradient(180deg, #D32F2F, #C2185B);
            overflow-x: hidden;
            transition: width 0.4s ease-in-out;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
            z-index: 999;
        }

        /* Desktop hover effect */
        .sidebar:hover {
            width: 240px;
        }

        body.open .sidebar {
            width: 240px;
        }

        .sidebar-header {
            width: 100%;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-burger {
            cursor: pointer;
        }

        .sidebar-burger i {
            font-size: 24px;
            color: #fff;
            transition: color 0.3s;
        }

        .sidebar-burger:hover i {
            color: #f8bbd0;
        }

        .sidebar-menu {
            padding: 10px 0;
        }

        .sidebar-menu button {
            width: 100%;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #fff;
            font-size: 16px;
            gap: 15px;
            background: none;
            border: none;
            transition: background 0.3s, transform 0.3s;
        }

        .sidebar-menu button:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.05);
        }

        .sidebar-menu button i {
            font-size: 24px;
            color: #fff;
            min-width: 24px;
        }

        .sidebar-menu button span {
            opacity: 0;
            white-space: nowrap;
            transition: opacity 0.4s ease-in-out;
            font-weight: 500;
            text-transform: uppercase;
        }

        .sidebar:hover .sidebar-menu button span,
        body.open .sidebar-menu button span {
            opacity: 1;
        }

        .sidebar-menu button.has-border {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 15px;
            margin-bottom: 10px;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #ffebee;
        }

        .dropdown-container {
            position: relative;
            display: inline-block;
            border-radius: 10px;
            padding: 10px 15px;
            cursor: pointer;
            width: 100%;
        }

        .dropdown-container .icon {
            display: flex;
            align-items: center;
            font-size: 18px;
            color: #fff;
            width: 100%;
        }

        .dropdown-container .icon i {
            margin-right: 8px;
            min-width: 24px;
        }

        .dropdown-container .p-fetch {
            opacity: 0;
            white-space: nowrap;
            transition: opacity 0.4s ease-in-out;
            font-weight: 500;
            text-transform: uppercase;
            margin: 0;
        }

        .sidebar:hover .dropdown-container .p-fetch,
        body.open .dropdown-container .p-fetch {
            opacity: 1;
        }

        /* Mobile Responsive Styles */
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
                border-radius: 0;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar:hover {
                width: 280px;
                transform: translateX(-100%);
            }

            .sidebar.mobile-open:hover {
                transform: translateX(0);
            }

            .sidebar.mobile-open .sidebar-menu button span,
            .sidebar.mobile-open .dropdown-container .p-fetch {
                opacity: 1;
            }

            .main-content {
                margin-left: 0;
                padding: 80px 20px 20px;
            }

            .sidebar-header {
                justify-content: flex-start;
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
            }

            .main-content {
                padding: 80px 15px 15px;
            }
        }

        /* SweetAlert Custom Styles */
        .swal-popup {
            border: 2px solid #D32F2F;
            font-family: 'Ubuntu', sans-serif;
        }

        .swal-title {
            color: #C2185B;
            font-size: 28px;
            font-weight: bold;
        }

        .swal-html-container {
            font-size: 16px;
            color: #333;
        }

        .swal-close-btn {
            background-color: #D32F2F !important;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }

        .swal-close-btn:hover {
            background-color: #C2185B;
        }

        .swal-popup a {
            font-size: 16px;
            text-decoration: none;
        }

        .swal-popup a:hover {
            color: #C2185B;
        }

        .swal-confirm-btn {
            background-color: #D32F2F;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .swal-confirm-btn:hover {
            background-color: #C2185B;
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleMobileSidebar()">
        <i class='bx bx-menu'></i>
    </button>

    <!-- Overlay for重ね -->
    <div class="overlay" onclick="closeMobileSidebar()"></div>

    <nav class="sidebar" id="sidebar">
        <div>
            <header class="sidebar-header">
                <div class="sidebar-burger" onclick="toggleSidebar()">
                    <i class='bx bx-menu'></i>
                </div>
            </header>
            <div class="sidebar-menu">
                <button type="button">
                    <a href="{{ route('dashboard') }}"><i class='bx bx-home'></i></a>
                    <span><a class="a" href="{{ route('dashboard') }}">accueil</a></span>
                </button>
                
                @can("user-list")
                <button type="button" class="has-border">
                    <a href="{{ route('users.index') }}"><i class='bx bx-group'></i></a>
                    <span><a class="a" href="{{ route('users.index') }}">Équipe</a></span>
                </button>
                @endcan

                <button type="button" class="has-border">
                    <a href="{{ route('login.history') }}"><i class="fas fa-history"></i></a>
                    <span><a class="a" href="{{ route('login.history') }}">Historique_session</a></span>
                </button>


                 @can("pointage-list")
                <button type="button">
                    <a href="{{ route('pointage.index') }}"><i class='bx bx-time'></i></a>
                    <span><a class="a" href="{{ route('pointage.index') }}">pointages</a></span>
                </button>
                @endcan


                 @can("tache-list")
                <button type="button">
                    <a href="{{ route('taches.index') }}"><i class='bx bx-task'></i></a>
                    <span><a class="a" href="{{ route('taches.index') }}">les tâches</a></span>
                </button>
                @endcan


                 @can("reclamation-list")
                <button type="button">
                    <a href="{{ route('reclamations.index') }}"><i class='bx bx-comment-detail'></i></a>
                    <span><a class="a" href="{{ route('reclamations.index') }}">reclamations</a></span>
                </button>
                @endcan


                 @can("formation-list")
                <button type="button">
                    <a href="{{ route('formations.index') }}"><i class='bx bx-chalkboard'></i></a>
                    <span><a class="a" href="{{ route('formations.index') }}">formations</a></span>
                </button>
                @endcan

                
                @can("objectif-list")
                <button type="button">
                    <a href="{{ route('objectifs.index') }}"><i class='bx bx-target-lock'></i></a>
                    <span><a class="a" href="{{ route('objectifs.index') }}">objectifs</a></span>
                </button>
                @endcan

               

               

                @can("image_preuve-list")
                <button type="button">
                    <a href="{{ route('image_preuve.index') }}"><i class="fas fa-image"></i></a>
                    <span><a class="a" href="{{ route('image_preuve.index') }}">image preuve</a></span>
                </button>
                @endcan

                @can("project-list")
                <button type="button">
                    <a href="{{ route('admin.projets.index') }}"><i class='bx bx-briefcase'></i></a>
                    <span><a class="a" href="{{ route('admin.projets.index') }}">projects</a></span>
                </button>
                @endcan

                 @can("project-list")
                <button type="button">
                    <a href="{{ route('admin.rendez-vous.index') }}"><i class='bx bx-briefcase'></i></a>
                    <span><a class="a" href="{{ route('admin.rendez-vous.index') }}">rendez vous</a></span>
                </button>
                @endcan

               

               

                @can("role-list")
                <button type="button">
                    <a href="{{ route('roles.index') }}"><i class="fas fa-user-shield"></i></a>
                    <span><a class="a" href="{{ route('roles.index') }}">roles</a></span>
                </button>
                @endcan

                <div class="dropdown-container">
                    <button class="icon" id="profileButton">
                        <i class='bx bx-user'></i>
                        <p class="p-fetch">Profile <i id="i-fetch" class="fa fa-chevron-down"></i></p>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let isMobile = window.innerWidth <= 768;

        // Check if mobile on resize
        window.addEventListener('resize', function() {
            isMobile = window.innerWidth <= 768;
            if (!isMobile) {
                closeMobileSidebar();
            }
        });

        // Toggle sidebar for desktop
        const toggleSidebar = () => {
            if (!isMobile) {
                document.body.classList.toggle('open');
            }
        };

        // Mobile-specific functions
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

        // Profile button SweetAlert on click
        document.getElementById('profileButton').addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Profile Options',
                html: `
                    <a href="{{ route('profile.edit') }}" style="display:block; margin-bottom: 10px; color: #C2185B; font-size: 16px; text-decoration: none; background-color: #ff00000a;">
                        <i class='bx bx-user-circle' style="color: #C2185B;"></i> Profile
 preceded                    </a>
                    <a href="{{ route('logout') }}" style="display:block; margin-bottom: 10px; color: #D32F2F; font-size: 16px; text-decoration: none; background-color: #ff00000a;" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                        <i class='bx bx-log-out' style="color: #D32F2F;"></i> Log Out
                    </a>
                    <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                `,
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: 'Annuler',
                customClass: {
                    popup: 'swal-popup',
                    title: 'swal-title',
                    htmlContainer: 'swal-html-container',
                    closeButton: 'swal-close-btn',
                    confirmButton: 'swal-confirm-btn'
                },
                background: '#ffffff',
                iconColor: '#C2185B',
                buttonsStyling: false,
                didOpen: () => {
                    const popup = document.querySelector('.swal-popup');
                    popup.style.padding = '20px';
                    popup.style.borderRadius = '10px';
                    popup.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
                    // Close mobile sidebar when clicking a link in SweetAlert
                    popup.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', () => {
                            if (isMobile) {
                                setTimeout(closeMobileSidebar, 200);
                            }
                        });
                    });
                }
            });
        });

        // Close mobile sidebar when clicking on other links
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', function() {
                if (isMobile) {
                    setTimeout(closeMobileSidebar, 200);
                }
            });
        });
    </script>
</body>
</html>