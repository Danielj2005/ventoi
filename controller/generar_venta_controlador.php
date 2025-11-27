<?php
error_reporting(E_PARSE);
session_start();
date_default_timezone_set('America/Caracas');

include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

// modulo a trabajar
$modulo = modeloprincipal::limpiar_cadena($_POST["modulo"]);

if (!isset($_POST["modulo"])) {
    alert_model::alerta_simple("Ocurrio un error!","Ha ocurrido un error al procesar tu solicitud","error");
    exit();
}

// datos del cliente 
$id_cliente = modeloPrincipal::limpiar_cadena($_POST['id_cliente']);
$cedula_cliente = modeloPrincipal::limpiar_cadena($_POST['nacionalidad'].$_POST['cedula']); 

// datos de los servicios
$id_servicios = (isset($_POST['UIDS'])) ? $_POST['UIDS'] : '';
$cantidad_servicios = (isset($_POST['cantidad_servicio'])) ? $_POST['cantidad_servicio'] : '';
$precio_servicio_dolar = (isset($_POST['precio_servicio_dolar'])) ? $_POST['precio_servicio_dolar'] : '';
$precio_servicio_bolivar = (isset($_POST['precio_servicio_bolivar'])) ? $_POST['precio_servicio_bolivar'] : '';

// datos  de los productos
$id_productos = (isset($_POST['id_producto'])) ? $_POST['id_producto'] : '';
$cantidad_productos = (isset($_POST['cantidad'])) ? $_POST['cantidad'] : '';
$precios_dolar_productos = (isset($_POST['precio_producto_dolar'])) ? $_POST['precio_producto_dolar'] : '';
$precios_bolivares_productos = (isset($_POST['precio_producto_bolivar'])) ? $_POST['precio_producto_bolivar'] : '';

// datos del metodo de pago--
$id_metodo_pago = $_POST['metodo_pago'];
$cantidad_pago = $_POST['monto_pagar'];
$referencia_pago = $_POST['num_referencia'];

// datos de la venta 
$precio_dolar = $_POST['dolar'];
$fecha_venta = date('Y-m-d h:i:s');

$sub_total_dolar = $_POST['sub_total_dolar'];
$sub_total_bs = $_POST['sub_total_bs'];

$total_venta_dolar = $_POST['totalDolar_iva'];
$total_venta_bolivares = $_POST['totalBolivar_iva'];


// Se verifica que no se hayan recibido campos vacíos.
modeloPrincipal::validar_campos_vacios([$id_metodo_pago, $cantidad_pago, $total_venta_dolar, $total_venta_bolivares, $id_cliente, $cedula_cliente, $referencia_pago, $precio_dolar, $fecha_venta, $sub_total_dolar, $sub_total_bs, $total_venta_dolar, $total_venta_bolivares]);

if($id_servicios[0] == "" && $id_productos[0] == "" ){
    alert_model::alerta_simple("¡Ocurrio un error!","Debes seleccionar un servicio o producto para generar una venta, verifique he intente de nuevo","error");
    exit();
}

// se verifica que el cliente este registrado de otro modo lo registra
$existe_cliente = modeloPrincipal::Consultar("SELECT id_cliente FROM cliente WHERE cedula = '$cedula_cliente'");

if(mysqli_num_rows($existe_cliente) < 1){

    $nombre_cliente = modeloPrincipal::limpiar_mayusculas($_POST['nombre']);
    $telefono_cliente = modeloPrincipal::limpiar_cadena($_POST['telefono']);

    try {

        $registrar_cliente = cliente_model::registrar($cedula_cliente, $nombre_cliente, $telefono_cliente);
        
        if ($registrar_cliente) {
            $id_cliente = cliente_model::obtener_id_cliente_recien_registrado();
            
            $existe_cliente = cliente_model::consultar_por_id("*", $id_cliente);
        }
    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar el cliente en la base de datos debido a un error de consulta.", "error");
        exit();
    }
}

$existe_cliente = mysqli_fetch_array($existe_cliente);
$id_cliente = $existe_cliente['id_cliente'];
$id_usuario = $_SESSION['id_usuario'];

// ************* se verifica que haya stock para los servicios *************
if ($id_servicios !== "") {
    venta_model::verify_stock_for_service($id_servicios, $cantidad_servicios);
}

// ************* se verifica que el stock sea igual o mayor a la cantidad solicitada *************
if ($id_productos !== "") {
    venta_model::verify_stock_for_product($id_productos, $cantidad_productos);
}

// se registran los datos de la venta
try {

    $registrar = venta_model::insert_sell($fecha_venta, $sub_total_dolar, $sub_total_bs, $total_venta_dolar, $total_venta_bolivares, $id_usuario, $id_cliente);
    
    if (!$registrar) {
        alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar la venta en la base de datos.","error");
    }

} catch (Exception $e) {
    alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar la entrada de productos en la base de datos debido a un error de consulta.", "error");
    exit();
}

$id_venta = venta_model::obtener_id_venta_recien_registrada();

//  ************** cuando la venta es de servicios y de productos ************** 
if ($id_servicios !== "" && $id_productos !== "" ){
    try {
        $regitrar_detalles_venta_servicios = venta_model::sell_only_service($id_servicios, $cantidad_servicios, $precio_servicio_dolar, $precio_servicio_bolivar, $id_venta);
        
        // $id_detalles_venta = mysqli_fetch_array(modeloPrincipal::Consultar("SELECT MAX(id_detalles_venta) AS id 
        //     FROM detalles_venta"))['id'];

        $regitrar_detalles_venta_productos = venta_model::sell_only_product( $id_productos, $cantidad_productos, $precios_dolar_productos, $precios_bolivares_productos, $id_venta, $id_usuario);

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar la venta en la base de datos debido a un error de consulta.", "error");
        exit();
    }
}

