<?php 
class cliente_model extends modeloPrincipal {

    /********************************************************************************************************/ 
    /*********************************     CRUD de Clientes         *****************************************/
    /********************************************************************************************************/ 
    public static function consultar_cliente($fields) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM cliente");
        modeloPrincipal::verificar_consulta($consul,'cliente'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consultar_condicional_cliente($fields, $condition) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM cliente WHERE $condition");
        modeloPrincipal::verificar_consulta($consul,'cliente'); // se verifica si la consulta fue exitosa
        return $consul;
    }
    
    

    public static function consultar_por_id($fields, $id_cliente) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM cliente WHERE id_cliente = $id_cliente");
        modeloPrincipal::verificar_consulta($consul,'cliente'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    // funcion para obtener el id de un proveedor

    public static function obtener_id_cliente_recien_registrado(){
        $id_cliente = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id_cliente) AS id FROM cliente"));
        $id_cliente = $id_cliente['id'];
        return $id_cliente;
    }


    public static function insertar_cliente ($cedula, $nombre, $telefono) {

        $registrar = modeloPrincipal::InsertSQL("cliente","cedula, nombre, telefono","'$cedula','$nombre','$telefono'");
    
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo registrar el cliente debido a un error interno o alteracion de la información a registrar, por favor verifique e intente nuevamente","error");
        }
        return $registrar;
    }
    
    public static function registrar ($cedula, $nombre, $telefono) {
        $nombre = modeloPrincipal::limpiar_mayusculas($nombre);
        $telefono = modeloPrincipal::limpiar_cadena($telefono);

        // Se verifica que no se hayan recibido campos vacíos.
        modeloPrincipal::validar_campos_vacios([$cedula, $nombre, $telefono]);

        // verificar que los datos cumplen con los parametros de formato
        if (modeloprincipal::verificar_datos("[V|E|J|P][0-9|-]{5,10}",$cedula)) {
            alert_model::alerta_simple("¡Ocurrio un error!","El campo cédula no cumple con el formato requerido o fue alterado. Por favor verifique e intente de nuevo ", "error");
            exit();
        }
        
        if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
            alert_model::alert_of_format_wrong("'nombre'");
            exit();
        }
        if (modeloPrincipal::verificar_datos("[0-9]{11}",$telefono)) {
            alert_model::alert_of_format_wrong("'teléfono'");
            exit();
        }

        // se registran los datos del proveedor
        try {
            $actualizar = Self::insertar_cliente($cedula, $nombre, $telefono);
            
            if (!$actualizar) {
                alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar un cliente en la base de datos.","error");
            }

        } catch (Exception $e) {
            alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar el cliente en la base de datos debido a un error de consulta.", "error");
            exit();
        }
        
        // se realiza la bitácora con los datos del proveedor a registrar
        try {
            $id_cliente = self::obtener_id_cliente_recien_registrado();

            $datos_originales = self::consultar_por_id("*", $id_cliente);
            $datos_originales = mysqli_fetch_array($datos_originales);

            bitacora::bitacora("Registro Exitoso de un Cliente.",
                "Se registro un Cliente con la Siguiente Informacón: <br><br>
                <b>****** Información del Cliente:   ******</b><br><br>
                Cédula: <b>".$datos_originales['cedula']." </b><br>
                Nombre y Apeellido: <b>".$datos_originales['nombre']." </b><br>
                Teléfono: <b>".$datos_originales['telefono']." </b><br><br>
            ");

            return true;
        } catch (Exception $e) {
            alert_model::alert_reg_error();
            exit();
        }
    }

    /***************************************************************/
    /******* Componentes del módulo de Clientes ********/
    /***************************************************************/

    public static function lista_clientes_registrados () {

        // se consultan los cliente de la base de datos
        $consulta = self::consultar_cliente("id_cliente, cedula, nombre, telefono");

        $m_cliente = modeloPrincipal::verificar_permisos_requeridos(['m_cliente']);

        $h_cliente = modeloPrincipal::verificar_permisos_requeridos(['h_cliente']);
        // se guardan los datos en un array y se imprime
        while ( $mostrar = mysqli_fetch_array($consulta)) { ?>    
            <tr>
                <td class="text-center col"> </td>
                <td class="text-center col"><?= $mostrar["cedula"]; ?></td>
                <td class="text-center col"><?= $mostrar["nombre"]; ?></td>
                <td class="text-center col"><?= $mostrar["telefono"]; ?></td>

                <?php if ($m_cliente == '1') : ?>

                    <td scope='col' class="text-center col">
                        <button 
                            value="<?= modeloPrincipal::encryptionId($mostrar["id_cliente"]); ?>" 
                            modal="clienteModificar" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modal" 
                            class="btn_modal btn <?= ICONO_MODIFICAR ?> btn-warning" >
                        </button>
                    </td>

                <?php endif; if ($h_cliente == '1') : ?>

                    <td scope='col' class="text-center col">
                        <button 
                            modal="clienteHistorial" 
                            class="btn_modal btn btn-info bi bi-eye detalles_generales" 
                            value="<?= modeloPrincipal::encryptionId($mostrar["id_cliente"]); ?>" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modal">
                        </button>
                    </td> 

                <?php endif; ?>
            </tr>
        <?php }
    }
}