<?php
require_once "../../../modelo/modeloPrincipal.php";

$id = modeloPrincipal::decryptionId($_POST['id']);

$detalles_menu = modeloPrincipal::consultar("SELECT P.nombre_producto AS producto,
    PS.cantidad AS presentacion, R.nombre AS representacion,
    C.nombre AS categoria, 
    DM.cantidad,
    MA.nombre AS marca
    FROM detalles_menu AS DM
    INNER JOIN producto AS P ON P.id_producto = DM.id_producto
    INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
    INNER JOIN representacion AS R ON R.id = PS.id_representacion
    INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria
    INNER JOIN marca AS MA ON MA.id = P.id_marca
    INNER JOIN menu AS M ON M.id_menu = DM.id_menu 
    WHERE DM.id_menu = $id ORDER BY P.nombre_producto
");

$datosServicio = mysqli_fetch_array(modeloPrincipal::consultar("SELECT * FROM menu WHERE id_menu = $id"));
?>

<div class="mb-3 ms-0 mt-0 me-0 row">
    <div class="col-md-6 col-12 mb-3">
        <label class="form-label">Descripción</label>
        <div class="col-md-4 input-group">
            <input type="text" disabled class="form-control" value="<?= $datosServicio['descripcion']; ?>" placeholder="Descripción del servicio" name="descripcion" id="descripcion">
        </div>
    </div>

    <div class="col-md-6 col-12 mb-3">
        <label class="form-label">Precio de venta en $</label>
        <div class="col-md-4 input-group">
            <input type="text" disabled class="form-control" value="<?= $datosServicio['precio_dolar']; ?> $" placeholder="Precio de venta en $ del servicio" name="precio_dolar" id="precio_dolar" required>
        </div>
    </div>

</div>
<div class="table-responsive">
    <h3>Productos del servicio</h3>
    <table class="table table-striped tableDetailsService">
        <thead>
            <tr>
                <th class="col text-center" scope="col">Nº</th>
                <th class="col text-center" scope="col">Producto</th>
                <th class="col text-center" scope="col">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (mysqli_num_rows($detalles_menu) <= 0) {
                    echo '<tr><td colspan="4" class="text-center">No se encontraron los detalles de este servicio</td></tr>';
                }
                // se guardan los datos en un array y se imprime
                while ( $mostrar = mysqli_fetch_array($detalles_menu)) { ?>    
                    <tr>
                        <td class="text-center"></td>

                        <td class="col text-center" scope="col">
                            <p class="text-primary fw-bold mb-1">
                                <?= $mostrar["producto"] . ' - ' . $mostrar["marca"] ?>
                            </p>
                            <small class="d-block text-muted">
                                Formato: <?= $mostrar["presentacion"] . ' ' . $mostrar["representacion"] ?>
                            </small>
                            <small class="d-block text-muted">
                                Categoría: <?= $mostrar["categoria"] ?>
                            </small>
                        </td>

                        <td class="text-center"><?= $mostrar['cantidad']; ?></td>
                    </tr>
            <?php } ?>
        
        </tbody>
    </table>
</div>