// ********************** cuendo la venta es solo de servicios ********************** 

if($id_servicios !== ""){
    try {
        
        $regitrar_detalles_venta_servicios = venta_model::sell_only_service($id_servicios, $cantidad_servicios, $precio_servicio_dolar, $precio_servicio_bolivar, $id_venta);
    
    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar la venta de servicios en la base de datos debido a un error de consulta.", "error");
        exit();
    }
}

//  ********************* cuando la venta es solo de productos ********************* 
if ($id_productos !== "" ){ 
    try {
        
        $regitrar_detalles_venta_productos = venta_model::sell_only_product( $id_productos, $cantidad_productos, $precios_dolar_productos, $precios_bolivares_productos, $id_venta, $id_usuario);
        
    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar la venta de productos en la base de datos debido a un error de consulta.", "error");
        exit();
    }
}

// se registran los detalles de pago de la venta

try {
    $detalles_pagos = venta_model::registrar_detalles_pago($id_venta, $id_metodo_pago, $referencia_pago, $precio_dolar, $cantidad_pago);
    
    if (!$detalles_pagos) {
        alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar los detalles de los métodos de pago de la venta en la base de datos.","error");
    }

} catch (Exception $e) {
    alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar los detalles de la venta en la base de datos debido a un error de consulta.", "error");
    exit();
}

// se registran la venta en bitácora
$dolar = mysqli_fetch_array(modeloprincipal::consultar("SELECT MAX(dolar) AS dolar FROM dolar"));
$dolar = $dolar['dolar'];

try {
    
    $datos_venta = modeloprincipal::consultar("SELECT V.fecha_venta, V.sub_total_dolares, V.sub_total_bs, 
        V.monto_total_dolares, V.monto_total_bolivares,
        U.cedula AS Ucedula, U.nombre AS Unombre, U.apellido AS Uapellido, U.correo, U.telefono, 
        C.cedula AS Ccedula, C.nombre AS Cnombre, C.telefono AS Ctelefono
        FROM venta AS V 
        INNER JOIN cliente AS C ON C.id_cliente = V.id_cliente 
        INNER JOIN usuario AS U ON U.id_usuario = V.id_usuario 
        WHERE V.id_venta = $id_venta");

    $datos_venta = mysqli_fetch_array($datos_venta);

    bitacora::bitacora("Venta realizada exitosamente.",
        '<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp; Se registró una venta con la siguiente informacón.</p> 
        <h4 class="text-center card-title"><b> Información del cliente </b></h4>
        <div class="d-flex justify-content-between border-bottom">
            <p> Cédula</p>
            <span>'.$datos_venta['Ccedula'].'</span>
        </div>
        <div class="d-flex justify-content-between border-bottom">
            <p> Nombre y Apellido</p>
            <span>'.$datos_venta['Cnombre'].'</span>
        </div>
        <div class="d-flex justify-content-between border-bottom">
            <p> Teléfono</p>
            <span>'.$datos_venta['Ctelefono'].'</span>
        </div>

        <h4 class="text-center card-title"><b> Información de la Venta </b></h4>
        <div class="d-flex justify-content-between border-bottom">
            <p> Subtotal ($)</p>
            <span>'.$datos_venta['sub_total_dolares'].' $</span>
        </div>
        <div class="d-flex justify-content-between border-bottom">
            <p> Subtotal (Bs)</p>
            <span>'.$datos_venta['sub_total_bs'].' Bs</span>
        </div>

        <div class="d-flex justify-content-between border-bottom">
            <p> Total ($) + IVA (16%)</p>
            <span>'.$datos_venta['monto_total_dolares'].' $</span>
        </div>
        <div class="d-flex justify-content-between border-bottom">
            <p> Total (Bs) + IVA (16%)</p>
            <span>'.$datos_venta['monto_total_bolivares'].' Bs</span>
        </div>

        <div class="d-flex justify-content-between border-bottom">
            <p> Fecha y Hora</p>
            <span>'.date("d-m-Y | H:i:a",strtotime($datos_venta['fecha_venta'])).'</span>
        </div>
        <div class="d-flex justify-content-between border-bottom">
            <p> Tasa de Cambio</p>
            <span>'.$dolar.' Bs</span>
        </div>
        <h4 class="text-center card-title"><b> Información del Usuario que realizó la venta </b></h4>
        <div class="d-flex justify-content-between border-bottom">
            <p> Cédula</p>
            '.$datos_venta['Ucedula'].'
        </div>
        <div class="d-flex justify-content-between border-bottom">
            <p> Nombre y Apellido</p>
            '.$datos_venta['Unombre']." ".$datos_venta['Uapellido'].'
        </div>
        <div class="d-flex justify-content-between border-bottom">
            <p> Correo</p>
            '.$datos_venta['correo'].'
        </div>
        <div class="d-flex justify-content-between border-bottom">
            <p> Teléfono</p>
            '.$datos_venta['telefono'].'
        </div>');

    alert_model::alert_reload ("Venta realizada!", "La venta se realizo correctamente", "success");
    exit();
} catch (Exception $e) {
    alert_model::alert_reg_error();
    exit();
}
