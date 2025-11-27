<?php 
session_start();

include_once "./model/modeloPrincipal.php"; // se incluye el modelo principal
include_once "./model/modelo_usuario.php";  // se incluye el modelo de usuario
include_once "./model/rol_model.php"; // se incluye el modelo rol
include_once "./model/empleado_model.php"; // se incluye el modelo empleado

include_once "./model/bitacora_model.php"; // se incluye el modelo de bitacora
include_once "./model/configuracion_model.php"; // se incluye el modelo de configuracion
include_once "./model/alert_model.php"; // se incluye el modelo de alertas

include_once "./model/proveedor_model.php"; // se incluye el modelo proveedor

include_once "./model/categoria_model.php"; // se incluye el modelo categoria
include_once "./model/presentacion_model.php"; // se incluye el modelo presentacion
include_once "./model/productos_model.php"; // se incluye el modelo producto
include_once "./model/marca_model.php"; // se incluye el modelo de marcas

include_once "./model/cliente_model.php"; // se incluye el modelo cliente
include_once "./model/servicio_model.php"; // se incluye el modelo servicio
include_once "./model/venta_model.php"; // se incluye el modelo venta




require_once "./controller/viewcontroller.php";

$ins_views = new viewController();

$view = $ins_views->obtener_vistas_controlador();


$viewConfig = [
    "index" => ["view/inc/index/css.php", "view/inc/index/js.php"],
    "login" => ["view/inc/login/css.php", "view/inc/login/js.php"],
    "./view/content/dashboard-view.php" => ["view/inc/views/css.php", "view/inc/views/js.php"],     
    "./view/content/producto-view.php" => ["view/inc/views/css.php", "view/inc/views/js.php"],    
    "./view/content/entrada-view.php" => ["view/inc/views/css.php", "view/inc/views/js.php"],    
    "./view/content/proveedor-view.php" => ["view/inc/views/css.php", "view/inc/views/js.php"],  
    "404" => ["", ""],
];


$allowViews = [
    "index" => ["view/inc/index/css.php", "view/inc/index/js.php"],
    "login" => ["view/inc/login/css.php", "view/inc/login/js.php"],
    "./view/content/dashboard-view.php" => ["view/inc/views/css.php", "view/inc/views/js.php"],    
    "./view/content/producto-view.php" => ["view/inc/views/css.php", "view/inc/views/js.php"],    
    "./view/content/entrada-view.php" => ["view/inc/views/css.php", "view/inc/views/js.php"],    
    "./view/content/proveedor-view.php" => ["view/inc/views/css.php", "view/inc/views/js.php"],
    "404" => ["", ""],
];


?>

<!DOCTYPE html>
<html <?= LANG ?>

<head>
    <?php include_once "view/inc/head.php";
    $viewConfig[$view][0] ? include_once $viewConfig[$view][0] : ""; ?>
</head>

<body id="page-top" class="index-page bg-dark-subtle" data-bs-theme="dark">

    <?php 

        if ($view == "login" || $view == "404" || $view == "index") : 

            require_once "./view/content/$view-view.php"; 

        else: 
        
            include_once "view/inc/topbar.php";
            include_once "view/inc/sidebar.php";
    ?>

            <main id="main" class="main"> <?php include $view; ?> </main>
            
            <!-- End of Main Content -->
            <?php include_once "view/inc/footer.php"; ?>

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top"> <i class="fas fa-angle-up"></i> </a>
            <!-- Modal -->
            <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div id="modal_tamano" class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                            <button id="btnCloseModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body row m-0" id="body_modal"> </div>

                        <div class="modal-footer">
                            <button id="btn_guardar_modal" type="submit" class="btn btn-success">Guardar</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        
    <?php
        endif; 
        
        // <!-- Script -->
        include_once "view/inc/script.php";
        
        $viewConfig[$view][1] ? include_once $viewConfig[$view][1] : "";
        
        model_user::validar_sesion_activa($id_usuario);

        config_model::verificar_actualizacion_configuracion(); 
    ?>
</body>
</html>
