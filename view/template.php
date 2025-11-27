<?php 

require_once "./controller/viewcontroller.php";

$ins_views = new viewController();

$view = $ins_views->obtener_vistas_controlador();

if ($view == "login" || $view == "404" || $view == "index") : 

    require_once "./view/content/$view-view.php";

else: 

    // <!-- Main Content -->
    include $view; 
        
endif;