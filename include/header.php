<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="./" class="logo d-flex align-items-center">
      <img src="img/favicon.ico" alt="">
      <span class="d-none d-lg-block">POLLERA LA CHINITA</span>
    </a>
    <?php if ($_SESSION['dataUsuario']["primerInicio"] == '0') { ?>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    <?php } ?>

  </div>

  <!-- 
    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="BUSCAR VENTA POR NÚMERO DE FACTURA O POR NOMBRE DEL CLIENTE" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div> 
  -->

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

        <button class="btn bg-secondary-light nav-icon fst-italic fs-6" data-bs-toggle="dropdown">
          <i class="bi bi-currency-exchange"></i>
          &nbsp; Tasa USD: <span id="tasa_dolar"><?= number_format((float)$precio_dolar_actual, 2, ',', '.') ?></span>Bs
        </button>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header row justify-content-center">
            <h6 class="text-center mb-3">Opciones de Actualización</h6>
            <div class=" col-12 mb-2">
              <button id="btn_update_dolar_auto" class="w-100 btn btn-success text-center">
                <i class="bi bi-arrow-repeat"></i>
                <span class="p-2 ms-2">Sincronizar Tasa (Automático)</span>
              </button>
            </div>
            <div class=" col-12 mb-2">
              <button class="btn btn-warning text-center w-100" data-bs-toggle="modal" data-bs-target="#dolarUpdate" id="btnUpdate">
                <i class="bi bi-pencil-square"></i>
                <span class="p-2 ms-2">Establecer Tasa (Manual)</span>
              </button>
            </div>
          </li>
        </ul>
      </li>

      <li class="nav-item dropdown pe-3">

        <button class="nav-link nav-profile d-flex align-items-center pe-0" data-bs-toggle="dropdown">
          <span class="d-none d-md-block dropdown-toggle ps-2"><?= $_SESSION['dataUsuario']['nombre']." ".$_SESSION['dataUsuario']['apellido']; ?></span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?= $_SESSION['dataUsuario']['nombre']." ".$_SESSION['dataUsuario']['apellido']; ?></h6>
            <span><?= $_SESSION['dataUsuario']['nombreRolUsuario']; ?></span>
          </li>

          <li> <hr class="dropdown-divider"> </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="./mi_perfil.php">
              <i class="bi bi-person"></i>
              <span>Mi Pefil</span>
            </a>
          </li>

          <li> <hr class="dropdown-divider"> </li>

          <?php 
            // Lista de todos los permisos que pertenecen al Módulo de Configuración/Ajustes

            $permiso_ajustes = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['ajustes']);

            // se evalua que este rol tenga el acceso a esta vista

            if ($permiso_ajustes) { ?>
            
              <li>
                <a class="dropdown-item d-flex align-items-center" href="./configuracion.php">
                  <i class="bi bi-gear-fill"></i>
                  <span>Configuración</span>
                </a>
              </li>

          <?php }  ?>

          <li> <hr class="dropdown-divider"> </li>

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
<div class="msjFormSend"></div>
