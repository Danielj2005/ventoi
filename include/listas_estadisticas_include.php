<?php 
// importacion de la conexion a la base de datos y al modelo principal

include_once "../modelo/modeloPrincipal.php";
/*------- función para mostrar los registros de una tabla -------*/
function consultar_registros($tabla){
        
    // se consultan los registros dependiendo de la tabla
    if($tabla === 'estadistica_producto'){
        // script para crear una lista de productos disponibles
        // consulta de los productos registrados

        $consulta = modeloPrincipal::consultar("SELECT P.nombre_producto, SUM(DV.cantidad) AS cantidad_vendida,
            PS.nombre AS presentacion,
            M.nombre AS marca
            FROM detalles_venta AS DV
            INNER JOIN producto AS P ON DV.id_producto = P.id_producto
            INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
            INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria
            INNER JOIN marca AS M ON M.id = P.id_marca
            GROUP BY DV.id_producto");
        $a=1;
        while ( $mostrar =  mysqli_fetch_assoc($consulta)) { ?>

            <tr>
                <td class="text-center">
                    <input type="hidden" value="<?php echo $mostrar['nombre_producto']." ".$mostrar['marca'].' '.$mostrar['presentacion']; ?>" id="<?php echo 'producto',$a; ?>">
                </td>
                <td class="text-center">
                    <input type="hidden" value="<?php echo $mostrar['cantidad_vendida']; ?>" id="<?php echo 'cantidad',$a; ?>">
                </td>
            </tr>
            
        <?php $a++;  }
    }
    if($tabla === 'estadistica_servicios'){
        // script para crear una lista de productos disponibles
        // consulta de los productos registrados

        $consulta = modeloPrincipal::consultar("SELECT M.nombre_platillo, SUM(DV.cantidad_servicio) 
            FROM detalles_venta AS DV
            INNER JOIN menu AS M ON M.id_menu = DV.id_servicio
            GROUP BY DV.id_servicio");

        $a=1;
        while ( $mostrar =  mysqli_fetch_assoc($consulta)) { ?>

            <tr>
                <td class="text-center"><input type="hidden" value="<?php echo $mostrar['nombre_platillo']; ?>" id="<?php echo 'producto',$a; ?>"></td>
                <td class="text-center"><input type="hidden" value="<?php echo $mostrar['SUM(detalles_venta.cantidad_servicio)']; ?>" id="<?php echo 'cantidad',$a; ?>"></td>
            </tr>
            
        <?php $a++;  }
    }
}; 

/*------- fin de la función -------*/