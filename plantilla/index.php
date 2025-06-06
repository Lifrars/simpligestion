<?php


session_start();
include "auth/logincheck.php";
include "assets/config.php";

$idPerfil = $_SESSION['pi']['act_idPerfil'];
$id = $_SESSION['pi']['act_id'];
$nombre = $_SESSION['pi']['act_nomre_completo'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestor Documental</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background: #1a1d29;
            min-height: 100vh;
            color: #e5e7eb;
        }

        .header {
            background: #232741;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #343852;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #e5e7eb;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #4f46e5;
        }

        .main-content {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 3rem;
            color: #e5e7eb;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #f8fafc;
        }

        .welcome-section p {
            font-size: 1.2rem;
            color: #9ca3af;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .menu-card {
            background: #232741;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border: 1px solid #343852;
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(79, 70, 229, 0.1), transparent);
            transition: left 0.5s;
        }

        .menu-card:hover::before {
            left: 100%;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(79, 70, 229, 0.3);
            border-color: #4f46e5;
        }

        .menu-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #4f46e5;
            transition: all 0.3s ease;
        }

        .menu-card:hover .menu-icon {
            transform: scale(1.1);
            color: #6366f1;
        }

        .menu-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #f8fafc;
        }

        .menu-description {
            color: #9ca3af;
            line-height: 1.5;
            font-size: 1rem;
        }

        .menu-card.primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-color: #6366f1;
        }

        .menu-card.primary .menu-icon {
            color: white;
        }

        .menu-card.primary .menu-title,
        .menu-card.primary .menu-description {
            color: white;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 3rem 0;
        }

        .stat-card {
            background: #232741;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid #343852;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
            display: block;
        }

        .stat-label {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .logout-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #232741;
            border: 1px solid #4f46e5;
            border-radius: 50px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            color: #e5e7eb;
        }

        .logout-btn:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .welcome-section h1 {
                font-size: 2rem;
            }
            
            .menu-card {
                min-height: 150px;
                padding: 1.5rem;
            }
            
            .menu-icon {
                font-size: 2.5rem;
            }
        }

        .submenu-items {
            display: none;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #343852;
        }

        .submenu-items.show {
            display: block;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            margin: 0.25rem 0;
            border-radius: 8px;
            transition: background 0.3s ease;
            cursor: pointer;
            color: #e5e7eb;
        }

        .submenu-item:hover {
            background: rgba(79, 70, 229, 0.2);
            color: #f8fafc;
        }
    </style>
</head>
<body>
    <!-- Header -->
        <?php include 'menu.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Bienvenido al Sistema</h1>
            <p>Gestiona todos tus procesos de manera eficiente</p>
        </div>

        <!-- Stats Section -->


        <!-- Menu Grid -->
        <div class="menu-grid">
            <!-- Tickets -->
            <div class="menu-card primary" onclick="navigateTo('tickets.php')">
                <div class="menu-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="menu-title">Tickets</div>
                <div class="menu-description">Gestiona y administra todos los tickets del sistema</div>
            </div>

            <!-- Votaciones -->
            <div class="menu-card" onclick="toggleSubmenu(this)">
                <div class="menu-icon">
                    <i class="fas fa-vote-yea"></i>
                </div>
                <div class="menu-title">Votaciones</div>
                <div class="menu-description">Sistema de votaciones y consultas</div>
                <div class="submenu-items">
                    <div class="submenu-item" onclick="navigateTo('votar.php')">
                        <i class="fas fa-check-circle"></i>
                        <span>Votar</span>
                    </div>
                    <div class="submenu-item" onclick="navigateTo('votaciones.php')">
                        <i class="fas fa-list"></i>
                        <span>Ver Votaciones</span>
                    </div>
                </div>
            </div>

            <!-- Areas Comunes -->
            <div class="menu-card" onclick="toggleSubmenu(this)">
                <div class="menu-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="menu-title">Áreas Comunes</div>
                <div class="menu-description">Administra y reserva espacios comunes</div>
                <div class="submenu-items">
                    <div class="submenu-item" onclick="navigateTo('area.php')">
                        <i class="fas fa-home"></i>
                        <span>Ver Áreas</span>
                    </div>
                    <div class="submenu-item" onclick="navigateTo('card.php')">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Reservar</span>
                    </div>
                </div>
            </div>

            <!-- Administración -->
            <div class="menu-card" onclick="toggleSubmenu(this)">
                <div class="menu-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="menu-title">Administración</div>
                <div class="menu-description">Panel de administración del sistema</div>
                <div class="submenu-items">
                    <div class="submenu-item" onclick="navigateTo('usuarios.php')">
                        <i class="fas fa-users"></i>
                        <span>Usuarios</span>
                    </div>
                    <div class="submenu-item" onclick="navigateTo('torres.php')">
                        <i class="fas fa-building"></i>
                        <span>Residencias</span>
                    </div>
                    <div class="submenu-item" onclick="navigateTo('perfiles.php')">
                        <i class="fas fa-user-shield"></i>
                        <span>Perfiles</span>
                    </div>
                </div>
            </div>

    

            <!-- Configuración -->
            <div class="menu-card" onclick="navigateTo('contra_user.php')">
                <div class="menu-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="menu-title">Seguridad</div>
                <div class="menu-description">Cambiar contraseña y configuración</div>
            </div>
        </div>
    </div>

 

    <script>
        function navigateTo(url) {
            // Efecto de clic antes de navegar
            event.currentTarget.style.transform = 'scale(0.95)';
            setTimeout(() => {
                window.location.href = url;
            }, 150);
        }

        function toggleSubmenu(card) {
            event.stopPropagation();
            const submenu = card.querySelector('.submenu-items');
            const isVisible = submenu.classList.contains('show');
            
            // Cerrar todos los submenús
            document.querySelectorAll('.submenu-items').forEach(menu => {
                menu.classList.remove('show');
            });
            
            // Abrir el actual si no estaba visible
            if (!isVisible) {
                submenu.classList.add('show');
            }
        }

        // Cerrar submenús al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.menu-card')) {
                document.querySelectorAll('.submenu-items').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Animaciones de entrada
        window.addEventListener('load', () => {
            const cards = document.querySelectorAll('.menu-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>