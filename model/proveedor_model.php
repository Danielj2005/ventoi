<?php

class proveedor_model extends modeloPrincipal {
    
    /********************************************************************************************************/ 
    /*********************************     CRUD de proveedores         *****************************************/
    /********************************************************************************************************/ 
    
    /***************************************************************/
    /******* funciones para consultar datos de los proveedores ********/
    /***************************************************************/

    public static function consultar($fields) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM proveedor");
        modeloPrincipal::verificar_consulta($consul,'proveedor'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consultar_proveedor_por_id($fields, $id_proveedor) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM proveedor WHERE id_proveedor = $id_proveedor");
        modeloPrincipal::verificar_consulta($consul,'proveedor'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    // funcion para obtener el id de un proveedor

    public static function obtener_id_proveedor_recien_registrado(){
        $id_proveedor = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id_proveedor) AS id FROM proveedor"));
        $id_proveedor = $id_proveedor['id'];
        return $id_proveedor;
    }


    public static function insertar_proveedor ($cedula, $nombre, $correo, $telefono, $direccion) {

        $registrar = modeloPrincipal::InsertSQL("proveedor","cedula_rif, nombre, correo, direccion, telefono","'$cedula','$nombre','$correo','$direccion','$telefono'");
    
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo registrar el proveedor debido a un error interno o alteracion de la información a registrar, por favor verifique e intente nuevamente","error");
        }
        return $registrar;
    }
    
