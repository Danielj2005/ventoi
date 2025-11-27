<?php 

$permiso_proveedor = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['proveedor']);
$permiso_productos = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['producto']['productos']);
$permiso_entrada_productos = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['producto']['entrada']);

$permiso_servicio = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['servicio']);

$permiso_modulo_venta = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['venta']);
$permiso_modulo_cliente = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['cliente']);
$permiso_modulo_usuario = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['usuario']);

$permiso_modulo_rol = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['rol']);
$permiso_ajustes = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['ajustes']);
$permiso_bitacora = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['bitacora']);

if($_SESSION['dataUsuario']["primerInicio"] == '0') {  ?>
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <!-- apartado de página principal -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="./">
          <i class="bi bi-speedometer2"></i>
          <span>Panel de Control</span>
        </a>
      </li>
      
      <?php if ($permiso_productos || $permiso_proveedor) {  ?>

          <li class="nav-item">

            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-box-seam-fill"></i>
              <span>Inventario</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

              <?php if ($permiso_productos) : ?>

                <li>
                  <a href="./gestion_productos.php">
                    <i class="bi bi-circle"></i>
                    <span>Gestión de Productos</span>
                  </a>
                </li>
                  
              <?php endif; if ($permiso_entrada_productos): ?>

                <li>
                  <a href="./entrada_de_productos.php">
                    <i class="bi bi-circle"></i>
                    <span>Registro de Compras</span>
                  </a>
                </li>

              <?php endif; if ($permiso_proveedor) : ?>

                <li>
                  <a href="./proveedor.php">
                    <i class="bi bi-circle"></i>
                    <span>Gestión de Proveedores</span>
                  </a>
                </li>

              <?php endif; ?>
            </ul>
          </li>

      <?php } if ($permiso_servicio) { ?>
        
          <li class="nav-item">
            <a href="gestion_servicios.php" class="nav-link collapsed">
              <i class="bi bi-person-workspace"></i>
              <span> Gestión de Servicios</span>
            </a>
          </li>

      <?php }  if ($permiso_modulo_venta) { ?>
        
          <li class="nav-item">
            
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-currency-dollar"></i>
              <span>Ventas</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

              <?php if (array_key_exists( "g_venta", $_SESSION['permisosRol'] )) {  ?>

                <li>
                  <a href="./generar_venta.php">
                    <i class="bi bi-circle"></i>
                    <span>Generar venta</span>
                  </a>
                </li>

              <?php } if (array_key_exists( "l_venta", $_SESSION['permisosRol'] ) || array_key_exists( "d_venta", $_SESSION['permisosRol'] ) || array_key_exists( "f_venta", $_SESSION['permisosRol'] )) {  ?>

                <li>
                  <a href="./venta.php">
                    <i class="bi bi-circle"></i>
                    <span>Historial de Ventas</span>
                  </a>
                </li>

              <?php } if (array_key_exists( "est_venta", $_SESSION['permisosRol'])) {  ?>

                <li>
                  <a href="./estadisticas_generales.php">
                    <i class="bi bi-graph-up"></i>
                    <span>Análisis de Ventas</span>
                  </a>
                </li>

              <?php } ?>

            </ul>
          </li>

      <?php } if ($permiso_modulo_cliente || $permiso_modulo_usuario || $permiso_modulo_rol) { ?>
        
          <li class="nav-item">

            <a class="nav-link collapsed" data-bs-target="#user-list" data-bs-toggle="collapse" href="#">
              <i class="bi bi-people-fill"></i>
              <span>Usuarios</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="user-list" class="nav-content collapse" data-bs-parent="#sidebar-nav">
              
              <?php if ($permiso_modulo_cliente): ?>

                  <!-- modulo de clientes -->
                  <li class="nav-item">
                    <a class="nav-link collapsed" href="./cliente.php">
                      <i class="bi bi-circle"></i>
                      <span>Clientes</span>
                    </a>
                  </li>

              <?php endif;  if ($permiso_modulo_usuario): ?>

                <li class="nav-item">
                  <a class="nav-link collapsed" href="./empleados.php">
                    <i class="bi bi-circle"></i>
                    <span>Empleados</span>
                  </a>
                </li>

              <?php endif; if ($permiso_modulo_rol): ?>

                  <li>
                    <a href="./roles.php">
                      <i class="bi bi-circle"></i>
                      <span>Gestión de Roles</span>
                    </a>
                  </li>

              <?php endif; ?>
            </ul>
          </li>

      <?php } ?>

      <!-- apartado del perfil de usuario  -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="./mi_perfil.php">
          <i class="bi bi-person-fill"></i>
          <span>Mi Perfil</span>
        </a>
      </li>

      <?php if ($permiso_ajustes || $permiso_bitacora) { ?>

          <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#setting-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-gear-fill"></i>
              <span>Configuración General</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="setting-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
              
              <?php if ($permiso_ajustes) {  ?>
                
                <li>
                  <a href="./configuracion.php">
                    <i class="bi bi-circle"></i>
                    <span>Ajustes del Sistema</span>
                  </a>
                </li>

              <?php } if ($permiso_bitacora) {  ?>
                
                <li>
                  <a href="./bitacora.php">
                    <i class="bi bi-circle"></i>
                    <span>Bitácora</span>
                  </a>
                </li>
                
              <?php } ?>
            </ul>
          </li>

      <?php } ?>

      <!-- apartado de ayuda  -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#ayuda-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-question-circle-fill"></i>
          <span>Soporte y Documentación</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="ayuda-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="./manuales/MANUAL_DE_USUARIO_CHINITA.pdf" target="_blank">
              <i class="bi bi-book"></i>
              <span>Manual de Usuario</span>
            </a>
          </li>

          <li>
            <a href="./manuales/MANUAL_DE_INSTALACION_CHINITA.pdf" target="_blank">
              <i class="bi bi-wrench"></i>
              <span>Guía de Instalación Técnica</span>
            </a>
          </li>
          <li>
            <a href="./manuales/MANUAL_DE_SISTEMA_CHINITA.pdf" target="_blank">
              <i class="bi bi-laptop"></i>
              <span>Manual de Referencia</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <button class="nav-link collapsed btn-exit-system">
          <i class="bi bi-box-arrow-right"></i>
          <span>Cerrar Sesión</span>
        </button>
      </li>
    </ul>
  </aside>
<?php } ?>