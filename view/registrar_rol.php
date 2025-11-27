<?php 
session_start();
require_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
$id_rol = $_SESSION['id_rol']; // se obtiene el id del usuario
// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "../include/verificacion_primer_inicio_usuario.php";

$permisos_rol = rol_model::obtenerPermisosRolById($id_rol);

$r_rol = rol_model::sumaPermisoRol(['r_rol'], $permisos_rol);
$m_rol = rol_model::sumaPermisoRol(['m_rol'], $permisos_rol);
$l_rol = rol_model::sumaPermisoRol(['l_rol'], $permisos_rol);

// se evalua que este rol tenga el acceso a esta vista
if ($r_rol) {  ?>

  <!DOCTYPE html>
  <html lang="en">
    <head>
      <!-- titulo -->
      <title>Registro de Nuevo Rol</title>
      <?php 
        // se incluyen los meta datos 
        include_once "../include/meta_include.php"; 
        // se incluyen los estilos css y sus librerias a la vista
        include_once "../include/css_include.php"; 
      ?>
    </head>
    <body>

      <?php 
        // se incluye el header / encabezado a la vista
        include_once "../include/header.php";
        // se incluye el menu lateral a la vista 
        include_once "../include/sliderbar.php"; 
      ?>
      
      <main id="main" class="main">
        <div class="pagetitle">
          <a href="<?= $m_rol == 0 && $l_rol == 0 ? './' : './roles.php'; ?>" class="btn btn-outline-secondary shadow-sm mb-3">
            <i class="bi bi-chevron-left"></i> 
            <span><?= $m_rol == 0 && $l_rol == 0 ? 'Volver al Panel Principal' : 'Volver a la lista de roles'; ?></span>
          </a>
          <h1 class="display-5 fw-bold text-start mb-4">
            <i class="bi bi-person-badge-fill me-3 text-primary"></i> 
            Registro de Nuevo Rol 
          </h1>
        </div>

        <section class="section dashboard">
          <div class="card p-3">
            <div class="container-fluid row mb-3 p-3 justify-content-around">
              <!-- vistas de proveedores -->
              <div class="col-12 col-sm-12 col-md-12 mb-1 m-0 rounded-3">
                <form action="../controlador/rol.php" method="post" class="SendFormAjax" autocomplete="off" data-type-form="save">
                  <input type="hidden" name="modulo" value="Guardar">
                  
                  <div class="mb-3 text-start">
                    <h5 class="fw-bold mb-3 text-primary">
                      <i class="bi bi-person-badge me-2"></i> 
                      Nombre del rol
                      <span style="color:#f00;">*</span>
                    </h5>
                    <input type="text" name="nombre_rol" id="nombre_rol" class="form-control">
                  </div>

                  <div class="row justify-content-center">
                    <!-- modulo inventario -->

                    <div class="col-12 col-md-6 mb-3">
                      <h4 class="mb-3 text-primary"> <i class="bi bi-box-seam me-2"></i> Inventario</h4>
                      <hr>

                      <div class="row g-3 justify-content-center">
                        
                        <div class="col-12 mb-2">

                          <div class="accordion" id="acordeon_proveedores">
                            <div class="accordion-item shadow-sm">
                              <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#proveedoresCard" aria-expanded="false" aria-controls="proveedoresCard">
                                  Módulo Proveedores
                                </button>
                              </h2>

                              <div id="proveedoresCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_proveedores">
                                <div class="accordion-body p-0">
                                  <div class="mb-2 ms-4 pt-2">
                                    <input class="form-check-input vista" type="checkbox" id="vista_proveedores" value="proveedores">
                                    <label class="form-check-label" for="vista_proveedores">
                                      Acceso Total al Módulo de Proveedores
                                    </label>
                                  </div>

                                  <ul class="list-unstyled ps-4 pt-2 border-top mt-2">
                                    <li class="mb-1 border-bottom">
                                      <input class="proveedores" type="checkbox" value="<?= modeloPrincipal::encryptionId(1); ?>" name="r_proveedores">
                                      <span>Registrar Nuevos Proveedores</span>
                                    </li>
                                    <li class="mb-1 border-bottom">
                                      <input class="proveedores" type="checkbox" value="<?= modeloPrincipal::encryptionId(2); ?>" name="m_proveedores">
                                      <span>Modificar Información de Proveedores</span>
                                    </li>
                                    <li class="mb-1 border-bottom">
                                      <input class="proveedores" type="checkbox" value="<?= modeloPrincipal::encryptionId(3); ?>" name="l_proveedores">
                                      <span>Consultar Lista de Proveedores Registrados</span>
                                    </li>
                                    <li class="mb-1 border-bottom">
                                      <input class="proveedores" type="checkbox" value="<?= modeloPrincipal::encryptionId(4); ?>" name="h_proveedores">
                                      <span>Visualizar Historial de Compras</span>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 mb-2 p-2" id="modulo_productos">

                          <div class="accordion" id="acordeon_productos">
                            <div class="accordion-item shadow-sm">
                              <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#productosCard" aria-expanded="false" aria-controls="productosCard">
                                  Módulo Productos
                                </button>
                              </h2>
                              
                              <div id="productosCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_productos">
                                <div class="accordion-body p-0">
                                  <div class="form-check mb-2 ms-3 pt-2">
                                    <input class="form-check-input vista" type="checkbox" id="vista_productos" value="productos">
                                    <label class="form-check-label fw-bold" for="vista_productos">
                                      Acceso a la Vista del Módulo de Productos
                                    </label>
                                  </div>
                                      
                                  <ul class="list-group list-group-flush list-unstyled">
                                    <li class="fw-bold ps-2 bg-light">
                                      <i class="bi bi-folder-fill me-2 text-secondary"></i>
                                      Categorías:
                                    </li>
                                    
                                    <ul class="list-group list-group-flush ms-3 list-unstyled">
                                      
                                      <li class="mb-1 border-bottom">
                                        <input name="r_categoria" class="productos" value="<?= modeloPrincipal::encryptionId(5); ?>" type="checkbox">
                                        <span>Registrar Nuevas Categorías</span>
                                      </li>
                                      
                                      <li class=" mb-1 border-bottom">
                                        <input class="productos" type="checkbox" value="<?= modeloPrincipal::encryptionId(6); ?>" name="m_categoria">
                                        <span>Modificar Información de Categorías</span>
                                      </li>
                                      
                                      <li class="mb-1 border-bottom">
                                        <input class="productos" type="checkbox" value="<?= modeloPrincipal::encryptionId(7); ?>" name="l_categoria">
                                        <span>Consultar Lista de Categorías Registradas</span>
                                      </li>
                                    </ul>

                                    <li class="fw-bold ps-2  bg-light ">
                                      <i class="bi bi-box-fill me-2 text-secondary"></i>
                                      Presentaciones:
                                    </li>
                                      
                                    <ul class="list-group list-group-flush ms-3 list-unstyled">
                                      
                                      <li class="mb-1 border-bottom">
                                        <input name="r_presentacion" class="productos" value="<?= modeloPrincipal::encryptionId(8); ?>" type="checkbox">
                                        <span>Registrar Nuevas Presentaciones</span>
                                      </li>
                                      
                                      <li class="mb-1 border-bottom">
                                        <input class="productos" type="checkbox" value="<?= modeloPrincipal::encryptionId(9); ?>" name="m_presentacion">
                                        <span>Modificar Información de Presentaciones</span>
                                      </li>
                                      
                                      <li class="mb-1 border-bottom">
                                        <input class="productos" type="checkbox" value="<?= modeloPrincipal::encryptionId(10); ?>" name="l_presentacion">
                                        <span>Consultar Lista de Presentaciones Registradas</span>
                                      </li>
                                    </ul>


                                    <li class="fw-bold ps-2  bg-light ">
                                      <i class="bi bi-tags-fill me-2 text-secondary"></i>
                                      Marcas:
                                    </li>

                                    <ul class="list-group list-group-flush ms-3 list-unstyled">
                                      
                                      <li class="mb-1 border-bottom">
                                        <input name="r_marca" class="productos" value="<?= modeloPrincipal::encryptionId(11); ?>" type="checkbox">
                                        <span>Registrar Nuevas Marcas</span>
                                      </li>
                                      
                                      <li class="mb-1 border-bottom">
                                        <input class="productos" type="checkbox" value="<?= modeloPrincipal::encryptionId(12); ?>" name="m_marca">
                                        <span>Modificar Información de Marcas</span>
                                      </li>
                                      
                                      <li class="mb-1 border-bottom">
                                        <input class="productos" type="checkbox" value="<?= modeloPrincipal::encryptionId(13); ?>" name="l_marca">
                                        <span>Consultar Lista de Marcas Registradas</span>
                                      </li>
                                    </ul>

                                    <li class="fw-bold ps-2  bg-light ">
                                      <i class="bi bi-bag-fill me-2 text-secondary"></i>
                                      Gestión de Productos:
                                    </li>

                                    <ul class="list-group list-group-flush ms-3 list-unstyled">
                                      
                                      <li class="mb-1 border-bottom">
                                        <input name="r_productos" class="productos" value="<?= modeloPrincipal::encryptionId(14); ?>" type="checkbox">
                                        <span>Registrar Nuevos Productos</span>
                                      </li>
                                      
                                      <li class="mb-1 border-bottom">
                                        <input name="l_productos" class="productos" value="<?= modeloPrincipal::encryptionId(15); ?>" type="checkbox">
                                        <span>Consultar Lista de Productos Registrados</span>
                                      </li>
                                    </ul>
                                    
                                    <li class="fw-bold ps-2 bg-light">
                                      <i class="bi bi-box-arrow-in-right me-2 text-secondary"></i>
                                      Entrada de Productos:
                                    </li>

                                    <ul class="list-group list-group-flush ms-3 list-unstyled">
                                      
                                      <li class="mb-1 border-bottom">
                                        <input name="e_productos" class="productos" value="<?= modeloPrincipal::encryptionId(16); ?>" type="checkbox">
                                        <span>Registrar Entrada de Productos</span>
                                      </li>
                                      
                                      <li class="mb-1 border-bottom">
                                        <input name="e_productos" class="productos" value="<?= modeloPrincipal::encryptionId(17); ?>" type="checkbox">
                                        <span>Consultar Lista de Entradas de Productos</span>
                                      </li>
                                    </ul>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- modulo ventas y servicios -->

                    <div class="col-12 col-md-6 mb-1">
                      <h4 class="mb-3 text-primary">
                        <i class="bi bi-currency-dollar"></i> Ventas |
                        <i class="bi bi-fork-knife"></i> Menú / Servicios
                      </h4>

                      <hr>

                      <div class="row g-3 justify-content-center">

                        <div class="col-12 mb-2"> 
                          <div class="accordion" id="acordeon_ventas">
                            <div class="accordion-item shadow-sm">
                              <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ventasCard" aria-expanded="true" aria-controls="ventasCard">
                                  Módulo Ventas
                                </button>
                              </h2>

                              <div id="ventasCard" class="accordion-collapse collapse " data-bs-parent="#acordeon_ventas">
                                <div class="accordion-body p-0">
                                  <div class="mb-2 ms-4 pt-2">
                                    <input class="vista" type="checkbox" value="Ventas">
                                    <label class="form-check-label" >
                                      Acceso Total al Módulo de Ventas
                                    </label>
                                  </div>
                                  
                                  <ul class="list-unstyled ps-4 pt-2 border-top mt-2">

                                    <li class="mb-1 border-bottom">
                                      <input name="g_venta" class="Ventas" value="<?= modeloPrincipal::encryptionId(18); ?>" type="checkbox">
                                      <span>Generar Nuevas Ventas</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="l_venta" class="Ventas" value="<?= modeloPrincipal::encryptionId(20); ?>" type="checkbox">
                                      <span>Consultar Lista de Ventas Realizadas</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="d_venta" class="Ventas" value="<?= modeloPrincipal::encryptionId(19); ?>" type="checkbox">
                                      <span>Visualizar Detalles de Ventas</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="f_venta" class="Ventas" value="<?= modeloPrincipal::encryptionId(21); ?>" type="checkbox">
                                      <span>Acceder a Facturas de Ventas</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="est_venta" class="Ventas" value="<?= modeloPrincipal::encryptionId(22); ?>" type="checkbox">
                                      <span>Consultar Estadísticas de Ventas</span>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 mb-2"> 

                          <div class="accordion" id="acordeon_servicios">
                            <div class="accordion-item shadow-sm">
                              <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#serviciosCard" aria-expanded="true" aria-controls="serviciosCard">
                                  Módulo Servicios
                                </button>
                              </h2>
                              
                              <div id="serviciosCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_servicios">
                                <div class="accordion-body p-0">
                                  <div class="mb-2 ms-4 pt-2">
                                    <input class="vista" type="checkbox" value="servicio">
                                    <label class="form-check-label">
                                      Acceso Total al Módulo de Servicios
                                    </label>
                                  </div>
                                  
                                  <ul class="list-unstyled ps-4 pt-2 border-top mt-2">
                                    <li class="mb-1 border-bottom">
                                      <input name="r_servicio" class="servicio" value="<?= modeloPrincipal::encryptionId(23); ?>" type="checkbox">
                                      <span>Registrar Nuevos Servicios</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="m_servicio" class="servicio" value="<?= modeloPrincipal::encryptionId(24); ?>" type="checkbox">
                                      <span>Modificar Información de Servicios</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="l_servicio" class="servicio" value="<?= modeloPrincipal::encryptionId(25); ?>" type="checkbox">
                                      <span>Consultar Lista de Servicios Registrados</span>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                        </div>

                      </div>

                    </div>

                    <!-- modulo usuario -->
                    
                    <div class="col-12 col-md-6 mb-1">
                      <h4 class="mb-3 text-primary"><i class="bi bi-people-fill"></i> Usuarios</h4>

                      <hr> 
                      <div class="row justify-content-center">
                        
                        <div class="col-12 mb-1 p-2">
                          <div class="accordion" id="acordeon_cliente">
                            <div class="accordion-item shadow-sm">
                              <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#clienteCard" aria-expanded="true" aria-controls="clienteCard">
                                  Módulo Cliente
                                </button>
                              </h2>

                              <div id="clienteCard" class="accordion-collapse collapse " data-bs-parent="#acordeon_cliente">
                                <div class="accordion-body p-0">
                                  <div class="mb-2 ms-4 pt-2">
                                    <input id="cliente" class="vista" type="checkbox" value="cliente">
                                    <label class="form-check-label">
                                      Acceso Total al Módulo de Clientes
                                    </label>
                                  </div>

                                  <ul class="list-unstyled ps-4 pt-2 border-top mt-2">
                                      
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="r_cliente" class="cliente" value="<?= modeloPrincipal::encryptionId(26); ?>" type="checkbox">
                                      <span>Registrar Nuevos Clientes</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="m_cliente" class="cliente" value="<?= modeloPrincipal::encryptionId(27); ?>" type="checkbox">
                                      <span>Modificar Información de Clientes</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="l_cliente" class="cliente" value="<?= modeloPrincipal::encryptionId(28); ?>" type="checkbox">
                                      <span>Consultar Lista de Clientes Registrados</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="h_cliente" class="cliente" value="<?= modeloPrincipal::encryptionId(29); ?>" type="checkbox">
                                      <span>Visualizar Historial de Clientes</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="f_cliente" class="cliente" value="<?= modeloPrincipal::encryptionId(30); ?>" type="checkbox">
                                      <span>Acceder a Facturas de Clientes</span>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>

                        <div class="col-12 mb-1 p-2">
                          <div class="accordion" id="acordeon_empleado">
                            <div class="accordion-item shadow-sm">
                              <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#empleadoCard" aria-expanded="true" aria-controls="empleadoCard">
                                  Módulo Empleados
                                </button>
                              </h2>
                              <div id="empleadoCard" class="accordion-collapse collapse" data-bs-parent="#accordionEmpleado">
                                <div class="accordion-body p-0">
                                  <div class="mb-2 ms-4 pt-2">
                                    <input id="empleado" class="vista" type="checkbox" value="empleado">
                                    <label class="form-check-label">
                                      Acceso Total al Módulo de Empleados
                                    </label>
                                  </div>

                                  <ul class="list-unstyled ps-4 pt-2 border-top mt-2">
                                      
                                    <li class="mb-1 border-bottom">
                                      <input name="r_empleado" class="empleado" value="<?= modeloPrincipal::encryptionId(31); ?>" type="checkbox">
                                      <span>Registrar Nuevos Empleados</span>
                                    </li>

                                    <li class="mb-1 border-bottom">
                                      <input name="m_empleado" class="empleado" value="<?= modeloPrincipal::encryptionId(32); ?>" type="checkbox">
                                      <span>Modificar Información de Empleados</span>
                                    </li>

                                    <li class="mb-1 border-bottom">
                                      <input name="l_empleado" class="empleado" value="<?= modeloPrincipal::encryptionId(33); ?>" type="checkbox">
                                      <span>Consultar Lista de Empleados Registrados</span>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 mb-1 p-2">
                          <div class="accordion" id="acordeon_roles">
                            <div class="accordion-item shadow-sm">
                              <h2 class="accordion-header"> 
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rolesCard" aria-expanded="true" aria-controls="rolesCard">
                                  Módulo Roles
                                </button>
                              </h2>
                              
                              <div id="rolesCard" class="accordion-collapse collapse " data-bs-parent="#accordionRoles">
                                <div class="accordion-body p-0">
                                  <div class="mb-2 ms-4 pt-2">
                                    <input class="vista" type="checkbox" value="roles">
                                    <label>
                                      Acceso Total al Módulo de Roles
                                    </label>
                                  </div>

                                  <ul class="list-unstyled ps-4 pt-2 border-top mt-2">
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="r_rol" class="roles" value="<?= modeloPrincipal::encryptionId(34); ?>" type="checkbox">
                                      <span>Registrar Nuevos Roles</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="m_rol" class="roles" value="<?= modeloPrincipal::encryptionId(35); ?>" type="checkbox">
                                      <span>Modificar Información de Roles</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="l_rol" class="roles" value="<?= modeloPrincipal::encryptionId(36); ?>" type="checkbox">
                                      <span>Consultar Lista de Roles Registrados</span>
                                    </li>
                                      
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>

                      </div>
                    </div>

                    <!-- modulo consfiguración -->

                    <div class="col-12 col-md-6 mb-1">

                      <h4 class="mb-3 text-primary"><i class="bi bi-gear-fill"></i> Configuración General</h4>
                      <hr>
                      <div class="row justify-content-center">
                        
                        <div class="col-12 mb-1 p-2">
                          <div class="accordion" id="acordeon_ajustes_del_sistema">
                            <div class="accordion-item shadow-sm">
                              <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ajustesCard" aria-expanded="false" aria-controls="ajustesCard">
                                  Ajustes del Sistema
                                </button>
                              </h2>

                              <div id="ajustesCard" class="accordion-collapse collapse " data-bs-parent="#acordeon_ajustes_del_sistema">
                                <div class="accordion-body p-0">
                                  <div class="mb-2 ms-4 pt-2">
                                    <input class="vista" type="checkbox" value="ajustes_sistema">
                                    <label>  Acceso Total a los Ajustes del Sistema </label>
                                  </div>

                                  <ul class="list-unstyled ps-4 pt-2 border-top mt-2">
                                            
                                    <li class="mb-1 border-bottom">
                                      <input name="m_cant_pregunta_seguridad" class="ajustes_sistema" value="<?= modeloPrincipal::encryptionId(37); ?>" type="checkbox">
                                      <span>Modificar Cantidad de Preguntas de Seguridad</span>
                                    </li>

                                    <li class="mb-1 border-bottom">
                                      <input name="m_tiempo_sesion" class="ajustes_sistema" value="<?= modeloPrincipal::encryptionId(38); ?>" type="checkbox">
                                      <span>Modificar Tiempo de Inactividad de Sesión</span>
                                    </li>

                                    <li class="mb-1 border-bottom">
                                      <input name="m_cant_caracteres" class="ajustes_sistema" value="<?= modeloPrincipal::encryptionId(39); ?>" type="checkbox">
                                      <span>Modificar Cantidad de Caracteres Permitidos</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="m_cant_simbolos" class="ajustes_sistema" value="<?= modeloPrincipal::encryptionId(40); ?>" type="checkbox">
                                      <span>Modificar Cantidad de Símbolos Permitidos</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="m_cant_num" class="ajustes_sistema" value="<?= modeloPrincipal::encryptionId(41); ?>" type="checkbox">
                                      <span>Modificar Cantidad de Números Permitidos</span>
                                    </li>
                                    
                                    <li class="mb-1 border-bottom">
                                      <input name="intentos_inicio_sesion" class="ajustes_sistema" value="<?= modeloPrincipal::encryptionId(42); ?>" type="checkbox">
                                      <span>Modificar Intentos de Inicio de Sesión</span>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                        

                        <div class="col-12 mb-1 p-2">
                          <div class="accordion" id="acordeon_bitacora">
                            <div class="accordion-item shadow-sm">
                              <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bitacoraCard" aria-expanded="true" aria-controls="rolesCard">
                                  Bitácora
                                </button>
                              </h2>
                              
                              <div id="bitacoraCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_bitacora">
                                <div class="accordion-body p-0">
                                  <div class="mb-2 ms-4 pt-2">
                                    <input class="vista" type="checkbox" value="bitacora">
                                    <label>Acceso Total a la Bitácora</label>
                                  </div>


                                  <ul class="list-unstyled ps-4 pt-2 border-top mt-2">
                                    <li class="mb-1 border-bottom">
                                      <input name="v_bitacora" class="bitacora" value="<?= modeloPrincipal::encryptionId(43); ?>" type="checkbox">
                                      <span>Consultar Registros de la Bitácora</span>
                                    </li>
                                    <li class="mb-1 border-bottom">
                                      <input name="m_bitacora" class="bitacora" value="<?= modeloPrincipal::encryptionId(44); ?>" type="checkbox">
                                      <span>Consultar Movimientos de un Usuario en la Bitácora</span>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>

                      </div>
                    </div>

                  </div>

                  <div class="form-group">
                      <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
                  </div>
                  
                  <div class="text-center">
                    <button type="submit" class="btn btn-success">Registrar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <script type="text/javascript" src="./js/funcion_seleccionar_casillas.js"></script>
      <script type="text/javascript">
        setTimeout(() => {
          evaluar_casillas();
        }, 300);
      </script>

      <?php 
        include_once "./modal/plantillaModalCustom.php"; 
        modalCustom ();
        // se incluye el footer / pie de pagina a la vista
        include_once "../include/footer.php";
        // se incluyen los script de javascript a la vista 
        include_once "../include/scripts_include.php"; 
      
        config_model::verificar_actualizacion_configuracion();
      ?>
    </body>
  </html>
<?php }else{
  // se registran las acciones del usuario en la bitacora y es redirijido al inicio
  bitacora::intento_de_acceso_a_vista_sin_permisos("registro de roles");
}