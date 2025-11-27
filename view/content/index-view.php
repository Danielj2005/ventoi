<header id="header" class="header d-flex flex-column justify-content-center">
    <i class="header-toggle d-xl-none bi bi-list"></i>
    <nav id="navmenu" class="navmenu">
        <ul>
            <li><a href="#hero" class="active"><i class="bi bi-house navicon"></i><span>Página Principal</span></a></li>
            <li><a href="#about"><i class="bi bi-person navicon"></i><span>Quiénes Somos</span></a></li>
            <li><a href="#services"><i class="bi bi-file-earmark-text navicon"></i><span>Planes y Funciones</span></a></li>
            <li><a href="#contact"><i class="bi bi-envelope navicon"></i><span>Contacto</span></a></li>
        </ul>
    </nav>
</header>

<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

        <img src="<?= SERVERURL; ?>view/assets/img/wallpaper_I.webp" alt="wallpaper de VENTOI">

        <div class="container" data-aos="zoom-out">
            <div class="row justify-content-center">
                <div class="col-lg-9">

                    <h2 class="d-flex align-items-center">VENTOI</h2>
                    <p>Simplifica la Gestión de Ventas e Inventario de tu Negocio. </p>

                    <div class="social-links">
                        <a target="_blank" href="#"><i class="bi bi-facebook text-primary"></i></a>
                        <a target="_blank" href="https://api.whatsapp.com/send?phone=584125238909"><i class="text-success bi bi-whatsapp"></i></a>

                        <div class="mt-2 d-flex justify-content-center">
                            <a class="mb-2 btn btn-outline-primary" href="login">Iniciar Sesión</a>
                            <a class="mb-2 btn btn-primary" href="register">Solicitar Demo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section bg-dark-subtle">

        <!-- Section Title -->
        <div class="container section-title text-white" style="text-wrap: balance !important;" data-aos="fade-up">
            <h2 class="text-white">Sobre Nosotros</h2>
            <p>"VENTOI" nació de la necesidad de simplificar la gestión de ventas e inventario en negocios. Nuestra misión es ofrecer una solución eficiente y fácil de usar para que los administradores puedan concentrarse en hacer crecer su negocio.</p>
        </div><!-- End Section Title -->

        <div class="container  text-white" data-aos="fade-up" data-aos-delay="200">

            <div class="row gy-4 justify-content-center">
                <div class="col-lg-4">

                    <img src="<?= SERVERURL; ?>view/assets/img/logo.webp" 
                        class="img-fluid rounded-circle bg-light" 
                        alt="Logo de VENTOI">
                </div>
                <div class="col-lg-8 content">
                    <h2 class="text-white">Sistema de Control de Ventas e Inventario.</h2>
                    <p class="fst-italic py-3" style="text-wrap: balance !important;">
                        Nuestro sistema te ayuda a gestionar de manera eficiente las ventas y el inventario de tu negocio, facilitando el control y seguimiento de tus productos y transacciones. Te ofrecemos:
                    </p>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <strong>Seguimiento y Control Centralizado de Ventas e Inventario.</strong> </li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Notificaciones Automáticas de Ventas (Email).</strong> </li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Gestión Detallada de Presentaciones, Marcas y Categorias.</strong> </li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Emisión de Facturas de Ventas en formato PDF.</strong> </li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul>
                                
                                <!-- <li><i class="bi bi-chevron-right"></i> <strong>Generación de Reportes Financieros y de Ganancias.</strong> </li> -->
                                <li><i class="bi bi-chevron-right"></i> <strong>Respaldo Seguro y Garantizado de Toda la Información de Ventas e Inventario.</strong> </li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Base de Datos y Gestión Integral de Clientes.</strong> </li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Identificación y Control de Cuentas por Cobrar (Deudores).</strong> </li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Acceso Rápido y Fácil a la Información de Clientes y Transacciones.</strong> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>


    <section id="services" class="services section bg-dark-subtle">

        <div class="container section-title" data-aos="fade-up">
            <h2 class="text-white">Planes Flexibles y Precios Transparentes</h2>
            <p class="text-white">Elige la solución que impulsa tu negocio sin compromisos. ¡Paga solo por lo que usas!</p>
        </div>

        <div class="container">

            <div class="row gy-1 justify-content-center">

                <div class="col-10 col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-4 service-item item-cyan position-relative pricing-card bg-dark text-white-50 border border-2">
                        <div class="icon h-auto"> <i class="bi bi-gem"></i> </div>

                        <div class="pricing-header text-white-50">
                            <h3 class="plan-name text-white">Control Inicial</h3>
                            <span class="price-tag fw-semibold text-white"> <sup>$</sup> <b> 20 </b> <span> / mes</span> </span>
                            <p class="description my-3">La solución esencial para organizar tus cobros y clientes.</p>
                        </div>

                        <ul class="features-list list-unstyled text-start">
                            <li class="mb-2 "><i class="bi bi-check-circle text-success"></i> Seguimiento y Control Centralizado de Ventas e Inventario.</li>
                            <li class="mb-2 "><i class="bi bi-check-circle text-success"></i> Base de Datos y Gestión Integral de Clientes y Proveedores.</li>
                            <li class="mb-2 "><i class="bi bi-check-circle text-success"></i> Emisión de Facturas de Ventas en formato PDF.</li>
                        </ul>

                        <a href="#" class="btn btn-buy stretched-link mt-4 btn-outline-light">Comenzar ahora</a>
                    </div>
                </div>

                <div class="col-10 col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-4 service-item item-cyan position-relative pricing-card bg-dark text-white-50 border border-2">
                        <div class="icon h-auto"> <i class="bi bi-star"></i> </div>

                        <div class="pricing-header text-white-50">
                            <h3 class="plan-name text-white">Gestión Pro <span class="badge bg-warning text-dark">Popular</span></h3>
                            <span class="price-tag fw-semibold text-white"> <sup>$</sup> <b> 30 </b> <span> / mes</span> </span>
                            <p class="description my-3">Control total y automatización para el crecimiento constante.</p>
                        </div>

                        <ul class="features-list list-unstyled text-start">
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Todas las funciones del Plan <b>Control Inicial</b>.</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> <b>Notificaciones Automáticas de Ventas (vía Email).</b></li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> <b>Identificación y Control de Cuentas por Cobrar (Deudores).</b> </li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Estadisticas de Productos Más Vendidos.</li>
                        </ul>

                        <button href="#" class="btn btn-buy stretched-link mt-4 btn-outline-light">¡Empezar a Crecer!</button>
                    </div>
                </div>

                <div class="col-10 col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-4 service-item item-cyan position-relative pricing-card bg-dark text-white-50 border border-2">
                        <div class="icon h-auto"> <i class="bi bi-shield-check"></i> </div>

                        <div class="pricing-header text-white-50">
                            <h3 class="plan-name text-white">Solución Integral</h3>
                            <span class="price-tag fw-semibold text-white"> <sup>$</sup> <b> 45 </b> <span> / mes</span> </span>
                            <p class="description my-3">Máximo rendimiento, seguridad y soporte para grandes volúmenes.</p>
                        </div>

                        <ul class="features-list list-unstyled text-start">
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Todas las funciones del Plan <b>Gestión Pro</b>.</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Generación de Reportes de inventario y Venta</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> <b>Respaldo Seguro y Garantizado</b> de Toda la Información.</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Soporte Prioritario 24/7.</li>
                        </ul>

                        <a href="#" class="btn btn-buy stretched-link mt-4 btn-outline-light">Solicitar Demo</a>
                    </div>
                </div>

            </div>

        </div>

    </section>


    <!-- Contact Section -->
    <section id="contact" class="contact section bg-dark-subtle">

        <div class="container section-title" data-aos="fade-up">
            <h2 class="text-white">¿Tienes Preguntas? Contáctanos</h2>
            <p class="text-white">Estamos listos para ayudarte a optimizar tu gestión de Ventas, Inventario y clientes. ¡Hablemos de tu negocio!</p>
        </div>

        <div class="container" data-aos="fade" data-aos-delay="100">

            <div class="row gy-4">
                <div class="col-lg-6 mb-3 justify-content-center align-items-center">
                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-geo-alt flex-shrink-0"></i>
                        <div class="">
                            <h3 class="text-white">Dirección</h3>
                            <p class="text-white">Turen, Estado Portuguesa.</p>
                        </div>
                    </div>

                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-envelope flex-shrink-0"></i>
                        <div class="">
                            <h3 class="text-white">Correo Electrónico</h3>
                            <p class="text-white">dbarrueta42@gmail.com</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-whatsapp flex-shrink-0"></i>
                        <div class="">
                            <h3 class="text-white">Contactanos a través de nuestro Whatsapp</h3>
                            <p class="text-white"> 
                                <a class="btn btn-link" 
                                    target="_blank" 
                                    href="https://api.whatsapp.com/send?phone=584125238909">Escribenos!
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<footer id="footer" class="footer position-relative bg-dark-subtle">
    <div class="container">
        <hr class="text-white">
        
        <h2 class="d-flex align-items-center justify-content-center gap-3">
            <img class="logo rounded-circle bg-light" 
                src="<?= SERVERURL; ?>view/assets/img/logo.webp"
                alt="Logo de Ventoi"> VENTOI
        </h2>

        <p class="text-white fs-5 mb-0">El control de tus finanzas es nuestro trabajo. Tu crecimiento, es tu prioridad.</p>
        <p class="text-white fs-5 mb-3">VENTOI es un Sistema seguro y confiable para que cada transacción cuente, sin perder un solo dato.</p>
        
        <div class="social-links d-flex justify-content-center">
            <a class="bg-light" target="_blank" href="#"><i class="bi bi-facebook text-primary"></i></a>
            <a class="bg-light" target="_blank" href="https://api.whatsapp.com/send?phone=584125238909"><i class="text-success bi bi-whatsapp"></i></a>
        </div>
        <hr class="text-white">

        <div class="container ">
            <div class="copyright text-white mb-2">
                &copy; <strong class="px-1 sitename">VENTOI</strong> <span>All Rights Reserved</span>
                <p>Diseñado por</p>
            </div>
            <h5 class="text-white fw-bold"> Create Tech Solution's</h5>
        
        </div>
    </div>
</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>