<?php 
session_start();

include_once ("../include/modelos_include.php"); // se incluyen los modelos necesarios para la vista

if (!isset($_POST["modulo"]) || $_POST['modulo'] == "") {
    alert_model::alerta_simple("Ocurrio un error!","Ha ocurrido un error al procesar tu solicitud, asegurese de no alterar la información del sistema","error");
    exit();
}

// modulo a trabajar
$modulo = modeloPrincipal::limpiar_cadena($_POST["modulo"]);

// modulo para Guardar un registro de un rol
if($modulo === "Guardar"){

    $nombre = modeloPrincipal::primeraLetraMayus(modeloPrincipal::limpiar_cadena($_POST["nombre_rol"]));

    $permisos_post = [
        // Proveedores
        "r_proveedores", "m_proveedores", "l_proveedores", "h_proveedores",
        // Productos
        "r_categoria", "m_categoria", "l_categoria",
        "r_presentacion", "m_presentacion", "l_presentacion",
        "r_marca", "m_marca", "l_marca",
        "r_productos", "l_productos",
        "r_entrada", "l_entrada",
        // Ventas
        "g_venta", "d_venta", "f_venta", "l_venta", "est_venta",
        // Servicios
        "r_servicio", "l_servicio", "m_servicio",
        // Clientes
        "r_cliente", "m_cliente", "l_cliente", "h_cliente", "f_cliente",
        // Empleados
        "r_empleado", "m_empleado", "l_empleado",
        // Roles
        "r_rol", "m_rol", "l_rol",
        // Ajustes
        "m_cant_pregunta_seguridad", "m_tiempo_sesion", "m_cant_caracteres", 
        "m_cant_simbolos", "m_cant_num", "intentos_inicio_sesion",
        // Bitácora
        "v_bitacora", "m_bitacora"
    ];

    $permisos_para_guardar = [];
    $permisos_para_bitacora = [];

    foreach ($permisos_post as $permiso) {
        // Para guardar en la BD (el valor del checkbox)
        $permisos_para_guardar[] = $_POST[$permiso] ?? '';
        // Para la bitácora (texto "Permitido" o "Denegado")
        $permisos_para_bitacora[$permiso] = isset($_POST[$permiso]) && $_POST[$permiso] != '' ? 'Permitido' : 'Denegado';
    }

    // se comprueba que no exista un registro con los mismos datos
    if(mysqli_num_rows(modeloPrincipal::consultar("SELECT nombre FROM rol WHERE nombre = '$nombre'")) > 0){
        alert_model::alerta_simple(
            "¡Ocurrió un error inesperado!", 
            "Ya se encuentra Registrado un ROL con ese nombre, por favor verifica e intenta de nuevo", 
            "error"); 
        exit(); 
    }

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$nombre]);
    

    if (modeloPrincipal::verificar_datos("[A-Za-zÁÉÍÚÓáéíóúñÑ ]{3,20}",$nombre)) {
        alert_model::alerta_simple( 
            "¡Ocurrio un error!", 
        "El campo NOMBRE debe contener entre 3 y 20 caracteres. Por favor, asegúrate de que cumple con este formato.", 
        "error");
        exit();
    }

    // datos verificados que se van a Registrar
    try {
        $registrar = rol_model::guardar_permisos_rol($nombre, ...$permisos_para_guardar);

        if (!$registrar) {
            alert_model::alerta_simple(
                "Ha ocurrido un error!", 
                "ocurrio un error al registrar la información del rol y sus permisos.", 
                "error");
            exit();
        }

    } catch (Exception $e) {
        alert_model::alerta_simple(
            "Ha ocurrido un error!", 
            "ocurrio un error no se pudo registrar el rol y sus permisos.",
            "error");
        exit();
    }
    
    try {

        $mensaje_bitacora = rol_model::generar_bitacora_guardar_rol($permisos_para_bitacora);

        $bitacora_registrar_rol = bitacora::bitacora("Registro Exitoso de un Rol", 
            '<p class="mb-3 text-primary-emphasis"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario registró un rol con la siguiente información:</p>
            <div class="row align-items-center mb-4 pb-2 border-bottom">

                <div class="col-12 col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="bi bi-person-badge me-2"></i>
                        Rol: '.$nombre.'
                    </h5>
                </div>

                <div class="col-12 col-md-6 text-center text-md-end">
                    <h5 class="fw-bold mb-0">
                        Estado: 
                        <span class="badge rounded-pill fs-6 bg-success"> Activo </span>
                    </h5>
                </div>
                
            </div>
            
            <div class="row mb-4 pb-2 border-bottom">
                '.$mensaje_bitacora.'
            </div>
        ');

        if (!$bitacora_registrar_rol) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al guardar la modificación en bitácora.", "error");
            exit();
        }

        alert_model::alert_reg_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_reg_error();
        exit();
    }
}


