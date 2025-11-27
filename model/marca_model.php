<?php
class marca_model extends modeloPrincipal {

    public static function consultar_marca() {
        $consul = modeloPrincipal::consultar("SELECT * FROM marca ORDER BY nombre ASC");
        modeloPrincipal::verificar_consulta($consul,'marca'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consultar_por_id($id) {
        $consul = modeloPrincipal::consultar("SELECT * FROM marca WHERE id = $id");
        modeloPrincipal::verificar_consulta($consul,'marca'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    // funcion para obtener el id de un categoria
    public static function obtener_id_recien_registrada(){
        $id = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id) AS id FROM marca"));
        $id = $id['id'];
        return $id;
    }

    public static function registrar ($nombre_marca) {
        $nombre_marca = ucwords(strtolower(modeloPrincipal::limpiar_cadena($nombre_marca)));
        $registrar = modeloPrincipal::InsertSQL("marca", "nombre, estado" ,"'$nombre_marca', 1");
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo registrar el marca debido a un error interno o alteracion de la información a registrar, por favor verifique e intente nuevamente","error");
        }
        return $registrar;
    }
    

    public static function verificar_existe_marca_unica($nombre){
        // se comprueba que no exista un registro con los mismos datos
        modeloPrincipal::validacion_registro_existente('nombre',"marca","nombre = '$nombre'");
    }

    public static function lista(){
        $consulta = self::consultar_marca();
        while ( $mostrar = mysqli_fetch_assoc($consulta)) { ?>
            <tr>
                <td class="col text-center"></td>
                <td class="col text-center"><?= $mostrar["nombre"]; ?></td>
                <?php if (modeloPrincipal::verificar_permisos_requeridos(['m_marca'])) { ?>
                    <td class="col text-center">
                        <?php 
                            if ($mostrar["estado"] === "1") { ?>
                                <button class="btn btn-outline-success bi-check-circle" title="estado de la Marca"></button>
                        <?php } else { ?>
                                <form 
                                    action="../controlador/marca.php" 
                                    method="post" 
                                    class="SendFormAjax" 
                                    data-type-form="update_estate" >
                                        <input type="hidden" name="modulo" value="inactivo">          
                                        <input type="hidden" name="UID" value="<?= modeloPrincipal::encryptionId($mostrar["id"]); ?>">
                                        <button 
                                            class="btn btn-outline-danger bi-x-circle" 
                                            title="estado de la Marca"
                                            type="submit"></button>
                                </form>
                        <?php }?>
                    </td>
                <?php } ?>
            </tr>
        <?php } 
    }

    public static function options() {
        $consulta = modeloPrincipal::consultar("SELECT nombre FROM marca");
        while ( $mostrar = mysqli_fetch_array($consulta)) { ?>
            <option value="<?= $mostrar["nombre"]; ?>"> <?= $mostrar["nombre"]; ?> </option>
        <?php }
    }

    public static function optionsId() {
        $consulta = modeloPrincipal::consultar("SELECT id, nombre FROM marca ORDER BY nombre");
        while ( $mostrar = mysqli_fetch_array($consulta)) { ?>
            <option value="<?= modeloPrincipal::encryptionId($mostrar["id"]); ?>"><?= $mostrar["nombre"]; ?></option>
        <?php }
    }

    
    public static function actualizar_estado($estado, $id_marca){
        // se comprueba que no exista un registro con los mismos datos
        
        if (!modeloprincipal::UpdateSQL("marca", "estado = $estado", "id = $id_marca")) {
            return false;
        }
        return true;
    }

    public static function bitacora_modificar_estado_marca ($cambios) {
        
        bitacora::bitacora("Modificación exitosa del estado de una Marca.",'<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp; Se modificó el estado de una Marca con la siguiente informacón.</p> 
            <h4 class="text-center card-title"><b> Información de la Marca </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre</p> '.$cambios['nombre'].' </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Estado</p> '.$cambios['estado'].' </div>');
        
    }

}