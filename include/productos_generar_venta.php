<?php

require_once ('../modelo/modeloPrincipal.php');

if (isset($_POST['producto'])) {

    $id_producto = $_POST['id_producto'];
    $j = 1;

    for($i = 0; $i < COUNT($id_producto); $i++){

        $datos_producto = mysqli_fetch_array(modeloPrincipal::Consultar("SELECT P.codigo, P.nombre_producto, 
            P.id_producto, P.precio_compra_dolar, P.precio_compra_bs,stock,
            PS.cantidad AS presentacion, R.nombre AS representacion
            FROM producto AS P
            INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
            INNER JOIN representacion AS R ON R.id = PS.id_representacion
            WHERE P.id_producto = ".$id_producto[$i]."")); ?>
        
        <tr id="producto_<?= $id_producto[$i] ?>">
            <th class="col text-center" scope="row"><?=$j++ ?></th>
            <td class="col text-center">
                
                <input type="text" class="send_data form-control" name="nombre_producto[]" disabled value="<?= $datos_producto["nombre_producto"] ?>" required>
            </td>
            <td class="col text-center" id="presentacion_<?= $id_producto[$i] ?>">
                <input type="text" class="send_data form-control" name="presentacion_[]" disabled value="<?= $datos_producto['presentacion'].' '.$datos_producto['representacion'] ?>" required>
            </td>
            <td class="col text-center" id="stock_<?= $id_producto[$i] ?>">
                <input type="text" class="send_data form-control" name="stock_producto[]" disabled value="<?= $datos_producto['stock'] ?>" required>
            </td>
            <td class="col text-center" id="cantidad_<?= $id_producto[$i] ?>">
                <input type="text" class="send_data form-control cantidad_total" name="cantidad_producto[]" onblur="monto_total_productos()" placeholder="cantidad a ingresar" required>
            </td>
            <td class="col text-center">
                <input type="text" id="precio_producto_dolar_<?= $id_producto[$i] ?>" class="send_data form-control precio_dolar_total bg-dark-subtle" name="precio_producto_dolar[]" value="<?= $datos_producto['precio_compra_dolar'];?>" readonly placeholder="precio en $" required>
            </td>
            <td class="col text-center">
                <input type="text" id="precio_producto_bolivar_<?= $id_producto[$i]?>" class="send_data form-control precio_bolivar_total bg-dark-subtle" name="precio_producto_bolivar[]" value="<?= $datos_producto['precio_compra_bs']?>" readonly placeholder="precion en bss" required>
            </td>
        </tr>
    <?php }
}