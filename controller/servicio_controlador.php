<?php 
session_start();

require_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

// modulo a trabajar
$modulo = modeloprincipal::limpiar_cadena($_POST["modulo"]);

if (!isset($_POST["modulo"])) {
    alert_model::alerta_simple("Ocurrio un error!","Ha ocurrido un error al procesar tu solicitud","error");
    exit();
}

if($modulo == 'Guardar'){
    // estos datos se guardan en la tabla menu de la base de datos
    $nombre_platillo = modeloprincipal::limpiar_mayusculas($_POST['nombre_platillo']);
    $descripcion = modeloprincipal::limpiar_mayusculas($_POST['descripcion']);

    //  datos de los productos a ingresar en el platillo
    $id_productos = $_POST['id_producto'];
    $cantidad_productos = $_POST['cantidad'];

    $precio_dolar = $_POST['precio_dolar'];
    $precio_bolivar = $_POST['precio_bolivar'];
    
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$nombre_platillo, $descripcion, $id_productos, $cantidad_productos]);
    
    $existe_platillo = modeloPrincipal::Consultar("SELECT id_menu FROM menu WHERE nombre_platillo = '$nombre_platillo'");

    if(mysqli_num_rows($existe_platillo) > 0){
        alert_model::alert_register_exist();
        exit(); 
    }

    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,50}",$nombre_platillo)) {
        alert_model::alert_of_format_wrong("'nombre'");
        exit();
    }

    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,250}",$descripcion)) {
        alert_model::alert_of_format_wrong("'descripción'");
        exit();
    }
        
    // se registran los datos del presentación
    try {
        $registrar = servicio_model::registrar( $nombre_platillo, $precio_dolar,$descripcion);
        
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar el servicio.","error");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar el servicio debido a un error de consulta.", "error");
        exit();
    }

    $id_servicio = modeloPrincipal::obtener_id_recien_registrado("id_menu", "menu");
    
    try {

        for($i = 0; $i < count($cantidad_productos); $i++){
            modeloPrincipal::InsertSQL("detalles_menu","id_producto, cantidad, id_menu","".modeloprincipal::decryptionId($id_productos[$i]).",".$cantidad_productos[$i].",'$id_servicio'");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar el servicio debido a un error de consulta.", "error");
        exit();
    }

    // se realiza la bitácora con los datos del servicio a registrar
    try {
        $mensaje = '';

        $datos_servicio = mysqli_fetch_array(servicio_model::consultar_por_id("*", $id_servicio));
        $datos_servicio['estatus'] = $datos_servicio['estatus'] == 1 ? 'Activo' : 'Inactivo';

        $detalles_menu = modeloPrincipal::consultar("SELECT P.codigo, P.nombre_producto AS producto, P.stock_actual,
            PS.cantidad AS presentacion, R.nombre AS representacion, 
            C.nombre AS categoria, 
            DM.cantidad,
            M.nombre AS marca
            FROM detalles_menu AS DM
            INNER JOIN producto AS P ON P.id_producto = DM.id_producto
            INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
            INNER JOIN representacion AS R ON R.id = PS.id_representacion
            INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria
            INNER JOIN marca AS M ON M.id = P.id_marca
            WHERE DM.id_menu = $id_servicio
        ");
        
        while ($row = mysqli_fetch_array($detalles_menu)) {
            $color_stock = producto_model::asignar_color_segun_stock($row["stock_actual"]);  

            $mensaje .= '<p class="text-secondary fw-bold mb-1"> Código: '.$row["codigo"].' </p>
                <p class="text-secondary fw-bold mb-1"> Nombre: <span class="text-primary fw-bold mb-1">'.$row["producto"].'</span> </p>
                <p class="text-secondary fw-bold mb-1"> Marca: '.$row["marca"].' </p>
                <small class="d-block text-muted"> Formato: '.$row["presentacion"].' '.$row["representacion"].' </small>
                <small class="d-block text-muted"> Categoría: '.$row["categoria"].' </small>
                <p class="fw-bold mb-1 '.$color_stock.'"> Cantidad: <span>'.$row["cantidad"].'</span> </p>';
                
            $mensaje .= mysqli_num_rows($detalles_menu) > 1 ? '<hr>' : '';
        }

        bitacora::bitacora("Registro exitoso de un nuevo servicio.",
        '<p class="mb-3 text-primary-emphasis"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario registró un servicio con la información</p> 
            <h4 class="text-center card-title"><b> Información del Servicio </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre del platilllo</p> <span>'.$nombre_platillo.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Precio ($)</p> <span>'.$precio_dolar.' $ </span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Descripción</p> <span>'.$descripcion.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Estado</p> <span>'.$datos_servicio['estatus'].'</span> </div>

            <h4 class="text-center card-title"><b> Detalles del Servicio </b></h4>
            
            '.$mensaje.'
        ');

        alert_model::alert_reg_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_reg_error();
        exit();
    }
}

if($modulo == 'Modificar'){
    // estos datos se guardan en la tabla menu de la base de datos

    $id = modeloPrincipal::decryptionId($_POST['UIS']);
    $id_servicio = modeloPrincipal::limpiar_cadena($id);

    $nombre_platillo = modeloprincipal::limpiar_mayusculas($_POST['nombre_platillo']);
    $estado_menu = modeloprincipal::limpiar_mayusculas($_POST['estado_menu']);
    $descripcion = modeloprincipal::limpiar_mayusculas($_POST['descripcion']);

    $precio_dolar = $_POST['precio_dolar'];
    $precio_bolivar = $_POST['precio_bolivar'];

    // crear la posibilidad de modificar los productos que componen un servicio....proximamente
    //  datos de los productos a ingresar en el platillo
    $id_productos = $_POST['producto']; // se recibe un array de las id de productos del servicio
    $cantidad_productos = $_POST['cantidad_producto']; // se recibe un array de la cantidad productos del servicio
    
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$id_servicio, $nombre_platillo, $descripcion, $estado_menu, $id_productos, $cantidad_productos]);

    $existe_platillo = modeloPrincipal::Consultar("SELECT * FROM menu WHERE nombre_platillo = '$nombre_platillo' AND id_menu != $id_servicio");

    if(mysqli_num_rows($existe_platillo) > 0){
        alert_model::alerta_simple(
            "¡Ocurrió un error inesperado!",
            "Ya se encuentra registrado un platillo con ese nombre, por favor ingresa otro",
            "error");
        exit(); 
    }

    $existe_platillo = mysqli_fetch_array(modeloPrincipal::Consultar("SELECT * FROM menu WHERE id_menu = $id_servicio"));

    $nombre_original = $existe_platillo['nombre_platillo'];
    $precio_dolar_original = $existe_platillo['precio_dolar'];
    $descripcion_original = $existe_platillo['descripcion'];
    $estatus_original = $existe_platillo['estatus'];
    
    // Comprobar si los detalles del servicio (productos y cantidades) han cambiado.
    $detalles_cambiaron = servicio_model::comparar_detalles_servicio($id_servicio, $id_productos, $cantidad_productos);

    // se consultan los datos del servicio antes de ser modificado para registrar los cambios en la bitacora
    $bitacora_original = "";

    $datos_producto_original = modeloPrincipal::Consultar("SELECT P.codigo, P.nombre_producto AS producto,
        PS.cantidad AS presentacion, R.nombre AS representacion, C.nombre AS categoria, DM.cantidad, M.nombre AS marca
        FROM detalles_menu AS DM
        INNER JOIN producto AS P ON P.id_producto = DM.id_producto
        INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
        INNER JOIN representacion AS R ON R.id = PS.id_representacion
        INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria
        INNER JOIN marca AS M ON M.id = P.id_marca
        WHERE DM.id_menu = $id_servicio");

    if ($detalles_cambiaron) {
        $bitacora_original = '<h4 class="text-center card-title"><b> Información de los productos del Servicio </b></h4>';
        while ($row = mysqli_fetch_array($datos_producto_original)) {

            $canridad_producto = $row["cantidad"];

            $bitacora_original .= '<p class="text-danger-emphasis text-secondary fw-bold mb-1"> Código: '.$row["codigo"].' </p>
                <p class="text-danger-emphasis text-secondary fw-bold mb-1"> Nombre: <span class="fw-bold mb-1">'.$row["producto"].'</span> </p>
                <p class="text-danger-emphasis text-secondary fw-bold mb-1"> Marca: '.$row["marca"].'  </p>
                <small class="text-danger-emphasis d-block text-muted"> Formato: '.$row["presentacion"].' '.$row["representacion"].' </small>
                <small class="text-danger-emphasis d-block text-muted"> Categoría: '.$row["categoria"].' </small>
                <p class="text-danger-emphasis  fw-bold mb-1"> Cantidad: '.$row["cantidad"].' </p> <hr>';

        }
    }

    // se registran los datos del producto
    try {
        $actualizar = modeloPrincipal::UpdateSQL("menu","nombre_platillo = '$nombre_platillo', precio_dolar = '$precio_dolar', descripcion = '$descripcion',estatus = '$estado_menu'","id_menu = $id_servicio");

        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar un producto.","error");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar el producto debido a un error de consulta.", "error");
        exit();
    }

    // registro / actualizacion de los productos de un servicio 
    if (count($id_productos) > 0) {
        try {

            $existe_detalles_servicio = modeloPrincipal::consultar("SELECT id_detalles_menu, id_producto, cantidad
                FROM detalles_menu WHERE id_menu = $id_servicio");

            // se compara si la cantidad de productos a registrar es igual a la cantidad actual de productos en el servicio
            if (mysqli_num_rows($existe_detalles_servicio) > 0) {
                
                $i = 0;
                
                // recorremos el array con las id de los productos del servicio a modificar
                while ($mostrar = mysqli_fetch_array($existe_detalles_servicio)) {

                    // si solo se recibe un producto, se actualiza el producto del servicio
                    $id_producto = modeloPrincipal::decryptionId($id_productos[$i]);
                    
                    $cantidad_producto = $cantidad_productos[$i];

                    $i++;

                    $detalle_id = $mostrar['id_detalles_menu'];
                    $detalle_id_producto = $mostrar['id_producto'];
                    $detalle_cantidad_producto = $mostrar['cantidad'];                        
                    
                    $actualizar = modeloPrincipal::UpdateSQL("detalles_menu","id_producto = $id_producto, cantidad = $cantidad_producto","id_detalles_menu = $detalle_id");

                    if (!$actualizar) {
                        alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al modificar el/los producto(s) de un servicio.","error");
                    }
                } 
            }

        } catch (Exception $e) {
            alert_model::alerta_simple("Ocurrido un error!", "No se pudo modificar los productos de un servicio debido a un error en la solicitud a la base de datos.", "error");
            exit();
        }
    }
    
    // se realiza la bitácora con los datos del producto a registrar
    try {
        
        $bitacora = '';
        if ($detalles_cambiaron) {
            $bitacora = '<h4 class="text-center card-title"><b> Información de los nuevos productos del Servicio </b></h4>';
        }

        $datos_producto_actualizados = modeloPrincipal::Consultar("SELECT P.codigo, P.nombre_producto AS producto, P.stock_actual,
            PS.cantidad AS presentacion, R.nombre AS representacion, C.nombre AS categoria, DM.cantidad, M.nombre AS marca
            FROM detalles_menu AS DM
            INNER JOIN producto AS P ON P.id_producto = DM.id_producto
            INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
            INNER JOIN representacion AS R ON R.id = PS.id_representacion
            INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria
            INNER JOIN marca AS M ON M.id = P.id_marca
            WHERE DM.id_menu = $id_servicio");
        
        
        if ($detalles_cambiaron) {
            while ($row = mysqli_fetch_array($datos_producto_actualizados)) {

                $bitacora .= '<p class="text-success-emphasis fw-bold mb-1"> Código: '.$row["codigo"].' </p>
                    <p class="text-success-emphasis fw-bold mb-1"> Nombre: <span class="fw-bold mb-1">'.$row["producto"].'</span> </p>
                    <p class="text-success-emphasis fw-bold mb-1"> Marca: '.$row["marca"].' </p>
                    <small class="text-success-emphasis d-block text-muted"> Formato: '.$row["presentacion"].' '.$row["representacion"].' </small>
                    <small class="text-success-emphasis d-block text-muted"> Categoría: '.$row["categoria"].' </small>
                    <p class="text-success-emphasis  fw-bold mb-1"> Cantidad: '.$row["cantidad"].' </p><hr>';

            }
        }

        $estado_menu = ($estado_menu == '1') ? 'Activo' : 'Inactivo' ;
        $estatus_original = ($estatus_original == '1') ? 'Activo' : 'Inactivo' ;

        $cambios = [
            "nombre" => config_model::obtener_comparacion([$nombre_original, $nombre_original], [ $nombre_platillo, $nombre_platillo]),
            "precio" => config_model::obtener_comparacion([$precio_dolar_original, $precio_dolar_original], [ $precio_dolar, $precio_dolar]),
            "descripcion" => config_model::obtener_comparacion([$descripcion_original, $descripcion_original], [ $descripcion, $descripcion]),
            "estado" => config_model::obtener_comparacion([$estatus_original, $estatus_original], [ $estado_menu, $estado_menu]),
        ];

        bitacora::bitacora("Modificación de un Servicio",
        '<p class="mb-3 text-primary-emphasis"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario actualizó la información de un servicio:</p> 
            <h4 class="text-center card-title"><b> Información del Servicio: </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre del platilllo</p> <span>'.$cambios['nombre'].'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Precio ($)</p> <span>'.$cambios['precio'].' $ </span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Descripción</p> <span>'.$cambios['descripcion'].'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Estado</p> <span>'.$cambios['estado'].'</span> </div>
            '.($detalles_cambiaron ? $bitacora_original . $bitacora : '').'');

        alert_model::alert_mod_success();
        exit();

    } catch (Exception $e) { // mensaje de error "no se pudo registrar"
        alert_model::alert_mod_error();
        exit();
    }
}

if($modulo == 'activo'){
    // estos datos se guardan en la tabla menu de la base de datos

    $id = modeloPrincipal::decryptionId($_POST['UIS']);
    $id_servicio = modeloPrincipal::limpiar_cadena($id);

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$id_servicio]);

    $existe_platillo = modeloPrincipal::Consultar("SELECT * FROM menu WHERE id_menu = $id_servicio");

    if(mysqli_num_rows($existe_platillo) < 0){
        alert_model::alerta_simple(
            "¡Ocurrió un error inesperado!",
            "Ha ocurrido un error al procesar tu solicitud, recarga la página he intentalo de nuevo.",
            "error");
        exit(); 
    }

    $existe_platillo = mysqli_fetch_array(modeloPrincipal::Consultar("SELECT * FROM menu WHERE id_menu = $id_servicio"));

    $nombre_original = $existe_platillo['nombre_platillo'];
    $precio_dolar_original = $existe_platillo['precio_dolar'];
    $descripcion_original = $existe_platillo['descripcion'];
    $estatus_original = $existe_platillo['estatus'];
    
    // se registran los datos verificados
    if (modeloPrincipal::UpdateSQL( "menu","estatus = '0'","id_menu = $id_servicio")) {
        
        $cambios = [
            "estado" => config_model::obtener_comparacion(["Activo", "Activo"], [ "Inactivo" ,"Inactivo"]),
        ];

        bitacora::bitacora("Modificación del estado de un servicio",
        '<p class="mb-3 text-primary-emphasis"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario actualizó el estado de un servicio</p> 
            <h4 class="text-center card-title"><b> Información del Servicio </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre del platilllo</p> <span>'.$nombre_original.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Precio ($)</p> <span>'.$precio_dolar_original.' $ </span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Descripción</p> <span>'.$descripcion_original.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Estado</p> <span>'.$cambios['estado'].'</span> </div>');

        alert_model::alert_mod_success();
        exit();
    }else{ // mensaje de error "no se pudo registrar"
        alert_model::alert_mod_error();
        exit();
    }
}

if($modulo == 'inactivo'){
    // estos datos se guardan en la tabla menu de la base de datos

    $id = modeloPrincipal::decryptionId($_POST['UIS']);
    $id_servicio = modeloPrincipal::limpiar_cadena($id);

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$id_servicio]);

    $existe_platillo = modeloPrincipal::Consultar("SELECT * FROM menu WHERE id_menu = $id_servicio");

    if(mysqli_num_rows($existe_platillo) < 0){
        alert_model::alerta_simple(
            "¡Ocurrió un error inesperado!",
            "Ha ocurrido un error al procesar tu solicitud, recarga la página he intentalo de nuevo.",
            "error");
        exit(); 
    }

    $existe_platillo = mysqli_fetch_array(modeloPrincipal::Consultar("SELECT * FROM menu WHERE id_menu = $id_servicio"));

    $nombre_original = $existe_platillo['nombre_platillo'];
    $precio_dolar_original = $existe_platillo['precio_dolar'];
    $descripcion_original = $existe_platillo['descripcion'];
    $estatus_original = $existe_platillo['estatus'];
    
    // se registran los datos verificados
    if (modeloPrincipal::UpdateSQL( "menu","estatus = '1'","id_menu = $id_servicio")) {
        
        $cambios = [
            "estado" => config_model::obtener_comparacion(["Inactivo", "Inactivo"], [ "Activo" ,"Activo"]),
        ];

        bitacora::bitacora("Modificación de un servicio",
        '<p class="mb-3 text-primary-emphasis"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario actualizó el estado de un servicio</p> 
            <h4 class="text-center card-title"><b> Información del Servicio </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre del platilllo</p> <span>'.$nombre_original.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Precio ($)</p> <span>'.$precio_dolar_original.' $ </span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Descripción</p> <span>'.$descripcion_original.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Estado</p> <span>'.$cambios['estado'].'</span> </div>');

        alert_model::alert_mod_success();
        exit();
    }else{ // mensaje de error "no se pudo registrar"
        alert_model::alert_mod_error();
        exit();
    }
}