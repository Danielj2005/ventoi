<?php
session_start();

require_once "../../config/APP.php";
// Asegúrate de incluir el archivo del router
require_once "../../model/modeloPrincipal.php";
require_once "../../model/proveedor_model.php";
require_once "../../model/productos_model.php";
require_once "../../model/servicio_model.php";
require_once "../../model/marca_model.php";
require_once "../../model/categoria_model.php";
require_once "../../model/presentacion_model.php";
require_once "../../model/rol_model.php";
require_once "../../model/alert_model.php";
require_once "../../router/Router.php"; // <--- Asegúrate que la ruta sea correcta

use App\Router\Router;

// Bloquear el acceso si la petición no es POST, lo cual es típico de una API/Router.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    alert_model::alerta_simple(
        "Acceso Denegado", 
        "Esta ruta solo acepta peticiones de tipo POST.", 
        "error"
    );
    exit;
}

// 1. Instanciar el router, pasándole todos los datos de POST.
$router = new Router($_POST);

// 2. Ejecutar el enrutamiento.
$router->route();