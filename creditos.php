<?php
include_once "app/usuario/model.php";
session_start();

date_default_timezone_set('America/Mexico_City');
include_once "helpers/vars.php";

?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column">

        <h1 class="my-3">
            <i class="fa-solid fa-building-columns"></i>
            The Tech Pantheon
            <i class="fa-solid fa-building-columns"></i>
        </h1>

        <h2 class="my-3">
            <i class="fa-solid fa-microchip"></i>
            The Tech Titans
            <i class="fa-solid fa-microchip"></i>
        </h2>

        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">

            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/the-tech-pantheon/tt.png" alt="Oscar Camara" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Oscar Camara</h5>
                        <p class="card-text"></p>
                        <div class="card-footer"><small class="text-body-secondary"></small></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/the-tech-pantheon/nito.jpg" alt="Nicolás Hernández" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Nicolás Hernández</h5>
                        <p class="card-text">
                            Soy Nicolas, estudiante de desarrollo de software. Participé apoyando a la universidad para crear
                            esta app web de pasaporte para nuestra semana de TICs. Cuando necesito despejarme, me gusta editar
                            fotos y videos, jugar unas partidas de Minecraft o pasar un buen rato con mi novia y mi gato.
                        </p>
                        <div class="card-footer"><small class="text-body-secondary"></small></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/the-tech-pantheon/yaeljl.jpg" alt="Irving Juárez" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Irving Juárez</h5>
                        <p class="card-text">
                            Soy Yael, estudiante de Ingeniería en Desarrollo de Software con interés en la tecnología y la
                            programación. Me gusta aprender nuevas herramientas y mejorar mis habilidades constantemente. En mi
                            tiempo libre disfruto ver series y jugar videojuegos, lo que me ayuda a desarrollar creatividad,
                            pensamiento lógico y habilidades para resolver problemas.
                        </p>
                        <div class="card-footer"><small class="text-body-secondary"></small></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/the-tech-pantheon/tt.png" alt="Ángel Ortiz" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Ángel Ortiz</h5>
                        <p class="card-text"></p>
                        <div class="card-footer"><small class="text-body-secondary"></small></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/the-tech-pantheon/tt.png" alt="Dayron Romero" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Dayron Romero</h5>
                        <p class="card-text"></p>
                        <div class="card-footer"><small class="text-body-secondary"></small></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/the-tech-pantheon/tt.png" alt="Jonathan Valenzuela" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Jonathan Valenzuela</h5>
                        <p class="card-text"></p>
                        <div class="card-footer"><small class="text-body-secondary"></small></div>
                    </div>
                </div>
            </div>

        </div>

        <h2 class="my-3">
            <i class="fa-solid fa-shield-virus"></i>
            The Defect Destroyers
            <i class="fa-solid fa-shield-virus"></i>
        </h2>

        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">

            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/the-tech-pantheon/dd.png" alt="Leonardo Polo" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Leonardo Polo</h5>
                        <p class="card-text"></p>
                        <div class="card-footer"><small class="text-body-secondary"></small></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/the-tech-pantheon/dd.png" alt="Joshua Torres" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Joshua Torres</h5>
                        <p class="card-text"></p>
                        <div class="card-footer"><small class="text-body-secondary"></small></div>
                    </div>
                </div>
            </div>

        </div>

        <h2 class="my-3">
            <i class="fa-solid fa-rocket"></i>
            The Mission Accelerator
            <i class="fa-solid fa-rocket"></i>
        </h2>

        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">

            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/the-tech-pantheon/rubenrg.jpg" alt="Rubén Ramírez Gómez" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Rubén Ramírez</h5>
                        <p class="card-text">
                            Ingeniero de Software con sólida formación en Matemáticas Aplicadas (UNAM) y Maestría en Ciencia de
                            Datos. Más de 10 años de experiencia diseñando arquitecturas de datos escalables y soluciones backend
                            de alto rendimiento. Experto en el ecosistema Python, AWS (Redshift, Glue) y Big Data. Especialista
                            en transformar requerimientos matemáticos complejos en productos tecnológicos de alto impacto.
                        </p>
                        <div class="card-footer"><small class="text-body-secondary">
                            <a href="https://me.rramirez.com/" target="_blank"> <i class="fa-solid fa-globe"></i> RRamirez</a>
                            | <a href="https://www.linkedin.com/in/rramirez0202/" target="_blank"><i class="fa-brands fa-linkedin-in"></i> Linked in</a>
                            | <a href="https://www.facebook.com/rramirez0202" target="_blank"><i class="fa-brands fa-facebook"></i> Facebook</a>
                        </small></div>
                    </div>
                </div>
            </div>

        </div>

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

        <div style="min-height: 100px;"></div>

    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
