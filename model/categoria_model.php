<?php

class category_model extends modeloPrincipal {

    public static function consultar_categoria($fields) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM categoria ORDER BY nombre");
        modeloPrincipal::verificar_consulta($consul,'categoria'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consultar_condicional($fields, $condicion) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM categoria WHERE $condicion");
        modeloPrincipal::verificar_consulta($consul,'categoria'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consultar_categoria_por_id($fields, $id_categoria) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM categoria WHERE id_categoria = $id_categoria");
        modeloPrincipal::verificar_consulta($consul,'categoria'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    
    // funcion para obtener el id de un categoria
    public static function obtener_id_categoria_recien_registrada(){
        $id_categoria = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id_categoria) AS id FROM categoria"));
        $id_categoria = $id_categoria['id'];
        return $id_categoria;
    }

    public static function registrar ($nombre, $descripcion) {

        $registrar = modeloPrincipal::InsertSQL("categoria", "nombre, descripcion, estado" ,"'$nombre', '$descripcion',1");
    
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo registrar el proveedor debido a un error interno o alteracion de la información a registrar, por favor verifique e intente nuevamente","error");
        }
        return $registrar;
    }


    // public static function verificar_existe_categoria($nombres){
    //     $nombres = modeloPrincipal::format_array_of_data_with_dublicated($nombres);
    //     // se comprueba que no exista un registro con los mismos datos
    //     $categorias_registradas = [];
    //     for ($i = 0; $i < count($nombres); $i++) {
            
    //         $nombre = strtolower($nombres[$i]);
    //         if(mysqli_num_rows(modeloPrincipal::consultar("SELECT nombre FROM categoria WHERE lower(nombre) = '$nombre'")) < 1){
    //             self::registrar($nombre);
    //             $categorias_registradas[$i] = $nombre;
    //         }
    //     }
    //     $categorias_registradas = array_values($categorias_registradas);

    //     if (count($categorias_registradas) > 0) {
    //         self::bitacora($categorias_registradas);
    //     }
    // }


    public static function lista(){
        $consulta = self::consultar_categoria("*");
        
        // se guardan los datos en un array y se imprime
        $i = 1;
        while ( $mostrar = mysqli_fetch_array($consulta)) { ?>    
            <tr>
                <td class="col text-center"><?= $i++ ?></td>
                <td class="col text-start"><?= $mostrar["nombre"]; ?></td>
                <td class="col text-start"><?= $mostrar["descripcion"]; ?></td>
                
                <?php if (modeloPrincipal::verificar_permisos_requeridos(['m_categoria'])) { ?>
                    <td scope="row" class="text-center">
                        <?php 
                            if ($mostrar["estado"] === "1") { ?>
                                <button class="btn btn-outline-success bi-check-circle" title="estado de la categoría"></button>
                            <?php } else { ?>
                                
                                <form 
                                    action="../controlador/categoria_controller.php" 
                                    method="post" 
                                    class="SendFormAjax" 
                                    data-type-form="update_estate" >
                                        <input type="hidden" name="modulo" value="inactivo">          
                                        <input type="hidden" name="UID" value="<?= modeloPrincipal::encryptionId($mostrar["id_categoria"]); ?>">
                                        <button class="btn btn-outline-danger bi-x-circle" title="estado de la categoría" type="submit"></button>
                                </form>
                            <?php }
                        ?>
                    </td>
                <?php } ?>
            </tr>
        <?php  } 
    }


    public static function options() {
        $consulta = self::consultar_condicional("nombre","estado = 1");
        // se guardan los datos en un array y se imprime
        while ( $mostrar = mysqli_fetch_array($consulta)) { ?>    
            <option value="<?= $mostrar["nombre"];?>"> <?= $mostrar["nombre"]; ?></option>
        <?php  } 
    }

    public static function optionsId () {
        $consulta = self::consultar_condicional("id_categoria, nombre","estado = 1 ORDER BY nombre");
        // se guardan los datos en un array y se imprime 
        while ( $mostrar = mysqli_fetch_array($consulta)) { ?>    
            <option value="<?= modeloPrincipal::encryptionId($mostrar["id_categoria"]) ?>"><?= $mostrar["nombre"]; ?></option>
        <?php }
    }

    public static function actualizar_estado($estado, $id_categoria){
        // se comprueba que no exista un registro con los mismos datos
        if (!modeloprincipal::UpdateSQL("categoria", "estado = $estado", "id_categoria = $id_categoria")) {
            return false;
        }
        return true;
    }



    public static function obtener_array_id_categorias_recien_registradas($categorias) {
        $cant_ids = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id_categoria) AS id FROM categoria"))['id'];

        $id_registradas = intval($cant_ids) - intval($categorias);

        $dataFind = [];
        $i = 0;

        for ( $id_registradas += 1;  $id_registradas <= $cant_ids; $id_registradas++ ) {
            $dataFind[$i++] .= $id_registradas;
        }
        $dataFind = array_values(array_unique($dataFind));

        return $dataFind;
    }


    
    public static function obtener_array_id_categorias($NC):array {
        // $NC es un array con los Nombres de las Categorías = NM
        
        $dataFind = [];

        for ( $i = 0;  $i < count($NC); $i++ ) {

            $dataFind[$i] = mysqli_fetch_array(modeloPrincipal::consultar("SELECT id_categoria FROM categoria WHERE nombre = '".$NC[$i]."'"))['id_categoria'];
        }

        $dataFind = array_values($dataFind);

        return $dataFind;
    }



    public static function bitacora_modificar_estado_categoria ($cambios) {
        
        bitacora::bitacora("Modificación exitosa del estado de una categoría.",'<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp; Se modificó el estado de una categoría con la siguiente informacón.</p> 
            <h4 class="text-center card-title"><b> Información de la categoría </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre</p> '.$cambios['nombre'].' </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Descripción</p> '.$cambios['descripcion'].' </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Estado</p> '.$cambios['estado'].' </div>');
        
    }
}