// modulo para modificar un rol registrado
if($modulo === "Modificar"){

    $id_rol = modeloPrincipal::decryptionId($_POST['UIDR']);
    $id_rol = modeloPrincipal::limpiar_cadena($id_rol);

    $nombre = modeloPrincipal::primeraLetraMayus(modeloPrincipal::limpiar_cadena($_POST["nombre_rol"]));
    $estado = modeloPrincipal::limpiar_cadena($_POST["estado_rol"]);
    

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$id_rol, $nombre, $estado]);
    
    if (modeloPrincipal::verificar_datos("[A-Za-zÁÉÍÚÓáéíóúñÑ ]{3,20}",$nombre)) {
        alert_model::alerta_simple( 
            "¡Ocurrio un error!", 
            "El campo NOMBRE debe contener entre 3 y 20 caracteres. Por favor, asegúrate de que cumple con este formato.", 
            "error");
        exit();
    }
    
    $permisos_post = [
        // Proveedores
        "r_proveedores", "m_proveedores", "l_proveedores", "h_proveedores",
        // Productos
        "r_categoria", "m_categoria", "l_categoria",
        "r_presentacion", "m_presentacion", "l_presentacion",
        "r_marca", "m_marca", "l_marca",
        "r_productos", "l_productos",
        "r_entrada", "l_entrada",
        // Ventas
        "g_venta", "d_venta", "f_venta", "l_venta", "est_venta",
        // Servicios
        "r_servicio", "l_servicio", "m_servicio",
        // Clientes
        "r_cliente", "m_cliente", "l_cliente", "h_cliente", "f_cliente",
        // Empleados
        "r_empleado", "m_empleado", "l_empleado",
        // Roles
        "r_rol", "m_rol", "l_rol",
        // Ajustes
        "m_cant_pregunta_seguridad", "m_tiempo_sesion", "m_cant_caracteres", 
        "m_cant_simbolos", "m_cant_num", "intentos_inicio_sesion",
        // Bitácora
        "v_bitacora", "m_bitacora"
    ];

    $permisos_nuevos_encriptados = [];
    $permisos_nuevos_bitacora = [];

    foreach ($permisos_post as $permiso) {

        if (isset($_POST[$permiso]) && $_POST[$permiso] != '') {
            // Para guardar en la BD (el valor del checkbox que es el ID de la función)
            $permisos_nuevos_encriptados[] = $_POST[$permiso];
        }
        // Para la bitácora (texto "Permitido" o "Denegado")
        $permisos_nuevos_bitacora[$permiso] = isset($_POST[$permiso]) && $_POST[$permiso] != '' ? 'Permitido' : 'Denegado';
    }

    // Obtener los permisos originales para la bitácora
    $permisos_originales_bd = rol_model::obtenerPermisosRolById($id_rol);
    $permisos_originales_bitacora = rol_model::texto_permisos_vista($permisos_originales_bd);

    try {
        // La función se encarga de borrar los permisos viejos y guardar los nuevos
        $actualizar = rol_model::modificar_permisos_rol($id_rol, $nombre, $estado, $permisos_nuevos_encriptados);

        if (!$actualizar) {
            alert_model::alerta_simple(
                "Ha ocurrido un error!", 
                "ocurrio un error al actualizar la información del rol seleccionado.", 
                "error");
            exit();
        }
    } catch (Exception $e) {
        alert_model::alerta_simple(
            "Ha ocurrido un error!", 
            "ocurrio un error al modificar la información del rol seleccionado.", 
            "error");
        exit();
    }
    
    
    try {

        // asignacion de roles a variables de session
        $_SESSION['permisosRol'] = rol_model::obtenerPermisosRol(); // variable con todos los permisos del usuario a los modulos
        
        $permisos_originales_bd = rol_model::obtenerPermisosRolById($id_rol);
        $permisos_actuales = rol_model::texto_permisos_vista($permisos_originales_bd);
        // Generar el HTML de la bitácora comparando los permisos

        $bitacora = rol_model::generar_bitacora_modificar_rol($permisos_originales_bitacora, $permisos_actuales);
        
        $colorBadge = $estado == 1 ? 'bg-success' : 'bg-danger';
        $textBadge = $estado == 1 ? 'Activo' : 'Inactivo';

        $mensaje = '<p class="mb-3 text-primary-emphasis"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario modificó el rol con la siguiente información:</p> <div class="row align-items-center mb-4 pb-2 border-bottom"> <div class="col-12 col-md-6 text-center text-md-start mb-2 mb-md-0"> <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-person-badge me-2"></i>Rol: '.$nombre.'</h5> </div><div class="col-12 col-md-6 text-center text-md-end"> <h5 class="fw-bold mb-0">Estado: <span class="badge rounded-pill fs-6 bg-success '.$colorBadge.'"> '.$textBadge.'</span></h5> </div></div><div class="row mb-4 pb-2 border-bottom">';

        $mensaje .= $bitacora.'</div>';
        
        
        $bitacora_modificacion_rol = bitacora::bitacora('Modificación Exitosa de un Rol', $mensaje);

        if (!$bitacora_modificacion_rol) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al guardar la modificación en bitácora.", "error");
            exit();
        }

        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}




