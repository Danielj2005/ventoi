<?php
session_start();
include_once "../../../modelo/modeloPrincipal.php"; 
include_once "../../../modelo/rol_model.php"; 
include_once "../../../include/obtener_icono_permisos_include.php"; 

$id_rol = modeloPrincipal::decryptionId($_POST['id']);

$rol = mysqli_fetch_assoc(modeloPrincipal::consultar("SELECT nombre, estado FROM rol WHERE id_rol = $id_rol"));

$nombre = $rol['nombre'];
$estadoRol = $rol['estado'];

// cantidad de vistas de inventario 
$permisos_rol = rol_model::obtenerPermisosRolById($id_rol);

$proveedor = rol_model::sumaPermisoRol(['r_proveedores', 'm_proveedores', 'l_proveedores', 'h_proveedores'], $permisos_rol);
$proveedor_total = $proveedor['r_proveedores'] + $proveedor['m_proveedores'] + $proveedor['l_proveedores'] + $proveedor['h_proveedores'];



$categoria = rol_model::sumaPermisoRol(['r_categoria','m_categoria','l_categoria'], $permisos_rol);
$categoria_total = $categoria['r_categoria'] + $categoria['m_categoria'] + $categoria['l_categoria'];

$presentacion = rol_model::sumaPermisoRol(['r_presentacion','m_presentacion','l_presentacion'], $permisos_rol);
$presentacion_total = $presentacion['r_presentacion'] + $presentacion['m_presentacion'] + $presentacion['l_presentacion'];

$marca = rol_model::sumaPermisoRol(['r_marca','m_marca','l_marca'], $permisos_rol);
$marca_total = $marca['r_marca'] + $marca['m_marca'] + $marca['l_marca'];

$entrada = rol_model::sumaPermisoRol(['r_entrada','l_entrada'], $permisos_rol);
$entrada_total = $entrada['r_entrada'] + $entrada['l_entrada'];

$productos = rol_model::sumaPermisoRol(['r_productos', 'l_productos'], $permisos_rol);
$productos_total = $categoria_total + $presentacion_total + $marca_total + $entrada_total + $productos['r_productos'] + $productos['l_productos'];

// cantidad de vistas de venta
$venta = rol_model::sumaPermisoRol(['g_venta','d_venta','f_venta','l_venta','est_venta'], $permisos_rol);
$venta_total = $venta["g_venta"] + $venta["d_venta"] + $venta["f_venta"] + $venta["l_venta"] + $venta["est_venta"];


// cantidad de vistas de menu
$menu = rol_model::sumaPermisoRol(['r_servicio','m_servicio','l_servicio'], $permisos_rol);
$menu_total = $menu['r_servicio'] + $menu['m_servicio'] + $menu['l_servicio'];


// cantidad de vistas de usuario
$cliente = rol_model::sumaPermisoRol(['r_cliente','m_cliente','l_cliente','h_cliente','f_cliente'], $permisos_rol);
$cliente_total = $cliente["r_cliente"] + $cliente["m_cliente"] + $cliente["l_cliente"] + $cliente["h_cliente"] + $cliente["f_cliente"];

$empleado = rol_model::sumaPermisoRol( ['r_empleado','m_empleado','l_empleado'], $permisos_rol);
$empleado_total = $empleado['r_empleado'] + $empleado['m_empleado'] + $empleado['l_empleado'];

$rol = rol_model::sumaPermisoRol(['r_rol','m_rol','l_rol'], $permisos_rol);
$rol_total = $rol['r_rol'] + $rol['m_rol'] + $rol['l_rol'];

// cantidad de vistas de configuración
$ajustes = rol_model::sumaPermisoRol(['m_cant_pregunta_seguridad','m_tiempo_sesion','m_cant_caracteres','m_cant_simbolos','m_cant_num','intentos_inicio_sesion'], $permisos_rol);
$ajustes_total = $ajustes["m_cant_pregunta_seguridad"] + $ajustes["m_tiempo_sesion"] + $ajustes["m_cant_caracteres"] + $ajustes["m_cant_simbolos"] + $ajustes["m_cant_num"] + $ajustes["intentos_inicio_sesion"];

