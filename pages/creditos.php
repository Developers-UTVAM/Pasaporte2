<?php include_once __DIR__ . "/../init.php"; ?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column">

        <div class="text-center my-5">
            <h1 class="fw-bold mb-2">
                <i class="fa-solid fa-building-columns me-2" style="color: var(--primary);"></i>
                The Tech Pantheon
                <i class="fa-solid fa-building-columns ms-2" style="color: var(--primary);"></i>
            </h1>
            <h4 style="color: var(--text-color); opacity: 0.8; font-weight: var(--font-weight-light);">
                Conoce a los desarrolladores de la plataforma
            </h4>
        </div>

        <h2 class="text-center mb-4" style="color: var(--primary);">
            <i class="fa-solid fa-microchip me-2 opacity-75"></i>The Tech Titans
        </h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">

            <div class="col">
                <div class="card h-100 glass-panel shadow-lg border-0" style="border-radius: 24px; overflow: hidden;">
                    <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/tt.png" alt="Oscar Camara" class="card-img-top" style="height: 250px; object-fit: cover; object-position: top; border-bottom: 1px solid var(--glass-border);">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-3" style="color: var(--primary);">Oscar Camara</h4>
                        <p class="card-text" style="color: var(--text-color); opacity: 0.85; line-height: 1.6;"></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 glass-panel shadow-lg border-0" style="border-radius: 24px; overflow: hidden;">
                    <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/nito.jpg" alt="Nicolás Hernández" class="card-img-top" style="height: 250px; object-fit: cover; object-position: top; border-bottom: 1px solid var(--glass-border);">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-3" style="color: var(--primary);">Nicolás Hernández</h4>
                        <p class="card-text" style="color: var(--text-color); opacity: 0.85; line-height: 1.6;">
                            Soy Nicolas, estudiante de desarrollo de software. Participé apoyando a la universidad para crear
                            esta app web de PASS-ID para nuestra semana de TICs. Cuando necesito despejarme, me gusta editar
                            fotos y videos, jugar unas partidas de Minecraft o pasar un buen rato con mi novia y mi gato.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 glass-panel shadow-lg border-0" style="border-radius: 24px; overflow: hidden;">
                    <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/yaeljl.jpg" alt="Irving Juárez" class="card-img-top" style="height: 250px; object-fit: cover; object-position: top; border-bottom: 1px solid var(--glass-border);">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-3" style="color: var(--primary);">Irving Juárez</h4>
                        <p class="card-text" style="color: var(--text-color); opacity: 0.85; line-height: 1.6;">
                            Soy Yael, estudiante de Ingeniería en Desarrollo de Software con interés en la tecnología y la
                            programación. Me gusta aprender nuevas herramientas y mejorar mis habilidades constantemente. En mi
                            tiempo libre disfruto ver series y jugar videojuegos, lo que me ayuda a desarrollar creatividad,
                            pensamiento lógico y habilidades para resolver problemas.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 glass-panel shadow-lg border-0" style="border-radius: 24px; overflow: hidden;">
                    <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/tt.png" alt="Ángel Ortiz" class="card-img-top" style="height: 250px; object-fit: cover; object-position: top; border-bottom: 1px solid var(--glass-border);">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-3" style="color: var(--primary);">Ángel Ortiz</h4>
                        <p class="card-text" style="color: var(--text-color); opacity: 0.85; line-height: 1.6;"></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 glass-panel shadow-lg border-0" style="border-radius: 24px; overflow: hidden;">
                    <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/tt.png" alt="Dayron Romero" class="card-img-top" style="height: 250px; object-fit: cover; object-position: top; border-bottom: 1px solid var(--glass-border);">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-3" style="color: var(--primary);">Dayron Romero</h4>
                        <p class="card-text" style="color: var(--text-color); opacity: 0.85; line-height: 1.6;"></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 glass-panel shadow-lg border-0" style="border-radius: 24px; overflow: hidden;">
                    <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/jona.jpg" alt="Jonathan Valenzuela" class="card-img-top" style="height: 250px; object-fit: cover; object-position: top; border-bottom: 1px solid var(--glass-border);">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-3" style="color: var(--primary);">Jonathan Valenzuela</h4>
                        <p class="card-text" style="color: var(--text-color); opacity: 0.85; line-height: 1.6;">
                            Soy Jonathan, estudiante de desarrollo de software. Me gustan los videojuegos, el anime, el fútbol y
                            soy gymrat. Me apasiona crear aplicaciones innovadoras con React y Laravel, siempre buscando
                            eficiencia técnica. Soy una persona disciplinada, competitiva y orientada a resultados, que disfruta
                            enfrentar desafíos tanto en el código como en el deporte.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pb-4 px-4 text-center">
                        <a href="https://www.facebook.com/jonathan.valenzuela.633432" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                            <i class="fa-brands fa-facebook me-1"></i> Facebook
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <h2 class="text-center mb-4 mt-5" style="color: var(--primary);">
            <i class="fa-solid fa-shield-virus me-2 opacity-75"></i>The Defect Destroyers
        </h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center mb-5">

            <div class="col">
                <div class="card h-100 glass-panel shadow-lg border-0" style="border-radius: 24px; overflow: hidden;">
                    <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/dd.png" alt="Leonardo Polo" class="card-img-top" style="height: 250px; object-fit: cover; object-position: top; border-bottom: 1px solid var(--glass-border);">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-3" style="color: var(--primary);">Leonardo Polo</h4>
                        <p class="card-text" style="color: var(--text-color); opacity: 0.85; line-height: 1.6;"></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 glass-panel shadow-lg border-0" style="border-radius: 24px; overflow: hidden;">
                    <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/dd.png" alt="Joshua Torres" class="card-img-top" style="height: 250px; object-fit: cover; object-position: top; border-bottom: 1px solid var(--glass-border);">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-3" style="color: var(--primary);">Joshua Torres</h4>
                        <p class="card-text" style="color: var(--text-color); opacity: 0.85; line-height: 1.6;"></p>
                    </div>
                </div>
            </div>

        </div>

        <h2 class="text-center mb-4 mt-5" style="color: var(--primary);">
            <i class="fa-solid fa-rocket me-2 opacity-75"></i>The Mission Accelerator
        </h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center mb-5">

            <div class="col">
                <div class="card h-100 glass-panel shadow-lg border-0" style="border-radius: 24px; overflow: hidden;">
                    <img src="<?php echo ROOT_URL; ?>assets/img/the-tech-pantheon/rubenrg.jpg" alt="Rubén Ramírez Gómez" class="card-img-top" style="height: 250px; object-fit: cover; object-position: top; border-bottom: 1px solid var(--glass-border);">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold mb-3" style="color: var(--primary);">Rubén Ramírez</h4>
                        <p class="card-text" style="color: var(--text-color); opacity: 0.85; line-height: 1.6;">
                            Ingeniero de Software con sólida formación en Matemáticas Aplicadas (UNAM) y Maestría en Ciencia de
                            Datos. Más de 10 años de experiencia diseñando arquitecturas de datos escalables y soluciones backend
                            de alto rendimiento. Experto en el ecosistema Python, AWS (Redshift, Glue) y Big Data. Especialista
                            en transformar requerimientos matemáticos complejos en productos tecnológicos de alto impacto.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pb-4 px-4 text-center">
                        <a href="https://me.rramirez.com/" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 m-1 shadow-sm"><i class="fa-solid fa-globe me-1"></i> Web</a>
                        <a href="https://www.linkedin.com/in/rramirez0202/" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 m-1 shadow-sm"><i class="fa-brands fa-linkedin-in me-1"></i> LinkedIn</a>
                        <a href="https://www.facebook.com/rramirez0202" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 m-1 shadow-sm"><i class="fa-brands fa-facebook me-1"></i> Facebook</a>
                    </div>
                </div>
            </div>

        </div>

        <!--
        <h4 class="text-center mb-4" style="color: var(--text-color); font-weight: var(--font-weight-light); opacity: 0.8;">
            Descubre nuestras últimas actividades
        </h4>

        <div class="card p-3 mb-4" style="border-radius: 24px;">
            <script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script>
            <iframe src="//lightwidget.com/widgets/ec85b02092e35b879334c0f3b5a05c69.html"
                    scrolling="no"
                    allowtransparency="true"
                    class="lightwidget-widget"
                    style="width:100%; border:0; overflow:hidden;">
            </iframe>
        </div>

        <div class="text-center mt-4">
            <a href="https://www.instagram.com/cybervibe_2026/" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow">
                <i class="fab fa-instagram me-2"></i> Síguenos
            </a>
        </div>
        -->

        <div style="min-height: 100px;"></div>

    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