    public static function registrar ($cedula, $nombre, $correo, $telefono, $direccion) {

        // Se verifica que no se hayan recibido campos vacíos.
        modeloPrincipal::validar_campos_vacios([$cedula, $nombre, $correo, $direccion, $telefono]);

        // Se verifica que no el proveedor aha registrar no exista.    
        if(mysqli_num_rows(modeloPrincipal::consultar("SELECT cedula_rif FROM proveedor WHERE cedula_rif = '$cedula'")) > 0){
            alert_model::alert_register_exist();
            exit(); 
        }

        if (modeloprincipal::verificar_datos("[V|E|J|P][0-9|-]{5,10}",$cedula)) {
            alert_model::alerta_simple("¡Ocurrio un error!","El campo cédula no cumple con el formato requerido o fue alterado. Por favor verifique e intente de nuevo ", "error");
            exit();
        }
        
        if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
            alert_model::alert_of_format_wrong("'nombre'");
            exit();
        }
        
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            alert_model::alert_of_format_wrong("correo");
            exit();
        }
        

        if (modeloPrincipal::verificar_datos("[0-9]{11}",$telefono)) {
            alert_model::alert_of_format_wrong("'teléfono'");
            exit();
        }

        if (modeloprincipal::verificar_datos("[A-Za-zÁÉÍÚÓáéíóúñÑ0-9-, ]{10,50}",$direccion)) {
            alert_model::alert_of_format_wrong("'dirección'");
            exit();
        }

        // se registran los datos del proveedor
        try {
            $actualizar = proveedor_model::insertar_proveedor($cedula, $nombre, $correo, $telefono, $direccion);
            
            if (!$actualizar) {
                alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar un proveedor en la base de datos.","error");
            }

        } catch (Exception $e) {
            alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar el proveedor en la base de datos debido a un error de consulta.", "error");
            exit();
        }
        
        // se realiza la bitácora con los datos del proveedor a registrar
        try {
            $id_proveedor = proveedor_model::obtener_id_proveedor_recien_registrado();

            $datos_originales = proveedor_model::consultar_proveedor_por_id("*", $id_proveedor);
            $datos_originales = mysqli_fetch_array($datos_originales);

            bitacora::bitacora("Registro exitoso de un proveedor.","Se registro un proveedor con la siguiente informacón: <br><br>
            <b>****** Información del proveedor:   ******</b><br><br>
            Cédula / RIF: <b>".$datos_originales['cedula_rif']." </b><br>
            Nombre: <b>".$datos_originales['nombre']." </b><br>
            Correo: <b>".$datos_originales['correo']." </b><br>
            Teléfono: <b>".$datos_originales['telefono']." </b><br>
            Dirección: <b>".$datos_originales['direccion']." </b><br><br>
            ");

            return true;
        } catch (Exception $e) {
            return false ;
        }

    }
    
    public static function actualizar_proveedor ($cedula, $nombre, $correo, $telefono, $direccion, $id_proveedor_modificar) {

        $actualizar = modeloPrincipal::UpdateSQL( "proveedor","cedula_rif = '$cedula', nombre = '$nombre', correo = '$correo', telefono = '$telefono', direccion = '$direccion'","id_proveedor = '$id_proveedor_modificar'");
    
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo registrar el proveedor debido a un error interno o alteracion de la información a registrar, por favor verifique e intente nuevamente","error");
        }
        return $actualizar;
    }
    public static function validar_existe_historial ($id) {
        $consulta = modeloPrincipal::consultar("SELECT count(id_entrada) AS cantidad_entradas FROM entrada WHERE id_proveedor = $id");
        $consulta = mysqli_fetch_array($consulta);
        $consulta = $consulta['cantidad_entradas'];
        return $consulta;
    }
    
    public static function lista_proveedores_registrados () {
    
        $consulta = modeloPrincipal::consultar("SELECT * FROM proveedor");
        
        $l_proveedores = modeloPrincipal::verificar_permisos_requeridos(['l_proveedores']);
        $m_proveedores = modeloPrincipal::verificar_permisos_requeridos(['m_proveedores']);
        $h_proveedores = modeloPrincipal::verificar_permisos_requeridos(['h_proveedores']);

        // se guardan los datos en un array y se imprime
        while ( $mostrar = mysqli_fetch_array($consulta)) { 
            $haveHistorial = self::validar_existe_historial ($mostrar["id_proveedor"]);
            $encryptionId = modeloPrincipal::encryptionId($mostrar["id_proveedor"]);
            $alterarId = modeloPrincipal::alterarId($mostrar['id_proveedor']);
            
            ?>    
            <tr>
                <td class="col text-center"></td>
                <td class="col text-center"><?= $mostrar["cedula_rif"]; ?></td>
                <td class="col text-center"><?= $mostrar["nombre"]; ?></td>

                <?php if ($l_proveedores == '1') { ?>

                    <td class="col text-center">
                        <button modal="proveedorDetalles" type="submit" value="<?= $encryptionId ; ?>" data-bs-toggle="modal" data-bs-target="#modal" class="btn_modal btn btn-info bi bi-eye"></button>
                    </td>

                <?php } ?>

                <?php if ($m_proveedores == '1') { ?>
                    <td class="col text-center">
                        <button modal="proveedorModificar" value="<?= $encryptionId ; ?>" type="submit" data-bs-toggle="modal" data-bs-target="#modal" class="btn_modal btn btn-warning <?= ICONO_MODIFICAR ?>"></button>
                    </td>
                <?php } ?>

                <?php if ($h_proveedores == '1') { ?>

                    <td class="col text-center">
                        <div class="m-0 row justify-content-center align-items-center">

                            <button 
                                modal="proveedorHistorial" 
                                value="<?= $encryptionId ; ?>" 
                                <?= $haveHistorial > 0 ?  'data-bs-toggle="modal" data-bs-target="#modal"' : '' ?> 
                                class="btn col col-auto bi <?= $haveHistorial > 0 ? "btn_modal bi-eye btn-info" : "btn-outline-light alert-history bi-eye-slash" ?>">
                            </button>
                            
                            <div class="dropstart col col-auto">
                                <button class="<?= $haveHistorial > 0 ? " btn-primary" : "btn-outline-light alert-history" ?> col-12 col btn col-auto bi bi-three-dots-vertical" type="button" <?= $haveHistorial > 0 ? 'data-bs-toggle="dropdown" aria-expanded="false"' : "" ?> >
                                    <span>PDF</span>
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                </button>

                                <ul class="dropdown-menu" style="width: 20rem;">

                                    <li class="p-2 text-center">
                                        <form target="_blank" action="./reportes/historial_proveedor.php" method="post">
                                            <input type="hidden" value="<?= $encryptionId ; ?>" name="UID">
                                            <button type="submit" class="btn bi bi-file-text btn-success"> Exportar todas las compras</button>
                                        </form>
                                    </li>
                                    <li> <hr class="dropdown-divider"> </li>
                                    <li> <hr class="dropdown-divider"> </li>

                                    <li class="p-0 text-center">
                                        <label class="dropdown-item">Exportar Lista de las compras por Fecha</label>

                                        <form action="./reportes/historial_por_fechas_proveedor.php" method="post" class="p-1 row" id="<?= $alterarId; ?>" target="_blank">
                                            <input form="<?= $alterarId; ?>" type="hidden" value="<?= $encryptionId ; ?>" name="UID">

                                            <div class="input-group mb-1 justify-content-center">
                                                <label class="input-group-text text-start control-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                                <input form="<?= $alterarId; ?>" onchange="validateDate(`<?= $alterarId; ?>`)" class="reportDates form-control" type="date" id="fechaReporteInicio_<?= $alterarId; ?>" name="fechaReporteInicio">
                                            </div>
                                            
                                            <div class="input-group mb-1 justify-content-center">
                                                <label class="input-group-text text-start control-label">Fecha de Fin<span class="text-danger">*</span></label>
                                                <input form="<?= $alterarId; ?>" onchange="validateDate(`<?= $alterarId; ?>`)" class="reportDates form-control" value="<?= date('Y-m-d') ?>" type="date" id="fechaReporteFin_<?= $alterarId; ?>" name="fechaReporteFin">
                                            </div>
                                            
                                            <div class="input-group mb-1 justify-content-center">
                                                <p class="showThis_<?= $alterarId; ?> bg-danger-light text-danger p-2 d-none" id="mensajefechaReporteInicio" style="width: fit-content;">La fecha de inicio no puede ser mayor a la fecha de fin y ninguno puede ser mayor a la fecha actual.</p>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 mb-1 text-center">
                                                <button form="<?= $alterarId; ?>" type="submit" class="d-none btn btn-success bi bi-file-text" id="btnReportesFechas_<?= $alterarId; ?>">&nbsp; Generar Reporte</button>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                <?php } ?>
            </tr>
        <?php } 
    }


    /*******************************************************************/ 
    /*     Funciones dedicadas a resolver peticiones del usuario       */
    /*******************************************************************/ 
    
    public static function buscar_proveedor_compra_por_dni($dni) {
        
        //datos via Post
        $cedula = modeloPrincipal::limpiar_cadena($dni); 
        $datos['existe'] = "0";
        //Consulta
        $proveedor = modeloPrincipal::consultar ("SELECT * FROM proveedor WHERE cedula_rif ='$cedula'");
        
        if (mysqli_num_rows($proveedor) < 1) {
            $datos['error'] = "El proveedor no existe.";
            echo json_encode($datos);
            exit();
        }
        $proveedor = mysqli_fetch_array($proveedor);

        $datos['existe'] = "1";
        $datos['nombre'] = $proveedor['nombre'];
        $datos['telefono'] = $proveedor['telefono'];
        $datos['correo'] = $proveedor['correo'];
        $datos['direccion'] = $proveedor['direccion'];
    
        $datos = json_encode($datos); 
        echo $datos;
    }

    
}