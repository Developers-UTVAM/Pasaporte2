<?php include_once __DIR__ . "/../init.php"; ?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
    <style>
        .pantheon-card {
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease, border-color 0.4s ease;
            background: rgba(15, 23, 42, 0.7) !important;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 24px !important;
        }

        .pantheon-card:hover {
            transform: translateY(-8px);
            border-color: color-mix(in oklab, var(--primary) 40%, transparent) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 25px color-mix(in oklab, var(--primary) 25%, transparent) !important;
        }

        .pantheon-img-box {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 20px;
        }

        .pantheon-square-img {
            width: 220px;
            height: 220px;
            object-fit: cover;
            object-position: center;
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            background: #0f172a;
        }

        .pantheon-card:hover .pantheon-square-img {
            transform: scale(1.04);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
        }

        .role-badge {
            background: color-mix(in oklab, var(--primary) 15%, transparent);
            color: var(--primary);
            border: 1px solid color-mix(in oklab, var(--primary) 30%, transparent);
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 12px;
        }

        .section-title {
            position: relative;
            display: inline-block;
            padding-bottom: 8px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }
    </style>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column py-4">

        <div class="text-center my-4">
            <h1 class="fw-black mb-2 shiny-title" style="font-size: 2.2rem;">
                <i class="fa-solid fa-building-columns me-2" style="color: var(--primary);"></i>
                The Tech Pantheon
                <i class="fa-solid fa-building-columns ms-2" style="color: var(--primary);"></i>
            </h1>
            <p class="text-light opacity-75 fs-6 fw-light">
                Conoce a los desarrolladores de la plataforma
            </p>
        </div>

        <!-- 1. THE TECH TITANS -->
        <div class="text-center mb-4 mt-2">
            <h3 class="section-title fw-bold text-white">
                <i class="fa-solid fa-microchip me-2 text-primary"></i>The Tech Titans
            </h3>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">

            <!-- Oscar Camara -->
            <div class="col">
                <div class="card h-100 pantheon-card p-2">
                    <div class="pantheon-img-box">
                        <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/tt.png" alt="Oscar Camara" class="pantheon-square-img">
                    </div>
                    <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold mb-2 text-uppercase" style="color: var(--primary); font-size: 1.2rem; letter-spacing: 0.5px;">Oscar Camara</h4>
                            <span class="role-badge"><i class="fa-solid fa-code me-1"></i> Fullstack Developer</span>
                            <p class="card-text small text-light opacity-85 text-start" style="line-height: 1.6;">
                                Estudiante de desarrollo de software enfocado en la arquitectura web, desarrollo backend y diseño de experiencia de usuario para la plataforma PASS-ID.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nicolás Hernández -->
            <div class="col">
                <div class="card h-100 pantheon-card p-2">
                    <div class="pantheon-img-box">
                        <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/nito.jpg" alt="Nicolás Hernández" class="pantheon-square-img">
                    </div>
                    <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold mb-2 text-uppercase" style="color: var(--primary); font-size: 1.2rem; letter-spacing: 0.5px;">Nicolás Hernández</h4>
                            <span class="role-badge"><i class="fa-solid fa-laptop-code me-1"></i> Fullstack Developer</span>
                            <p class="card-text small text-light opacity-85 text-start" style="line-height: 1.6;">
                                Soy Nicolas, estudiante de desarrollo de software. Participé apoyando a la universidad para crear esta app web de PASS-ID para nuestra semana de TICs. Cuando necesito despejarme, me gusta editar fotos y videos, jugar unas partidas de Minecraft o pasar un buen rato con mi novia y mi gato.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Irving Juárez -->
            <div class="col">
                <div class="card h-100 pantheon-card p-2">
                    <div class="pantheon-img-box">
                        <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/yaeljl.jpg" alt="Irving Juárez" class="pantheon-square-img">
                    </div>
                    <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold mb-2 text-uppercase" style="color: var(--primary); font-size: 1.2rem; letter-spacing: 0.5px;">Irving Juárez</h4>
                            <span class="role-badge"><i class="fa-solid fa-layer-group me-1"></i> Fullstack Developer</span>
                            <p class="card-text small text-light opacity-85 text-start" style="line-height: 1.6;">
                                Soy Yael, estudiante de Ingeniería en Desarrollo de Software con interés en la tecnología y la programación. Me gusta aprender nuevas herramientas y mejorar mis habilidades constantemente. En mi tiempo libre disfruto ver series y jugar videojuegos, lo que me ayuda a desarrollar creatividad, pensamiento lógico y habilidades para resolver problemas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ángel Ortiz -->
            <div class="col">
                <div class="card h-100 pantheon-card p-2">
                    <div class="pantheon-img-box">
                        <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/tt.png" alt="Ángel Ortiz" class="pantheon-square-img">
                    </div>
                    <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold mb-2 text-uppercase" style="color: var(--primary); font-size: 1.2rem; letter-spacing: 0.5px;">Ángel Ortiz</h4>
                            <span class="role-badge"><i class="fa-solid fa-terminal me-1"></i> Software Engineer</span>
                            <p class="card-text small text-light opacity-85 text-start" style="line-height: 1.6;">
                                Desarrollador de software apasionado por la creación de soluciones digitales eficientes, optimización de código e integración de módulos académicos para la plataforma.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dayron Romero -->
            <div class="col">
                <div class="card h-100 pantheon-card p-2">
                    <div class="pantheon-img-box">
                        <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/tt.png" alt="Dayron Romero" class="pantheon-square-img">
                    </div>
                    <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold mb-2 text-uppercase" style="color: var(--primary); font-size: 1.2rem; letter-spacing: 0.5px;">Dayron Romero</h4>
                            <span class="role-badge"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> Frontend Engineer</span>
                            <p class="card-text small text-light opacity-85 text-start" style="line-height: 1.6;">
                                Desarrollador enfocado en la maquetación de interfaces web responsive, accesibilidad y mejora de componentes de interacción para el usuario.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jonathan Valenzuela -->
            <div class="col">
                <div class="card h-100 pantheon-card p-2">
                    <div class="pantheon-img-box">
                        <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/jona.jpg" alt="Jonathan Valenzuela" class="pantheon-square-img">
                    </div>
                    <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold mb-2 text-uppercase" style="color: var(--primary); font-size: 1.2rem; letter-spacing: 0.5px;">Jonathan Valenzuela</h4>
                            <span class="role-badge"><i class="fa-solid fa-cubes me-1"></i> Fullstack Developer</span>
                            <p class="card-text small text-light opacity-85 text-start" style="line-height: 1.6;">
                                Soy Jonathan, estudiante de desarrollo de software. Me gustan los videojuegos, el anime, el fútbol y soy gymrat. Me apasiona crear aplicaciones innovadoras con React y Laravel, siempre buscando eficiencia técnica. Soy una persona disciplinada, competitiva y orientada a resultados, que disfruta enfrentar desafíos tanto en el código como en el deporte.
                            </p>
                        </div>
                        <div class="pt-3 text-center">
                            <a href="https://www.facebook.com/jonathan.valenzuela.633432" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                                <i class="fa-brands fa-facebook me-1"></i> Facebook
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 2. THE DEFECT DESTROYERS -->
        <div class="text-center mb-4 mt-4">
            <h3 class="section-title fw-bold text-white">
                <i class="fa-solid fa-shield-virus me-2 text-info"></i>The Defect Destroyers
            </h3>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-4 justify-content-center mb-5">

            <!-- Leonardo Polo -->
            <div class="col-md-5">
                <div class="card h-100 pantheon-card p-2">
                    <div class="pantheon-img-box">
                        <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/dd.png" alt="Leonardo Polo" class="pantheon-square-img">
                    </div>
                    <div class="card-body p-3 text-center">
                        <h4 class="card-title fw-bold mb-2 text-uppercase" style="color: var(--primary); font-size: 1.2rem; letter-spacing: 0.5px;">Leonardo Polo</h4>
                        <span class="role-badge" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8; border-color: rgba(14, 165, 233, 0.3);">
                            <i class="fa-solid fa-bug-slash me-1"></i> QA & Test Engineer
                        </span>
                        <p class="card-text small text-light opacity-85 text-start" style="line-height: 1.6;">
                            Especialista en pruebas funcionales, detección de defectos y validación del aseguramiento de calidad del software para la plataforma PASS-ID.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Joshua Torres -->
            <div class="col-md-5">
                <div class="card h-100 pantheon-card p-2">
                    <div class="pantheon-img-box">
                        <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/dd.png" alt="Joshua Torres" class="pantheon-square-img">
                    </div>
                    <div class="card-body p-3 text-center">
                        <h4 class="card-title fw-bold mb-2 text-uppercase" style="color: var(--primary); font-size: 1.2rem; letter-spacing: 0.5px;">Joshua Torres</h4>
                        <span class="role-badge" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8; border-color: rgba(14, 165, 233, 0.3);">
                            <i class="fa-solid fa-vial-circle-check me-1"></i> QA & Test Engineer
                        </span>
                        <p class="card-text small text-light opacity-85 text-start" style="line-height: 1.6;">
                            Analista de calidad enfocado en auditoría de casos de uso, pruebas de integración y verificación del correcto funcionamiento de módulos.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. THE MISSION ACCELERATOR -->
        <div class="text-center mb-4 mt-4">
            <h3 class="section-title fw-bold text-white">
                <i class="fa-solid fa-rocket me-2 text-warning"></i>The Mission Accelerator
            </h3>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <div class="card h-100 pantheon-card p-2">
                    <div class="pantheon-img-box">
                        <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/rubenrg.jpg" alt="Rubén Ramírez Gómez" class="pantheon-square-img">
                    </div>
                    <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold mb-2 text-uppercase" style="color: var(--primary); font-size: 1.2rem; letter-spacing: 0.5px;">Rubén Ramírez</h4>
                            <span class="role-badge" style="background: rgba(234, 179, 8, 0.15); color: #facc15; border-color: rgba(234, 179, 8, 0.3);">
                                <i class="fa-solid fa-user-astronaut me-1"></i> Project Lead & Tech Advisor
                            </span>
                            <p class="card-text small text-light opacity-85 text-start" style="line-height: 1.6;">
                                Ingeniero de Software con sólida formación en Matemáticas Aplicadas (UNAM) y Maestría en Ciencia de Datos. Más de 10 años de experiencia diseñando arquitecturas de datos escalables y soluciones backend de alto rendimiento. Experto en el ecosistema Python, AWS y Big Data.
                            </p>
                        </div>
                        <div class="pt-3 text-center">
                            <a href="https://me.rramirez.com/" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 m-1 shadow-sm"><i class="fa-solid fa-globe me-1"></i> Web</a>
                            <a href="https://www.linkedin.com/in/rramirez0202/" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 m-1 shadow-sm"><i class="fa-brands fa-linkedin-in me-1"></i> LinkedIn</a>
                            <a href="https://www.facebook.com/rramirez0202" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 m-1 shadow-sm"><i class="fa-brands fa-facebook me-1"></i> Facebook</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="min-height: 60px;"></div>

    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
