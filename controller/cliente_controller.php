<?php 
session_start();

include_once ("../include/modelos_include.php"); // se incluyen los modelos necesarios para la vista

$modulo = modeloPrincipal::limpiar_cadena($_POST['modulo']);

if (!isset($_POST["modulo"])) {
    alert_model::alerta_simple("Ocurrio un error!","Ha ocurrido un error al procesar tu solicitud","error");
    exit();
}

if($modulo === 'modificar'){
    
    $id = modeloPrincipal::decryptionId($_POST['UIC']);
    $id = modeloPrincipal::limpiar_cadena($id);

    $cedula = modeloPrincipal::limpiar_mayusculas($_POST["nacionalidad"].$_POST['cedula']);
    $nombre = modeloPrincipal::limpiar_mayusculas($_POST['nombre']);
    $telefono = modeloPrincipal::limpiar_cadena($_POST['telefono']);
    
    $datos_originales = mysqli_fetch_array(modeloPrincipal::consultar("SELECT cedula, nombre, telefono FROM cliente WHERE id_cliente = $id"));
    
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$id, $cedula, $nombre, $telefono]);
    
    if (modeloprincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,80}",$nombre)) {
        alert_model::alert_of_format_wrong("'nombre'");
        exit();
    }

    if (modeloprincipal::verificar_datos("[0-9]{11}",$telefono)) {
        alert_model::alert_of_format_wrong("'teléfono'");
        exit();
    }

    if (modeloprincipal::verificar_datos("[V|E|J|P][0-9|-]{5,10}",$cedula)) {
        alert_model::alerta_simple("¡Ocurrio un error!","El campo cédula no cumple con el formato requerido o fue alterado. Por favor verifique e intente de nuevo ", "error");
        exit();
    }

    try {
        $actualizar = modeloprincipal::UpdateSQL("cliente","cedula = '$cedula', nombre = '$nombre', telefono ='$telefono'", "id_cliente = $id");
        
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al guardar la actualización del cliente.","error");
        }

        $datos_actuales = mysqli_fetch_array(modeloPrincipal::consultar("SELECT cedula, nombre, telefono FROM cliente WHERE id_cliente = $id"));

        bitacora::bitacora("Modificación exitosa de un cliente.","Se modificó un cliente con la siguiente informacón: <br><br>
        <b>****** Información original del cliente modificado:   ******</b><br><br>
        Cédula: <b>".$datos_originales['cedula']." </b><br>
        Nombre: <b>".$datos_originales['nombre']." </b><br>
        Teléfono: <b>".$datos_originales['telefono']." </b><br><br>
        <b>****** Información actualizada del cliente modificado:   ******</b><br><br>
        Cédula: <b>".$datos_actuales['cedula']." </b><br>
        Nombre: <b>".$datos_actuales['nombre']." </b><br>
        Teléfono: <b>".$datos_actuales['telefono']." </b><br>
        ");

        alert_model::alert_redirect('¡Modificacion exitosa!','Los datos se modificaron correctamente.','success',"../vista/cliente.php");
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}