/* ----------------- modulo para cambiar el estado de un rol ------------------ */
$id_rol = modeloPrincipal::decryptionId($_POST['UIDR']);
$id_rol = modeloPrincipal::limpiar_cadena($id_rol);
$rol_info = mysqli_fetch_array(modeloPrincipal::consultar("SELECT nombre FROM rol WHERE id_rol = $id_rol"));
$rol_info = $rol_info['nombre'];

if ($modulo === "activo"){

    if(modeloPrincipal::UpdateSQL("rol","estado = '0'", "id_rol = '$id_rol'")){
        
        bitacora::bitacora("Cambio exitoso del estado de un rol","El usuario cambió el estado del rol con la siguiente información: <br><br>
        <b>***** Información del rol original: *****</b><br><br>
        Nombre del rol:  <b>$rol_info </b><br><br>
        Estado: <b>Activo</b> <br><br>
        <b>***** Información del rol actualizada: *****</b><br><br>
        Nombre del rol:  <b>$rol_info </b><br>
        Estado: <b>Inactivo</b>");

        alert_model::alert_reload("¡Rol Desactivado!","El rol se desactivo exitosamente.","success");
        exit();
    }else{
        alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo realizar la operacion, por favor intente nuevamente","error");
        exit();
    }
}

if ($modulo === "inactivo"){

    if(modeloPrincipal::UpdateSQL("rol","estado = '1'", "id_rol = '$id_rol'")){
        bitacora::bitacora("Cambio exitoso del estado de un rol","El usuario cambió el estado del rol con la siguiente información: <br><br>
        <b>***** Información del rol original: *****</b><br><br>
        Nombre del rol:  <b>$rol_info </b><br><br>
        Estado: <b>Inactivo</b> <br><br>
        <b>***** Información del rol actualizada: *****</b><br><br>
        Nombre del rol:  <b>$rol_info </b><br>
        Estado: <b>Activo</b>");

        alert_model::alert_reload("¡Rol activado!","El rol se activo exitosamente.","success");
        exit();
    }else{
        alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo realizar la operacion, por favor intente nuevamente","error");
        exit();
    } 
}
