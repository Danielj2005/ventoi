<?php 
session_start();

require_once "../../../modelo/modeloPrincipal.php";
require_once "../../../modelo/alert_model.php";
require_once "../../../modelo/proveedor_model.php";
require_once "../../../modelo/productos_model.php";

$id = modeloPrincipal::decryptionId($_POST['id']);
$id_menu = modeloPrincipal::limpiar_cadena($id);

if (!isset($_POST['id'])) {
    alert_model::alerta_simple("¡Ocurrio un error!","No se está recibiendo correctamente el identificador del servicio","error");
    exit();
}

$precio_dolar_actual = $_SESSION['dolar'];

$servicios = mysqli_fetch_assoc(modeloprincipal::consultar("SELECT * FROM menu WHERE id_menu = $id_menu"));

$detalles_menu = modeloPrincipal::consultar("SELECT P.id_producto, P.nombre_producto AS producto,
    PS.cantidad AS presentacion, R.nombre AS representacion,
    C.nombre AS categoria, 
    DM.cantidad,
    M.nombre AS marca
    FROM detalles_menu AS DM
    INNER JOIN producto AS P ON P.id_producto = DM.id_producto
    INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
    INNER JOIN representacion AS R ON R.id = PS.id_representacion
    INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria
    INNER JOIN marca AS M ON M.id = P.id_marca
    INNER JOIN menu AS S ON S.id_menu = DM.id_menu
    WHERE DM.id_menu = $id_menu");

$id_select = modeloPrincipal::encryptionId($id_menu);
?>
<form id="modalSendForm" action="../controlador/servicio_controlador.php" method="post" class="SendFormAjax" autocomplete="off" data-type-form="update">
    <div class="card-body p-2">
        <input type="hidden" name="dolar" id="precioDolar" value="<?= $precio_dolar_actual; ?>">
        <input type="hidden" name="modulo" value="Modificar">    
        <input type="hidden" value="<?= modeloPrincipal::encryptionId($id_menu) ?>" name="UIS">
    
        <div class="col-12 col-sm-12 col-md-12 mb-3">
            <h5 class="card-title"> Datos del Servicio </h5>
            <div class="row">
                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Nombre del Servicio</label>
                    <div class="col-md-4 input-group">
                    <input type="text" class="form-control" value="<?= $servicios['nombre_platillo'] ?>" placeholder="ingresa el nombre del servicio" name="nombre_platillo" id="nombre_platillo" required>
                    </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" class="form-control" value="<?= $servicios['descripcion'] ?>" placeholder="ingresa la descripción" id="descripcion" name="descripcion" required>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Precio de venta en $</label>
                    <div class="col-md-4 input-group">
                    <input type="text" class="form-control" value="<?= $servicios['precio_dolar'] ?>" placeholder="ingresa el precio de venta en $" name="precio_dolar" id="precio_dolar" required>
                    </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Estado</label>
                    
                    <select class="form-select" name="estado_menu" id="id_estado">
    
                        <option value="1" <?= ($servicios['estatus'] == 1) ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?= ($servicios['estatus'] == 1) ? '' : 'selected'; ?>>Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <h5 class="card-title">Productos del servicio</h5>

            <div class="col-12 mb-3 text-center d-none">
                <button type="button" onclick="addProductOnService()" class="btn btn-primary bi bi-plus">&nbsp;Agregar otro Producto</button>
            </div>

            <table class="table table-striped tableModifyService">
                <thead>
                    <tr>
                        <th class="col text-center" scope="col">Nº</th>
                        <th class="col text-center" scope="col">Producto</th>
                        <th class="col text-center" scope="col">Cantidad</th>
                        <!-- <th class="col-3 text-center" scope="col">Eliminar</th> -->
                    </tr>
                </thead>
                <tbody id="tableModifyService">
                    <?php
                    if (mysqli_num_rows($detalles_menu) <= 0) {
                        echo '<tr><td colspan="4" class="text-center">No se encontraron los detalles de este servicio</td></tr>';
                    }
                    // se guardan los datos en un array y se imprime
                    while ($mostrar = mysqli_fetch_array($detalles_menu)) { ;
                                            
                        $productos = modeloPrincipal::consultar("SELECT P.id_producto, P.nombre_producto AS producto,
                            PS.cantidad AS presentacion, R.nombre AS representacion,
                            C.nombre AS categoria,
                            M.nombre AS marca
                            FROM producto AS P 
                            INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
                            INNER JOIN representacion AS R ON R.id = PS.id_representacion
                            INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria
                            INNER JOIN marca AS M ON M.id = P.id_marca
                            ORDER BY P.nombre_producto ASC");

                    ?>    
                        <tr>
                            <td class="col"> </td>
                            <td class="col text-start">
                                <select name="producto[]" class="form-select select2" id="select_productos_<?= modeloPrincipal::encryptionId($id_menu) ?>" required>
                                    <?php 
                                        while ($row = mysqli_fetch_array($productos)) { ?>
                                            <option <?= $mostrar['id_producto'] == $row['id_producto'] ? 'selected' : ''; ?> value="<?= modeloPrincipal::encryptionId($row['id_producto']) ?>"><?= $row['producto'].' '.$row['marca'].' '.$row['presentacion'].' '.$row['representacion']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td class="col text-center">
                                <input value="<?= $mostrar['cantidad']; ?>" type="number" min="0" class="form-control" name="cantidad_producto[]" placeholder="Escribe la cantidad a ingresar" id="cantidad_<?= modeloPrincipal::encryptionId($mostrar["id_producto"]) ?>" required>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</form>