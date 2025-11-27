<header id="header" class="header fixed-top d-flex align-items-center bg-dark justify-content-between">

    <div class="d-flex align-items-center justify-content-between" style="width: 310px;">
        <a href="./dashboard" class="d-flex align-items-center" >
            <img style="width: 80px !important;height: 80px !important;" src="<?= SERVERURL; ?>/view/assets/img/logo.webp" alt="Logo de ventoi" />
            <span style="position: relative;top: 0.3rem;" class="ms-4 h4 d-none d-lg-block"><?= COMPANY ?></span>
        </a>

        <?php if ($_SESSION['dataUsuario']["primerInicio"] == '0') { ?>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        <?php } ?>
    </div>


    <?php
        $id_usuario = $_SESSION['id_usuario'];
        
        $precio_dolar_actual = modeloPrincipal::obtener_precio_dolar();

        $_SESSION['dolar'] = $precio_dolar_actual;

        $tiempo_config = modeloPrincipal::obtener_tiempo_inactividad();

        echo '<script type="text/javascript"> const tiempo_config = '.$tiempo_config.' * 60 * 1000</script>';

    ?>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown">

                <button class="btn btn-light nav-icon bi bi-currency-exchange fst-italic fs-6" data-bs-toggle="dropdown">
                    &nbsp; La Tasa del Día: <span id="tasa_dolar">178.88</span>bs
                </button>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header row justify-content-center">
                        <div class=" col-12 mb-2">
                            <button id="btn_update_dolar_auto" class="w-100 btn btn-success text-center">
                                <span class="p-2 ms-2">Actualizar automáticamente</span>
                            </button>
                        </div>
                        <div class=" col-12 mb-2">
                            <button class="btn btn-warning text-center w-100" data-bs-toggle="modal" data-bs-target="#dolarUpdate" id="btnUpdate">
                                <span class="p-2 ms-2">Actualizar manualmente</span>
                            </button>
                        </div>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="" data-bs-toggle="dropdown">
                    <span class="d-none d-md-block dropdown-toggle ps-2">Usuario</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header  text-white-50">
                        <h6>Usuario</h6>
                        <span>admin</span>
                    </li>

                    <li> <hr class="dropdown-divider" /> </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="./mi_perfil.php">
                            <i class="bi bi-person"></i>
                            <span>Mi Pefil</span>
                        </a>
                    </li>

                    <li> <hr class="dropdown-divider" /> </li>
                    
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="./configuracion.php">
                            <i class="bi bi-gear"></i>
                            <span>Configuración</span>
                        </a>
                    </li>

                    <li> <hr class="dropdown-divider" /> </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center btn-exit-system" href="#!">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>