$bitacora = rol_model::sumaPermisoRol(['v_bitacora','m_bitacora'], $permisos_rol);
$bitacora_total = $bitacora['v_bitacora'] + $bitacora['m_bitacora'];

?>

<div class="container-fluid p-3">
    
    <div class="row align-items-center mb-4 pb-2 border-bottom">
        
        <div class="col-12 col-md-6 text-center text-md-start mb-2 mb-md-0">
            <h5 class="fw-bold mb-0 text-primary">
                <i class="bi bi-person-badge me-2"></i>
                Rol: <?= $nombre; ?>
            </h5>
        </div>
        
        <div class="col-12 col-md-6 text-center text-md-end">
            <h5 class="fw-bold mb-0">
                Estado: 
                <span class="badge rounded-pill fs-6 <?= ($estadoRol == 1) ? 'bg-success' : 'bg-danger' ?>">
                    <?= ($estadoRol == 1) ? 'Activo' : 'Inactivo' ?>
                </span>
            </h5>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-12">
            <p class="fw-bold mb-2 text-secondary d-flex align-items-center">
                <i class="bi bi-info-circle me-2"></i>
                Leyenda de Acceso:
            </p>
            <ul class="list-unstyled d-flex flex-wrap gap-4 small ps-3">
                <li>
                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                    Acceso Total al Módulo
                </li>
                <li>
                    <i class="bi bi-dash-circle-fill text-primary me-1"></i>
                    Acceso Parcial (Lectura/Escritura)
                </li>
                <li>
                    <i class="bi bi-x-circle-fill text-danger me-1"></i>
                    Acceso Denegado (Sin Permiso)
                </li>
            </ul>
        </div>
    </div>

    <hr class="my-0">
    
    <div class="row mt-3">
        <div class="col-12">
            <p class="text-muted small">Desglose de permisos por módulo:</p>
        </div>
    </div>

    <div class="row g-3">
        
        <?php if ($proveedor_total > 0 || $productos_total > 0 ) { ?>

            <div class="col-12 col-md-6 mb-1 ">
    
                <h4 class="mb-3 text-primary"> <i class="bi bi-box-seam me-2"></i> Inventario</h4>
                <hr>
                <div class="row g-3 justify-content-center">
                    <?php if ($proveedor_total > 0) { ?>
    
                        <div class="col-12 mb-2">
                            <div class="accordion" id="acordeon_proveedores">
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#proveedoresCard" aria-expanded="false" aria-controls="proveedoresCard">
                                            Módulo Proveedores
                                            <span class="ms-auto me-2"> <i class="bi <?= obtenerIconoPermisos($proveedor_total, 4) ?>"></i> </span>
                                        </button>
                                    </h2>
    
                                    <div id="proveedoresCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_proveedores">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">
                                                <?php if ($proveedor_total == 4 ) { ?>
                                                    <li class="list-group-item bg-light border-0">
                                                        <strong class="text-success">Acceso Total al Módulo de Proveedores</strong>
                                                    </li>
                                                <?php } ?>
                                                
                                                <ul class="list-group list-group-flush">

                                                    <?php if ($proveedor['r_proveedores'] == 1) { ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Registrar Nuevos Proveedores
                                                            <i class="bi bi-check-circle-fill text-success"></i>
                                                        </li>
                                                    <?php }
                                                    if ($proveedor['l_proveedores'] == 1) { ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Consultar Lista de Proveedores Registrados
                                                            <i class="bi bi-check-circle-fill text-success"></i>
                                                        </li>
                                                    <?php }
                                                    if ($proveedor['m_proveedores'] == 1) { ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Modificar Información de Proveedores
                                                            <i class="bi bi-check-circle-fill text-success"></i>
                                                        </li>
                                                    <?php } if ($proveedor['h_proveedores'] == 1) { ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Visualizar Historial de Compras
                                                            <i class="bi bi-check-circle-fill text-success"></i>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    <?php } if ($productos_total > 0) { ?>
    
                        <div class="col-12 mb-2">
                            <div class="accordion" id="acordeon_productos">
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#productosCard" aria-expanded="false" aria-controls="productosCard">
                                            Módulo Productos
                                            <span class="ms-auto me-2">
                                                <i class="bi <?= obtenerIconoPermisos($productos_total, 13) ?>"></i>
                                            </span>
                                        </button>
                                    </h2>
    
                                    <div id="productosCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_productos">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush list-unstyled">
                                                <?php if ($productos_total == 13 ) { ?>
                                                    <li class="list-group-item bg-light border-0">
                                                        <strong class="text-success">Acceso Total al Módulo de Productos</strong>
                                                    </li>
                                                <?php } ?>
                                                
                                                <?php if ($categoria_total > 0) { ?>
                                                    <li class="fw-bold ps-2  bg-light ">
                                                        <i class="bi bi-folder-fill me-2 text-secondary"></i>
                                                        Categorías:
                                                    </li>

                                                    <ul class="list-group list-group-flush ms-3">
                                                        <?php if ($categoria['r_categoria'] == 1) { ?>

                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Registrar Nuevas Categorías
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>

                                                        <?php } ?>
                                                        <?php if ($categoria['m_categoria'] == 1) { ?>

                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Modificar Información de Categorías
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>

                                                        <?php } ?>
                                                        <?php if ($categoria['l_categoria'] == 1) { ?>

                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Consultar Lista de Categorías
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>

                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
    
                                                <?php if ($presentacion_total > 0) { ?>
                                                    <li class="fw-bold ps-2  bg-light ">
                                                        <i class="bi bi-box-fill me-2 text-secondary"></i>
                                                        Presentaciones:
                                                    </li>
                                                    <ul class="list-group list-group-flush ms-3">
                                                        <?php if ($presentacion['r_presentacion'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Registrar Nuevas Presentaciones
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } if ($presentacion['m_presentacion'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Modificar Información de Presentaciones
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } if ($presentacion['l_presentacion'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Consultar Lista de Presentaciones Registradas
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
    
                                                <?php if ($marca_total > 0) { ?>
                                                    <li class="fw-bold ps-2  bg-light ">
                                                        <i class="bi bi-tags-fill me-2 text-secondary"></i>
                                                        Marcas:
                                                    </li>
                                                    <ul class="list-group list-group-flush ms-3">
                                                        <?php if ($marca['r_marca'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Registrar Nuevas Marcas
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } if ($marca['m_marca'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Modificar Información de Marcas
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } if ($marca['l_marca'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Consultar Lista de Marcas Registradas
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
    
                                                <?php if ($productos['r_productos'] == 1 || $productos['l_productos'] == 1) { ?>
                                                    <li class="fw-bold ps-2  bg-light ">
                                                        <i class="bi bi-bag-fill me-2 text-secondary"></i>
                                                        Gestión de Productos:
                                                    </li>
                                                    <ul class="list-group list-group-flush ms-3">
                                                        <?php if ($productos['r_productos'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Registrar Nuevos Productos
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } if ($productos['l_productos'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Consultar Lista de Productos Registrados
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                                
                                                <?php if ($entrada_total > 0) { ?>
                                                    <li class="fw-bold ps-2  bg-light ">
                                                        <i class="bi bi-box-arrow-in-right me-2 text-secondary"></i>
                                                        Entrada de Productos:
                                                    </li>
                                                    <ul class="list-group list-group-flush ms-3">
                                                        <?php if ($entrada['r_entrada'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Registrar Entrada de Productos
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } if ($entrada['l_entrada'] == 1) { ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Consultar Lista de Entradas de Productos
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    <?php } ?>
                </div>

            </div>

        <?php } if ($venta_total > 0 || $menu_total > 0) { ?>

            <div class="col-12 col-md-6 mb-1">
                <h4 class="mb-3 text-primary">
                    <?php if ($venta_total > 0) { ?>
                        <i class="bi bi-currency-dollar"></i> Ventas |
                    <?php } if ($menu_total > 0) { ?>
                        <i class="bi bi-fork-knife"></i> Menú / Servicios
                    <?php } ?>
                </h4>

                <hr>
                    
                <div class="row g-3 justify-content-center">

                    <?php if ($venta_total > 0) { ?>

                        <div class="col-12 mb-2">      
                            <div class="accordion" id="acordeon_ventas">
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ventasCard" aria-expanded="false" aria-controls="ventasCard">
                                            Módulo Ventas
                                            <span class="ms-auto"><i class="bi <?= obtenerIconoPermisos($venta_total, 5) ?>"></i></span>
                                        </button>
                                    </h2>

                                    <div id="ventasCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_ventas">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">
                                                <?php if ($venta_total == 5) { ?>
                                                    <li class="list-group-item bg-light border-0">
                                                        <strong class="text-success">Acceso Total al Módulo de Ventas</strong>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($venta['g_venta'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Generar Nuevas Ventas
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($venta['l_venta'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Consultar Lista de Ventas Realizadas
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($venta['d_venta'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Visualizar Detalles de Ventas
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($venta['f_venta'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Acceder a Facturas de Ventas
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($venta['est_venta'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Consultar Estadísticas/Reportes de Ventas
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } if ($menu_total > 0) { ?>

                        <div class="col-12 mb-2">
                            <div class="accordion" id="acordeon_servicios">
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#serviciosCard" aria-expanded="false" aria-controls="serviciosCard">
                                            Módulo Servicios
                                            <span class="ms-auto"><i class="bi <?= obtenerIconoPermisos($menu_total, 3) ?>"></i></span>
                                        </button>
                                    </h2>

                                    <div id="serviciosCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_servicios">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">
                                                <?php if ($menu_total == 3) { ?>
                                                    <li class="list-group-item bg-light border-0">
                                                        <strong class="text-success">Acceso Total al Módulo de Servicios</strong>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($menu['r_servicio'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Registrar Nuevos Servicios
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($menu['l_servicio'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Lista de Servicios Registrados
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($menu['m_servicio'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar Información de Servicios
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>
            </div>       

        <?php }  if ($cliente_total > 0 || $empleado_total > 0 || $rol_total > 0) { ?>

            <div class="col-12 col-md-6 mb-1">
                <h4 class="mb-3 text-primary"><i class="bi bi-people-fill"></i> Usuarios</h4>

                <hr>

                <div class="row justify-content-center">

                    <?php if ($cliente_total > 0) { ?>

                        <div class="col-12 mb-1 p-2">
                            <div class="accordion" id="acordeon_cliente">
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#clienteCard" aria-expanded="false" aria-controls="clienteCard">
                                            Módulo Clientes
                                            <span class="ms-auto"><i class="bi <?= obtenerIconoPermisos($cliente_total, 5) ?>"></i></span>
                                        </button>
                                    </h2>

                                    <div id="clienteCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_cliente">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">
                                                
                                                <?php if ($cliente_total == 5) { ?>
                                                    <li class="list-group-item bg-light border-0">
                                                        <strong class="text-success">Acceso Total al Módulo de Clientes</strong>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($cliente['r_cliente'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Registrar Nuevos Clientes
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($cliente['l_cliente'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Consultar Lista de Clientes Registrados
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($cliente['m_cliente'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar Información de Clientes
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($cliente['h_cliente'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Visualizar Historial de Compras
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>
                                                
                                                <?php if ($cliente['f_cliente'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Visualizar Facturas de Compras
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    <?php if ($empleado_total >= 1) { ?>
                        
                        <div class="col-12 mb-1 p-2">
                            <div class="accordion" id="acordeon_empleado">
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#empleadoCard" aria-expanded="false" aria-controls="empleadoCard">
                                            Módulo Empleados
                                            <span class="ms-auto"><i class="bi <?= obtenerIconoPermisos($empleado_total, 3) ?>"></i></span>
                                        </button>
                                    </h2>

                                    <div id="empleadoCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_empleado">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">
                                                
                                                <?php if ($empleado_total == 3) { ?>
                                                    <li class="list-group-item bg-light border-0">
                                                        <strong class="text-success">Acceso Total al Módulo de Empleados</strong>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($empleado['r_empleado'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Registrar Nuevos Empleados
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($empleado['l_empleado'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Consultar Lista de Empleados Registrados
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($empleado['m_empleado'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar Información de Empleados
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    <?php if ($rol_total >= 1) { ?>

                        <div class="col-12 mb-1 p-2">
                            <div class="accordion" id="acordeon_roles">
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rolesCard" aria-expanded="false" aria-controls="rolesCard">
                                            Módulo Roles
                                            <span class="ms-auto"><i class="bi <?= obtenerIconoPermisos($rol_total, 3) ?>"></i></span>
                                        </button>
                                    </h2>

                                    <div id="rolesCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_roles">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">

                                                <?php if ($rol_total == 3) { ?>
                                                    <li class="list-group-item bg-light border-0">
                                                        <strong class="text-success">Acceso Total al Módulo de Roles</strong>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($rol['r_rol'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Registrar Nuevos Roles
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($rol['l_rol'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Consultar Lista de Roles Registrados
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($rol['m_rol'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar Información de Roles
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>
            </div>

        <?php }  if ($ajustes_total > 0 || $bitacora_total > 0) { ?>
            
            <div class="col-12 col-md-6 mb-1">

                <h4 class="mb-3 text-primary"><i class="bi bi-gear-fill"></i> Configuración General</h4>
                <hr>

                <div class="row justify-content-center">

                    <?php if ($ajustes_total > 0) { ?>
                        <div class="col-12 mb-1 p-2">
                            <div class="accordion" id="acordeon_ajustes_del_sistema">
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ajustesCard" aria-expanded="false" aria-controls="ajustesCard">
                                            Ajustes del Sistema
                                            <span class="ms-auto"><i class="bi <?= obtenerIconoPermisos($ajustes_total, 6) ?>"></i></span>
                                        </button>
                                    </h2>
                                    <div id="ajustesCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_ajustes_del_sistema">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">
                                                <?php if ($ajustes_total == 6) { ?>
                                                    <li class="list-group-item bg-light border-0">
                                                        <strong class="text-success">Acceso Total a la Configuración del Sistema</strong>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($ajustes['m_cant_pregunta_seguridad'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar cantidad de preguntas de seguridad
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($ajustes['m_tiempo_sesion'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar tiempo de inactividad de sesión
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($ajustes['m_cant_caracteres'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar cantidad de caracteres de contraseña
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($ajustes['m_cant_simbolos'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar cantidad de símbolos de contraseña
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($ajustes['m_cant_num'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar cantidad de números de contraseña
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($ajustes['intentos_inicio_sesion'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Modificar intentos de inicio de sesión
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($bitacora_total > 0) { ?>
                        <div class="col-12 mb-1 p-2">
                            <div class="accordion" id="acordeon_bitacora">
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bitacoraCard" aria-expanded="false" aria-controls="bitacoraCard">
                                            Bitácora
                                            <span class="ms-auto"><i class="bi <?= obtenerIconoPermisos($bitacora_total, 2) ?>"></i></span>
                                        </button>
                                    </h2>
                                    <div id="bitacoraCard" class="accordion-collapse collapse" data-bs-parent="#acordeon_bitacora">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">
                                                <?php if ($bitacora_total == 2) { ?>
                                                    <li class="list-group-item bg-light border-0">
                                                        <strong class="text-success">Acceso Total a la Bitácora</strong>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($bitacora['v_bitacora'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Consultar Registros de la Bitácora
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($bitacora['m_bitacora'] == 1) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Consultar Movimientos de un Usuario en la Bitácora
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>

        <?php } ?>

    </div>
</div>
